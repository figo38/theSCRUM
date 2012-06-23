<?php
	include '_include/header.php';
	
	//$groupId = $_REQUEST['id'];
	try {
		$G = new FeatureGroup($groupId, true);
	} catch (Exception $e) {
		redirectToHomepage();	
	}
	$stories = $G->getAllStories();
?>

<h1>&laquo;<?=$G->getName()?>&raquo; Tag</h1>

<table>
<thead>
<tr>
	<th>#</th>
	<th>Project</th>
	<th>%</th>
	<th>User story</th>
	<th>Acceptance criteria</th>
</tr>
</thead>
<tbody>
<?php 
	foreach ($stories as $key => $story) { 
		$S = new StoryDisplay($story);
		$S->renderSimpleView();
	} 
?>
</tbody>
</table>