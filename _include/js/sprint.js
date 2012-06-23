// JavaScript Document
var SprintMngt = Class.create({
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
		
		new ProductBacklogHelperTip('nr_days', 'The number of working days in the sprint', 'You should exclude all week-ends, bank holidays <br/>or any event that could occur in your company.');

		new ProductBacklogHelperTip('nr_units_per_day', 'The number of working hours or story points per day', 'If you remove meetings that occur during a day, the coffee time, any disturbance...,<br/>chances are each of your team member is only working 5 or 6 hours a day. <br/>Change this setting to reflect the habits in your company');		
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