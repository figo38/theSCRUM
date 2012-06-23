/**
  * Class to manage interactions on stories (inline editing...)
  */
var SprintPlanning = Class.create({
	initialize: function() {
	},
	initReadMode: function() {
		new SprintPlanning().manageAllocationPanel();		
	},

	initWriteMode: function() {
		new SprintPlanning().manageAllocationPanel();

		// Manage the "copy unfinished tasks" panel
		if ($('unfinishedtasks-no') != null) {
			$('unfinishedtasks-no').observe('click', function() {
				var S = new PBSavingMsg();
				new Ajax.Request(PATH_TO_ROOT + '_ajax/field_update.php', {
					method:'post',
					parameters: { 
						id: 'sprint-copytask-' + $F('sprintBacklog_sprintId'), 
						old_content: 0,
						new_content: 1 },
					onComplete: function(transport){
						new Effect.Fade($('unfinishedtasks'));
						S.done();
					},
					onFailure: function(){ alert('Something went wrong...') }
				});
			});
		}
	},

	manageAllocationPanel: function() {
		// Manage the "allocation" panel
		new Draggable('allocationtable');
		new SprintPlanning().refreshTotalEstimForTeam();
		new SprintPlanning().refreshAllocatedForTeam();

		// Show/hide panel details
		$('allocationtableshowhide').observe('click', function(event) {
			$('teamallocationtable_thead').toggleClassName('hidden');
			$('teamallocationtable_tbody').toggleClassName('hidden');
		
			if ($('teamallocationtable_tbody').hasClassName('hidden')) {
				$('allocationtableshowhide').innerHTML = '(Show details)';
			} else {
				$('allocationtableshowhide').innerHTML = '(Hide details)';
			}
		});		
	},

	refreshAllocatedForTeam: function() {
		$$('td.teammember div').each(function(people) {
			new SprintPlanning().refreshAllocatedForTeamMember(people.innerHTML);
		});
	},
	
	refreshAllocatedForTeamMember: function(membername) {
		totalAllocated = 0;
		$$('span.task-owner').each(function(peoplename) {
			if (peoplename.innerHTML == membername) {
				taskId = peoplename.id.substr(11);
				totalAllocated += parseInt($('task-estim-' + taskId).innerHTML);
			}
		});
		$('totalEstim-' + membername).innerHTML = totalAllocated;
		
		$('totalRemain-' + membername).removeClassName('red');
		var totalRemaining = parseInt($('totalAvail-' + membername).innerHTML) - parseInt($('totalEstim-' + membername).innerHTML);
		$('totalRemain-' + membername).innerHTML = totalRemaining;
		if (totalRemaining < 0) {
			$('totalRemain-' + membername).addClassName('red');
		}
	},
	
	refreshTotalEstimForTeam: function() {
		totalEstim = 0;
		$$('span.task-estim').each(function(estim) {
			totalEstim += parseInt(estim.innerHTML);
		});
		$('totalEstim').innerHTML = totalEstim;
		
		var totalRemaining = parseInt($('totalAvail').innerHTML) - parseInt($('totalEstim').innerHTML);
		$('totalRemain').innerHTML = totalRemaining;	
	},

	moveTask: function(taskId, storyId) {
		var taskIdPrio = $('task-prio-' + taskId).innerHTML;		
		var taskIdToInsertBefore = 0; // While iterating over all tasks of the story, this is the taskId identified to move the updated task before.
		var currentTaskId = 0; // Iterate over all the tasks of the story - currentTaskId is the current task being evaluated
		
		// Iterate over all the tasks belonging to the story
		ok = false;
		$$('.task' + storyId).each(function(item) {
			currentTaskId = item.id.substr(8);
			// Skipping the updated task
			if (currentTaskId != taskId) {
				currentPrio = $('task-prio-' + currentTaskId).innerHTML;
				currentPrioDiff = (taskIdPrio - currentPrio);
				if (currentPrioDiff > 0 && !ok) {
					taskIdToInsertBefore = currentTaskId;
					ok = true;
				}
			}
		});
					
		if (taskIdToInsertBefore == 0) {
			// Moving the updated story at the bottom of the epic
			$("taskrow-" + currentTaskId).insert({ 'after': $('taskrow-' + taskId) });						
		} else {
			$("taskrow-" + taskIdToInsertBefore).insert({ 'before': $('taskrow-' + taskId) });
		}
		new Effect.Highlight($('taskrow-' + taskId));			
	},

	enableInteraction: function(taskId, storyId) {
		new PBInPlaceEditor('task-title-' + taskId, { rows: 2 });
		new PBInPlaceEditor('task-prio-' + taskId, { 
			cols: 3,
			onComplete: function() {
				new SprintPlanning().moveTask(taskId, storyId);
			}
		});		
		new PBInPlaceSelect('task-owner-' + taskId, 'teamMemberList', {
			onComplete: function() {
				new SprintPlanning().refreshAllocatedForTeam();
			}
		});
		new PBInPlaceEditor('task-estim-' + taskId, { 
			cols: 2, 
			onComplete: function() {
				new SprintPlanning().refreshTotalEstimForTeam();
				new SprintPlanning().refreshAllocatedForTeam();
				$('task-reestim-' + taskId).innerHTML = $('task-estim-' + taskId).innerHTML;				
			}  
		});
		
		// Display the tooltip to manage the deletion of a user story
		new PBDeleteObject('task-delete-' + taskId, "Delete the task", 'taskrow-' + taskId);
	},
	
	enableInteractionOnStory: function(storyId) {
		new ProductBacklogTip('task-add-' + storyId, "Add a task", {
			title: "Add a new task",
			stem: 'rightMiddle',
			hook: { target: 'leftMiddle', tip: 'rightMiddle' },
			ajax: {
				url: PATH_TO_ROOT + '_ajax/task/add_task.php?',
				options: { 
					method: 'get',
					parameters: {
						id: storyId,
						sprintId: $F('sprintBacklog_sprintId')
					},					
					onComplete: function() {
						// Once the "add story" pop-up is shown, add a Click event listener on the "add task" button
						$('sprintBacklog_addTask_submit_' + storyId).observe('click', function(event) {
							var task = new SprintPlanning();
							task.addTask(storyId);
						});	
						$('sprintBacklog_addTask_cancel_' + storyId).observe('click', function(event) {
							Tips.hideAll();																							   
						});
						$('task-add-' + storyId).focus();
					} 
				}
			}
		});
		$('task-add-' + storyId).observe('prototip:shown', function() {
			$('new_task_' + storyId).focus();
		});		
	},	
	
	addTask: function(storyId) {
		// Ajax call to register the new task in the DB.
		var sprintId = $F('sprintBacklog_sprintId');		
		new Ajax.Updater('storyrow-' + storyId, PATH_TO_ROOT + '_ajax/task/add_task_db.php', {
			method:'get',
			parameters: {
				sprintid: $F('sprintBacklog_sprintId'),
				task: $F('new_task_' + storyId), 
				id: storyId },
			insertion: Insertion.After,
			onComplete: function(transport){
				if (200 == transport.status) {
					Tips.hideAll();
					var TrElementOfAddedTask = $('storyrow-' + storyId).next('tr');
					var taskId = TrElementOfAddedTask.id.substr(8);
					Effect.Appear('taskrow-' + taskId);

					new SprintPlanning().enableInteraction(taskId);

					// Reinit content of the fields, in case we want to create a new sub-story.
					$('new_task_' + storyId).value = '';
				}
			},
			onFailure: function(){ alert('Something went wrong...'); }
		});
	}
});