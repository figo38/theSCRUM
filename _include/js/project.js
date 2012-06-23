/**
  * Support the project management page - add / edit projects...
  */
var ProjectMngt = Class.create({
	initialize: function() {},
	init: function() {
		// Initialize the "Add Project" button
		new PBAddButton('Add a new project', 'project', function(id){
			var projectmngt = new ProjectMngt();
			projectmngt.enableInteraction(id);
		});

		$$('tr.project').each(function(p) {
			var projectId = p.id.substr(11);
			new ProjectMngt().enableInteraction(projectId);
		});
	},
	enableInlineEditingRoadmap: function(projectId) {
		new PBInPlaceEditor('project-sprintbyquarter-' + projectId, {});
	},
	// Enable inline editing of project
	enableInteraction: function(projectId) {
		new PBInPlaceEditor('project-name-' + projectId, {});
		new PBInPlaceSelect('project-unit-' + projectId, 'projectUnitList', {});		
		new PBInPlaceEditor('project-velocity-' + projectId, {});
		new PBInPlaceEditor('project-goal-' + projectId, { rows:3 });	
		new PBInPlaceCheckbox('project-hassprint-' + projectId, {});
		new PBDeleteObject('project-delete-' + projectId, "Delete the project", 'projectrow-' + projectId);		
	}
});