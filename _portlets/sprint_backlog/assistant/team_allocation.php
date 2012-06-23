<?php
	/**
	  * Display the panel to define the team allocation
	  * @param $S Sprint object
	  * @param $configurationUrl Beginning of URL to lead to the configuration page
	  * @param $allocationErrorMessage true/False indicates if an error msg forcing user to fill team allocation must be displayed.
	  */
	if ($allocationErrorMessage) {
		HelpersControl::warningMsg('You must define the amount of time each team member will be able to work on this sprint. Then <a href="' . $configurationUrl . 'days-selection">select working days &raquo;</a>');		
	} else {
		HelpersControl::infoMsg('Define the amount of time each team member will be able to work on this sprint. Then <a href="' . $configurationUrl . 'days-selection">select working days &raquo;</a>');
	}
?>

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

<script>
<!--

var ta = new TeamAllocation();
<?php foreach ($TEAM as $key => $member) { ?>
ta.enableInteraction('<?php echo $member['login'].'-'.$sprintId?>');
<?php } ?>

-->
</script>