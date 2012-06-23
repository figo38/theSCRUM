<div class="infoMsg">
	<div class="inner">The sprint is now closed.</div>
</div>

<table>
<caption>Retrospective</caption>
<thead>
<tr>
	<th>What went well during the sprint?</th>
    <th>What could be improved in the next sprint?</th>
</tr>
</thead>
<tbody>
<tr>
	<td style="width:50%"><span id="sprint-retro1-<?=$sprintId?>"><?=nl2br($S->getRetro1())?></span></td>
    <td style="width:50%"><span id="sprint-retro2-<?=$sprintId?>"><?=nl2br($S->getRetro2())?></span></td>
</tr>
</tbody>
</table>
<br/>

<?php 
	$stats = $S->getStatistics();
	$UNIT = $S->getUnit();
	$totalnbtasks = 0;
	foreach ($stats as $key => $val) {
		$totalnbtasks = $totalnbtasks + $val['nbtasks'];
	}
?>
<table>
<caption>Statistics of the sprint</caption>
<thead>
<tr>
	<th>&nbsp;</th>
	<th>Nr tasks</th>
	<th>Estim.</th>
	<th>Reestim.</th>
	<th>Worked</th>
</tr>
</thead>
<?php		
	foreach ($stats as $key => $val) {
		switch ($val['status']) {
			case '0':
				$title = 'Tasks in TODO status';
				break;
			case '1':
				$title = 'Tasks in IN PROGRESS status';
				break;
			case '2':
				$title = 'Tasks in DONE status';
				break;
		}
?>
<tr>
	<td><?=$title?></td>
	<td><?=round($val['nbtasks'] / $totalnbtasks * 100)?>%</td>
	<td><?=$val['totalestim']?><?=$UNIT?></td>
	<td><?=$val['totalreestim']?><?=$UNIT?></td>
	<td><?=$val['worked']?><?=$UNIT?></td>	
</tr>
<?php
	}
?>
</table>
<br/>