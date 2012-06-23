var Retro = Class.create({
	initialize: function() {
		var sprintId = $F('sprintBacklog_sprintId');
		new PBInPlaceEditor('sprint-retro1-' + sprintId, { rows: 5 });
		new PBInPlaceEditor('sprint-retro2-' + sprintId, { rows: 5 });		
	}						 
});