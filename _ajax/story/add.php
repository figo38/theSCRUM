<?php
	$projectId = $_REQUEST['id'];
?>
<form id="productBacklog_addStory" name="productBacklog_addStory" method="post" action="#">
<input type="hidden" name="productBacklog_addStory_projectId" id="productBacklog_addStory_projectId" value="<?=$projectId?>"/>

<fieldset>
<legend>Type of user story:</legend>
<div><label for="new_story_type1"><input type="radio" name="new_story_type" value="STORY" id="new_story_type1" checked="checked">Story</label></div>
<div><label for="new_story_type2"><input type="radio" name="new_story_type" value="EPIC" id="new_story_type2">Epic</label></div>
<div><label for="new_story_type3"><input type="radio" name="new_story_type" value="SPIKE" id="new_story_type3">Spike</label></div>
</fieldset>

<p class="field">
<a href="#" id="new_story_emptyit" class="reset">Reset</a>
<label for="new_story">User story:</label>
<textarea style="width:400px; height:100px" id="new_story">As a [role], I can [feature] so that [reason]</textarea>
</p>

<p class="field">
<a href="#" id="new_acceptance_emptyit" class="reset">Reset</a>
<label for="new_acceptance">Acceptance criteria:</label>
<textarea style="width:400px; height:100px" id="new_acceptance">Given [context] And [some more context]... When [event] Then [outcome] And [another outcome]...</textarea>
</p>

<p>
<button type="button" id="productBacklog_addStory_submit" class="action">ADD THIS STORY</button>
<button type="button" id="productBacklog_addStory_cancel">CANCEL</button>

</p>
</form>