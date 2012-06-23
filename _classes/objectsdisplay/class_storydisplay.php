<?php	
	$currentEpic = 0;
	class StoryDisplay extends Story {
	
		public static $ProductBacklogView = 0;
		public static $SprintPlanningView = 1;		
		public static $SprintBacklogView = 2;
		public static $ClosedSprintPlanningView = 3;
		
		private $displaynone = false;

		function __construct($story) {
			if (is_numeric($story)) {
				parent::__construct($story, true);
			} else {
				$this->setDetails($story);
			}
		}

		function setDisplayNone($flag) { $this->displaynone = $flag; }

		function renderPostIt() {

			$id = $this->getId();
			$storyStory = $this->getStory();
			$storytype = $this->getStoryType();
			$releaseid = $this->getReleaseId();

			if (parent::isEpic()) { $classname = 'storymapepic'; }
			elseif (parent::isStandAlone()) { $classname = 'storystandalonestory'; }
			else { $classname = 'storymapstory'; }
			
			if (parent::isBug()) { $classname .= ' bug'; }
			if (parent::isImpediment()) { $classname .= ' impediment'; }
			if (parent::isSpike()) { $classname .= ' spike'; }
			
			if ($this->isCompleted()) {
				$classname .= ' storypostitdone';
			}
?>
<div class="storypostit <?php echo $classname?>" id="storypostit-<?php echo $id?>">
	<strong><?php formatStoryId($id); ?></strong> <?php formatStoryType($storytype); ?> <?php echo $storyStory?>
<span id="story-url-<?php echo $id?>"><?php if (parent::isBug() && parent::getUrl() != NULL) { ?><a href="<?php echo parent::getUrl();?>" class="url">&raquo;</a><?php } ?></span>
<?php if ($releaseid > 0) { ?>
	<span class="storymaprelease"><?php echo displayReleaseName($releaseid); ?></span>
<?php } ?>
</div>
<?php
		}

		function sc() { if ($this->isCompleted()) { echo ' class="done"';}}
		function pc() { 
			$percentage = $this->getPercentage();
			if ($percentage < 100) {
				echo ' class="percent' . (floor($percentage/10)*10) . '"';
			}
		}

		/**
		  * "simple view" is used to display story in secondary screens: release page, feature group page...
		  * @param $custom 
		  */
		function renderSimpleView($custom = 0) {
			global $USERAUTH;
			global $currentEpic;
			$id = $this->getId();
			$epicId = $this->getEpicId();
			$storytype = $this->getStoryType();			

			if (isset($epicId) && $epicId > 0) {
				// In case the story belongs to an epic, we will try to display the story in a separate line
				if ($currentEpic == 0 || $currentEpic != $epicId) {
					// If several sequential stories belong to the same epic, then we display the epic once
					$currentEpic = $epicId;
?>
<tr class="blankline">
	<td colspan="<?php if ($custom == 1) { ?>6<?php } else {?>5<?php } ?>">&nbsp;</td>
</tr>
<tr class="lastsubstory">
	<td colspan="3">&nbsp;</td>
	<td class="epic"><strong><?php echo $this->getField('epictitle')?></strong></td>
	<td>&nbsp;</td><?php if ($custom == 1) { ?>
	<td>&nbsp;</td><?php } ?>
</tr>
<?php
				}
			} else {
				$currentEpic = 0;
			}
?>
<tr id="storyrow-<?php echo $id?>" class="storyn<?php if ($epicId > 0) { echo ' substory'; } ?>">
	<td class="sid"><?php formatStoryId($id); ?></td>
	<td<?php $this->sc();?>><a href="<?php echo PATH_TO_ROOT.'project/'.string2url($this->getField('projectname'))?>"><?php echo $this->getField('projectname')?></a></td>
	<td<?php $this->sc();?><?php $this->pc();?>><?php echo parent::getPercentage()?>%</td>
	<td<?php $this->sc();?>><?php echo img('accept.png', 'In the release'); ?><?php formatStoryType($storytype); ?> <?php echo $this->getStory()?></td>
	<td<?php $this->sc();?>><?php echo nl2br($this->getAcceptance())?></td><?php if ($custom == 1) { ?>
	<td<?php $this->sc();?>><?php if ($USERAUTH->isProductOwner() || $USERAUTH->isScrumMaster() || $USERAUTH->isAdmin()) { echo img('link_break.png', 'Delete the link story-release', 'deletelink-' . $id); } else { echo '&nbsp;'; } ?></td>
<?php } ?>		
</tr>
<?php
		}

		/**
		  * @param $custom 
		  *    - 0 means "product backlog"
		  *    - 1 means "sprint planning"
		  *    - 2 means "sprint backlog"
		  *    - 3 means "closed sprint planning"
		  */
		function render($custom = 0, $TASKS = array()) {
			global $USERAUTH;
			global $projectId;
			global $projectUnit;
			
			$view = $custom;
			
			$id = $this->getId();
			$storytype = $this->getStoryType();
			$epicId = $this->getEpicId();
			$hasComment = ($this->getLengthComment() > 0);

			// Management of classes to style the product backlog
			$st = '';
			if ($this->isEpic() || $this->isStandAlone()) { $st .= 'levelone ';}
			if ($this->isEpic()) { $st .= 'epic ';}			
			if ($this->isSubStory()) {
				$st = 'substory ';
				$st .= 'substory' . $epicId . ' ';
			}
			$st = trim($st);

			$style = ($this->displaynone) ? ' style="display:none"' : '';

			switch ($view) {
				case (StoryDisplay::$ProductBacklogView):
					if ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) {
						$nbcolumnblankline = 7;
					} else {
						$nbcolumnblankline = 6; // No column for actions
					}
					break;					
				case (StoryDisplay::$SprintPlanningView):
				case (StoryDisplay::$SprintBacklogView):
				case (StoryDisplay::$ClosedSprintPlanningView):
					$nbcolumnblankline = 10;
					break;					
				default:
					$nbcolumnblankline = 7;
					break;
			}
				
			// Management of tasks attached to a story (in case we are displaying the sprint backlog)			
			$condition = true;
			if ($custom == StoryDisplay::$SprintPlanningView) {
				$hasTask = false;
				foreach ($TASKS as $key => $task) {
					if ($task['storyid'] == $id) {
						$hasTask = true;
					}
				}
				if ($this->isCompleted()) {
					$condition = $hasTask;
				}
			}
								
			if ($condition) {
?>
<?php if (!$this->isSubStory()) { ?>
<tr class="blankline" id="storyrowblankline-<?php echo $id?>">
	<td colspan="<?php echo $nbcolumnblankline?>">&nbsp;</td>
</tr>
<?php } ?>
<tr id="storyrow-<?php echo $id?>" class="storyn<?php if (strlen($st) > 0) { echo ' ' . $st;} ?>"<?php echo $style; ?>>
	<td class="sid"><?php formatStoryId($id); ?></td>
	<td<?php $this->sc();?>><span id="story-prio-<?php echo $id?>"><?php echo parent::getPriority()?></span></td>
	<td<?php $this->sc();?>><span id="story-estim-<?php echo $id?>"><?php echo parent::getEstimation()?></span><?php echo $projectUnit?></td>
	<td id="percenttd-<?php echo $id?>" <?php $this->sc();?> <?php $this->pc();?>><span id="story-percentage-<?php echo $id?>"><?php echo parent::getPercentage()?></span>%</td>
	<td id="storymaincell-<?php echo $id?>" <?php $this->sc();?><?php if (parent::isEpic()) { ?> class="epic"<?php } ?><?php if (parent::isBug()) { ?> class="bug"<?php } ?><?php if (parent::isImpediment()) { ?> class="impediment"<?php } ?><?php if (parent::isSpike()) { ?> class="spike"<?php } ?>>
		<div class="story">
			<span style="display:none" id="storytype-<?php echo $id?>"><?php echo $storytype?></span>
			<span id="storytypedisp-<?php echo $id?>"><?php formatStoryType($storytype); ?></span>
			<span id="story-story-<?php echo $id?>"><?php echo nl2br(parent::getStory())?></span>&nbsp;<span id="story-url-<?php echo $id?>"><?php if (parent::isBug() && parent::getUrl() != NULL) { ?><a href="<?php echo parent::getUrl();?>" class="url">&raquo;</a><?php } ?></span>
			<div class="storycomment"><?php echo img((!$hasComment) ? 'comment-light.png' : 'comment.png', 'Edit comment', 'storynotes-' . $id)?></div>
		</div>
	</td>
	<td<?php $this->sc();?> <?php if ($custom == StoryDisplay::$SprintPlanningView || $custom == StoryDisplay::$SprintBacklogView || $custom == StoryDisplay::$ClosedSprintPlanningView) {?>colspan="4"<?php } ?>><span id="story-criteria-<?php echo $id?>"><?php echo nl2br(parent::getAcceptance())?></span></td>
<?php
	switch ($view) {
		// View ProductBacklog: display icons (story details, story delete, add sub-story) only for the product owner
		case (StoryDisplay::$ProductBacklogView):
			if ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) {
?>
	<td<?php $this->sc();?> class="icons">
    	<?php echo img('pencil.png', 'Show story details', 'details-' . $id)?>
    	<?php echo img('delete.png', 'Delete the story', 'delete-' . $id)?>  
        <?php echo img('add.png', 'Add a sub-story', 'addstory-' . $id, parent::isEpic() ? '' : 'hidden')?>      
	</td>
<?php					
			}
			break;
		// View SprintPlanning: display icons (add task) only for the scrum master
		case (StoryDisplay::$SprintPlanningView):
			if ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isProductOwnerOf($projectId)) {
?>
	<td<?php $this->sc();?> class="icons addtask">
		<?php if (!$this->isCompleted()) { if ($this->isStandAlone() || $this->isSubStory()) { echo img('add.png', 'Add task', 'task-add-' . $id); }} else { echo '&nbsp;';} ?>
	</td>
<?php				
			}
			break;
	}
?>
</tr>
<?php
			}
			
			// In case we are in the context of a spring planning / sprint backlog, then display the tasks attached to a story.
			if ($custom == StoryDisplay::$SprintPlanningView || $custom == StoryDisplay::$SprintBacklogView || $custom == StoryDisplay::$ClosedSprintPlanningView) {
				foreach ($TASKS as $key => $task) {
					// Type of viewed aligned between class StoryDisplay and TaskDisplay
					if ($task['storyid'] == $id) {
						$TD = new TaskDisplay($task);					
						$TD->render($custom, $id);
					}
				}	
			}
		}
	}
?>
