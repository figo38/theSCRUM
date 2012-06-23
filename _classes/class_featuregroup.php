<?php
	class FeatureGroup extends PBObject {

		protected $sqlTableName = 'featuregroup';
		protected $sqlIdField = 'fea_id';
		protected $readSqlStatement = 'SELECT fea_id id, fea_name name FROM featuregroup WHERE fea_id=?';

		public function getName() { return $this->getField('name');}

		public function updateName($value) { return $this->updateStringField('fea_name', $value);}

		public function getAllStories() {
			global $DB;
			$sth = $DB->prepare('SELECT a.sto_id id, sto_percentage percentage, sto_story story, sto_acceptance acceptance, a.pro_id projectid, pro_name projectname FROM story a, story_featuregroup b, project c WHERE a.sto_id = b.sto_id AND a.pro_id = c.pro_id AND fea_id=? ORDER BY percentage DESC');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}
		
		// @return a list of all feature groups sorted by project name	
		public static function getAllFeatureGroups() { return Helpers::execute('SELECT fea_id id, fea_name name FROM featuregroup ORDER BY fea_name ASC');}		
	}
?>