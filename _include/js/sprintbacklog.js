var ResizingWhiteboard = Class.create({
	initialize: function() {
		$('whiteboardlarge').observe('click', function(event) {
			$$('div.taskplaceholder').each(function(s) {
				s.removeClassName('taskplaceholdersmall');
				s.removeClassName('taskplaceholdermedium');
			});
			$$('div.taskpostit').each(function(s) {
				s.removeClassName('taskpostitsmall');
				s.removeClassName('taskpostitmedium');
			});
			$$('div.storypostit').each(function(s) {
				s.removeClassName('storypostitsmall');
				s.removeClassName('storypostitmedium');
			});
		});
		$('whiteboardmedium').observe('click', function(event) {
			$$('div.taskplaceholder').each(function(s) {
				s.removeClassName('taskplaceholdersmall');
				s.addClassName('taskplaceholdermedium');
			});
			$$('div.taskpostit').each(function(s) {
				s.removeClassName('taskpostitsmall');
				s.addClassName('taskpostitmedium');
			});
			$$('div.storypostit').each(function(s) {
				s.removeClassName('storypostitsmall');
				s.addClassName('storypostitmedium');
			});
		});
		$('whiteboardsmall').observe('click', function(event) {
			$$('div.taskplaceholder').each(function(s) {
				s.removeClassName('taskplaceholdermedium');
				s.addClassName('taskplaceholdersmall');
			});
			$$('div.taskpostit').each(function(s) {
				s.removeClassName('taskpostitmedium');
				s.addClassName('taskpostitsmall');
			});
			$$('div.storypostit').each(function(s) {
				s.removeClassName('storypostitmedium');
				s.addClassName('storypostitsmall');
			});
		});
	}
});



/**
  * Class to manage a product backlog: add story, edit story...
  */
var SprintBacklog = Class.create({
	initialize: function() {
		var params = { 
			ghosting: true, constraint: false, tag: 'div', dropOnEmpty: true, only: 'taskpostit'
		};

		$$('div.storypostit').each(function(s) {
			var storyId = s.id.substr(12);
			params.containment = ['todolist-' + storyId, 'inprogresslist-' + storyId, 'donelist-' + storyId];

			['todolist', 'donelist', 'inprogresslist'].each(function(s) {
				params.onUpdate = function() {	
					var p = new SprintBacklog();
					p.updateTasks(storyId, s);
				};
				Sortable.create(s + '-' + storyId, params);
			});			
		});
		
		$$('div.taskpostit').each(function(s) {
			var taskId = s.id.substr(7);
			
			new Tip('task-edit-' + taskId, "Edit task", {
				title: "Edit task",
				showOn: 'click',
				hideOn: { element: 'closeButton', event: 'click'},
				hideOthers: 'true',
				offset: { x: 0, y: 2 },
				style: 'postit',
				target: 'postit_' + taskId,
				hook: { target: 'bottomLeft', tip: 'topLeft' },
				ajax: {
					url: PATH_TO_ROOT + '_ajax/task/edit_task.php?id=' + taskId,
					options: { onComplete: function() {
						new PBInPlaceSelect('task-owner-' + taskId, 'teamMemberList', {
							onComplete: function() {
								$('disp-task-owner-' + taskId).innerHTML = $('task-owner-' + taskId).innerHTML; 
								new Effect.Highlight($('disp-task-owner-' + taskId));
							} 
						});
						new PBInPlaceEditor('task-reestim-' + taskId, { 
							cols: 2, 
							onComplete: function() { 
								$('disp-task-remaining-' + taskId).innerHTML = parseInt($('task-reestim-' + taskId).innerHTML) - parseInt($('task-worked-' + taskId).innerHTML); 
							} 
						});
						new PBInPlaceEditor('task-worked-' + taskId, {
							cols: 2, 
							onComplete: function() {
								worked = $('task-worked-' + taskId).innerHTML;
								reestim = $('task-reestim-' + taskId).innerHTML;
								
								if (reestim - worked < 0) {
									new Ajax.Updater('task-reestim-' + taskId, PATH_TO_ROOT + '_ajax/field_update.php', {
										method:'post',
										parameters: { 
											id: 'task-reestim-' + taskId, 
											old_content: reestim,
											new_content: worked },
										onComplete: function(transport){
											if (200 == transport.status) {
												new Effect.Highlight($('task-reestim-' + taskId));
												
												$('disp-task-remaining-' + taskId).innerHTML = 0;
												new Effect.Highlight($('disp-task-remaining-' + taskId));
											}											
										},
										onFailure: function(){ alert('Something went wrong...') }
									});									
								} else {
									$('disp-task-remaining-' + taskId).innerHTML = parseInt($('task-reestim-' + taskId).innerHTML) - parseInt($('task-worked-' + taskId).innerHTML);
									new Effect.Highlight($('disp-task-remaining-' + taskId));
								}
							} 
						});
					} 
				}
			}
			});
		});
	},
	
	updateTasks: function(storyId, listName) {
		var S = new PBSavingMsg();		
		var tasks = Sortable.sequence(listName + '-' + storyId).join();
		new Ajax.Request(PATH_TO_ROOT + '_ajax/sprint/updateTaskStatus.php', {
			method: 'post',
			parameters: {
				ids: tasks,
				listtype: listName
            },
			onSuccess: function() {
				S.done();
			}
		});
	}
});