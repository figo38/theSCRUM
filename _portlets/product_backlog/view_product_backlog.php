<?php if ($flagHasRight) { ?>
<div id="actionbar">
	<div class="left">
		<button type="button" id="addnewobject"><span class="btngAddObject">Add a new story</span></button>
	</div>
	<div class="right">
		<form method="post" id="filtering" action="<?php echo $projectUrl?>">
		<input type="hidden" name="id" id="productBacklog_projectId" value="<?php echo $projectId?>"/>
		<input type="checkbox" name="showCompletedStories" id="showCompletedStories" value="1" <?php if ($showCompletedStories == 1) echo 'checked'; ?>/> <label for="showCompletedStories" style="display:inline">Show completed stories</label>	
		<button type="submit" id="submitform">Refresh</button>
		</form>
	</div>
	<div class="clear"></div>
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
	if ($stories) {
		foreach ($stories as $key => $story) { 
			$D = new StoryDisplay($story);
			$D->render();
		}
	}
?>
</tbody>
</table>

<script type="text/javascript">
<!--
var productbackloginstance = new ProductBacklog();
<?php if ($flagHasRight) { ?>
new PBInPlaceEditor('project-goal-<?php echo $projectId;?>', { rows:3 });
productbackloginstance.initAddStoryButton();
var story = new Story();
<?php foreach ($stories as $key => $story) { ?>
story.enableInteraction(<?php echo $story['id']?>,<?php echo $story['storytype']?>,<?php if (strlen($story['epicid']) == 0) { echo '0'; } else { echo $story['epicid']; } ?>);
<?php }} else { ?>
productbackloginstance.initReadOnly();
<?php } ?>
-->	
</script>

