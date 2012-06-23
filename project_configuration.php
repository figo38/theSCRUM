<?php
	$JS = array('productbacklog', 'sprint');	
	$menu = 3;
	$pageTitle = 'Sprint configuration';
	include '_portlets/project_header.php';
	
	$SPRINTS = $P->getAllSprints();
?>
	
<?php if ($USERAUTH->isScrumMasterOf($projectId)) { ?>
<div id="actionbar">
	<button type="button" id="addnewobject"><span>Add a new sprint</span></button>
</div>
<?php } else { ?>
<br/>
<?php } ?>
	
<table>
<thead>
<tr>
	<th>Sprint no</th>
	<th>Start date</th>
	<th>End date</th>
	<th>Nr days</th>
	<th>Unit</th>
	<th>Nr units per day</th>		
	<th>Sprint goal</th>
	<th>Closed?</th>
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

<?php if ($USERAUTH->isScrumMasterOf($projectId)) { ?>
<script type="text/javascript">
<!--
new SprintMngt().init(<?=$projectId?>);
-->	
</script>
<?php } ?>