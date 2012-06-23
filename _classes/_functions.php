<?php
	function getRequestParameter($name, $defaultValue = NULL) {
		return isset($_REQUEST[$name]) ? trim($_REQUEST[$name]) : $defaultValue;	
	}
	
	function getRequestIntParameter($name, $defaultValue = NULL) {
		return isset($_REQUEST[$name]) && is_numeric($_REQUEST[$name]) ? (integer)$_REQUEST[$name] : $defaultValue;
	}

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
	
	function redirectToURI($uri) {
		$host  = $_SERVER['HTTP_HOST'];
		header("Location: http://$host" . PATH_TO_ROOT . $uri);
		exit;
	}	

	function formatStoryType($storyType) {
		if ($storyType == 2) {
			echo '<strong class="labelepic">EPIC:</strong>';
		} elseif ($storyType == 3) { 
			echo '<strong class="labelspike">SPIKE:</strong>';
		} elseif ($storyType == 4) {
			echo '<strong class="labelbug">BUG:</strong>';
		} elseif ($storyType == 5) {
			echo '<strong class="labelimpediment">IMPEDIMENT:</strong>';			
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