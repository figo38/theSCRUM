<?php
	$nbDays = $S->getNrDays();
	$UNIT = $S->getUnit();
    $res = $S->getBurndownData();

	$nbTasks = $res['nbtasks'];
    $totalEstim = $res['totalestim'];

	$tab = SprintSnapshot::getSnapshots($sprintId);
?>

<div id="sprintburndown"></div> 

<table id="sprintburndowndata">
<thead>
<tr>
	<th>&nbsp;</th>
    <th>Remaining</th>
    <th>Reestim</th>
    <th>Task todo</th>
    <th>Task remaining</th>
</tr>
</thead>
<?php
	$i = 1;
	foreach($tab as $key => $val) {
?>
<tr>
	<td>Day <span class="dayindex"><?=$i?></span></td>
    <td><span id="unit-remaining-<?=$i?>"><?=$val['sps_unit_remaining']?></span><?=$UNIT?></td>
    <td><span id="unit-reestim-<?=$i?>"><?=$val['sps_unit_reestim']?></span><?=$UNIT?></td>    
    <td id="task-todo-<?=$i?>"><?=$val['sps_tasks_todo']?></td>    
    <td id="task-remaining-<?=$i?>"><?=$val['sps_tasks_todo'] + $val['sps_tasks_inprogress']?></td>    
       
</tr>
<?
		$i++;
	} 
?>
</table>

<script type="text/javascript">
<!--

new BurndownChart( <?=$nbDays?>, <?=$totalEstim?>, <?=$nbTasks?>, '<?=$UNIT?>');

-->
</script>