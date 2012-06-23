<?php
	$projectId = $_REQUEST['id'];
	$epicId = isset($_REQUEST['epicId']) ? $_REQUEST['epicId'] : 0;
?>

<form id="productBacklog_addStory-<?=$epicId?>" name="productBacklog_addStory-<?=$epicId?>" method="post" action="#">

<input type="hidden" name="productBacklog_addStory_projectId" id="productBacklog_addStory_projectId" value="<?=$projectId?>"/>

<fieldset>
<legend>Type of user story:</legend>
<div><label for="new_story_type1"><input type="radio" name="new_story_type-<?=$epicId?>" value="STORY" id="new_story_type1" checked="checked">Story</label></div>
<div><label for="new_story_type3"><input type="radio" name="new_story_type-<?=$epicId?>" value="SPIKE" id="new_story_type3">Spike</label></div>
</fieldset>

<p class="field">
<a href="#" id="new_story_emptyit_<?=$epicId?>" class="reset">Reset</a>
<label for="new_story_<?=$epicId?>">User story:</label>
<textarea style="width:400px; height:100px" id="new_story_<?=$epicId?>">As a [role], I can [feature] so that [reason]</textarea>
</p>

<p class="field">
<a href="#" id="new_acceptance_emptyit_<?=$epicId?>" class="reset">Reset</a>
<label for="new_acceptance_<?=$epicId?>">Acceptance criteria:</label>
<textarea style="width:400px; height:100px" id="new_acceptance_<?=$epicId?>">Given [context] And [some more context]… When [event] Then [outcome] And [another outcome]...</textarea>
</p>

<p>
<button type="button" id="productBacklog_addStory_submit_<?=$epicId?>" class="action">ADD THIS STORY TO EPIC</button>
<button type="button" id="productBacklog_addStory_cancel_<?=$epicId?>">CANCEL</button>
</p>

 </form>