<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	$storyId = $_GET['id'];
	$featuregroups = $_GET['featuregroups'];
	$storyType = isset($_GET ['storytypeid']) ? $_GET ['storytypeid'] : 1;

	$featureGroupIDs = array();
	$tab = explode(';', $featuregroups);
	foreach ($tab as $key=>$val) {
		if (is_numeric($val)) {
			$featureGroupIDs[] = $val;
		}
	}

	$S = new Story($storyId);
	$S->updateType($storyType);

	$releaseId = $_GET['releaseId'];
	if ($releaseId > 0) {
		$res= $S->updateReleaseLink($releaseId);
	} else {
		$S->deleteLinkRelease();
	}

	$storyId = $S->updateFeatureGroups($featureGroupIDs);
?>