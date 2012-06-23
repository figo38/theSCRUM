<?php
	include_once '../../global.php';
	include_once '../../_classes/classloader.php';
	
	$storyId = $_REQUEST['id'];
	$featuregroups = $_REQUEST['featuregroups'];
	$storyType = isset($_REQUEST ['storytypeid']) ? $_REQUEST ['storytypeid'] : 1;
	$url = isset($_REQUEST['url']) ? trim($_REQUEST['url']) : NULL;

	if ($storyType != Story::BUG) {
		error_log('ici');
		$url = NULL;
	}

	$featureGroupIDs = array();
	$tab = explode(';', $featuregroups);
	foreach ($tab as $key=>$val) {
		if (is_numeric($val)) {
			$featureGroupIDs[] = $val;
		}
	}

	$S = new Story($storyId);
	$S->updateType($storyType);
	if ($storyType == Story::BUG) {
		error_log($url . '-' . $storyId);
		$S->updateURL($url);
	}

	$releaseId = $_REQUEST['releaseId'];
	if ($releaseId > 0) {
		$res= $S->updateReleaseLink($releaseId);
	} else {
		$S->deleteLinkRelease();
	}

	$storyId = $S->updateFeatureGroups($featureGroupIDs);
?>