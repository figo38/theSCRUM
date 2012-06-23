<?php
	class Scrum {


		
		public static function addRelease($releaseName, $releaseType) {
			global $DB;
			$sth = $DB->prepare('INSERT INTO releases (rel_name, rel_type) VALUES (?, ?)');
			$sth->bindParam(1, trim($releaseName), PDO::PARAM_STR);
			$sth->bindParam(2, trim($releaseType), PDO::PARAM_STR);
			return Helpers::executeInsertStatement($sth);
		}
		
		public static function addProject($projectName) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('INSERT INTO project (pro_name, usr_login, pro_create_date, pro_update_date) VALUES (?, ?, NOW(), NOW())');
			$sth->bindParam(1, trim($projectName), PDO::PARAM_STR);
			$sth->bindParam(2, $USERAUTH->getUserLogin());		
			return Helpers::executeInsertStatement($sth);
		}

		public static function addFeatureGroup($featureGroupName) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('INSERT INTO featuregroup (fea_name, usr_login, fea_create_date, fea_update_date) VALUES (?, ?, NOW(), NOW())');
			$sth->bindParam(1, $featureGroupName, PDO::PARAM_STR);
			$sth->bindParam(2, $USERAUTH->getUserLogin());		
			return Helpers::executeInsertStatement($sth);
		}


	}
?>