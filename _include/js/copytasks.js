// JavaScript Document
var CopyTasks = Class.create({
	obs: function(eltId, sId, url) {
			$(eltId).observe('click', function(evt){
				// Check if the copy tasks screen has been actioned by the user (yes or no)
				new Ajax.Request(PATH_TO_ROOT + '_ajax/sprint/tasks_copied.php', {
					method:'post',
					parameters: {
						sprintId: sId
					},
					onComplete: function(transport){
						if (transport.responseText == 'true') {
							// If actioned, then mark the sprint as configured							
							new Ajax.Request(PATH_TO_ROOT + '_ajax/sprint/configured.php', {
								method:'post',
								parameters: {
									sprintId: sId
								},
								onComplete: function(transport){
									window.location = url;
								},
								onFailure: function(){}
							});
						} else {				
							// Otherwise, display an error message
							if ($('bSprintBacklogWarnMsg').visible()) {
								$('bSprintBacklogWarnMsg').hide();
								Effect.Appear('bSprintBacklogWarnMsg');
							} else {
								$('bSprintBacklogWarnMsg').show();
								$('bSprintBacklogMsg').hide();
							}
						}
					},
					onFailure: function(){ alert('iooi');}
				});
			});		
	},
	
	initialize: function(sId, url) {
		if ($('unfinishedtasks-yes')) {
			$('unfinishedtasks-yes').observe('click', function(evt){
				$('copyFromPreviousSprint').value = '1';
				$('copyTasksForm').submit();		
			});
		}
	
		if ($('unfinishedtasks-no')) {
			$('unfinishedtasks-no').observe('click', function(evt){
				$('copyFromPreviousSprint').value = '0';
				$('copyTasksForm').submit();			
			});
		}
		
		if ($('buildSprintBacklog')) { this.obs('buildSprintBacklog', sId, url); }
		if ($('buildSprintBacklog2')) { this.obs('buildSprintBacklog2', sId, url); }		
	}
});