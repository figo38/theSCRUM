/**
  * Support the project management page - add / edit projects...
  */
var ProjectMngt = Class.create({
	init: function() {
		// Initialize the "Add Project" button
		new PBAddButton('Add a new project', 'project', function(id){
			new ProjectMngt().enableInteraction(id);
		});

		new ProductBacklogHelperTip('project_unit', 'The unit used in the product backlog', 'Choose hours or story points to estimate<br/>each story in your product backlog.');

		new ProductBacklogHelperTip('generation_hour', 'The time of the day when the <br/>burndown chart will be generated', 'You can adjust the time when the burndown chart<br/>will be generated in order to have it ready for your<br/>daily standup. Use HH:MM format to change the<br/>time of generation.');

		$$('tr.project').each(function(p) {
			var projectId = p.id.substr(11);
			new ProjectMngt().enableInteraction(projectId);
		});
	},
	// Enable inline editing of project
	enableInteraction: function(projectId) {
		new PBInPlaceEditor('project-name-' + projectId, {});
		new PBInPlaceSelect('project-unit-' + projectId, 'projectUnitList', {});		
		new PBInPlaceEditor('project-velocity-' + projectId, {});
		new PBInPlaceEditor('project-goal-' + projectId, { rows:3 });	
		new PBInPlaceCheckbox('project-hassprint-' + projectId, {});
		new PBInPlaceEditor('project-generationhour-' + projectId, {});	
		new PBDeleteObject('project-delete-' + projectId, "Delete the project", 'projectrow-' + projectId);		
	}
});