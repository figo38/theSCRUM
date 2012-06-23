<?php
	$projectId = $_REQUEST['id'];
?>
<form id="productBacklog_addStory" name="productBacklog_addStory" method="post" action="#">
<input type="hidden" name="productBacklog_addStory_projectId" id="productBacklog_addStory_projectId" value="<?php echo $projectId?>"/>

<fieldset>
<legend>Type of user story:</legend>
<div>
	<div class="left" style="margin-right:100px">
		<label for="new_story_type1"><input type="radio" name="new_story_type" value="1" id="new_story_type1" checked="checked">Story</label>
		<label for="new_story_type2"><input type="radio" name="new_story_type" value="2" id="new_story_type2">Epic</label>
		<label for="new_story_type3"><input type="radio" name="new_story_type" value="3" id="new_story_type3">Spike</label>
	</div>
    <div class="left">
		<label for="new_story_type4"><input type="radio" name="new_story_type" value="4" id="new_story_type4">Bug</label>
		<label for="new_story_type5"><input type="radio" name="new_story_type" value="5" id="new_story_type5">Impediment</label>
	</div>
	<div class="clear"></div>
</div>
</fieldset>

<p class="field" id="bug_url_field" style="display:none;">
	<span class="reset">
		<span class="errorText" id="bug_url_field_error" style="display:none">An error occured.</span>
		<a href="#" id="bug_url_field_get_title" title="Retrieve the title of the page on this URL and store it in the 'user story' field">Get title</a>
		<span class="waitingText" id="bug_url_field_waiting" style="display:none">Loading...</span>
		- <a href="#" id="bug_url_field_emptyit">Reset</a>
	</span>
	<label for="bug_url_field_text">URL:</label>
	<input type="text" id="bug_url_field_text" value="http://..." style="width:400px;"/>
</p>

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