<?php
	$flagHasRight = $USERAUTH->isProductOwnerOf($projectId) || $USERAUTH->isScrumMasterOf($projectId);
?>	

<?php if ($flagHasRight) { ?>
<div id="actionbar">
	<button type="button" id="addnewobject"><span class="btngAddObject">Add a new story</span></button>
</div>
<?php } else { ?>
<br/>
<?php } ?>

<table id="sortableproductbacklog">
<thead>
<tr>
	<th>#</th>
	<th>Prio</th>
	<th>Estim</th>
	<th>%</th>
	<th>User story</th>
	<th>Acceptance criteria</th>
<?php if ($flagHasRight) { ?>	<th>&nbsp;</th><?php } ?>
</tr>
</thead>
<tbody id="story_tbody">
<?php 
	foreach ($stories as $key => $story) { 
		$D = new StoryDisplay($story);
		$D->render();
	}
?>
</tbody>
</table>

<script type="text/javascript">
<!--
var productbackloginstance = new ProductBacklog();
<?php if ($flagHasRight) { ?>
productbackloginstance.initAddStoryButton();
var story = new Story();
<?php foreach ($stories as $key => $story) { ?>
story.enableInteraction(<?=$story['id']?>,<?=$story['storytype']?>,<?php if (strlen($story['epicid']) == 0) { echo '0'; } else { echo $story['epicid']; } ?>);
<?php }} else { ?>
productbackloginstance.initReadOnly();
<?php } ?>
-->	
</script>

