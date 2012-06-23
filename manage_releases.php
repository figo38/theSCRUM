<?php
	$JS = array('release');
	$pageTitle = 'Release dashboard';	
	include '_include/header.php';

	$showCompletedReleases = isset($_POST['showCompletedReleases']) ? $_POST['showCompletedReleases'] : 0;

	if ($showCompletedReleases == 1) {
		$releases = Release::getAllReleases();	
	} else {
		// Already set on header.php
	}
	
	$hasRights = $USERAUTH->isProductOwner() || $USERAUTH->isAdmin() || $USERAUTH->isScrumMaster();
?>

<h1>Releases</h1>

<div id="actionbar">
<?php if ($hasRights) { ?>
	<div class="left">
		<button type="button" id="addnewobject"><span class="btngAddObject">Add a new release</span></button>
	</div>
<?php } ?>
	<div class="right">
        <form method="post" name="filtering" action="#">
        <input type="checkbox" name="showCompletedReleases" id="showCompletedReleases" value="1" <?php if ($showCompletedReleases == 1) echo 'checked'; ?>/> 
        <label for="showCompletedReleases" style="display:inline">Show completed releases</label>
        <button type="submit">Refresh</button>
        </form>    
    </div>
    <div class="clear"></div>
</div>
 
<?php if ($showCompletedReleases == 0) { echo '<div style="margin-bottom:5px; font-style:italic; color:#999">Only releases being worked on are displayed in this list.</div>'; } ?>

<table id="sortablereleaselist">
<thead>
<tr>
	<th>Platform</th>
	<th>Release name</th>
	<th>Planned Date</th>
	<th>Deployed Date</th>
	<th>Comment</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody id="release_tbody">
<?php
	foreach ($releases as $key => $release) {
		$RD = new ReleaseDisplay($release);
		$RD->render();
	}
?>
</tbody>
</table>

<?php if ($hasRights) { ?>
<script type="text/javascript">
<!--
	var release = new ReleaseMngt();
	release.initAddReleaseButton();
<?php foreach ($releases as $key => $release) { ?>
	release.enableInteraction(<?php echo $release['id']?>);
<?php } ?>
-->
</script>
<?php } ?>