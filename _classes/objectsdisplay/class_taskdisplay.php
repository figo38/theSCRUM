<?php
	class TaskDisplay extends Task {

		private $displaynone = false;

		public static $SprintPlanningView = 1;		
		public static $SprintBacklogView = 2;
		public static $ClosedSprintPlanningView = 3;

		public function __construct($task) {
			if (is_numeric($task)) {
				parent::__construct($task, true);
			} else {
				$this->setDetails($task);
			}			

		}

		public function setDisplayNone($flag) { $this->displaynone = $flag; }

		public function renderPostIt() {
			global $USERAUTH;
			global $projectId;
			global $UNIT;			
			$id = $this->getId();
			$owntask = ($USERAUTH->getUserLogin() == $this->getOwner());
?>
<div class="taskpostit<?php if ($owntask) echo ' owntask';?>" id="postit_<?php echo $this->getId()?>" >
	<div class="draggabletitle<?php if ($owntask) echo ' draggableowntitle';?>"><?php echo $this->getTitle()?></div>
	<div class="inner">
		<span class="inactive">
			<strong title="Owner">O:</strong><span id="disp-task-owner-<?php echo $id?>"><?php echo $this->getOwner()?></span>
			<strong title="Remaining work">R:</strong><span id="disp-task-remaining-<?php echo $id?>"><?php echo $this->getReestim() - $this->getWorked()?></span><?php echo $UNIT?>
		</span>
	</div>
<?php if (($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) || $USERAUTH->isTeamMemberOf($projectId)) { ?>
	<div class="edit"><?php echo img('pencil.png', 'Edit the task', 'task-edit-' . $id);?></div>
<?php } ?>
</div>
<?php	
		}

		
		public function render($view = 1, $storyId = 0) {
			global $USERAUTH;
			global $projectId;		
			global $UNIT;
			
			$id = $this->getId();
			$style = ($this->displaynone) ? ' style="display:none"' : '';			
?>
<tr class="task<?php echo $storyId?> taskrow<?php if ($this->isDone()) { echo ' done';} ?>" id="taskrow-<?php echo $id?>"<?php echo $style; ?>>
	<td class="tid"><strong>TASK</strong></td>
	<td colspan="3"><span id="task-prio-<?php echo $id?>"><?php echo $this->getPriority()?></span></td>
	<td><span id="task-title-<?php echo $id?>"><?php echo $this->getTitle()?></span></td>
	<td><strong>Own.:</strong> <span class="task-owner" id="task-owner-<?php echo $id?>"><?php echo $this->getOwner()?></span></td>
	<td<?php if ($view == TaskDisplay::$SprintBacklogView) { echo ' class="inactive"'; } ?>><strong>Estim.:</strong> <span class="task-estim" id="task-estim-<?php echo $id?>"><?php echo $this->getEstim()?></span><?php echo $UNIT?></td>
	<td<?php if ($view == TaskDisplay::$SprintPlanningView) { echo ' class="inactive"'; } ?>><strong>Curr. estim.:</strong> <span id="task-reestim-<?php echo $id?>"><?php echo $this->getReestim()?></span><?php echo $UNIT?></td>	
	<td<?php if ($view == TaskDisplay::$SprintPlanningView) { echo ' class="inactive"'; } ?>><strong>Worked:</strong> <span id="task-worked-<?php echo $id?>"><?php echo $this->getWorked()?></span><?php echo $UNIT?></td>
<?php if (($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) && $view == TaskDisplay::$SprintPlanningView) {?>	
	<td class="icons"><?php echo img('delete.png', 'Delete the task', 'task-delete-' . $id);?></td>
<?php } ?>
</tr>
<?php		
		}
	}		
?>