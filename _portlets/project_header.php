<?php
	try {
		$projectUnit = $P->getUnit();
		$projectName = $P->getName();
		$projectUrl = PATH_TO_ROOT . 'project/' . string2url($projectName);
	} catch (Exception $e) {
		redirectToHomepage();	
	}
		
	$pageTitle .= ' - ' . $projectName;
	include '_include/header.php';
?>
</div>

<ul class="submenu">
	<li class="title">&laquo;<?php echo $P->getName()?>&raquo;</li>
	<li><?php if ($menu == 1) { ?>Product backlog<?php } else { ?><a href="<?php echo $projectUrl?>">Product backlog</a><?php } ?></li>
    
<?php
	if ($P->hasSprints()) {		
		if ($menu == 2 && $sprintId > 0) {
				
		}
?>
	<li><?php if ($menu == 2) { ?>Sprint backlog<?php if ($sprintId > 0) {?> #<?php echo $S->getSprintNumber()?><?php } ?><?php } else { ?><a href="<?php echo $projectUrl . '/sprintbacklog'?>">Sprint backlog</a><?php } ?></li>
	<li class="secondary"><?php if ($menu == 3) { ?>Sprints<?php } else { ?><a href="<?php echo $projectUrl . '/sprints'?>">Sprints</a><?php } ?></li>
<?php
	}
?>
	<li class="secondary"><?php if ($menu == 4) { ?>Team<?php } else { ?><a href="<?php echo $projectUrl . '/team'?>">Team</a><?php } ?></li>
	<li class="secondary"><?php if ($menu == 5) { ?>Roadmap<?php } else { ?><a href="<?php echo $projectUrl . '/roadmap'?>">Roadmap</a><?php } ?></li>
	<li class="secondary"><?php if ($menu == 6) { ?>Stats<?php } else { ?><a href="<?php echo $projectUrl . '/stats'?>">Stats</a><?php } ?></li>    
</ul>

<div class="page">
