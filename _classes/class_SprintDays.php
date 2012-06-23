<?php
	class SprintDays {

		public static function registerDay($sprintId, $dayAsString) {
			global $DB;
			$sth = $DB->prepare('INSERT INTO sprint_days (spr_id, spd_date) VALUES (?, ?)');
			$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
			$sth->bindParam(2, $dayAsString, PDO::PARAM_STR);
			return Helpers::executeStatement($sth);
		}
		
		public static function unregisterDay($sprintId, $dayAsString) {
			global $DB;
			$sth = $DB->prepare('DELETE FROM sprint_days WHERE spr_id=? AND spd_date=?');
			$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
			$sth->bindParam(2, $dayAsString, PDO::PARAM_STR);
			return Helpers::executeStatement($sth);			
		}
		
		public static function getRegisteredDays($sprintId) {
			global $DB;
			$sth = $DB->prepare('SELECT spd_date dt FROM sprint_days WHERE spr_id=?');
			$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}
	}
?>