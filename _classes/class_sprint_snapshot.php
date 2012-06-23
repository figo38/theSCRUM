<?php
	class SprintSnapshot {

		/**
		  * Returns the list of snapshots for the given sprint. Used to build the burndown chart of the sprint.
		  * @param $sprintId ID of the sprint
		  */
		public static function getSnapshots($sprintId) {
			global $DB;
			$sth = $DB->prepare('SELECT spr_id, sps_snapshot_date, sps_tasks_nr, sps_tasks_todo, sps_tasks_inprogress, sps_tasks_done, sps_unit_reestim, sps_unit_remaining FROM sprint_snapshot WHERE spr_id = ? ORDER BY sps_id ASC');
			$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
			return Helpers::fetchAll($sth);
		}

		/**
		  * Take a snaphot for the given sprint, and the current day
		  * @param $sprintId ID of the sprint
		  */		  
		public static function takeSnapshot($sprintId) {
			global $DB;
			$sth = $DB->prepare('SELECT s.spr_id, tsk_status status, COUNT(tsk_id) tasks_nr, SUM(tsk_reestim) unit_reestim, SUM(tsk_reestim) - SUM(tsk_worked) unit_remaining FROM sprint s, task t WHERE s.spr_id = t.spr_id AND s.spr_id = ? GROUP BY s.spr_id, tsk_status');
			$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
			$res = Helpers::fetchAll($sth);
		
			if (sizeof($res) > 0) {			
				$tasks_total_nr = 0;
				$tasks_total_reestim = 0;
				$tasks_total_remaining = 0;
				$tasks_todo = 0;
				$tasks_inprogress = 0;
				$tasks_done = 0;
				
				foreach ($res as $key => $val) {
					$tasks_total_nr += (integer)$val['tasks_nr'];
					$tasks_total_reestim += (integer)$val['unit_reestim'];
					$tasks_total_remaining += (integer)$val['unit_remaining'];
					switch ($val['status']) {
						case '0':
							$tasks_todo = (integer)$val['tasks_nr'];
							break;
						case '1':
							$tasks_inprogress = (integer)$val['tasks_nr'];
							break;
						case '2':
							$tasks_done = (integer)$val['tasks_nr'];
							break;
					}
				}

				// Ensure there isn't two snapshots per day
				$sth = $DB->prepare('DELETE FROM sprint_snapshot WHERE sps_snapshot_date = CURDATE() AND spr_id=?');
				$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
				Helpers::executeStatement($sth);
	
				$sth = $DB->prepare('INSERT INTO sprint_snapshot (spr_id, sps_snapshot_date, sps_tasks_nr, sps_tasks_todo, sps_tasks_inprogress, sps_tasks_done, sps_unit_reestim, sps_unit_remaining) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?)');
				$sth->bindParam(1, $sprintId, PDO::PARAM_INT);
				$sth->bindParam(2, $tasks_total_nr, PDO::PARAM_INT);
				$sth->bindParam(3, $tasks_todo, PDO::PARAM_INT);
				$sth->bindParam(4, $tasks_inprogress, PDO::PARAM_INT);
				$sth->bindParam(5, $tasks_done, PDO::PARAM_INT);
				$sth->bindParam(6, $tasks_total_reestim, PDO::PARAM_INT);
				$sth->bindParam(7, $tasks_total_remaining, PDO::PARAM_INT);
				return Helpers::executeStatement($sth);
			} else {
				return false;	
			}
		}
	}
?>