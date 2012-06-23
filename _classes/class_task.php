<?php
	class Task extends PBObject {

		protected $sqlTableName = 'task';
		protected $sqlIdField = 'tsk_id';
		protected $readSqlStatement = "SELECT tsk_id id, tsk_title title, spr_id sprintid, sto_id storyid, usr_login login, tsk_estim estim, tsk_reestim reestim, tsk_worked worked, tsk_status status, tsk_prio prio FROM task WHERE tsk_id=?";

		public function getTitle() { return $this->getField('title'); }
		public function getSprintId() { return $this->getField('sprintid'); }
		public function getStoryId() { return $this->getField('storyid'); }
		public function getEstim() { return $this->getField('estim'); }
		public function getReestim() { return $this->getField('reestim'); }
		public function getWorked() { return $this->getField('worked'); }
		public function getOwner() { return $this->getField('login'); }
		public function getPriority() { return $this->getField('prio'); }

		public function isToDo() { return $this->getField('status') == 0; }
		public function isInProgress() { return $this->getField('status') == 1; }
		public function isDone() { return $this->getField('status') == 2; }
		
		public function updateTitle($value) { return $this->updateStringField('tsk_title', $value);}
		public function updateOwner($value) { return $this->updateStringField('usr_login', $value);}		
		public function updateEstim($value) { return $this->updateIntField('tsk_reestim', $value) && $this->updateIntField('tsk_estim', $value);}
		public function updateReestim($value) { return $this->updateIntField('tsk_reestim', $value);}
		public function updateWorked($value) { return $this->updateIntField('tsk_worked', $value);}
		public function updatePriority($value) { return $this->updateIntField('tsk_prio', $value);}

		public function markAsTodo() { return $this->updateIntField('tsk_status', 0);}
		public function markAsInProgress() { return $this->updateIntField('tsk_status', 1);}		
		public function markAsDone() { return $this->updateIntField('tsk_status', 2);}
	}
?>