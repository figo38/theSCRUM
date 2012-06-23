/**
  * Class to manage interactions on stories (inline editing...)
  */
var ProjectPeople = Class.create({
	initialize: function(projectId) { 
		var params = { 
			ghosting: true, constraint: false, tag: 'div', dropOnEmpty: true, only: 'lineitem', 
			containment: ['productowners','allusers', 'scrummasters', 'team'] 
		};
		Sortable.create('allusers', params);		

		// TODO: must be a way to optimize the code below...

		// Make the list of product owners sortable
		params.onUpdate = function() {	
			var p = new ProjectPeople(projectId);
			p.updateList(projectId, 'productowners');			
		};
		Sortable.create('productowners', params);

		// Make the list of scrum masters sortable
		params.onUpdate = function() {	
			var p = new ProjectPeople(projectId);
			p.updateList(projectId, 'scrummasters');			
		};		
		Sortable.create('scrummasters', params);

		// Make the list of team members sortable
		params.onUpdate = function() {	
			var p = new ProjectPeople(projectId);
			p.updateList(projectId, 'team');			
		};		
		Sortable.create('team', params);	
	},
	updateList: function(projectId, listName) {
		var S = new PBSavingMsg();
		var people = Sortable.sequence(listName).toString();

		new Ajax.Request(PATH_TO_ROOT + '_ajax/project/project_update_team.php', {
			method: 'get',
			parameters: {
				id: projectId,
				peoplelist: people,
				listtype: listName
			},
			onSuccess: function() {
				S.done();
			}
		});

	}
});