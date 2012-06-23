<?php
	$releasename = trim($_REQUEST['new_release']);
	$releasetype = trim($_REQUEST['release_type']);

	if (strlen($releasename)) {
		$id = Scrum::addRelease($releasename, $releasetype);
		$RD = new ReleaseDisplay($id);
		$RD->setDisplayNone(true);
		$RD->render();
	}
?>