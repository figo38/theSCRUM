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
	
	function timeago($referencedate=0, $timepointer='', $measureby='', $autotext=true){    ## Measureby can be: s, m, h, d, or y
		if($timepointer == '') $timepointer = time();
			$Raw = $timepointer-$referencedate;    ## Raw time difference
			$Clean = abs($Raw);
			$calcNum = array(array('s', 60), array('m', 60*60), array('h', 60*60*60), array('d', 60*60*60*24), array('y', 60*60*60*24*365));    ## Used for calculating
			$calc = array('s' => array(1, 'second'), 'm' => array(60, 'minute'), 'h' => array(60*60, 'hour'), 'd' => array(60*60*24, 'day'), 'y' => array(60*60*24*365, 'year'));    ## Used for units and determining actual differences per unit (there probably is a more efficient way to do this)
    
			if($measureby == ''){    ## Only use if nothing is referenced in the function parameters
				$usemeasure = 's';    ## Default unit
    
				for($i=0; $i<count($calcNum); $i++){    ## Loop through calcNum until we find a low enough unit
					if($Clean <= $calcNum[$i][1]){        ## Checks to see if the Raw is less than the unit, uses calcNum b/c system is based on seconds being 60
						$usemeasure = $calcNum[$i][0];    ## The if statement okayed the proposed unit, we will use this friendly key to output the time left
						$i = count($calcNum);            ## Skip all other units by maxing out the current loop position
					}
				}
			} else {
				$usemeasure = $measureby;                ## Used if a unit is provided
			}
    
			$datedifference = floor($Clean/$calc[$usemeasure][0]);    ## Rounded date difference
    
			if($autotext==true && ($timepointer==time())){
				if($Raw < 0){
					$prospect = ' from now';
				}else{
					$prospect = ' ago';
				}
			}
    
			if($referencedate != 0){        ## Check to make sure a date in the past was supplied
				if($datedifference == 1){    ## Checks for grammar (plural/singular)
					return $datedifference . ' ' . $calc[$usemeasure][1] . ' ' . $prospect;
				}else{
					return $datedifference . ' ' . $calc[$usemeasure][1] . 's ' . $prospect;
				}
			}else{
				return 'No input time referenced.';
			}
		}	
?>