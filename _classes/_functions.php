<?php
	function formatStoryId($storyId) {
		$res = '#';
		if ($storyId < 10) { $res .= '000' . $storyId; }
		else if ($storyId < 100) { $res .= '00' . $storyId; }
		else if ($storyId < 1000) { $res .= '0' . $storyId; }
		else $res .= $storyId;
		echo $res;
	}

	function getSelfURL() {
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		return 'http://' . $host . $uri;
	}

	function redirectToHomepage() {
		$extra = 'index.php';		
		$location = getSelfURL() . '/' . $extra;		
		header('Location: ' . $location);
		exit;
	}

	function formatStoryType($storyType) {
		if ($storyType == 2) {
			echo '<strong>(EPIC)</strong>';
		} elseif ($storyType == 3) { 
			echo '<strong style="color:#f00">(SPIKE)</strong>';
		}
	}
	
	function string2url($s) {
		$s = strtolower($s);
		$s = str_replace(' ', '-', $s);
		$s = str_replace('.', '-', $s);		
		return $s;
	}
	
	function img($img, $label, $id='', $class='') {
		$s = '<img class="pointer';
		if ($class != '') { $s .= ' ' . $class; }
		$s .= '"';
		if ($id != '') { $s .= ' id="' . $id . '"'; }
		$s .= ' src="' . PATH_TO_ROOT . 'images/icons/' . $img . '" width="16" height="16" title="' . $label . '" alt="' . $label . '"/>'; 
		return $s;
	}
	
	function displayReleaseName($releaseId) {
		global $allReleases;
		foreach ($allReleases as $key => $release) {
			if ($release['id'] == $releaseId) {
				$releasename = $release['fullname'];
				$releaselink = PATH_TO_ROOT.'release/'.string2url($release['fullname']);
			}
		}
		return '<a href="' . $releaselink . '" class="releaseTag">' . $releasename . '</a>';
	}
?>