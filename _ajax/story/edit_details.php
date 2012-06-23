<?php
	/**
	  * Story "Edit details" panel
	  */
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$storyId = $_REQUEST['id'];
	$S = new Story($storyId, true);
	$tab = $S->getSelectedFeatureGroups();

	$st = $S->getStoryType();
	$associatedReleaseId = $S->getReleaseId();
	$disabled = ($S->isEpic()) ? ' disabled="true" ' : '';
	
	// Decide if we display the "move to" panel, and see which option to display in this panel if needed.
	$showMoveToPanel = false;
	$displayMoveInsideEpic = false;
	$displayMoveOutsideEpic = false;
	if ($S->isStandAlone() && ($S->isStory() || $S->isSpike())) {
		$showMoveToPanel = true;
		$displayMoveInsideEpic = true;
	} else if ($S->isSubStory()) {
		$showMoveToPanel = true;
		$displayMoveOutsideEpic = true;
	}
	
	// Display navigation bar, then independant panels
?>

<ul class="editStoryPanel" id="editStoryPanelNavigation-<?php echo $storyId?>">
	<li class="selected" id="li-editStoryPanelProperties-<?php echo $storyId?>">Properties</li>
<?php if ($showMoveToPanel) { ?>
    <li id="li-editStoryPanelMoveTo-<?php echo $storyId?>">Move to...</li>
<?php } ?>
</ul>

<div id="editStoryPanelProperties-<?php echo $storyId?>">
<form id="productBacklog_showdetails-<?php echo $storyId?>" name="productBacklog_showdetails-<?php echo $storyId?>" method="post" action="#">

<fieldset>
<legend>Type of user story</legend>
<?php if ($S->isEpic()) echo 'You cannot change an epic into a standalone story (story, spike, bug or impediment).'; ?>

<div>
	<div class="left" style="margin-right:75px">
		<label for="new_story_type1"><input type="radio" name="new_story_type" value="1" id="new_story_type1" <?php echo $disabled; if ($st == 1) echo ' checked="checked" '; ?>>Story</label>
<?php if (!$S->isSubStory()) { // We cannot change a sub-story to an epic ?>
		<label for="new_story_type2"><input type="radio" name="new_story_type" value="2" id="new_story_type2" <?php echo $disabled; if ($st == 2) echo ' checked="checked" '; ?>>Epic</label>
<?php } ?>
		<label for="new_story_type3"><input type="radio" name="new_story_type" value="3" id="new_story_type3" <?php echo $disabled; if ($st == 3) echo ' checked="checked" '; ?>>Spike</label>
	</div>
<?php if (!$S->isSubStory()) { // No bug / impediment inside epics ?>    
    <div class="left">
		<label for="new_story_type4"><input type="radio" name="new_story_type" value="4" id="new_story_type4" <?php echo $disabled; if ($st == 4) echo ' checked="checked" '; ?>>Bug</label>
		<label for="new_story_type5"><input type="radio" name="new_story_type" value="5" id="new_story_type5" <?php echo $disabled; if ($st == 5) echo ' checked="checked" '; ?>>Impediment</label>
	</div>
<?php } ?>
</div>
</fieldset>

<fieldset<?php if ($st != 4) {?> style="display:none"<?php } ?> id="bug_url_field_<?php echo $storyId?>">
<legend>Bug details</legend>
<p class="field">
	<a href="#" class="reset" id="bug_url_field_emptyit_<?php echo $storyId?>">Reset</a>
	<label for="bug_url_field_text_<?php echo $storyId?>">URL:</label>
	<input type="text" id="bug_url_field_text_<?php echo $storyId?>" value="<?php echo $S->getUrl()?>" style="width:100%"/>
</p>
</fieldset>

<fieldset>
<legend>Tags</legend>
<div class="editStory_tagList">
<ul class="field">
<?php
	foreach ($tab as $key => $val) {
		$checked = ($val['status'] == 0) ? '' : ' checked';
		$fieldId = 'featuregroups-' . $storyId . '-' . $val['id']; 
?>
<li><input id="featuregroups-<?php echo $storyId?>-<?php echo $val['id']?>" type="checkbox"<?php echo $checked?> value="<?php echo $val['id']?>"><label for="featuregroups-<?php echo $storyId?>-<?php echo $val['id']?>"><?php echo $val['name']?></label></li>
<?php } ?>
</ul>
</div>
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
	<option <?php if ($selected) { echo 'selected="true"'; } ?> value="<?php echo $release['id']?>"><?php echo $release['type']?> <?php echo $release['name']?></option>
<?php
	}
	if ($associatedReleaseId > 0 && !$found) {
		$R = new Release($associatedReleaseId, true);
?>
	<option selected="true" value="<?php echo $associatedReleaseId?>"><?php echo $R->getDisplayName()?></option>
<?php	
	}
?>
</select>

</fieldset>

<p>
<button type="button" id="productBacklog_showDetails_save_<?php echo $storyId?>" class="action">SAVE</button>
<button type="button" id="productBacklog_showDetails_cancel_<?php echo $storyId?>">CANCEL</button>
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
</div>

<div id="editStoryPanelMoveTo-<?php echo $storyId?>" style="display:none; min-height:300px;"">

<?php if ($displayMoveInsideEpic) { ?>
<form id="productBacklog_moveInsideEpic-<?php echo $storyId?>" name="productBacklog_moveInsideEpic-<?php echo $storyId?>" method="post" action="#">

<fieldset>
<legend>Move inside epic</legend>
<p>You can move this story inside an existing epic by entering the epic number (first column in the product backlog) and validating.</p>
<p class="field">
	<label for="productBacklog_moveInsideEpic_epicId-<?php echo $storyId?>">Epic number:</label>
	<input type="text" id="productBacklog_moveInsideEpic_epicId-<?php echo $storyId?>" value=""/>
    <span id="productBacklog_moveInsideEpic_error-<?php echo $storyId?>" class="errorText" style="display:none">This is not a valid epic number!</span>
</p>
<p>
<button type="button" id="productBacklog_moveInsideEpic_save-<?php echo $storyId?>" class="action">MOVE INSIDE EPIC</button>
</p>
</fieldset>

</form>
<?php } else if ($displayMoveOutsideEpic) { ?>
<form id="productBacklog_moveOutsideEpic-<?php echo $storyId?>" name="productBacklog_moveOutsideEpic-<?php echo $storyId?>" method="post" action="#">

<fieldset>
<legend>Move outside epic</legend>
<p>You can move this story outside the epic by validating below.</p>
<p>
<button type="button" id="productBacklog_moveOutsideEpic_save-<?php echo $storyId?>" class="action">MOVE OUTSIDE EPIC</button>
</p>
</fieldset>

</form>
<?php } ?>


</div>
