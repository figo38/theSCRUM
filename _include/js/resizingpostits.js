var ResizingPostIts = Class.create({
	initialize: function() {
		$('whiteboardlarge').observe('click', function(event) {
			$$('div.taskplaceholder').each(function(s) {
				s.removeClassName('taskplaceholdersmall');
				s.removeClassName('taskplaceholdermedium');
			});
			$$('div.taskpostit').each(function(s) {
				s.removeClassName('taskpostitsmall');
				s.removeClassName('taskpostitmedium');
			});
			$$('div.storypostit').each(function(s) {
				s.removeClassName('storypostitsmall');
				s.removeClassName('storypostitmedium');
			});
		});
		$('whiteboardmedium').observe('click', function(event) {
			$$('div.taskplaceholder').each(function(s) {
				s.removeClassName('taskplaceholdersmall');
				s.addClassName('taskplaceholdermedium');
			});
			$$('div.taskpostit').each(function(s) {
				s.removeClassName('taskpostitsmall');
				s.addClassName('taskpostitmedium');
			});
			$$('div.storypostit').each(function(s) {
				s.removeClassName('storypostitsmall');
				s.addClassName('storypostitmedium');
			});
		});
		$('whiteboardsmall').observe('click', function(event) {
			$$('div.taskplaceholder').each(function(s) {
				s.removeClassName('taskplaceholdermedium');
				s.addClassName('taskplaceholdersmall');
			});
			$$('div.taskpostit').each(function(s) {
				s.removeClassName('taskpostitmedium');
				s.addClassName('taskpostitsmall');
			});
			$$('div.storypostit').each(function(s) {
				s.removeClassName('storypostitmedium');
				s.addClassName('storypostitsmall');
			});
		});
	}
});