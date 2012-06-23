<?php
	include_once '../global.php';
	include_once '../_classes/classloader.php';
	$releaseId = $_REQUEST['id'];
	
?>
<form id="productBacklog_deleteReleaseLink_<?=$releaseId?>" name="productBacklog_deleteReleaseLink_<?=$releaseId?>" method="post" action="#">

<p>Are you sure you want to delete this link story-release?</p>

<p>
<button type="button" id="productBacklog_deleteReleaseLink_delete_<?=$releaseId?>" class="delete">DELETE</button>
<button type="button" id="productBacklog_deleteReleaseLink_cancel_<?=$releaseId?>">CANCEL</button>
</p>

</form>