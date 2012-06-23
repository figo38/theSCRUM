var Stats = Class.create({
	initialize: function() {
	},
	draw: function(idPrefix, containerId, dataLabel, yLabel) {
		var d1 = [];
		$$('.sprintno').each(function(e) { 
			var i = parseInt(e.innerHTML);
			var worked = $(idPrefix + i).innerHTML;
			d1.push([i, worked]);
		});
		var nbSprint = d1.length;
		var maxHours = 0;
		var averageValue = 0;
		var average4Value = 0;
		var average4Data = [];
		
		for(var i = 0; i < d1.length; i++) {			
			if (maxHours < parseInt(d1[i][1])) {
				maxHours = parseInt(d1[i][1]);
			}
			averageValue = parseInt(d1[i][1]) + averageValue;
			
			if ( i<4) {
				average4Value = averageValue;
				average4Data.push([d1[i][0], average4Value/(i+1)]);
			}
			else {
				average4Value = average4Value -parseInt(d1[i-4][1]) + parseInt(d1[i][1]);
				average4Data.push([d1[i][0], average4Value/4]);
			}			
		}
		
	    var f = Flotr.draw(
			$(containerId), [
				{data:d1, label: dataLabel},
				{data:average4Data, label:'Average (last 4 values)'}
			],{
				legend:{
					position: 'nw', // => position the legend 'south-east'.
					backgroundColor: '#D2E8FF' // => a light blue background color.
				},
				xaxis:{
					noTicks: nbSprint,
					tickFormatter: function(n){ return 'S' + parseInt(n); }
				},
				yaxis:{
					tickFormatter: function(n){ return parseInt(n) + ' ' + yLabel; },
					min: 0,
					max: maxHours + Math.round(maxHours * 10 / 100) /* 25% error margin */
				}
			}
		);
	}
});