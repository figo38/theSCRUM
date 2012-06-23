<?php
	class Story extends PBObject {

		protected $sqlTableName = 'story';
		protected $sqlIdField = 'sto_id';
		protected $readSqlStatement = 'SELECT sto_id id, pro_id projectid, sto_prio priority, sto_estim estimation, sto_percentage percentage, sto_story story, sto_acceptance acceptance, sto_type storytype, sto_update_date, sto_create_date, usr_login, epi_id, epi_id epicid, rel_id releaseid, sto_comment, LENGTH(sto_comment) lengthcomment FROM story WHERE sto_id=?';

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

		public function isCompleted() { return ($this->getField('percentage') == 100); }
	
		public function isStory() { return ($this->getField('storytype') == 1); }
		public function isEpic() { return ($this->getField('storytype') == 2); }
		public function isSpike() { return ($this->getField('storytype') == 3); }
		public function isStandAlone() { return (!$this->isEpic() && !$this->isSubStory()); }
		public function isSubStory() { return ($this->getField('epicid') > 0); }

		public function updateFeatureGroups($featureGroupIDs) {
			// TODO: transaction mngt
			global $DB;
			$sth = $DB->prepare('DELETE FROM story_featuregroup WHERE sto_id=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			$sth->execute();

			if ($featureGroupIDs) {
				$sth1 = $DB->prepare('INSERT INTO story_featuregroup (sto_id, fea_id) VALUES (?,?)');
				foreach ($featureGroupIDs as $key => $val) {
					$sth1->bindParam(1, $this->id, PDO::PARAM_INT);
					$sth1->bindParam(2, $val, PDO::PARAM_INT);
					$sth1->execute();
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

		public function getSelectedFeatureGroups() {
			global $DB;
			$sth = $DB->prepare('SELECT a.fea_id id, fea_name name, IFNULL(sto_id,0) status FROM featuregroup a LEFT JOIN story_featuregroup b ON a.fea_id = b.fea_id AND sto_id=?');
			$sth->bindParam(1, $this->id, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		public function updateStory($content) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET sto_story=?, usr_login=?, sto_update_date=NOW() WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_STR);
			$sth->bindParam(2, $USERAUTH->getUserLogin());
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updateComment($content) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET sto_comment=?, usr_login=?, sto_update_date=NOW() WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_STR);
			$sth->bindParam(2, $USERAUTH->getUserLogin());
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
		}

		public function updateAcceptance($content) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET sto_acceptance=?, usr_login=?, sto_update_date=NOW() WHERE sto_id=?');
			$sth->bindParam(1, $content, PDO::PARAM_STR);
			$sth->bindParam(2, $USERAUTH->getUserLogin());
			$sth->bindParam(3, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
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

		public function updateReleaseLink($releaseId) {
			global $DB;
			global $USERAUTH;
			$sth = $DB->prepare('UPDATE story SET rel_id=? WHERE sto_id=?');
			$sth->bindParam(1, $releaseId, PDO::PARAM_INT);
			$sth->bindParam(2, $this->id, PDO::PARAM_INT);
			return Helpers::executeStatement($sth);
                }

                public function deleteLinkRelease() {
                        global $DB;
                        global $USERAUTH;
                        $sth = $DB->prepare('UPDATE story SET rel_id=NULL where sto_id=?');
                        $sth->bindParam(1, $this->id, PDO::PARAM_STR);

                        return Helpers::executeStatement($sth);
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
	}
?>