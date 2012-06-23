var ReleaseReport = Class.create({
	initialize: function() {
		$('submitform').observe('click', function(event) {
			var curr = $('filtering').action;
			$('filtering').action = curr + '/' + $F('yearmonth');
		});

		$$('#list span').each(function(e){
			var releaseId = e.id.substring(10);
			e.observe('click', function(event){
				Effect.ScrollTo('release-' + releaseId);
			});
		});		
	}
});