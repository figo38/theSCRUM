<?php
	class SprintDisplay extends Sprint {

		private $displaynone = false;

		function __construct($sprint) {
			if (is_numeric($sprint)) {
				parent::__construct($sprint, true);
			} else {
				$this->setDetails($sprint);
			}
		}

		function setDisplayNone($flag) { $this->displaynone = $flag; }

		function render() {
			global $USERAUTH;
			global $projectId;
			global $projectUrl;

			$id = $this->getId();
			$projectid = $this->getProjectId();
			$nb = $this->getSprintNumber();
			$startdate = $this->getStartDate();
			$enddate = $this->getEndDate();
			$goal = $this->getGoal();
			$style = ($this->displaynone) ? ' style="display:none"' : '';
			
			$current = $this->getField('current');
?>
<tr class="sprint<?php if ($this->isClosed()) { echo ' done';} ?>" id="sprintrow-<?=$id?>"<?php echo $style; ?>>	
	<td class="sid">#<?=$nb?><?php if ($current ==1) {?> <strong>(Current sprint)</strong><?php } ?></td>
	<td>
		<div id="sprint-startdate-<?=$id?>" class="editable sprint-startdate-<?=$id?>"><?php if (!empty($startdate)) { echo $startdate;} else { echo '(Click to edit...)'; } ?></div>	 
	</td>
	<td >
		<div id="sprint-enddate-<?=$id?>" class="editable sprint-enddate-<?=$id?>"><?php if (!empty($enddate)) { echo $enddate;} else { echo '(Click to edit...)'; } ?></div>
	</td>
	<td><span id="sprint-nrdays-<?=$id?>"><?=$this->getNrDays()?></span></td>
	<td><span id="sprint-unit-<?=$id?>"><?=$this->getUnit()?></span></td>	
	<td><span id="sprint-nbhours-<?=$id?>"><?=$this->getNrHoursPerDay()?></span></td>
	<td><span id="sprint-goal-<?=$id?>"><?=nl2br($goal)?></span></td>
	<td><input type="checkbox" id="sprint-closed-<?=$id?>" <?php if ($this->isClosed()) { echo 'checked="checked"'; } ?>/></td>
	<td>
		<a href="<?=$projectUrl?>/sprintbacklog/<?=$nb?>"><?=img('page_white_go.png', 'Go to sprint backlog')?></a>	
<?php if ($USERAUTH->isScrumMasterOf($projectId)) { ?>
		<?=img('time.png', 'Time allocation', 'sprint-time-' . $id)?>
		<?=img('delete.png', 'Delete the sprint', 'sprint-delete-' . $id)?>
<?php } ?>
	</td>
</tr>
<?
		}

	}
?>