/**
  * Class to manage interactions on stories (inline editing...)
  */
var ProjectPeople = Class.create({
	init: function(projectId) { 
		var params = { 
			ghosting: true, constraint: false, tag: 'div', dropOnEmpty: true, only: 'lineitem', 
			containment: ['productowners','allusers', 'scrummasters', 'team'],
			format: /^user_(.*)$/
		};
		Sortable.create('allusers', params);		

		var listNames = ['productowners', 'scrummasters', 'team'];
		listNames.each(function(listName){
			// Make the list sortable			
			params.onUpdate = function() {	
				new ProjectPeople().updateList(projectId, listName);	
			};
			Sortable.create(listName, params);			
		});
	},
	updateList: function(projectId, listName) {
		var S = new PBSavingMsg();
		var people = Sortable.sequence(listName).toString();
		
		new Ajax.Request(PATH_TO_ROOT + '_ajax/project/project_update_team.php', {
			method: 'post',
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