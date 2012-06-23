<?php
	$pageTitle = 'Roadmap ';
	include '_include/header.php';
?>

<h1>Roadmap</h1>

<div id="roadmap">
	<h3>Sprints</h3>

<?php
	$projectHeight = 50;
	$sprintWidth = 50;
	
	$sprintNb = 25;
	
	$nbProjects = sizeof($projects);	
	$containerWidth = ($sprintWidth * $sprintNb) + $sprintNb + 152;
	$containerHeight = $nbProjects * (10 + $projectHeight);
?>

	<div style="position:relative; width:<?php echo $containerWidth; ?>px; height: <?php echo $containerHeight?>px"">
		<div class="projectColumn" style="height: <?php echo $containerHeight?>px">Projects</div>
<?php for($i = 1; $i <= $sprintNb; $i++){ ?>
		<div class="sprints" style="width: <?php echo $sprintWidth?>px; height: <?php echo $containerHeight?>px"><?php echo $i?></div>
<?php } ?>

		<div id="projects">
<?php
	foreach ($projects as $key => $project){
		$projectId = $project['id'];
		$P = new Project($projectId, true);

		$projectName = $P->getName();
		$streamVelocity = $P->getVelocity();		
?>
			<div id="project-<?php echo $projectId?>" class="project" style="width:<?php echo $containerWidth; ?>px;">
				<div class="projectName"><a href="<?php echo PATH_TO_ROOT.'project/'.string2url($projectName)?>"><?php echo $projectName?></a></div>      
<?php
		$stories = $P->getAllEpics();
		$i=0;
		foreach ($stories as $key => $story) {
			if (($story['isroadmapdisplayed'] == 1) && ($story['estimation'] >= 1) && ($story['percentage'] < 100)) {
				$i++;
				$sprintLength = (((float)$story['estimation'] / (float)$streamVelocity)*(100-$story['percentage'])/100);
				$storyLength = (int)($sprintLength * $sprintWidth);
				if ($storyLength < 10) { $storyLength = 10; /* 10px width min */}
?>
				<div class="epic-<?php echo $i%2; ?>" style="width: <?php echo $storyLength; ?>px;" title="<?php echo $story['title']; ?>" ><?php echo $story['title']; ?></div>
<?php				
			}
		}
?>
			</div>
<?php
	}
?>		
		</div>	                
	</div>
</div>

<div class="clear"></div>
<br /><br />