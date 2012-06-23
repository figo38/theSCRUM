var BurndownChart = Class.create({
	initialize: function(nbDays, nbUnitTotal, nbTaskTotal, taskUnit) {
		/* Building the "ideal burndown" line - add a value per day, for the purpose of the interactive graph */
		var d1 = [];
		for(var i = 0; i <= nbDays; i += 1){
			d1.push([i, (nbUnitTotal / nbDays) * (nbDays-i)]); /* TODO Fix formula */
		}

		var d2 = []; d2.push([0, nbUnitTotal]);
		var d3 = []; d3.push([0, nbUnitTotal]);
		var d4 = []; d4.push([0, nbTaskTotal])
		//var b1 = [];
	
		$$('.dayindex').each(function(e) {
			var i = parseInt(e.innerHTML);
			var unitRemaining = $('unit-remaining-' + i).innerHTML;
			var unitReestim = $('unit-reestim-' + i).innerHTML;
			var taskTodo = $('task-todo-' + i).innerHTML;
			var taskRemaining = $('task-remaining-' + i).innerHTML;
	
			d2.push([i, unitRemaining]);
			d3.push([i, unitReestim]);
			d4.push([i, taskRemaining]);
			//b1.push([i, taskTodo]);
		});

		var f = Flotr.draw(
			$('sprintburndown'), [
				{data:d1, label:'Ideal burndown', mouse:{track:false}, lines: {show: true, fill: true, fillOpacity: 0.001}, points: {show: true}}, 
				{data:d3, label:'Reestim', mouse:{track:false}, lines: {show: true}},
				{data:d2, label:'Remaining effort', mouse:{track:true}, lines: {show: true, fill: true, fillOpacity: 0.001}, points: {show: true}},
				{data:d4, label:'Remaining tasks', yaxis:2, mouse:{track:false}, lines: {show: true}, points: {show: false}}				
				/*{data:b1, label:'Tasks', yaxis:2, bars: { show: true, barWidth: 0.2, lineWidth: 1, fillOpacity: 0.5}}*/
			], {
				xaxis:{
					noTicks: nbDays,
					tickFormatter: function(n){ return 'Day ' + parseInt(n); },
					min: 0,
					max: nbDays,
				},
				yaxis:{
					tickFormatter: function(n){ return parseInt(n) + taskUnit; },
					min: 0,
					max: nbUnitTotal + Math.round(nbUnitTotal * 25 / 100) /* 25% error margin */
				},
				y2axis:{
					min: 0,
					max: nbTaskTotal + Math.round(nbTaskTotal * 25 / 100)
				},
				mouse:{
					track: true,
					lineColor: 'purple',
					relative: true,
					position: 'ne',
					sensibility: 5, // => The smaller this value, the more precise you've to point
					trackDecimals: 0,
					trackFormatter: function(obj){ return parseInt(obj.y) + taskUnit + ' remaining at Day ' + parseInt(obj.x); }
				},
				legend: {
				  position: 'ne'
				},
				crosshair:{
					mode: 'xy'
				}
			}
		);		
	}								 
});