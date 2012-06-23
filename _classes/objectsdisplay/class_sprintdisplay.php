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
<tr class="sprint<?php if ($this->isClosed()) { echo ' done';} ?>" id="sprintrow-<?php echo $id?>"<?php echo $style; ?>>	
	<td class="sid"><a href="<?php echo $projectUrl?>/sprintbacklog/<?php echo $nb?>">#<?php echo $nb?></a><?php if ($current ==1) {?> <strong>(Current sprint)</strong><?php } ?></td>
	<td>
		<div id="sprint-startdate-<?php echo $id?>" class="editable sprint-startdate-<?php echo $id?>"><?php if (!empty($startdate)) { echo $startdate;} else { echo '(Click to edit...)'; } ?></div>	 
	</td>
	<td >
		<div id="sprint-enddate-<?php echo $id?>" class="editable sprint-enddate-<?php echo $id?>"><?php if (!empty($enddate)) { echo $enddate;} else { echo '(Click to edit...)'; } ?></div>
	</td>
	<td><span id="sprint-nrdays-<?php echo $id?>"><?php echo $this->getNrDays()?></span></td>
	<td><span id="sprint-unit-<?php echo $id?>"><?php echo $this->getUnit()?></span></td>	
	<td><span id="sprint-nbhours-<?php echo $id?>"><?php echo $this->getNrHoursPerDay()?></span></td>
	<td><span id="sprint-goal-<?php echo $id?>"><?php echo nl2br($goal)?></span></td>
	<td><input type="checkbox" id="sprint-closed-<?php echo $id?>" <?php if ($this->isClosed()) { echo 'checked="checked"'; } ?>/></td>
	<td>
		<a href="<?php echo $projectUrl?>/sprintbacklog/<?php echo $nb?>"><?php echo img('page_white_go.png', 'Go to sprint backlog')?></a>	
<?php if ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) { ?>
		<a href="<?php echo $projectUrl?>/configuration/<?php echo $nb?>"><?php echo img('table.png', 'Go to configuration tool')?></a>	
		<?php echo img('delete.png', 'Delete the sprint', 'sprint-delete-' . $id)?>
<?php } ?>
	</td>
</tr>
<?php
		}

	}
?>