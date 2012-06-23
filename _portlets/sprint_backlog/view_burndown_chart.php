<br/>

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
	<td>Day <span class="dayindex"><?php echo $i?></span></td>
    <td><span id="unit-remaining-<?php echo $i?>"><?php echo $val['sps_unit_remaining']?></span><?php echo $UNIT?></td>
    <td><span id="unit-reestim-<?php echo $i?>"><?php echo $val['sps_unit_reestim']?></span><?php echo $UNIT?></td>    
    <td id="task-todo-<?php echo $i?>"><?php echo $val['sps_tasks_todo']?></td>    
    <td id="task-remaining-<?php echo $i?>"><?php echo $val['sps_tasks_todo'] + $val['sps_tasks_inprogress']?></td>    
       
</tr>
<?php
		$i++;
	} 
?>
</table>

<script type="text/javascript">
<!--

new BurndownChart( <?php echo $nbDays?>, <?php echo $totalEstim?>, <?php echo $nbTasks?>, '<?php echo $UNIT?>');

-->
</script>