<?php	
	try {
		$R = new ReleaseDisplay($releaseId);
	} catch (Exception $e) {
		redirectToHomepage();
	}

	$storiesList = $R->getStories();
	
	$JS = array('release');
	$pageTitle = 'Release - ' . $R->getDisplayName();
	include '_include/header.php';	
?>
<h1>Release &laquo;<?=$R->getDisplayName()?>&raquo;</h1>

<table>
<thead>
<tr>
	<th>Platform</th>
	<th>Release name</th>
	<th>Planned Date</th>
	<th>Deployed Date</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php $R->render(0);?>
</tbody>
</table>

<h2>Stories linked to this release</h2>

<table>
<thead>
<tr>
	<th>#</th>
	<th>Project</th>
	<th>%</th>
	<th>User story</th>
	<th>Acceptance criteria</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody>
<?php
	foreach ($storiesList as $key => $story) {
		$S = new StoryDisplay($story);
		$S->renderSimpleView(1);	
	}
?>
</tbody>
</table>

<?php if ($USERAUTH->isProductOwner() || $USERAUTH->isScrumMaster() || $USERAUTH->isAdmin()) { ?>
<script type="text/javascript">
<!--
var release = new ReleaseMngt();
release.enableInteraction(<?=$releaseId?>);
<?php foreach ($storiesList as $key => $story) { ?>
release.enableLinkDeletion(<?=$story['id']?>);
<?php } ?>
-->
</script>
<?php } ?>