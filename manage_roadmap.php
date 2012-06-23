<?php
	$JS = array('story','project');
	$menu = 5;
	$pageTitle = 'Roadmap management';
	include '_portlets/project_header.php';			
?>

Number of sprint by quarter: <span id="project-sprintbyquarter-<?=$projectId?>"><?=$P->getSprintByQuarter()?></span>

<h2>List of epics in the product backlog</h2>
<?php
	$epicsList = $P->getAllEpics();
	if ($epicsList) {
?>
<table>
<thead>
<tr>
	<th>#</th>
	<th>Story</th>
	<th>Estimate</th>
	<th>Title</th>
	<th>Displayed in Roadmap?</th>
</tr>
</thead>
<tbody id="EPIC_list">	
<?php
	} else {
		echo "no EPICs";
	}

	foreach ($epicsList as $key => $story) {
?>	
<tr id="storyrow-<?=$story['id']?>">
	<td class="done">#<?=$story['id']?></td>
	<td>(EPIC) <span id="story-<?=$story['id']?>"><?=$story['story']?></span></td>
	<td><span id="estimation-<?=$story['id']?>"><?=$story['estimation']?></span></td>
	<td><span id="story-title-<?=$story['id']?>"><?=$story['title']?></span></td>
	<td><span id="story-isroadmapdisplayed-<?=$story['id']?>"><?=$story['isroadmapdisplayed']?></span></td>
</tr>
<?php 
	} 
?>
</tbody>
</table>

<script type="text/javascript">
<!--
var story = new Story();
<?php foreach ($epicsList as $key => $story) { ?>
	story.enableInlineEditingRoadmap(<?=$story['id']?>);
<? } ?>

var project = new ProjectMngt();
project.enableInlineEditingRoadmap(<?=$projectId?>);
-->	
</script>