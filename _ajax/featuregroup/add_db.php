<?php
	$featuregroupname = trim($_REQUEST['new_feature_group']);
	
	if (strlen($featuregroupname)) {
		$id = Scrum::addFeatureGroup($featuregroupname);
		$D = new FeatureGroupDisplay($id);
		$D->setDisplayNone(true);
		$D->render();
	}
?>