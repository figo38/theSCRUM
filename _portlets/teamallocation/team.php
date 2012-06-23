<table>
<caption>Team allocation</caption>
<thead>
<tr>
	<th>Team member</th>
	<th>%</th>
</tr>
</thead>
<tbody>
<?php foreach ($TEAM as $key => $member) { 
	$timeavailable = $S->getNrDays() * $S->getNrHoursPerDay();
	$allocation = isset($ALLOCATION[$member['login']]) ? $ALLOCATION[$member['login']] : '';
?>
<tr>
	<td class="teammember"><div class="inner"> <?=$member['login']?></div></td>
	<td><span id="team-percentage-<?=$member['login']?>-<?=$sprintId?>"><?=$allocation?></span>%</td>
</tr>
<?php } ?>
</tbody>
</table>

<?php if ($USERAUTH->isScrumMasterOf($projectId)) { ?>
<script>
<!--

var ta = new TeamAllocation();
<?php foreach ($TEAM as $key => $member) { ?>
ta.enableInteraction('<?=$member['login'].'-'.$sprintId?>');
<?php } ?>

-->
</script>
<?php } ?>