// JavaScript Document
var DaysSelection = Class.create({
	initialize: function(sId) {
		$$('.big_calendar .day').each(function(day){
			day.observe('click', function(dayevt) {
				var nbdays = parseInt($('sprint-nbdays-allocated').innerHTML);
				var dayid = day.id.substring(4);
				
				if (nbdays > 0 || day.hasClassName('selected')) {
					day.toggleClassName('selected');
					if (day.hasClassName('selected')) {
						var S = new PBSavingMsg();
						new Ajax.Request(PATH_TO_ROOT + '_ajax/sprintdays/registerDay.php', {
							method:'post',
							parameters: { 
								sprintId: sId, 
								dayAsString: dayid
							},
							onComplete: function(transport){
								if (200 == transport.status) {
									S.done();
								}							
							}
						});				
						nbdays--;									
					} else {
						var S = new PBSavingMsg();
						new Ajax.Request(PATH_TO_ROOT + '_ajax/sprintdays/unregisterDay.php', {
							method:'post',
							parameters: { 
								sprintId: sId, 
								dayAsString: dayid
							},
							onComplete: function(transport){
								if (200 == transport.status) {
									S.done();
								}							
							}
						});					
						nbdays++;
					}
					
					$('sprint-nbdays-allocated').innerHTML = nbdays;
					if (nbdays == 0) {
						$('sprint-nbdays-allocated').addClassName('ok');
					} else {
						$('sprint-nbdays-allocated').removeClassName('ok');
					}
				}
			});   
		});		
	}
});