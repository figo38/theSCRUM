// JavaScript Document
var SprintMngt = Class.create({
	initialize: function() { },
	init: function(projectId) {
		// Initialize the "Add Project" button
		$('addnewobject').observe('click', function(event) {
			var sprintmngt = new SprintMngt();
			sprintmngt.addSprint(projectId);
		});

		$$('tr.sprint').each(function(s) {
			var sprintId = s.id.substr(10);
			new SprintMngt().enableInteraction(sprintId);
		});
	},	
	// Enable inline editing of each sprint
	enableInteraction: function(sprintId) {
		new PBInPlaceEditor('sprint-goal-' + sprintId, { rows: 3 });
		new PBInPlaceEditor('sprint-nrdays-' + sprintId, { cols: 3 });
		new PBInPlaceEditor('sprint-nbhours-' + sprintId, { cols: 3 });
		new PBInPlaceSelect('sprint-unit-' + sprintId, 'sprintUnitList', {});

		new PBCalendar('sprint-startdate-' + sprintId, {});		
		new PBCalendar('sprint-enddate-' + sprintId, {});

		new PBInPlaceCheckbox('sprint-closed-' + sprintId, function(fieldname) { 
			$('sprintrow-' + sprintId).toggleClassName('done');
		});
		new PBLightview('sprint-time-' + sprintId, 'Team allocation', PATH_TO_ROOT + 'teamallocation/' + sprintId);
		
		new PBDeleteObject('sprint-delete-' + sprintId, "Delete the sprint", 'sprintrow-' + sprintId);				
	},
	addSprint: function(projectId) {
		new Ajax.Updater('sprint_tbody', PATH_TO_ROOT + '_ajax/sprint/add_db.php', {
			method:'get',
			parameters: { id: projectId },
			insertion: Insertion.Top,
			onComplete: function(transport){
				if (200 == transport.status) {
					var TrElementOfAddedItem = $('sprint_tbody').firstDescendant();
					var sprintId = parseInt(TrElementOfAddedItem.id.substr(10),10);
					var sprintmngt = new SprintMngt();
					sprintmngt.enableInteraction(sprintId);					
					Effect.Appear('sprintrow-' + sprintId);
				}
			},
			onFailure: function(){ alert('Something went wrong...') }
		});		
	}
});