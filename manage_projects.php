<?php
	$JS = array('project');
	$pageTitle = 'Project dashboard';
	include '_include/header.php';
?>
<h1>Project dashboard</h1>

<?php if ($USERAUTH->isAdmin()) { ?>
<div id="actionbar">
	<button type="button" id="addnewobject"><span>Add a new project</span></button>
</div>
<?php } else { ?>
<br/>
<?php } ?>

<table id="sortableprojectlist">
<thead>
<tr>
	<th>Project name</th>
	<th>Unit</th>
	<th>Velocity</th>
	<th>Goal</th>
	<th>Manage sprints?</th>
	<th>&nbsp;</th> 
</tr>
</thead>
<tbody id="project_tbody">
<?php 
	foreach ($projects as $key => $project) { 
		$PD = new ProjectDisplay($project);
		$PD->render();
	} 
?>
</tbody>
</table>

<select id="projectUnitList">
<option value="H">Hour</option>
<option value="P">Story points</option>
</select>

<?php if ($USERAUTH->isAdmin()) { ?>
<script type="text/javascript">
<!--
new ProjectMngt().init();
-->	
</script>
<?php } ?>
