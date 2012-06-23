var StoryDefautValue = 'As a [role], I can [feature] so that [reason]';
var AcceptanceDefaultValue = 'Given [context] And [some more context]... When [event] Then [outcome] And [another outcome]...';

/**
  * Class to manage a product backlog: add story, edit story...
  */
var ProductBacklog = Class.create({
	// Init the "add story" button by adding a Tip listener on it
	// @param projectid ID of the project for which we want to create a new user story
	initAddStoryButton: function() {
		new ProductBacklogTip('addnewobject', 'Add a new story', {
			stem: 'topLeft',
			hook: { target: 'topMiddle', tip: 'topLeft' },
			ajax: {
				url: PATH_TO_ROOT + '_ajax/story/add.php?id=' + $F('productBacklog_projectId'),
				options: { 
					onComplete: function() {
						PBCancelButton('productBacklog_addStory_cancel');
						PBReset('new_acceptance_emptyit', 'new_acceptance');
						PBReset('new_story_emptyit', 'new_story');
						PBReset('bug_url_field_emptyit', 'bug_url_field_text');
						
						$('productBacklog_addStory_submit').observe('click', function(event) {
							new ProductBacklog().addNewStory();
						});
												
						$('bug_url_field_get_title').observe('click', function(event) {
							$('bug_url_field_get_title').hide();
							$('bug_url_field_waiting').show();
							new Ajax.Request(PATH_TO_ROOT + '_ajax/get_title_from_url.php', {
								method:'post',
								parameters: { url: $F('bug_url_field_text') },
								onComplete: function(transport){
									$('bug_url_field_get_title').show();
									$('bug_url_field_waiting').hide();
									
									if (transport.status == 200) {
										$('new_story').value = transport.responseText;
									}
								},
								onFailure: function(){
									$('bug_url_field_error').show();
									Effect.Fade('bug_url_field_error', { duration: 2.0 });
								}
							});							
						});
						
						$$('#productBacklog_addStory input[type=radio]').each(function(elt){
							elt.observe('click', function(event){
								if (elt.value == 4) {
									$('bug_url_field').show();
								} else {
									$('bug_url_field').hide();								
								}
							});
						});
					},
					onFailure: function(){ alert('Something went wrong...') } 
				}
			}
		});
	},


	/* TODO: a tentative to avoid having too many JS calls in the HTML page */
	init: function() {
		$$('tr.storyn').each(function(s) {
			var storyId = s.id.substr(9);
			var storyType = 0;
			var e = $('storyrow-' + storyId);
			if (e.hasClassName('levelone')) {
				if (e.hasClassName('epic')) {
					storyType = 2;
				} else {
					storyType = 1;
				}
			} else if (e.hasClassName('substory')) {
				storyType = 1;
			}
			alert(storyId);
			//new ProjectMngt().enableInteraction(projectId);
		});
	},
	
	initReadOnly: function() {
		$$('tr.storyn').each(function(s) {
			var storyId = s.id.substr(9);
			var e = $('storyrow-' + storyId);
			if (e.hasClassName('epic')) {
				new ProductBacklog().applyStylesToEpic(storyId);
			}
			new PBLightview("storynotes-" + storyId, $('story-story-' + storyId).innerHTML, PATH_TO_ROOT + 'notes/' + storyId);			
		});
	},

	// Add a new story to the product backlog
	addNewStory: function() {
		// Ajax call to register the new story in the DB.
		var projectId = $F('productBacklog_projectId');

		new Ajax.Updater('story_tbody', PATH_TO_ROOT + '_ajax/story/add_db.php', {
			method:'post',
			parameters: { 
				story: $F('new_story'), 
				acceptance: $F('new_acceptance'),
				url: $F('bug_url_field_text'),
				storytype: Form.getInputs('productBacklog_addStory','radio','new_story_type').find(function(radio) { return radio.checked; }).value,
				id: projectId },
			insertion: Insertion.Top,
			onComplete: function(transport){
				Tips.hideAll();
				var storyId = $('story_tbody').firstDescendant().id.substr(18);
				var storyType = $('storytype-' + storyId).innerHTML;
				var story = new Story();
				story.enableInteraction(storyId, storyType, 0);
										
				Effect.Appear('storyrow-' + storyId);
				Effect.Appear('storyrowblankline-' + storyId);

				// Reinit content of the fields, in case we want to create a new sub-story.
				$('new_story').value = StoryDefautValue;
				$('new_acceptance').value = AcceptanceDefaultValue;
			},
			onFailure: function(){ alert('Something went wrong...') }
		});	
	},
	
	// Add a new story to the product backlog
	addSubStory: function(epicId) {
		// Ajax call to register the new story in the DB.
		var projectId = $F('productBacklog_projectId');
		new Ajax.Updater('storyrow-' + epicId, PATH_TO_ROOT + '_ajax/story/add_story_to_epic_db.php', {
			method:'get',
			parameters: {
				eid: epicId,
				story: $F('new_story_' + epicId), 
				acceptance: $F('new_acceptance_' + epicId),
				storytype: Form.getInputs('productBacklog_addStory-' + epicId,'radio','new_story_type-' + epicId).find(function(radio) { return radio.checked; }).value,				
				id: projectId },
			insertion: Insertion.After,
			onComplete: function(transport){
				if (200 == transport.status) {
					Tips.hideAll();
					var TrElementOfAddedStory = $('storyrow-' + epicId).next('tr');
					var TdElementOfStoryId = TrElementOfAddedStory.firstDescendant();
					var storyId = parseInt(TdElementOfStoryId.innerHTML.substr(1),10);
					var story = new Story();
					story.enableInteraction(storyId, -1, epicId);

					Effect.Appear('storyrow-' + storyId);

					new ProductBacklog().applyStylesToEpic(epicId);

					// Reinit content of the fields, in case we want to create a new sub-story.
					$('new_story_' + epicId).value = StoryDefautValue;
					$('new_acceptance_' + epicId).value = AcceptanceDefaultValue;
				}
			},
			onFailure: function(){ alert('Something went wrong...') }
		});	
	},
	
	// Apply CSS style to epic; called when changes occurs to epic (new story added, updated priorities for sub-stories...)
	applyStylesToEpic: function(epicId) {	
		currentStoryId = 0;
		first = true;
		$$('.substory' + epicId).each(function(item) {
			currentStoryId = item.id.substr(9);
			if (first) {
				first = false;
				item.addClassName('firstsubstory');
				item.removeClassName('lastsubstory');
			} else {
				item.removeClassName('firstsubstory');
				item.removeClassName('lastsubstory');
			}						
		});
		if (currentStoryId > 0) {
			$('storyrow-' + currentStoryId).addClassName('lastsubstory');
		}
	}	
});