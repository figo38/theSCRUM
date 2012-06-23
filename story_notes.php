<?php
	$S = new Story($storyId, true);
	$projectId = $S->getProjectId();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<title>Edit story note</title>
<script type="text/javascript">
<!-- 
var PATH_TO_ROOT = '<?=PATH_TO_ROOT?>';
-->
</script>
<script type="text/javascript" src="<?=PATH_TO_ROOT?>_include/protoaculous.1.8.2.p1.6.1.min.js"></script>
<script type="text/javascript" src="<?=PATH_TO_ROOT?>_include/nicEdit.js"></script>
<script type="text/javascript" src="<?=PATH_TO_ROOT?>_include/js/rich-text-editor.js"></script>
<link rel="stylesheet" href="<?=PATH_TO_ROOT?>_include/styles.css" type="text/css" media="screen" />
<style>
<!--
body { background: none;}
-->
</style>
</head>
<body>

<?php if ($USERAUTH->isProductOwnerOf($projectId) || $USERAUTH->isScrumMasterOf($projectId)) { ?>
<div id="story_notes_panel">
	<div id="story_notes_panel_ok_msg">Changes saved.</div>
	<div id="story_notes_panel_save_msg">Do not forget to save your changes.</div>
</div>

<div id="story_notes"><?php if (strlen($S->getComment()) > 0) echo $S->getComment(); else echo '(Click to edit...)';?></div>

<script type="text/javascript">
<!--
	var storynotesinstance = new StoryNotes();
	storynotesinstance.enableInteraction(<?=$storyId?>);
-->	
</script>
<?php } else {?>
<?php if (strlen($S->getComment()) > 0) echo $S->getComment(); else echo '(No comment)';?>
<?php } ?>

</body>
</html>