<?php
	class Story extends PBObject {
		
		const TABLE_NAME = 'story';
		
		const STORY=1;
		const EPIC=2;
		const SPIKE=3;
		const BUG=4;
		const IMPEDIMENT=5;

		protected $sqlTableName = 'story';
		protected $sqlIdField = 'sto_id';
		protected $readSqlStatement = 'SELECT sto_id id, pro_id projectid, sto_prio priority, sto_estim estimation, sto_percentage percentage, sto_story story, sto_acceptance acceptance, sto_type storytype, sto_update_date, sto_create_date, usr_login, epi_id, epi_id epicid, rel_id releaseid, sto_comment, LENGTH(sto_comment) lengthcomment, sto_url url FROM story WHERE sto_id=?';

		public function getEpicId() { return $this->getField('epicid', 0); }

		public function getProjectId() { return $this->getField('projectid'); }
		public function getAcceptance() { return $this->getField('acceptance'); }
		public function getPriority() { return $this->getField('priority'); }
		public function getEstimation() { return $this->getField('estimation'); }
		public function getPercentage() { return $this->getField('percentage'); }

		public function getStoryType() { return $this->getField('storytype'); }
		public function getUserLogin() { return $this->getField('usr_login'); }
		public function getUpdateDate() { return $this->getField('sto_update_date'); }
		public function getCreateDate() { return $this->getField('sto_create_date'); }
		public function getReleaseId() { return $this->getField('releaseid'); }
		public function getComment() { return $this->getField('sto_comment'); }
		public function getLengthComment() { return $this->getField('lengthcomment'); }
		public function getStory() { return $this->getField('story'); }
		public function getUrl() { return $this->getField('url'); }

		public function isCompleted() { return ($this->getField('percentage') == 100); }
	
		public function isStory() { return ($this->getField('storytype') == 1); }
		public function isEpic() { return ($this->getField('storytype') == 2); }
		public function isSpike() { return ($this->getField('storytype') == 3); }
		public function isBug() {return ($this->getField('storytype') == 4); }
		public function isImpediment() {return ($this->getField('storytype') == 5); }
		public function isStandAlone() { return (!$this->isEpic() && !$this->isSubStory()); }
		public function isSubStory() { return ($this->getField('epicid') > 0); }

		public function updateTags($tagIDs) {
			// TODO: transaction mngt
			global $DB;
			$sth = $DB->prepare('DELETE FROM story_featuregroup WHERE sto_id=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			
			if (Helpers::executeStatement($sth) && $tagIDs) {
				$sth1 = $DB->prepare('INSERT INTO story_featuregroup (sto_id, fea_id) VALUES (?,?)');
				foreach ($tagIDs as $key => $val) {
					$sth1->bindParam(1, $this->id, PDO::PARAM_INT);
					$sth1->bindParam(2, $val, PDO::PARAM_INT);
					Helpers::executeStatement($sth1);
				}				
			}
		}
		
		public function hasTasks() {
			global $DB;
			if ($this->isEpic()) {
				$sth = $DB->prepare('SELECT COUNT(*) nb FROM task WHERE sto_id IN (SELECT sto_id FROM story WHERE epi_id=?)');
			} else {			
				$sth = $DB->prepare('SELECT COUNT(*) nb FROM task WHERE sto_id=?');
			}						
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$tab = Helpers::fetchOneRow($sth);
			return $tab ? $tab['nb'] : 0;						
		}		

		public function getSelectedTags() {
			global $DB;
			$sth = $DB->prepare('SELECT a.fea_id id, fea_name name, IFNULL(sto_id,0) status FROM featuregroup a LEFT JOIN story_featuregroup b ON a.fea_id = b.fea_id AND sto_id=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		// TODO Overriden from Object class
		protected function updateStringField($fieldname, $value) {
			global $DB;
			global $USERAUTH;
			
			$sth = $DB->prepare('UPDATE ' . $this->sqlTableName . ' SET ' . $fieldname . '=?, usr_login=?, sto_update_date=NOW() WHERE ' . $this->sqlIdField . '=?');
			$sth->bindParam(1, $value, PDO::PARAM_STR);
			$sth->bindParam(2, $USERAUTH->getUserLogin());			
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}	


		public function updateStory($content) {
			return $this->updateStringField('sto_story', $content);
		}

		public function updateURL($content) {
			return $this->updateStringField('sto_url', $content);
		}

		public function updateComment($content) {
			return $this->updateStringField('sto_comment', $content);
		}

		public function updateAcceptance($content) {
			return $this->updateStringField('sto_acceptance', $content);
		}

		public function updatePriority($content) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET sto_prio=?, usr_login=?, sto_update_date=NOW() WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_INT);
			$sth->bindParam(2, $USERAUTH->getUserLogin());
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updateEstimation($content) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET sto_estim=?, usr_login=?, sto_update_date=NOW() WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_INT);
			$sth->bindParam(2, $USERAUTH->getUserLogin());
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updatePercentage($content) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET sto_percentage=?, usr_login=?, sto_update_date=NOW() WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_INT);
			$sth->bindParam(2, $USERAUTH->getUserLogin());
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updateType($storytype) {
			global $DB;
			global $USERAUTH;		
			$sth = $DB->prepare('UPDATE story SET sto_type=?, usr_login=?, sto_update_date=NOW() WHERE sto_id=?');
			$sth->bindParam(1, $storytype, PDO::PARAM_INT);
			$sth->bindParam(2, $USERAUTH->getUserLogin());
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updateTicketId($ticketid) {
			global $DB;
			$sth = $DB->prepare('UPDATE story SET tck_id=? WHERE sto_id=?');
			$sth->bindParam(1, $ticketid, PDO::PARAM_INT);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updateRelease($releaseId) {
			global $DB;
			if ($releaseId == 0) {
				$sth = $DB->prepare('UPDATE story SET rel_id=NULL WHERE sto_id=?');
				$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			} else {
				$sth = $DB->prepare('UPDATE story SET rel_id=? WHERE sto_id=?');
				$sth->bindParam(1, $releaseId, PDO::PARAM_INT);
				$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			}
			return Helpers::executeStatement($sth);
		}

		public function removeFromRelease() {
			return $this->updateRelease(0);
		}

		public function updateTitle($content) {
			global $DB;
			$sth = $DB->prepare('UPDATE story SET sto_title=? WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_STR);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updateIsRoadmpaDisplayed($content) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET is_roadmap_displayed=? WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_STR);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		/**
		  * @return a list of storyIDs as a string (i.e: if we delete an epic and all its sub-stories)
		  */
		public function delete() {			
			global $DB;
			$idsstring = (string)($this->id);
			
			if ($this->isEpic()) {
				$sth = $DB->prepare('SELECT sto_id FROM story WHERE epi_id=?');			
				$sth->bindParam(1, $this->id, PDO::PARAM_INT);
				$result = Helpers::fetchAll($sth);
				
				foreach ($result as $key => $val) {
					$idsstring = $val['sto_id'] . ',' . $idsstring;
				}				
			}
			parent::delete();
			return $idsstring;
		}
		
		/**
		  * Check if the given ID belongs to a valid epic. Used to move a standalone story inside an epic
		  * @param id Epic ID to check
		  * @return true or false
		  */
		public static function isValidEpicId($epicid) {
			global $DB;			
			$sth = $DB->prepare('SELECT sto_type FROM story WHERE sto_id=?');			
			$sth->bindParam(1, $epicid, PDO::PARAM_INT);
			$tab = Helpers::fetchOneRow($sth);
			
			return ($tab && $tab['sto_type'] == Story::EPIC);
		}
		
		public function moveToEpic($epicId) {
			global $DB;
			$sth = $DB->prepare('UPDATE story SET epi_id=?, sto_prio=0 WHERE sto_id=?');			
			$sth->bindParam(1, $epicId, PDO::PARAM_INT);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);			
		}

		public function moveOutsideEpic() {
			global $DB;
			$sth = $DB->prepare('UPDATE story SET epi_id=NULL, sto_prio=0 WHERE sto_id=?');			
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);			
		}
	}
?>