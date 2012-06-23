var STORYTYPE_EPIC=2;

/**
  * Class to manage interactions on stories (inline editing...)
  */
var Story = Class.create({
	/**
	  *
	  */
	addSubStory: function(storyId) {
		new ProductBacklogTip('addstory-' + storyId, 'Add a sub-story', {
			stem: 'rightMiddle',
			hook: { target: 'leftMiddle', tip: 'rightMiddle' },
			ajax: {
				url: PATH_TO_ROOT + '_ajax/story/add_story_to_epic.php?id=' + $F('productBacklog_projectId') + '&epicId=' + storyId,
				options: { 
					onComplete: function() {
						// Once the "add story" pop-up is shown, enables the "add story", "cancel" buttons and reset links
						PBClick('productBacklog_addStory_submit_' + storyId, function(evt) {
							new ProductBacklog().addSubStory(storyId);
						});						
						PBCancelButton('productBacklog_addStory_cancel_' + storyId);
						PBReset('new_acceptance_emptyit_' + storyId, 'new_acceptance_' + storyId);
						PBReset('new_story_emptyit_' + storyId, 'new_story_' + storyId);
					} 
				}
			}
		});
	},

	/**
	  * Move a substory whose priority has been changed to its right place in the epic (sorted by priority desc)
	  * @param storyId Story whose priority has been changed
	  * @param epicId Epic the story belongs to
	  */
	moveStoryInsideEpic: function(storyId, epicId) {
		var storyIdPrio = $('story-prio-' + storyId).innerHTML;		
		var storyIdToInsertBefore = 0; // While iterating over all stories of the epic, this is the storyId identified to move the updated substory before.
		var currentStoryId = 0; // Iterate over all the stories of the epic - currentStoryId is the current story being evaluated
		
		// Iterate over all the stories belonging to the epic of the updated story
		ok = false;
		$$('.substory' + epicId).each(function(item) {
			currentStoryId = item.id.substr(9);
			// Skipping the updated story
			if (currentStoryId != storyId) {
				currentPrio = $('story-prio-' + currentStoryId).innerHTML;
				currentPrioDiff = (storyIdPrio - currentPrio);
				if (currentPrioDiff > 0 && !ok) {
					storyIdToInsertBefore = currentStoryId;
					ok = true;
				}
			}
		});
					
		if (storyIdToInsertBefore == 0) {
			// Moving the updated story at the bottom of the epic
			$("storyrow-" + currentStoryId).insert({ 'after': $('storyrow-' + storyId) });						
		} else {
			$("storyrow-" + storyIdToInsertBefore).insert({ 'before': $('storyrow-' + storyId) });
		}
		new ProductBacklog().applyStylesToEpic(epicId);					
		new Effect.Highlight($('storyrow-' + storyId));			
	},
	
	/**
	  * Move a level-one story (epic or standalone story/spike) whose priority has been changed to its right place in the product backlog (sorted by priority desc)
	  * @param storyId Story whose priority has been changed
	  */
	moveLevelOneStory: function(storyId) {
		var storyIdPrio = $('story-prio-' + storyId).innerHTML;					
		var storyIdToInsertBefore = 0; // While iterating over all stories of the backlog, this is the storyId identified to move the updated story before.
		var currentStoryId = 0; // Iterate over all the stories of the backlog - currentStoryId is the current story being evaluated
		var isEpic = $("storyrow-" + storyId).hasClassName('epic');

		// Iterate over all the level-one stories of the product backlog to find where to insert the updated story
		ok = false;
		$$('.levelone').each(function(item) {
			currentStoryId = item.id.substr(9);
			// Skipping the updated story
			if (currentStoryId != storyId) {
				currentPrio = $('story-prio-' + currentStoryId).innerHTML;
				currentPrioDiff = (storyIdPrio - currentPrio);

				if (currentPrioDiff > 0 && !ok) {
					storyIdToInsertBefore = currentStoryId;
					ok = true;
				}
			}
		});
					
		if (storyIdToInsertBefore == 0) {
			// Moving the updated story at the bottom of the backlog
			$("storyrow-" + currentStoryId).insert({ 'after': $('storyrow-' + storyId) });
		} else {
			// Moving the updated story at the right position in the backlog
			$("storyrowblankline-" + storyIdToInsertBefore).insert({ 'before': $('storyrow-' + storyId) });		
		}
		$('storyrow-' + storyId).insert({ 'before': $('storyrowblankline-' + storyId) });

		new Effect.Highlight($('storyrow-' + storyId));
		
		// If the moved story was an epic, then move all its sub-stories accordingly
		if (isEpic) {			
			previousStoryId = storyId;
			$$('.substory' + storyId).each(function(item) {
				currentStoryId = item.id.substr(9);
				// Skipping the updated story
				$("storyrow-" + previousStoryId).insert({ 'after': $('storyrow-' + currentStoryId) });
				previousStoryId = currentStoryId;
			});					
		}		
	},

	/* TODO */
	enableInlineEditingRoadmap: function(storyId) {
		new PBInPlaceEditor('story-title-' + storyId, {});
		new PBInPlaceCheckbox('story-isroadmapdisplayed-' + storyId, {});
	},


	editStoryDetails: function(storyId, storyType, epicId) {
		// Manage the tooltip to change the details of a story
		new ProductBacklogTip('details-' + storyId, "Story details", {
			stem: 'rightMiddle',
			hook: { target: 'leftMiddle', tip: 'rightMiddle' },
			ajax: {
				url: PATH_TO_ROOT + '_ajax/story/edit_details.php?id=' + storyId,
				options: { onComplete: function() {
					// Manage navigation bar
					$$('#editStoryPanelNavigation-' + storyId + ' li').each(function(elt){
						elt.observe('click', function(evt) {
							$$('#editStoryPanelNavigation-' + storyId + ' li').each(function(elt1){
								var toHide = elt1.id.substring(3);
								$(toHide).hide();
								elt1.removeClassName('selected');
							});
							var toShow = elt.id.substring(3);
							elt.addClassName('selected');
							$(toShow).show();		
						});
					});
					
					// Manage the "Move outside epic" option
					PBClick('productBacklog_moveOutsideEpic_save-' + storyId, function(evt){
						var S = new PBSavingMsg();
						new Ajax.Request(PATH_TO_ROOT + '_ajax/story/move_outside_epic.php', {
							method:'post',
							parameters: {
								id: storyId
							},
							onSuccess: function(transport){
								if (transport.responseText == 'true') {
									Tips.hideAll();

									$('storyrow-' + storyId).addClassName('levelone');
									$('storyrow-' + storyId).removeClassName('substory');
									$('storyrow-' + storyId).removeClassName('firstsubstory');
									$('storyrow-' + storyId).removeClassName('lastsubstory');										
									$('storyrow-' + storyId).removeClassName('substory' + epicId);
									$("storyrowblankline-" + epicId).insert({ 'before': $('storyrow-' + storyId) });
									$('story-prio-' + storyId).innerHTML = '0';
										
									var td = new Element('td', { colspan: '7' }).update("&nbsp;");
									var tr = new Element('tr', { class: 'blankline', id: 'storyrowblankline-' + storyId }).update(td);
									$("storyrow-" + storyId).insert({ 'before': tr });
																							
									new ProductBacklog().applyStylesToEpic(epicId);
									Effect.ScrollTo('storyrow-' + storyId);
									new Effect.Highlight($('storyrow-' + storyId));										
									S.done();						

									// Force the reloading of the "show details" panel 
									new Story().editStoryDetails(storyId, storyType, 0);									
								} else {
									S.done();
								}
							}
						});
					});


					// Manage the "Move inside epic" option
					PBClick('productBacklog_moveInsideEpic_save-' + storyId, function(evt){																					
						// First step: validate if the epic ID is valid
						var S = new PBSavingMsg();
						var newepicId = $F('productBacklog_moveInsideEpic_epicId-' + storyId);
						new Ajax.Request(PATH_TO_ROOT + '_ajax/story/move_inside_epic.php', {
							method:'post',
							parameters: {
								id: storyId,
								eid: newepicId
							},
							onSuccess: function(transport){
								if (transport.responseText == 'true') {
									Tips.hideAll();
									// Move the story to its new location
									$("storyrow-" + newepicId).insert({ 'after': $('storyrow-' + storyId) });
									$("storyrowblankline-" + storyId).remove();
									$('storyrow-' + storyId).removeClassName('levelone');
									$('storyrow-' + storyId).addClassName('substory');
									$('storyrow-' + storyId).addClassName('substory' + newepicId);
									new ProductBacklog().applyStylesToEpic(newepicId);
									Effect.ScrollTo('storyrow-' + newepicId);										
									new Effect.Highlight($('storyrow-' + storyId));
									S.done();
										
									// Force the reloading of the "show details" panel
									new Story().editStoryDetails(storyId, storyType, newepicId);
								} else {
									$('productBacklog_moveInsideEpic_error-' + storyId).show();
									Effect.Fade('productBacklog_moveInsideEpic_error-' + storyId);
									S.done();
								}
							}
						});
					});

					PBCancelButton('productBacklog_showDetails_cancel_' + storyId);
					PBReset('bug_url_field_emptyit_' + storyId, 'bug_url_field_text_' + storyId);

					$$('#productBacklog_showdetails-' + storyId + ' input[type=radio]').each(function(elt){
						elt.observe('click', function(event){
							if (elt.value == 4) {
								$('bug_url_field_' + storyId).show();
							} else {
								$('bug_url_field_' + storyId).hide();								
							}
						});
					});

					// When saving, AJAX call to register changes in the DB
					$('productBacklog_showDetails_save_' + storyId).observe('click', function(event) {
						// Retrieve the values of the fields
						var fields = Form.getElements("productBacklog_showdetails-" + storyId);
						var selectedFields = '';
						var storyType = '';
						var selectedRelease = '';
						var urlField = $F('bug_url_field_text_' + storyId);

						fields.each(function(item) {
							if (item.id.startsWith('featuregroups-') && item.checked) {
								selectedFields += item.value + ';';
							}
							if (item.id.startsWith('new_story_type') && item.checked) {
								storyType = item.value;
							}
							if (item.id.startsWith('relatedrelease')) {
								selectedRelease = item.value ;
							}
						});
						
						// AJAX call to save changes
						var S = new PBSavingMsg();						
						new Ajax.Request(PATH_TO_ROOT + '_ajax/story/save_details.php', {
							method:'post',
							parameters: { 
								tags: selectedFields, 
								id: storyId,
								url: urlField,
								releaseId: selectedRelease,
								storytype: storyType },
							onSuccess: function(transport){
								// When changes saved, hide the tooltip
								Tips.hideAll();
								// Refresh the display of the story in the backlog
								$('storymaincell-' + storyId).removeClassName('epic');
								$('storymaincell-' + storyId).removeClassName('bug');
								$('storymaincell-' + storyId).removeClassName('impediment');
								$('storymaincell-' + storyId).removeClassName('spike');

								$('story-url-' + storyId).innerHTML = '';
								$('storytype-' + storyId).innerHTML = storyType;
								
								if (storyType == 1) {
									// Story
									$('storytypedisp-' + storyId).innerHTML = '';
								} else if (storyType == 2) {
									// Epic
									$('storyrow-' + storyId).addClassName('epic');
									$('storytypedisp-' + storyId).innerHTML = '<strong class="labelepic">EPIC:</strong>';
									$('storymaincell-' + storyId).addClassName('epic');
									$('addstory-' + storyId).removeClassName('hidden');
									new Story().addSubStory(storyId);
								} else if (storyType == 3) {
									// Spike
									$('storymaincell-' + storyId).addClassName('spike');
									$('storytypedisp-' + storyId).innerHTML = '<strong class="labelspike">SPIKE:</strong>';
								} else if (storyType == 4) {
									// Bug
									$('storymaincell-' + storyId).addClassName('bug');
									$('storytypedisp-' + storyId).innerHTML = '<strong class="labelbug">BUG:</strong>';
								
									if (urlField.length > 0) {
										var a = new Element('a', { 'class': 'url', href: urlField }).update("&raquo;");
										$('story-url-' + storyId).insert(a);
									}
								} else if (storyType == 5) {
									// Impediment
									$('storymaincell-' + storyId).addClassName('impediment');
									$('storytypedisp-' + storyId).innerHTML = '<strong class="labelimpediment">IMPEDIMENT:</strong>';
								}
								S.done();
								
								new Story().editStoryDetails(storyId, storyType, epicId);
								//new Effect.Highlight($('storymaincell-' + storyId));	
							},
							onFailure: function(){ alert('Something went wrong...') }
						});
					}); 
				} }
			}
		});
	},

	getEpicId: function(storyId) {
		var epicId = 0;
		if ($('storyrow-' + storyId).hasClassName('substory')) {
			$('storyrow-' + storyId).classNames().each(function(classname) {
				if (classname.startsWith('substory') && classname.length > 8) {
					epicId = classname.substr(8);
				}												
			});
		}
		return epicId;
	},

	/**
	  * In-place editor for the priority of story
	  */
	enableInteractionPriority: function(storyId, storyType) {
		// In-line editor for the "piority" field
		new PBInPlaceEditor('story-prio-' + storyId, { 
			cols: 4,
			onComplete: function() {
				// When the "priority" field has been updated, we do the following actions:
				// 1. If a sub-story has been updated, then synch-up the priority of the corresponding epic
				// 2. Dynamically re-order the product backlog based on the new priority
				var epicId = new Story().getEpicId(storyId);
				
				if (epicId > 0) {
					// Move the updated story in order for all the substories inside the epic to be sorted properly
					new Story().moveStoryInsideEpic(storyId, epicId);
					
					// The updated story was a sub-story of an epic
					var storyIdPrio = $('story-prio-' + storyId).innerHTML;
					var epicIdPrio = $('story-prio-' + epicId).innerHTML;
					// If the updated prio of the sub-story is lower than the prio of the epic, then we update the prio of the epic with the same value.
					if (storyIdPrio - epicIdPrio > 0) {
						new Ajax.Updater('story-prio-' + epicId, PATH_TO_ROOT + '_ajax/field_update.php', {
							method:'post',
							parameters: {
								id: 'story-prio-' + epicId, 
								old_content: epicIdPrio,
								new_content: storyIdPrio },
							onComplete: function(transport){
								new Effect.Highlight($('story-prio-' + epicId));
								new Story().moveLevelOneStory(epicId);
							},
							onFailure: function(){ alert('Something went wrong...') }
						});
					}
					
				} else {
					// Move the updated story in order for all the substories inside the epic to be sorted properly
					new Story().moveLevelOneStory(storyId);
				} 
			}
		});
	},

	/**
	  * In-place editor for the priority of story
	  */
	enableInteractionPercentage: function(storyId) {
		new PBInPlaceEditor('story-percentage-' + storyId, { 
			cols: 3, 
			onComplete: function() {
				// Once percentage has been changed, adjust the display of the story.
				var pc = $('story-percentage-' + storyId).innerHTML;
				
				// Add a color indicating the progress of the tasks
				var classArray = $('percenttd-' + storyId).classNames().toArray();
				classArray.each(function(item) {
					if (item.startsWith('percent')) {
						$('percenttd-' + storyId).removeClassName(item);
					}
				});
				$('percenttd-' + storyId).addClassName("percent" + (10*Math.floor(pc/10)) );
				
				// Add or remove grey background on the whole story line when changing the story to 100% or less.
				if (pc == 100) {
					$$('#storyrow-' + storyId + ' td').each(function(td) {
						td.addClassName('done');
					});				
				} else {
					$$('#storyrow-' + storyId + ' td').each(function(td) {
						td.removeClassName('done');
					});					
				}				
			}
		});		
	},

	/**
	  * Display the tooltip to manage the deletion of a user story, and AJAX call to effectively delete the story
	  */
	enableInteractionDeleteStory: function(storyId, storyType, epicId) {
		new ProductBacklogTip('delete-' + storyId, 'Delete the story', {
			stem: 'topRight',
			hook: { target: 'topMiddle', tip: 'topRight' },
			ajax: {			
				url: PATH_TO_ROOT + '_ajax/story/delete.php?id=' + storyId,
				options: { onComplete: function() {
					PBCancelButton('productBacklog_deleteStory_cancel_' + storyId);

					// When clicking on the "GO AHEAD" button, do an AJAX call to remove the story from the DB.
					$('productBacklog_deleteStory_delete_' + storyId).observe('click', function(event) {
						new Ajax.Request(PATH_TO_ROOT + '_ajax/story/delete_db.php', {
							method: 'post',
							parameters: { id: storyId },
							onComplete: function(transport) {
								if (transport.responseText == 'FAILED') {
									alert('Problem');
								} else {
									Tips.hideAll();
									$w(transport.responseText.replace(/,/g," ")).each(function(sid){
										if ($('storyrow-' + sid) != undefined) {
											Effect.Fade('storyrow-' + sid);
										}
										if ($('storyrowblankline-' + sid) != undefined) {
											Effect.Fade('storyrowblankline-' + sid);
										}
									});
								}
								new ProductBacklog().applyStylesToEpic(epicId);
							}
						});
					}); 
				} }
			}
		});		
	},

	/**
	  * Enable interaction on all fields of a story
	  * TODO: not good design: storyType = -1 ; epicID = -1 when we don't know those values when calling the method
	  */
	enableInteraction: function(storyId, storyType, epicId) {
		// Basic in-place editors (estimation, story title, acceptance criteria, story notes)
		new PBInPlaceEditor('story-estim-' + storyId, { cols: 3 });
		new PBInPlaceEditor('story-story-' + storyId, { rows: 3 });
		new PBInPlaceEditor('story-criteria-' + storyId, { rows: 3 });
		new PBLightview('storynotes-' + storyId, $('story-story-' + storyId).innerHTML, PATH_TO_ROOT + 'notes/' + storyId);		
		
		// In-place editor for percentage of completion
		this.enableInteractionPercentage(storyId);
		
		// In-place editors for priority and story details
		this.enableInteractionPriority(storyId, storyType);				
		this.editStoryDetails(storyId, storyType, epicId);
		
		// Manage the deletion of story
		this.enableInteractionDeleteStory(storyId, storyType, epicId);

		// If this is an epic, then activate the "add sub story" button
		if (storyType == STORYTYPE_EPIC) {
			new ProductBacklog().applyStylesToEpic(storyId);
			this.addSubStory(storyId);
		}
	}
});