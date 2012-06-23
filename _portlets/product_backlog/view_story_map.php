<?php
	$allReleases = Release::getAllReleases();

	$nbcolumns = 0;
	foreach ($stories as $key => $story) {
		switch ($story['storytype']) {
			case '1':
				if ($story['epicid'] == 0) { $nbcolumns++; };
				break;
			case '2':
				$nbcolumns++;
				break;
			case '3':
				if ($story['epicid'] == 0) { $nbcolumns++; };
				break;
		}
	}
	$width = ($nbcolumns * 200) + ($nbcolumns - 1) * 20;
?>

<div class="storytimeline" style="width:<?=$width?>px;">Timeline &raquo;</div>

<div class="storymap" style="width:<?=$width+20?>px;">
<?php 
	$currentEpic = 0;
	foreach ($stories as $key => $story) {
		$STORY = new StoryDisplay($story);
		switch ($story['storytype']) {
			case '1': // NORMAL
			case '3': // SPIKE
				if ($currentEpic == 0) {
					// Standalone story - not part of an epic
?>
	<div class="storymapcolumn"><?=$STORY->renderPostIt()?></div>
<?
				} else if ($story['epicid'] == $currentEpic) {
?>
<?=$STORY->renderPostIt()?>
<?php
				} else {
					$currentEpic = 0;
?>
	</div>
	<div class="storymapcolumn"><?=$STORY->renderPostIt()?></div>
<?php
				}
				break;
			case '2': // EPIC
				if ($currentEpic > 0) {
					$currentEpic = $story['id'];
?>
	</div>
	<div class="storymapcolumn">
		<?=$STORY->renderPostIt()?>
<?php
				} else {
					$currentEpic = $story['id'];
?>
	<div class="storymapcolumn">
		<?=$STORY->renderPostIt()?>
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