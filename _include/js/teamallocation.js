// JavaScript Document
var TeamAllocation = Class.create({
	initialize: function() {
	},
	enableInteraction: function(fieldname) {
		new PBInPlaceEditor('team-percentage-' + fieldname, { cols: 3 });
	}
});