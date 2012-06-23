<?php
	/**
	  * Checks if an URL is valid
	  * @param url URL to check
	  */

	include_once '../_classes/_functions.php';
	
	$url = getRequestParameter('url');	

	$error = false;
	$output = NULL;
	
	ob_start();
	if ($url != NULL) {
		// Check if the URL is valid
		if (preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url)) {
			// URL is valid - trying to load its content
			if (false == ($str = &file_get_contents($url))) {
				$error = true;
			} else {
				if(strlen($str)>0){
					preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
					$output = $title[1];
				}
			}			
		} else {
			// URL is not valid
			$error = true;
		}
	} else {
		// No URL in parameter ; output an empty string
		$output = '';
	}
	ob_end_clean();

	if (!$error) {
		echo $output;
	} else {
		header("HTTP/1.0 404 Not Found");
		echo 'ERROR';
	}
?>