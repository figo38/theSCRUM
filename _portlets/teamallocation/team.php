<table style="width:60%">
<caption>Team allocation</caption>
<thead>
<tr>
	<th>Team member</th>
	<th>Percentage</th>
</tr>
</thead>
<tbody>
<?php foreach ($TEAM as $key => $member) { 
	$timeavailable = $S->getNrDays() * $S->getNrHoursPerDay();
	$allocation = isset($ALLOCATION[$member['login']]) ? $ALLOCATION[$member['login']] : '';
?>
<tr>
	<td class="teammember"><div class="inner"> <?php echo $member['login']?></div></td>
	<td><span id="team-percentage-<?php echo $member['login']?>-<?php echo $sprintId?>"><?php echo $allocation?></span>%</td>
</tr>
<?php } ?>
</tbody>
</table>

<?php if ($USERAUTH->isScrumMasterOf($projectId)) { ?>
<script>
<!--

var ta = new TeamAllocation();
<?php foreach ($TEAM as $key => $member) { ?>
ta.enableInteraction('<?php echo $member['login'].'-'.$sprintId?>');
<?php } ?>

-->
</script>
<?php } ?>