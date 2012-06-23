<?php
	class TaskDisplay extends Task {

		private $displaynone = false;

		public static $SprintPlanningView = 1;		
		public static $SprintBacklogView = 2;
		public static $ClosedSprintPlanningView = 3;

		function __construct($task) {
			if (is_numeric($task)) {
				parent::__construct($task, true);
			} else {
				$this->setDetails($task);
			}			

		}

		function setDisplayNone($flag) { $this->displaynone = $flag; }

		function renderPostIt() {
			global $USERAUTH;
			global $projectId;
			global $UNIT;			
			$id = $this->getId();
			$owntask = ($USERAUTH->getUserLogin() == $this->getOwner());
?>
<div class="taskpostit<?php if ($owntask) echo ' owntask';?>" id="postit_<?=$this->getId()?>" >
	<div class="draggabletitle<?php if ($owntask) echo ' draggableowntitle';?>"><?=$this->getTitle()?></div>
	<div class="inner">
		<span class="inactive">
			<strong title="Owner">O:</strong><span id="disp-task-owner-<?=$id?>"><?=$this->getOwner()?></span>
			<strong title="Remaining work">R:</strong><span id="disp-task-remaining-<?=$id?>"><?=$this->getReestim() - $this->getWorked()?></span><?=$UNIT?>
		</span>
	</div>
<?php if (($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) || $USERAUTH->isTeamMemberOf($projectId)) { ?>
	<div class="edit"><?=img('pencil.png', 'Edit the task', 'task-edit-' . $id);?></div>
<?php } ?>
</div>
<?			
		}

		
		function render($view = 1, $storyId = 0) {
			global $USERAUTH;
			global $projectId;		
			global $UNIT;
			
			$id = $this->getId();
			$style = ($this->displaynone) ? ' style="display:none"' : '';			
?>
<tr class="task<?=$storyId?> taskrow<?php if ($this->isDone()) { echo ' done';} ?>" id="taskrow-<?=$id?>"<?php echo $style; ?>>
	<td class="tid">&nbsp;</td>
	<td colspan="3"><span id="task-prio-<?=$id?>"><?=$this->getPriority()?></span></td>
	<td><span id="task-title-<?=$id?>"><?=$this->getTitle()?></span></td>
	<td><strong>Own.:</strong> <span class="task-owner" id="task-owner-<?=$id?>"><?=$this->getOwner()?></span></td>
	<td<?php if ($view == TaskDisplay::$SprintBacklogView) { echo ' class="inactive"'; } ?>><strong>Estim.:</strong> <span class="task-estim" id="task-estim-<?=$id?>"><?=$this->getEstim()?></span><?=$UNIT?></td>
	<td<?php if ($view == TaskDisplay::$SprintPlanningView) { echo ' class="inactive"'; } ?>><strong>Curr. estim.:</strong> <span id="task-reestim-<?=$id?>"><?=$this->getReestim()?></span><?=$UNIT?></td>	
	<td<?php if ($view == TaskDisplay::$SprintPlanningView) { echo ' class="inactive"'; } ?>><strong>Worked:</strong> <span id="task-worked-<?=$id?>"><?=$this->getWorked()?></span><?=$UNIT?></td>
<?php if (($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) && $view == TaskDisplay::$SprintPlanningView) {?>	
	<td class="icons"><?=img('delete.png', 'Delete the task', 'task-delete-' . $id);?></td>
<?php } ?>
</tr>
<?php		
		}
	}		
?>