<?php
	class Project extends PBObject {

		protected $sqlTableName = 'project';
		protected $sqlIdField = 'pro_id';
		protected $readSqlStatement = 'SELECT pro_id id, pro_name name, pro_velocity velocity, pro_sprint_by_quarter sprintbyquarter, pro_goal goal, pro_has_sprints hassprints, pro_unit unit FROM project WHERE pro_id=?';
		public static $realSqlSelectStatement = 'SELECT pro_id id, pro_name name, pro_velocity velocity, pro_sprint_by_quarter sprintbyquarter, pro_goal goal, pro_has_sprints hassprints, pro_unit unit FROM project ';
		public static $getStoriesListSql = 'SELECT s.sto_id id, s.pro_id projectid, s.sto_story story, s.sto_acceptance acceptance, s.sto_percentage percentage, s.sto_type type, p.pro_name projectname, IFNULL(b.sto_title, b.sto_story) epictitle, s.epi_id epicid FROM story s LEFT OUTER JOIN story b ON s.epi_id=b.sto_id JOIN project p ON s.pro_id=p.pro_id ';

		public function getName() { return $this->getField('name');}
		public function getVelocity() { return $this->getField('velocity');}	
		public function getSprintByQuarter() { return $this->getField('sprintbyquarter');}
		public function getGoal() { return $this->getField('goal');}	
 		public function hasSprints() { return $this->getField('hassprints');; }
		public function getUnit() { return $this->getField('unit');}	

		public function updateName($value) { return $this->updateStringField('pro_name', $value);}
		public function updateGoal($value) { return $this->updateStringField('pro_goal', $value);}		
		public function updateVelocity($value) { return $this->updateIntField('pro_velocity', $value);}
		public function updateHasSprints($value) { return $this->updateIntField('pro_has_sprints', $value);}
		public function updateSprintByQuarter($value) { return $this->updateIntField('pro_sprint_by_quarter', $value);}
		public function updateUnit($value) { return $this->updateStringField('pro_unit', $value);}

		/**
		  *
		  * "limit" - Integer indicating the number max of stories to retrieve
		  * "includecompleted" - 0 (default) indicates completed stories not included ; 1 indicates complete stories are retrieved.
		  */
		public function getAllStories($params = array()) {
			global $DB;
			
			$limit = isset($params['limit']) ? (int)($params['limit']) : 0;
			$includecompleted = isset($params['includecompleted']) ? (int)($params['includecompleted']) : 0;

			$sql = 'SELECT a.sto_id id, a.sto_prio priority, a.sto_estim estimation, LENGTH(a.sto_comment) lengthcomment, a.sto_percentage percentage, a.sto_story story, a.sto_acceptance acceptance, a.sto_type storytype, a.epi_id epicid, a.rel_id releaseid, b.sto_prio epic_prio, IF(a.epi_id>0, b.sto_prio, a.sto_prio) priosort1, IF(a.epi_id>0, b.sto_prio + b.sto_id, a.sto_prio + a.sto_id) priosort2, IF(a.sto_type=1 OR a.sto_type=3,IF((a.epi_id>0), a.sto_prio + 90000, a.sto_prio),a.sto_prio+100000) priosort3 FROM story a LEFT OUTER JOIN story b ON a.epi_id=b.sto_id WHERE a.pro_id=?';
			
			if ($includecompleted == 0) {
				$sql .= 'AND a.sto_percentage < 100';
			} 
			$sql .= ' ORDER BY priosort1 DESC, priosort2 DESC, priosort3 DESC';
			if ($limit > 0) { 
				$sql .= ' LIMIT ' . $limit; 
			}
			$sth = $DB->prepare($sql);
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		public function getAllEpics() {
			global $DB;			
			$sth = $DB->prepare('SELECT a.sto_id id, a.sto_prio priority, a.sto_estim estimation, a.sto_percentage percentage, a.sto_story story, a.sto_acceptance acceptance, a.sto_type storytype, a.epi_id epicid, a.sto_title title, a.is_roadmap_displayed isroadmapdisplayed FROM story a WHERE a.sto_type=2 AND a.pro_id=? ORDER BY a.sto_prio DESC');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		public function addStory($story, $acceptance, $type, $epicID = NULL) {
			global $DB, $USERAUTH;
			$sth = $DB->prepare('INSERT INTO story (sto_story, sto_acceptance, sto_type, pro_id, epi_id, sto_create_date, sto_update_date, usr_login) VALUES (?, ?, ?, ?, ?, NOW(), NOW(), ?)');
			$sth->bindParam(1, trim($story), PDO::PARAM_STR);
			$sth->bindParam(2, trim($acceptance), PDO::PARAM_STR);
			$sth->bindParam(3, $type, PDO::PARAM_INT);
			$sth->bindParam(4, $this->id, PDO::PARAM_INT);
			$sth->bindParam(5, $epicID, PDO::PARAM_INT);	
			$sth->bindParam(6, $USERAUTH->getUserLogin());
			return Helpers::executeInsertStatement($sth);
		}

		/**
		  * ------------------------------------- TEAM MANAGEMENT
		  */

		public function getAllUsers() {
			global $DB;
			// TODO There must be an easier SQL
			$sth = $DB->prepare("SELECT login, MAX(role) role FROM (SELECT u.usr_login login, usr_role role FROM user u, project_user p WHERE u.usr_login = p.usr_login AND pro_id = ? UNION SELECT u.usr_login login, 'N' role FROM user u ORDER BY login) t GROUP BY login");
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}
		
		/**
		  * Get project team members with roles
		  */
		public function getTeam() {
			global $DB;	
			$sth = $DB->prepare("SELECT u.usr_login login, usr_role role FROM user u, project_user p WHERE u.usr_login = p.usr_login AND pro_id = ? ORDER BY login");
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}		
		
		public function updateProductOwners($peoplelist) { return $this->updateTeam($peoplelist, 'P');}
		public function updateScrumMasters($peoplelist) { return $this->updateTeam($peoplelist, 'S');}
		public function updateTeamMembers($peoplelist) { return $this->updateTeam($peoplelist, 'T');}
		
		/**
		  * Update team members
		  * @param $peoplelist List of people name separated by "," (result of serialization of scriptaculous sortable)
		  * @param $type "P" (product owners), "S" (scrum masters), "T" (team members)
		  */
		private function updateTeam($peoplelist, $type) {
			global $DB;
			// TODO Transaction management			
			$sth = $DB->prepare('DELETE FROM project_user WHERE pro_id=? AND usr_role=?');				
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$sth->bindParam(2, $type, PDO::PARAM_STR);	
			Helpers::executeStatement($sth);
			
			$tab = explode(',', $peoplelist);
			if ($tab) {								
				$sth = $DB->prepare('INSERT INTO project_user (usr_login, pro_id, usr_role) VALUES (?, ?, ?)');
				$sth2 = $DB->prepare('DELETE FROM project_user WHERE pro_id=? AND usr_login=?');				
				foreach ($tab as $key => $person) {
					$person = trim($person);
					if (strlen($person) > 0) {
						// Clearing the role of the person
						$sth2->bindParam(1, $this->id, PDO::PARAM_INT);
						$sth2->bindParam(2, $person, PDO::PARAM_STR);
						Helpers::executeStatement($sth2);
						
						// Adding the new role
						$sth->bindParam(1, $person, PDO::PARAM_STR);
						$sth->bindParam(2, $this->id, PDO::PARAM_INT);
						$sth->bindParam(3, $type, PDO::PARAM_STR);
						Helpers::executeStatement($sth);
					}
				}
			}		
		}
		
		/**
		  * ------------------------------------- SPRINT MANAGEMENT
		  */
		
		/**
		  * Retrieve the current sprintId based on current date
		  */
		public function getCurrentSprint() {
			global $DB;
			$sth = $DB->prepare("SELECT spr_id sprintid FROM sprint WHERE DATEDIFF(spr_start_date, NOW()) <= 0 AND DATEDIFF(NOW(), spr_end_date) <= 0 AND pro_id=? LIMIT 1");
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$tab = Helpers::fetchOneRow($sth);
			return $tab['sprintid'];		
		}

		/**
		  * Retrieve the latest sprintId based on current date
		  */
		public function getLastSprint() {
			global $DB;
			$sth = $DB->prepare("SELECT spr_nb nb, spr_id sprintid FROM sprint WHERE pro_id=? ORDER BY spr_nb DESC LIMIT 1");
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchOneRow($sth);
		}

		public function getSprintIdFromNumber($sprintNumber) {
			global $DB;
			$sth = $DB->prepare("SELECT spr_id sprintid FROM sprint WHERE pro_id=? AND spr_nb=?");
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$sth->bindParam(2, $sprintNumber, PDO::PARAM_INT);
			$tab = Helpers::fetchOneRow($sth);
			return $tab['sprintid'];	
		}
		
		/**
		  * Add a new sprint to the current project
		  */
		public function addSprint() {
			global $DB;
			// Retrieve the latest sprint created for the project
			$sth = $DB->prepare("SELECT IFNULL(spr_nb+1,1) nb, DATE_FORMAT(DATE_ADD(spr_end_date, INTERVAL 1 DAY), '%Y-%m-%d') startdate, DATE_ADD(spr_end_date, INTERVAL DATEDIFF(spr_end_date, spr_start_date)+1 DAY) AS enddate, spr_nr_days nrdays, spr_nr_hours_per_day nbhours FROM sprint WHERE pro_id=? ORDER BY spr_nb DESC LIMIT 1");
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$tab = Helpers::fetchOneRow($sth);
		
			if ($tab == NULL) {
				// First sprint created for the project - basic insertion
				$sprintNumber = 1;
				$sth = $DB->prepare('INSERT INTO sprint (pro_id, spr_nb) VALUES (?, ?)');
				$sth->bindParam(1, $this->id, PDO::PARAM_INT);
				$sth->bindParam(2, $sprintNumber, PDO::PARAM_INT);
				return Helpers::executeInsertStatement($sth);							
			} else {
				// We create the new sprint by copying settings from previous one (ease of use for scrum masters)
				$sth = $DB->prepare("INSERT INTO sprint (pro_id, spr_nb, spr_start_date, spr_end_date, spr_nr_days, spr_nr_hours_per_day) VALUES (?, ?, DATE_FORMAT(?, '%Y-%m-%d'), DATE_FORMAT(?, '%Y-%m-%d'), ?, ?)");
				$sth->bindParam(1, $this->id, PDO::PARAM_INT);
				$sth->bindParam(2, $tab['nb'], PDO::PARAM_INT);
				$sth->bindParam(3, $tab['startdate'], PDO::PARAM_STR);						
				$sth->bindParam(4, $tab['enddate'], PDO::PARAM_STR);
				$sth->bindParam(5, $tab['nrdays'], PDO::PARAM_INT);
				$sth->bindParam(6, $tab['nbhours'], PDO::PARAM_INT);
				return Helpers::executeInsertStatement($sth);									
			}	
		}
		
		/**
		  * Retrieve all the sprints for the current project (used to build the sprint dashboard
		  */
		public function getAllSprints() {
			global $DB;
			$sth = $DB->prepare('SELECT spr_nb sprintnb, pro_id projectid, spr_id id, DATE_FORMAT( spr_start_date, \'' . DATE_FORMAT . '\' ) startdate, DATE_FORMAT( spr_end_date, \'' . DATE_FORMAT . '\' ) enddate, spr_goal goal, (DATEDIFF(spr_start_date, NOW()) <= 0 AND DATEDIFF(NOW(), spr_end_date) <= 0) current, spr_nr_days nrdays, spr_nr_hours_per_day nbhours, spr_closed closed, spr_unit unit FROM sprint WHERE pro_id=? ORDER BY spr_nb DESC');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);				
			return Helpers::fetchAll($sth);	
		}

		// @return a list of all projects sorted by project name	
		public static function getAllProjects() { return Helpers::execute(Project::$realSqlSelectStatement . 'ORDER BY pro_name ASC');}
	}
?>