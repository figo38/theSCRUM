<?php
	$JS = array('productbacklog', 'story', 'resizingpostits');	
	$menu = 1;
	$includePortlet = '';
	$pageTitle = '';
	switch ($viewtype) {
		case '1':
			$includePortlet = '_portlets/product_backlog/view_product_backlog.php';
			$pageTitle = 'Product backlog';
			break;
		case '2':
			$includePortlet = '_portlets/product_backlog/view_story_map.php';
			$pageTitle = 'Story map';
			break;
	}
	include '_portlets/project_header.php';
	
	$showCompletedStories = isset($_POST['showCompletedStories']) ? $_POST['showCompletedStories'] : 0;

	$stories = $P->getAllStories(array(
		'includecompleted' => $showCompletedStories
	));
?>
<form method="post" id="filtering" action="<?php echo $projectUrl?>">
<div id="formgoalbox">
	<div id="filteringoptions">
		<div class="filters">
			<h2>Filtering options</h2>
			<input type="hidden" name="id" id="productBacklog_projectId" value="<?php echo $projectId?>"/>
			<input type="checkbox" name="showCompletedStories" id="showCompletedStories" value="1" <?php if ($showCompletedStories == 1) echo 'checked'; ?>/> <label for="showCompletedStories" style="margin-right:40px">Show completed stories</label>
	
			<label for="viewtype">View:</label>
			<select name="viewtype" id="viewtype">
				<option value="1"<?php if ($viewtype==1) echo ' selected="selected"';?>>Backlog</option>
				<option value="2"<?php if ($viewtype==2) echo ' selected="selected"';?>>Story Map</option>
			</select>
	
			<button type="submit" id="submitform">Refresh</button>
		</div>
	</div>
<?php if (strlen($P->getGoal()) > 0) { ?>	
	<div id="goals"><strong>Project goal:</strong> <?php echo $P->getGoal()?></div>
<?php } ?>
	<div class="clear"></div>
</div>
</form>

<?php include $includePortlet; ?>

<script type="text/javascript">
<!--
$('submitform').observe('click', function(event) {
	var curr = $('filtering').action;
	if ($F('viewtype') == 1) { $('filtering').action = curr + '/';} else
	if ($F('viewtype') == 2) { $('filtering').action = curr + '/storymap';}
});
-->
</script>