var StoryNotes = Class.create({
	initialize: function() { },
	enableInteraction: function(storyId) {
		var richTextEditor = new nicEditor({
			buttonList : ['save','bold','italic','underline','strikethrough','fontFormat','forecolor','left','center','right','justify','ol','ul','indent','outdent','removeformat','link','unlink','image'],
			iconsPath : PATH_TO_ROOT + '/images/nicEditorIcons.gif',
			onSave : function(content, id, instance) {
				new Ajax.Request(PATH_TO_ROOT + '_ajax/story/update_comment.php', {
					method: 'post',
					parameters: {
						id: storyId,
						comment: content },
					onComplete: function(transport) {
						if (transport.responseText == "FAILED") {
							alert('Problem');
						} else {
							$('story_notes_panel_save_msg').setStyle({ display: 'none' });
							$('story_notes_panel_ok_msg').setStyle({ display: 'block' });
							Effect.Fade('story_notes_panel_ok_msg');
						}
					}
				});
			} 
		});
		richTextEditor.setPanel('story_notes_panel');
		richTextEditor.addInstance('story_notes');	

		richTextEditor.addEvent('focus', function() {
			$('story_notes_panel_save_msg').setStyle({ display: 'block' });
		});
	}
});