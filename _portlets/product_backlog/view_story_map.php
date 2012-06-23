<?php
	$allReleases = Release::getAllReleases();

	$nbcolumns = 0;
	foreach ($stories as $key => $story) {
		switch ($story['storytype']) {
			case '2':
				$nbcolumns++;
				break;
			case '1':
			case '3':
			case '4':
			case '5':
				if ($story['epicid'] == 0) { $nbcolumns++; };
				break;
		}
	}
	$width = ($nbcolumns * 200) + ($nbcolumns - 1) * 20;
?>

<table style="margin-top:10px; margin-bottom:10px;">
<thead>
<tr>
	<th>Timeline
		<div style="float:right; top:2px; right:2px">
<?php echo img('icon_big.png', 'Big', 'whiteboardlarge')?>
<?php echo img('icon_medium.png', 'Medium', 'whiteboardmedium')?>
<?php echo img('icon_small.png', 'Small', 'whiteboardsmall')?>
		</div>
    </th>
</tr>
</thead>
</table>

<div class="storymap" style="width:<?php echo $width+20?>px;">
<?php 
	$currentEpic = 0;
	foreach ($stories as $key => $story) {
		$STORY = new StoryDisplay($story);
		switch ($story['storytype']) {
			case '1': // NORMAL
			case '3': // SPIKE
			case '4':
			case '5':
				if ($currentEpic == 0) {
					// Standalone story - not part of an epic
?>
	<div class="storymapcolumn"><?php echo $STORY->renderPostIt()?></div>
<?php
				} else if ($story['epicid'] == $currentEpic) {
?>
<?php echo $STORY->renderPostIt()?>
<?php
				} else {
					$currentEpic = 0;
?>
	</div>
	<div class="storymapcolumn"><?php echo $STORY->renderPostIt()?></div>
<?php
				}
				break;
			case '2': // EPIC
				if ($currentEpic > 0) {
					$currentEpic = $story['id'];
?>
	</div>
	<div class="storymapcolumn">
		<?php echo $STORY->renderPostIt()?>
<?php
				} else {
					$currentEpic = $story['id'];
?>
	<div class="storymapcolumn">
		<?php echo $STORY->renderPostIt()?>
<?php
				}
				break;
		}
	}
	if ($currentEpic > 0) {
?>
	</div>
<?php
	}
?>
	<div style="clear:both"/>
</div>

<script type="text/javascript">
<!--
new ResizingPostIts();
<?php if ($flagHasRight) { ?>
new PBInPlaceEditor('project-goal-<?php echo $projectId;?>', { rows:3 });
<?php } ?>
-->
</script>