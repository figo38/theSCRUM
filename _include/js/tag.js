var TagMngt = Class.create({
	initialize: function() { },
	enableInteraction: function(featureGroupId) {
		new PBInPlaceEditor('featuregroup-name-' + featureGroupId, {});
		new PBDeleteObject('featuregroup-delete-' + featureGroupId, "Delete the tag", 'featuregrouprow-' + featureGroupId);		
	},
	initAddFeatureGroupButton: function() {
		new PBAddButton('Add a new tag', 'featuregroup', function(id){
			var tag = new TagMngt();
			tag.enableInteraction(id);
			// Reinit content of the fields, in case we want to create a new project.
		});
	}
});