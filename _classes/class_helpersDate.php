<?php
	class HelpersDate {

		private static function getFormatted($mysql_timestamp, $format) {
			return date($format, $mysql_timestamp);
		}

		public static function getFormattedDate($mysql_timestamp) {
			return HelpersDate::getFormatted($mysql_timestamp, DISPLAY_DATE_FORMAT);
		}

		public static function getFormattedDateTime($mysql_timestamp) {
			return HelpersDate::getFormatted($mysql_timestamp, DISPLAY_DATETIME_FORMAT);
		}
		
		public static function getFormattedFullDateTime($mysql_timestamp) {		
			$dt = explode('-', date('Y-m-d', $mysql_timestamp));
			$d2 = mktime(19, 0, 0, (integer)$dt[1], (integer)$dt[2], (integer)$dt[0]);			
			return HelpersDate::getFormattedDateTime($mysql_timestamp) . ' <span class="timeago">(' . HelpersDate::timeago($d2) . ')</span>';
		}
		
		## Measureby can be: s, m, h, d, or y
		public static function timeago($referencedate=0, $timepointer='', $measureby='', $autotext=true){    
			if($timepointer == '') {
				$timepointer = time();
			}
			
			$Raw = $timepointer - $referencedate; # Raw time difference
			$Clean = abs($Raw);
			$calcNum = array(
				array('s', 60), 
				array('m', 60*60), 
				array('h', 60*60*60), 
				array('d', 60*60*60*24), 
				array('y', 60*60*60*24*365)
			); # Used for calculating
			
			$calc = array(
				's' => array(1, 'second'), 
				'm' => array(60, 'minute'), 
				'h' => array(60*60, 'hour'), 
				'd' => array(60*60*24, 'day'),
				'y' => array(60*60*24*365, 'year')
			); # Used for units and determining actual differences per unit (there probably is a more efficient way to do this)
    
			if($measureby == '') { # Only use if nothing is referenced in the function parameters
				$usemeasure = 's'; # Default unit
    
				for($i = 0; $i < count($calcNum); $i++) { # Loop through calcNum until we find a low enough unit
					if ($Clean <= $calcNum[$i][1]) { # Checks to see if the Raw is less than the unit, uses calcNum b/c system is based on seconds being 60
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
	}
?>