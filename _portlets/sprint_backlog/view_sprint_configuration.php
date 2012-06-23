<?php
	/**
	  * Manage the "assistant-like" navigation bar for sprint configuration
	  * @param $subportlet ID of current selected tab
	  * @param $S Sprint object	  
	  */

	// Forcing team allocation to be filled before going to the next step
	$allocationErrorMessage = false;
	if ($subviewtype > 1 && $ALLOCATION == NULL) {
		$subviewtype = 1;
		$allocationErrorMessage = true;
	}	  

	// Forcing days selection to be filled before going to the next step
	$daysselectionErrorMessage = false;
	if ($subviewtype > 2) {
		$registeredDays = SprintDays::getRegisteredDays($S->getId());
		if ($S->getNrDays() - sizeof($registeredDays) > 0) {
			$daysselectionErrorMessage = true;
			$subviewtype = 2;
		}		
	}	  
	  
	switch ($subviewtype) {
		case '1':
			$subportlet = 'assistant/team_allocation.php';
			break;
		case '2':
			$subportlet = 'assistant/days_selection.php';		
			break;
		case '3':
			$subportlet = 'assistant/copy_tasks.php';		
			break;			
	}
	$configurationUrl = $projectUrl . '/configuration/' . $sprintNumber . '/';	
?>

<ol class="assistant">
	<li<?php if ($subviewtype == 1) echo ' class="selected"';?>><span class="nb">1</span> <a href="<?php echo $configurationUrl;?>team-allocation">Define team allocation</a></li>
	<li<?php if ($subviewtype == 2) echo ' class="selected"';?>><span class="nb">2</span> <a href="<?php echo $configurationUrl;?>days-selection">Select working days</a></li>	
	<li<?php if ($subviewtype == 3) echo ' class="selected"';?>><span class="nb">3</span> <a href="<?php echo $configurationUrl;?>copy-tasks">Copy tasks from previous sprint</a></li>
</ol>

<?php
	if ($flagHasRight) {
		include $subportlet;
	} else {
		HelpersControl::infoMsg('This sprint has not been configured yet.');
	}
?>