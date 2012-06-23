<?php
		// Do not include classloader in order to avoid the session stuff, for cron-launched script
        include_once 'global.php';		
        include_once '_classes/class_object.php';
        include_once '_classes/class_helpers.php';
        include_once '_classes/class_sprint_snapshot.php';
        include_once '_classes/class_sprint.php';

        $DB = new PDO('mysql:host=' . PRODUCTBACKLOG_DB_HOST . ';dbname=' . PRODUCTBACKLOG_DB_NAME, PRODUCTBACKLOG_DB_USER, PRODUCTBACKLOG_DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $activeSprints = Sprint::getActiveSprints();
					
        if ($activeSprints) {
                foreach ($activeSprints as $key => $sprint) {
                        $sprintId = $sprint['id'];
                        echo 'Taking snapshot of sprint #' . $sprintId . PHP_EOL;
                        SprintSnapshot::takeSnapshot($sprintId);
                }
        } else {
				echo 'Do nothing as there is no active sprints' . PHP_EOL;
		}
?>