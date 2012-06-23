// JS used by the "Manage release" dashboard
var ReleaseMngt = Class.create({
	initialize: function() { },
	// Enable inline editing of each release
	enableInteraction: function(releaseId) {
		new PBInPlaceEditor('release-release-' + releaseId, {});
		new PBInPlaceEditor('release-type-' + releaseId, {});
		new PBInPlaceEditor('release-comment-' + releaseId, { rows: 3 });		
		new PBCalendar('release-planneddate-' + releaseId, {});
		new PBCalendar('release-deployeddate-' + releaseId, {});
		new PBDeleteObject('release-delete-' + releaseId, "Delete the release", 'releaserow-' + releaseId);
	},

	// Display the tooltip to manage the deletion of a link story-release
	enableLinkDeletion: function(storyId) {
		new ProductBacklogTip('deletelink-' + storyId, "Delete the link story-release", {
			title: "Delete the link story-release",
			stem: 'topRight',
			hook: { target: 'topMiddle', tip: 'topRight' },
			ajax: {
				url: PATH_TO_ROOT + '_ajax/releaselink_delete.php?id=' + storyId,
				options: { 
					onComplete: function() {
						// When clicking on the "CANCEL" button, do nothin
						$('productBacklog_deleteReleaseLink_cancel_' + storyId).observe('click', function(event) {
							Tips.hideAll();
						});
						// When clicking on the "GO AHEAD" button, do an AJAX call to remove the story from the DB.
						$('productBacklog_deleteReleaseLink_delete_' + storyId).observe('click', function(event) {
							new Ajax.Request(PATH_TO_ROOT + '_ajax/releaselink_delete_db.php?id=' + storyId, {
								method: 'get',
								onComplete: function(transport) {
									if (transport.responseText == "FAILED") {
										alert('Could not delete the link story-release');
									} else {
										Tips.hideAll();
										Effect.Fade('storyrow-' + storyId);
									}
								}
							});
						});
					}
				} 
			}
		});
	},
	// Initialize the "Add Release" button
	initAddReleaseButton: function() {
		new PBAddButton('Add a new release', 'release', function(id){
			var releasemngt = new ReleaseMngt();
			releasemngt.enableInteraction(releaseId);
		});
	}
});