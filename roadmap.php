<?php
	$pageTitle = 'Roadmap ';
	include '_include/header.php';
?>

<h1>Roadmap</h1>

<?php
	// Calcul du nombre de sprint maxi			
	$iMaxSprintsGlobal = 0;
	$iMaxStream = 0;
	
	foreach ($projects as $key => $project){
		$projectId= $project['id'];
		$P = new Project($projectId, true);
		
		$streamVelocity = $P->getVelocity();
		$stories = $P->getAllEpics();
		$iMaxSprints = 0;
	
		foreach ($stories as $key => $story){
			if (($story['isroadmapdisplayed'] == 1) && ($story['percentage'] < 100)) {
				$iSprint = ceil(((float)$story['estimation'] / (float)$streamVelocity)*(100-$story['percentage'])/100);
				if ($iSprint < 0) { $iSprint = 1;}
				$iMaxSprints = $iMaxSprints + $iSprint;
			}
		}

		if ($iMaxSprints > $iMaxSprintsGlobal) {
			$iMaxSprintsGlobal = $iMaxSprints;
		}

		if ($streamVelocity > 0) {
			$iMaxStream++;
		}
	}

	// adjust the size of the sprints columns based on the total width
	$sWidth = floor((1524-150) / ($iMaxSprintsGlobal + 1)) -1;
	$sHeight = $iMaxStream * 60 + 20;
?>

<div id="roadmap">
	<h3>Sprints</h3>

	<div id="bar-sprints">
		<div class="sprints" style="width: 150px; height: <?=$sHeight; ?>px">STREAMS</div>
<?php for($i = 1; $i <= $iMaxSprintsGlobal + 1; $i++){ ?>
		<div class="sprints" style="width: <?=$sWidth-1?>px; height: <?=$sHeight?>px"><?=$i?></div>
<?php } ?>
	</div>

	<div id="bars">
<?php
	foreach ($projects as $key => $project){
		$projectId = $project['id'];
		$P = new Project($projectId, true);

		$projectName = $P->getName();
		$streamVelocity = $P->getVelocity();

		$stories = $P->getAllEpics();

		if ($streamVelocity > 0) {
?>
		<div id="stream<?=$projectId?>" class="line" style="width: <?php echo ceil($sWidth+1)*($iMaxSprintsGlobal + 1) + 150; ?>px;">
			<div class="project"><a href="<?=PATH_TO_ROOT.'project/'.string2url($projectName)?>"><?=$projectName?></a></div>
<?php
		}
		
		$iMaxSprints = 0;
		$i=0;

		foreach ($stories as $key => $story) {	
			if (($story['isroadmapdisplayed'] == 1) && ($story['estimation'] >= 1) && ($story['percentage'] < 100)) {
				$i++;
				$iSprint = (((float)$story['estimation'] / (float)$streamVelocity)*(100-$story['percentage'])/100);

				if ($iSprint < 0) { $iSprint = 1;}
	
				//if ($iSprint > $iMaxSprints) $iMaxSprints = $iSprint;
				if ($iSprint < 3) {
?>
			<div class="epic-<?php echo $i%2; ?>" style="width: <?php echo (int)($iSprint*($sWidth)); ?>px;" title="<?php echo $story['title']; ?>" ><?php echo $story['title']; ?></div>
<?php
				} else {
?>
			<div class="epic-<?php echo $i%2; ?>" style="width: <?php echo (int)($iSprint*($sWidth) -1); ?>px;" title="<?php echo $story['title']; ?>" ><?php echo $story['title']; ?></div>
<?php
				}
			}	
		}

		if ($streamVelocity>0) {
?>
			<div class="clear"></div>
		</div>
<?php
		}
	}
?>
	</div>
</div>

<div class="clear"></div>
