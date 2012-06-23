<table>
<thead>
<tr>
	<th>#</th>
	<th>Estimate</th>    
	<th>Epic</th>
	<th>To be displayed in the roadmap?</th>
	<th>Title used to display the epic in the roadmap</th>
</tr>
</thead>
<tbody id="EPIC_list">	
<?php

	$epicsList = $P->getAllEpics();
	if ($epicsList) {
		foreach ($epicsList as $key => $story) {
?>	
<tr class="blankline">
	<td colspan="5">&nbsp;</td>
</tr>
<tr id="storyrow-<?php echo $story['id']?>">
	<td class="sid"><?php formatStoryId($story['id'])?></td>
	<td><span id="estimation-<?php echo $story['id']?>"><?php echo $story['estimation']?></span></td>
	<td class="epic"><?php formatStoryType(2); ?> <span id="story-<?php echo $story['id']?>"><?php echo $story['story']?></span></td>
	<td><input type="checkbox" id="story-isroadmapdisplayed-<?php echo $story['id']?>" <?php if ($story['isroadmapdisplayed']==1) { echo 'checked="checked"'; } ?>/></td>
	<td><span id="story-title-<?php echo $story['id']?>"><?php echo $story['title']?></span></td>
</tr>
<?php
		}
	} 
?>
</tbody>
</table>

<script type="text/javascript">
<!--
<?php 
	if ($flagHasRight) {
?>
var story = new Story();
<?php
		foreach ($epicsList as $key => $story) { 
?>
story.enableInlineEditingRoadmap(<?php echo $story['id']?>);
<?php 
		} 
?>
new PBInPlaceEditor('project-goal-<?php echo $projectId;?>', { rows:3 });
<?php } ?>
-->	
</script>