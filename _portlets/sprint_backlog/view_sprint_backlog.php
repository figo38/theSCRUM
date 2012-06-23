<br/>

<?php
	$ALLOCATION = $S->getTeamAllocation();	
	$UNIT = $S->getUnit();
	
	if ($ALLOCATION != NULL) {
?>

<table>
<thead>
<tr>
	<th>#</th>
	<th>Prio</th>
	<th>Estim</th>
	<th>%</th>
	<th>User story / Task</th>
	<th colspan="4">Acceptance criteria</th><?php if (!$S->isClosed() && $flagHasRight) { ?>
	<th>&nbsp;</th><?php } ?>
</tr>
</thead>
<tbody>
<?php 
	if ($stories) {
		foreach ($stories as $key => $story) { 
			$D = new StoryDisplay($story);
			$D->render(($S->isClosed() ? StoryDisplay::$ClosedSprintPlanningView : StoryDisplay::$SprintPlanningView), $TASKS);
		}
	}
?>
</tbody>
</table>

<?php if (!$S->isClosed()) { 
	$totalavailable = 0;
	foreach ($TEAM as $key => $member) {
		$percentage = isset($ALLOCATION[$member['login']]) ? $ALLOCATION[$member['login']] : '0';
		$available = round($S->getNrDays() * $S->getNrHoursPerDay() * $percentage / 100);
		$totalavailable += $available;
	}
?>
<div id="allocationtable">
<table>
<caption>Team allocation: <?php echo $S->getNrHoursPerDay()?> <?php echo $UNIT?>/day for <?php echo $S->getNrDays()?> days <span id="allocationtableshowhide">(Show details)</span></caption>
<thead id="teamallocationtable_thead" class="hidden">
<tr>
	<th>Team member</th>
	<th>%</th>
	<th>Available</th>
	<th>Allocated</th>
	<th>Remaining</th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="2"><strong>Team:</strong></td>
	<td><span id="totalAvail"><?php echo $totalavailable?></span><?php echo $UNIT?></td>
	<td><span id="totalEstim"></span><?php echo $UNIT?></td>
	<td><span id="totalRemain"></span><?php echo $UNIT?></td>
</tr>
</tfoot>
<tbody id="teamallocationtable_tbody" class="hidden">
<?php 
	foreach ($TEAM as $key => $member) { 
		$percentage = isset($ALLOCATION[$member['login']]) ? $ALLOCATION[$member['login']] : '0';
		$available = round($S->getNrDays() * $S->getNrHoursPerDay() * $percentage / 100);
?>
<tr>
	<td class="teammember"><div class="inner"><?php echo $member['login']?></div></td>
	<td><?php echo $percentage?>%</td>
	<td><span id="totalAvail-<?php echo $member['login']?>"><?php echo $available?></span><?php echo $UNIT?></td>
	<td><span id="totalEstim-<?php echo $member['login']?>"></span><?php echo $UNIT?></td>
	<td><span id="totalRemain-<?php echo $member['login']?>"></span><?php echo $UNIT?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

<script type="text/javascript">
<!--
var tasks = new SprintPlanning();
<?php if ($flagHasRight) { ?>
new PBInPlaceEditor('sprint-goal-<?php echo $S->getId();?>', { rows:3 });
tasks.initWriteMode();
<?php foreach ($TASKS as $key => $task) { ?>
tasks.enableInteraction(<?php echo $task['id']?>, <?php echo $task['storyid']?>);
<?php } ?>

<?php } else { ?>
tasks.initReadMode();
<?php } ?>
-->
</script>
<?php }} ?>