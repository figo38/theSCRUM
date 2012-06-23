<?php
	$projectId = $_REQUEST['id'];
	$epicId = isset($_REQUEST['epicId']) ? $_REQUEST['epicId'] : 0;
?>

<form id="productBacklog_addStory-<?php echo $epicId?>" name="productBacklog_addStory-<?php echo $epicId?>" method="post" action="#">

<input type="hidden" name="productBacklog_addStory_projectId" id="productBacklog_addStory_projectId" value="<?php echo $projectId?>"/>

<fieldset>
<legend>Type of user story:</legend>
<div><label for="new_story_type1"><input type="radio" name="new_story_type-<?php echo $epicId?>" value="1" id="new_story_type1" checked="checked">Story</label></div>
<div><label for="new_story_type3"><input type="radio" name="new_story_type-<?php echo $epicId?>" value="3" id="new_story_type3">Spike</label></div>
</fieldset>

<p class="field">
<a href="#" id="new_story_emptyit_<?php echo $epicId?>" class="reset">Reset</a>
<label for="new_story_<?php echo $epicId?>">User story:</label>
<textarea style="width:400px; height:100px" id="new_story_<?php echo $epicId?>">As a [role], I can [feature] so that [reason]</textarea>
</p>

<p class="field">
<a href="#" id="new_acceptance_emptyit_<?php echo $epicId?>" class="reset">Reset</a>
<label for="new_acceptance_<?php echo $epicId?>">Acceptance criteria:</label>
<textarea style="width:400px; height:100px" id="new_acceptance_<?php echo $epicId?>">Given [context] And [some more context]… When [event] Then [outcome] And [another outcome]...</textarea>
</p>

<p>
<button type="button" id="productBacklog_addStory_submit_<?php echo $epicId?>" class="action">ADD THIS STORY TO EPIC</button>
<button type="button" id="productBacklog_addStory_cancel_<?php echo $epicId?>">CANCEL</button>
</p>

 </form>