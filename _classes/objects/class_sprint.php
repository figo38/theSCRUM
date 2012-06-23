<?php
	class Sprint extends PBObject {
		
		const TABLE_NAME = 'sprint';

		protected $sqlTableName = 'sprint';
		protected $sqlIdField = 'spr_id';	
		protected $readSqlStatement = 'SELECT spr_id id, spr_nb sprintnb, pro_id projectid, spr_goal goal, DATE_FORMAT( spr_start_date, \'%Y-%m-%d\' ) startdate, DATE_FORMAT( spr_end_date, \'%Y-%m-%d\' ) enddate, spr_nr_days nrdays, spr_nr_hours_per_day nbhours, spr_closed closed, spr_unit unit, spr_copied_from_previous copiedfromprevious, spr_retro_1, spr_retro_2, UNIX_TIMESTAMP(spr_start_date) startdate_ts, UNIX_TIMESTAMP(spr_end_date) enddate_ts, spr_configured configured FROM sprint WHERE spr_id=?';

		public function getSprintNumber() { return $this->getField('sprintnb'); }		
		public function getStartDate() { return $this->getField('startdate'); }		
		public function getEndDate() { return $this->getField('enddate'); }

		public function getStartDateTimestamp() { return $this->getField('startdate_ts'); }		
		public function getEndDateTimestamp() { return $this->getField('enddate_ts'); }
		
		public function getGoal() { return $this->getField('goal'); }
		public function getProjectId() { return $this->getField('projectid'); }
		public function getNrDays() { return $this->getField('nrdays'); }
		public function getNrHoursPerDay() { return $this->getField('nbhours'); }
		public function isClosed() { return $this->getField('closed'); }
		public function getUnit() { return $this->getField('unit'); }
		public function hasTasksCopiedFromPrevious() { return $this->getField('copiedfromprevious'); }		
		public function getRetro1() { return $this->getField('spr_retro_1'); }
		public function getRetro2() { return $this->getField('spr_retro_2'); }
		public function getConfigured() { return $this->getField('configured'); }
				
		public function updateStartDate($value) { return $this->updateDateField('spr_start_date', $value);}	
		public function updateEndDate($value) { return $this->updateDateField('spr_end_date', $value);}	
		public function updateConfigured($value) { return $this->updateIntField('spr_configured', $value);}						
		public function updateNrDays($value) { return $this->updateIntField('spr_nr_days', $value);}		
		public function updateNrHoursPerDay($value) { return $this->updateIntField('spr_nr_hours_per_day', $value);}		
		public function updateClosed($value) { return $this->updateIntField('spr_closed', $value);}
		public function updateGoal($value) { return $this->updateStringField('spr_goal', $value);}
		public function updateUnit($value) { return $this->updateStringField('spr_unit', $value);}
		public function updateCopiedFromPrevious($value) { return $this->updateIntField('spr_copied_from_previous', $value);}		
		public function updateRetro1($value) { return $this->updateStringField('spr_retro_1', $value);}
		public function updateRetro2($value) { return $this->updateStringField('spr_retro_2', $value);}

		public function getUnfinishedTasksFromPrevious() {
			global $DB;
			$nb = $this->getSprintNumber();
			if ($nb > 1) {
				$sprintId = Sprint::getSprintId($nb - 1, $this->getProjectId());
				$sth = $DB->prepare('SELECT tsk_title title, sto_id, tsk_reestim, tsk_worked, tsk_estim, IF(tsk_reestim - tsk_worked < 0, 0, tsk_reestim - tsk_worked) estim, tsk_status, tsk_prio FROM task WHERE tsk_status in (0,1) AND spr_id=?');
				$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
				$sth->execute();
				return $sth->fetchAll();
			} else {
				return array();
			}
		}

		public function copyTasksFromPrevious() {
			global $DB;
			$tab = $this->getUnfinishedTasksFromPrevious();
			if ($tab) {				
				$sth2 = $DB->prepare('INSERT INTO task (tsk_title, spr_id, sto_id, tsk_status, tsk_prio, tsk_estim, tsk_reestim) VALUES (?, ?, ?, ?, ?, ?, ?)');			
				foreach ($tab as $key => $val) {
					$sth2->bindParam(1, $val['title'], PDO::PARAM_STR);
					$sth2->bindParam(2, $this->id, PDO::PARAM_INT);
					$sth2->bindParam(3, $val['sto_id'], PDO::PARAM_INT);
					$sth2->bindParam(4, $val['tsk_status'], PDO::PARAM_INT);					
					$sth2->bindParam(5, $val['tsk_prio'], PDO::PARAM_INT);
					$sth2->bindParam(6, $val['estim'], PDO::PARAM_INT);
					$sth2->bindParam(7, $val['estim'], PDO::PARAM_INT);														
					$res = $sth2->execute();
				}
				
				$this->updateCopiedFromPrevious(1);
				$this->setField('copiedfromprevious', 1);
			}
		}

		public static function getSprintId($sprintNumber, $projectId) {
			global $DB;
			$sth = $DB->prepare('SELECT spr_id FROM sprint WHERE spr_nb=? AND pro_id=?');
			$sth->bindParam(1, $sprintNumber, PDO::PARAM_INT);
			$sth->bindParam(2, $projectId, PDO::PARAM_INT);
			$sth->execute();
			$res = $sth->fetch(PDO::FETCH_ASSOC);			
			return $res['spr_id'];
		}

		public function getTasks($params = array()) {
			global $DB;
			$includecompleted = isset($params['includecompleted']) ? (int)($params['includecompleted']) : 0;

			$sql = 'SELECT tsk_id id, tsk_title title, spr_id sprintid, sto_id storyid, usr_login login, tsk_estim estim, tsk_reestim reestim, tsk_worked worked, tsk_status status, tsk_prio prio FROM task WHERE spr_id=?';
			if ($includecompleted == 0) {
				$sql .= 'AND tsk_status != 2';
			}			
			$sql .= ' ORDER BY sto_id ASC, tsk_prio DESC';
						
			$sth = $DB->prepare($sql);
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$sth->execute();
			return $sth->fetchAll();		
		}
		
		public function addTask($storyId, $task) {
			global $DB;
			$task = trim($task);
			
			$sth = $DB->prepare('INSERT INTO task (tsk_title, spr_id, sto_id) VALUES (?, ?, ?)');
			$sth->bindParam(1, $task, PDO::PARAM_STR);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			$sth->bindParam(3, $storyId, PDO::PARAM_INT);
			$res = $sth->execute();
			
			if ($res) {
				return $DB->lastInsertId();
			} else { 
				error_log($sth->errorInfo());
				return 0;
			}			
		}
		
		public function getTeamAllocation() {
			global $DB;	
			$sth = $DB->prepare("SELECT usr_login login, spu_percentage percentage FROM sprint_user WHERE spr_id=?");
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$sth->execute();
			$res = $sth->fetchAll();
			$ret = NULL;
			if ($res) {
				$ret = array();
				foreach ($res as $key => $val) {
					$ret[$val['login']] = $val['percentage'];
				}
			}
			return $ret;
		}
		
		public function updateMemberAllocation($login, $percentage) {
			global $DB;
			$sth = $DB->prepare('UPDATE sprint_user SET spu_percentage=? WHERE usr_login=? AND spr_id=?');
			$sth->bindParam(1, $percentage, PDO::PARAM_INT);
			$sth->bindParam(2, $login, PDO::PARAM_STR);
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);			
			$res = $sth->execute();
			if ($sth->rowCount() == 0) {
				$sth = $DB->prepare('INSERT INTO sprint_user (spu_percentage, usr_login, spr_id) VALUES (?, ?, ?)');
				$sth->bindParam(1, $percentage, PDO::PARAM_INT);
				$sth->bindParam(2, $login, PDO::PARAM_STR);
				$sth->bindParam(3, $this->id, PDO::PARAM_INT);
				$res = $sth->execute();			
			}
			return $res;
		}
		
		public function getStatistics() {
			global $DB;	
			$sth = $DB->prepare('SELECT tsk_status status, COUNT(*) nbtasks, SUM(tsk_estim) totalestim, SUM(tsk_reestim) totalreestim, SUM(tsk_worked) worked FROM task WHERE spr_id=? GROUP BY tsk_status');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		public function getBurndownData() {
			global $DB;
			$sth = $DB->prepare('SELECT COUNT(*) nbtasks, IFNULL(SUM(tsk_estim),0) totalestim FROM task WHERE spr_id=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchOneRow($sth);   
    }

		public static function getActiveSprints() {
			return Helpers::execute("SELECT spr_id id FROM sprint WHERE (DATEDIFF(spr_start_date, NOW()) <= 0 AND DATEDIFF(NOW(), spr_end_date) <= 0)");
		}

		public static function getSprintsToGenerateBurndown() {
			/*return Helpers::execute("SELECT a.spr_id id FROM sprint a, sprint_days b, project c WHERE a.spr_id = b.spr_id AND a.pro_id = c.pro_id AND DATE_FORMAT(NOW(),'%Y%m%d') = spd_date AND TIMEDIFF(TIME(NOW()), TIME(pro_generation_hour)) <= 0 AND TIMEDIFF(TIME(pro_generation_hour), TIME(NOW() + INTERVAL 30 MINUTE)) <= 0");*/			
			return Helpers::execute("SELECT a.spr_id id FROM sprint a, sprint_days b, project c WHERE a.spr_id = b.spr_id AND a.pro_id = c.pro_id AND DATE_FORMAT(CURDATE(),'%Y%m%d') = spd_date AND TIME(NOW()) < TIME(pro_generation_hour) AND TIME(pro_generation_hour) < TIME(NOW() + INTERVAL 30 MINUTE)");			
		}
	}		
?>