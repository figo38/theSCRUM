<?php
	$SPRINTS = $P->getAllSprints();

	if ($flagHasRight) { 
?>
<div id="actionbar">
	<button type="button" id="addnewobject"><span>Add a new sprint</span></button>
</div>
<?php 
	} else { 
?>
<br/>
<?php 
	} 
?>
	
<table>
<thead>
<tr>
	<th>Sprint no</th>
	<th>Start date</th>
	<th>End date</th>
	<th><abbr id="nr_days">Nr days</abbr></th>
	<th>Unit</th>
	<th><abbr id="nr_units_per_day">Nr units per day</abbr></th>		
	<th>Sprint goal</th>
	<th>Close the sprint</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody id="sprint_tbody">
<?php
	foreach ($SPRINTS as $key => $sprint) { 
		$SD = new SprintDisplay($sprint);
		$SD->render();
	} 
?>
</tbody>
</table>	

<select id="sprintUnitList">
<option value="H">Hour</option>
<option value="P">Story points</option>
</select>

<?php if ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) { ?>
<script type="text/javascript">
<!--
new SprintMngt().init(<?php echo $projectId?>);
-->	
</script>
<?php } ?>