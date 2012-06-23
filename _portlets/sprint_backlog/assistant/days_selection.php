<?php
	/**
	  * Display the panel to define the working days in the current sprint
	  * @param $S Sprint object
	  * @param $configurationUrl Beginning of URL to lead to the configuration page	  
	  * @param $daysselectionErrorMessage true/False indicates if an error msg forcing user to fill days selection must be displayed.
	  */

	if ($S->getStartDateTimestamp() && $S->getEndDateTimestamp()) {
		if ($S->getStartDateTimestamp() < $S->getEndDateTimestamp()) {
			if ($daysselectionErrorMessage) {
				HelpersControl::warningMsg('You must select the working days for your sprint (the burndown chart will only be updated on those days). Then <a href="' . $configurationUrl . 'copy-tasks">copy tasks from previous sprint &raquo;</a>');
			} else {
				HelpersControl::infoMsg('Select the working days in your sprint (the burndown chart will only be updated on those days). Then <a href="' . $configurationUrl . 'copy-tasks">copy tasks from previous sprint &raquo;</a>');
			}
		
			$registeredDays = SprintDays::getRegisteredDays($S->getId());
			$nbDaysToBeRegistered = $S->getNrDays() - sizeof($registeredDays);	
?>
<p>Sprint start date: <strong><?php echo $S->getStartDate();?></strong> ; sprint end date: <strong><?php echo $S->getEndDate();?></strong></p>

<p>Number of working days in the sprint: <strong><?php echo $S->getNrDays();?></strong></p>

<p>Number of days to choose: <span id="sprint-nbdays-allocated"<?php if ($nbDaysToBeRegistered == 0) echo ' class="ok"';?>><?php echo $nbDaysToBeRegistered;?></span></p>

<div class="big_calendar">
<?php
			// From start date to end date, display each date to let scrum master choose the working days
			$D = new DayIterator($S->getStartDateTimestamp(), $S->getEndDateTimestamp());
			foreach ($D as $key => $ts) {
				$dmy = date(DISPLAY_DATE_FORMAT, $ts);
				$dname = date('l', $ts);
				$dnameN = date('N', $ts);
				$dateid = date('Ymd', $ts);
				$weekend = $dnameN == 6 || $dnameN == 7;
				
				// For days already stored in the database, make them selected
				$selected = false;
				if ($registeredDays) {
					foreach ($registeredDays as $k => $v) {
						if (strcmp($v['dt'], $dateid) == 0) {
							$selected = true;
						}
					}
				}
?>
	<div class="day<?php if ($weekend) echo ' weekend'; if ($selected) echo ' selected';?>" id="day_<?php echo $dateid;?>">
		<p><?php echo $dname;?></p>
		<?php echo $dmy;?>
	</div>
<?php
			}
		} else {
			HelpersControl::warningMsg('The <strong>start date</strong> <a href="' . $projectUrl . '/sprints/">must be before</a> the <strong>end date</strong>.');
		}
	} else {
		HelpersControl::warningMsg('The <strong>start date</strong> and <strong>end date</strong> of the sprint <a href="' . $projectUrl . '/sprints/">must be defined</a>.');
	}
?>	
	<div class="clear"></div>
</div>

<script type="text/javascript">
<!--
new DaysSelection(<?php echo $S->getId();?>);
//-->
</script>