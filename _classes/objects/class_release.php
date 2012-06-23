<?php
	class Release extends PBObject {
		
		const TABLE_NAME = 'releases';

		protected $sqlTableName = 'releases';
		protected $sqlIdField = 'rel_id';
		
		// TODO: how to avoid duplicated statement??
		protected $readSqlStatement = 'SELECT rel_id id, rel_name name, rel_type type, DATE_FORMAT( rel_planned_date, \'%Y-%m-%d\' ) planneddate, DATE_FORMAT(rel_deployed_date, \'%Y-%m-%d\') deployeddate, rel_comment comment, (rel_deployed_date IS NULL) OR IFNULL(TO_DAYS(NOW()) - TO_DAYS(rel_deployed_date) <= 2,0) isactive FROM releases WHERE rel_id=?';
		private static $getAllReleasesSql = 'SELECT rel_id id, rel_name name, rel_type type, CONCAT(rel_type, CONCAT(\' \', rel_name)) fullname, DATE_FORMAT( rel_planned_date, \'%Y-%m-%d\' ) planneddate, DATE_FORMAT(rel_deployed_date, \'%Y-%m-%d\') deployeddate, rel_comment comment, (rel_deployed_date IS NULL) OR IFNULL(TO_DAYS(NOW()) - TO_DAYS(rel_deployed_date) <= 2,0) isactive FROM releases ';
		
		public function getId() { return $this->id;}
		public function getName() { return $this->getField('name');}		
		public function getType() { return $this->getField('type');}	
		public function getPlannedDate() { return $this->getField('planneddate');}
		public function getDeployedDate() { return $this->getField('deployeddate');}		
		public function getComment() { return $this->getField('comment');}
		public function isActive() { return $this->getField('isactive');}
		public function getDisplayName() { return $this->getType() . ' ' . $this->getName(); }

		public function updateType($value) { return $this->updateStringField('rel_type', $value);}	
		public function updateName($value) { return $this->updateStringField('rel_name', $value);}
		public function updateComment($value) { return $this->updateStringField('rel_comment', $value);}
		public function updatePlannedDate($value) { return $this->updateDateField('rel_planned_date', $value);}
		public function updateDeployedDate($value) { return $this->updateDateField('rel_deployed_date', $value);}	

		/**
		  * Delete the current release
		  */
		public function deleteRelease() {
			// TODO transaction management
			global $DB;
			$sth = $DB->prepare('DELETE FROM releases WHERE rel_id=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			Helpers::executeStatement($sth);

			$sth = $DB->prepare('UPDATE story SET rel_id = NULL WHERE rel_id=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);			

			return Helpers::executeStatement($sth);
		}
		
		/**
		  * Retrieves all the stories attached to the current release
		  */
		public function getStories() {
			global $DB;
			$sth = $DB->prepare(Project::$getStoriesListSql . 'AND s.rel_id=? ORDER BY s.epi_id ASC');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		/**
		  * ------------------------------------- STATIC METHODS
		  */
		
		/**
		  * Retrieve the list of all releases
		  */
		public static function getAllReleases() {
			global $DB;
			$sth = $DB->prepare(Release::$getAllReleasesSql . 'ORDER BY rel_type,rel_name ASC');
			return Helpers::fetchAll($sth);
		}

		/**
		  * Retrieve the list of all active release (not deployed yet)
		  */
		public static function getAllActiveReleases() {
			global $DB;
			$sth = $DB->prepare(Release::$getAllReleasesSql . 'WHERE rel_deployed_date IS NULL OR TO_DAYS(NOW()) - TO_DAYS(rel_deployed_date) <= 2 ORDER BY rel_type,rel_name ASC');
			return Helpers::fetchAll($sth);
		}
		
		public static function getAllReleaseType() {
			return Helpers::execute('SELECT distinct rel_type type FROM releases ORDER BY rel_type ASC');
		}

		public static function getAllReleasesbyType($releaseType) {
			global $DB;
			$sth = $DB->prepare('SELECT rel_id id, rel_name name, rel_type type, DATE_FORMAT( rel_planned_date, \'%Y-%m-%d\' ) planneddate, DATE_FORMAT(rel_deployed_date, \'%Y-%m-%d\') deployeddate, rel_comment comment FROM releases WHERE rel_type=? ORDER BY rel_name ASC');
			$sth->bindParam(1, $releaseType, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}
		
		public static function getAllPlannedReleasesbyYearMonth($yearMonth) {
			global $DB;
			$sth = $DB->prepare("SELECT rel_id id, rel_name name, rel_type type, DATE_FORMAT( rel_planned_date, '" . DATE_FORMAT . "') planneddate, DATE_FORMAT(rel_deployed_date, '" . DATE_FORMAT . "') deployeddate, rel_comment comment FROM releases WHERE EXTRACT(YEAR_MONTH FROM rel_planned_date)=? ORDER BY rel_type, rel_name DESC");
			$sth->bindParam(1, $yearMonth, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		public static function getDistinctMonthReleases($nb = 0) {
			$sql = "SELECT DISTINCT EXTRACT(YEAR_MONTH FROM IFNULL(rel_planned_date, rel_deployed_date)) dt, DATE_FORMAT(IFNULL(rel_planned_date, rel_deployed_date), '%M %Y') dispdt FROM releases WHERE EXTRACT(YEAR_MONTH FROM rel_planned_date) != 0 ORDER BY dt DESC";
			if ($nb > 0) { $sql .= ' LIMIT ' . $nb; }
			return Helpers::execute($sql);			
		}		
	}
?>