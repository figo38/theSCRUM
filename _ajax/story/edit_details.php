<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	$storyId = $_REQUEST['id'];
	$S = new Story($storyId, true);
	$tab = $S->getSelectedFeatureGroups();

	$st = $S->getStoryType();
	$associatedReleaseId = $S->getReleaseId();
	$disabled = ($S->isEpic()) ? ' disabled="true" ' : '';
?>
<form id="productBacklog_showdetails-<?=$storyId?>" name="productBacklog_showdetails-<?=$storyId?>" method="post" action="#">

<fieldset>
<legend>Type of user story</legend>

<?php if ($S->isEpic()) echo 'You cannot change an epic into a story or a spike.'; ?>

<div><label for="new_story_type1"><input type="radio" name="new_story_type" value="1" id="new_story_type1" <?php echo $disabled; if ($st == 1) echo ' checked="checked" '; ?>>Story</label></div>
<?php if (!$S->isSubStory()) { // We cannot change a sub-story to an epic ?>
<div><label for="new_story_type2"><input type="radio" name="new_story_type" value="2" id="new_story_type2" <?php echo $disabled; if ($st == 2) echo ' checked="checked" '; ?>>Epic</label></div>
<?php } ?>
<div><label for="new_story_type3"><input type="radio" name="new_story_type" value="3" id="new_story_type3" <?php echo $disabled; if ($st == 3) echo ' checked="checked" '; ?>>Spike</label></div>
</fieldset>

<fieldset>
<legend>Tags</legend>
<ul class="field">
<?php
	foreach ($tab as $key => $val) {
		$checked = ($val['status'] == 0) ? '' : ' checked';
		$fieldId = 'featuregroups-' . $storyId . '-' . $val['id']; 
?>
<li><input id="featuregroups-<?=$storyId?>-<?=$val['id']?>" type="checkbox"<?=$checked?> value="<?=$val['id']?>"><label for="featuregroups-<?=$storyId?>-<?=$val['id']?>"><?=$val['name']?></label></li>
<? } ?>
</ul>
</fieldset>

<fieldset>
<legend>Release</legend>

<select name="relatedrelease" id="relatedrelease">
	<option value="0">None</option>
<?php
	$found = false;
	$releases = Release::getAllActiveReleases();
	foreach ($releases as $key => $release) {
		$selected = ($associatedReleaseId == $release['id']);
		if ($selected) { $found = true; }
?>
	<option <?php if ($selected) { echo 'selected="true"'; } ?> value="<?=$release['id']?>"><?=$release['type']?> <?=$release['name']?></option>
<?php
	}
	if ($associatedReleaseId > 0 && !$found) {
		$R = new Release($associatedReleaseId, true);
?>
	<option selected="true" value="<?=$associatedReleaseId?>"><?=$R->getDisplayName()?></option>
<?php	
	}
?>
</select>

</fieldset>

<p>
<button type="button" id="productBacklog_showDetails_save_<?=$storyId?>" class="action">SAVE</button>
<button type="button" id="productBacklog_showDetails_cancel_<?=$storyId?>">CANCEL</button>
</p>

<fieldset>
<legend>Log</legend>
<ul class="field">
	<li><strong>Created:</strong> <?php echo $S->getCreateDate(); ?></li>
	<li><strong>Updated:</strong> <?php echo $S->getUpdateDate(); ?></li>
	<li><strong>By:</strong> <?php echo $S->getUserLogin(); ?></li>
</ul>
</fieldset>

</form>