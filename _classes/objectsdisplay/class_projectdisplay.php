<?php
	class ProjectDisplay extends Project {

		private $displaynone = false;

		function __construct($project) {
			if (is_numeric($project)) {
				parent::__construct($project, true);
			} else {
				$this->setDetails($project);
			}
		}

		function setDisplayNone($flag) { $this->displaynone = $flag; }

		function render() {
			global $USERAUTH;
			$id = $this->id;
			$name = $this->getName();
			$velocity = $this->getVelocity();	
			$style = ($this->displaynone) ? ' style="display:none"' : '';
?>
<tr class="project" id="projectrow-<?php echo $id?>"<?php echo $style; ?>>
	<td><span id="project-name-<?php echo $id?>"><?php echo $name?></span></td>
	<td><span id="project-unit-<?php echo $id?>"><?php echo $this->getUnit()?></span></td>		
	<td><span id="project-velocity-<?php echo $id?>"><?php echo $velocity?></span></td>
	<td><span id="project-goal-<?php echo $id?>"><?php echo nl2br($this->getGoal())?></span></td>
	<td><input type="checkbox" id="project-hassprint-<?php echo $id?>" <?php if ($this->hasSprints()) { echo 'checked="checked"'; } ?>/></td>
	<td><span id="project-generationhour-<?php echo $id?>"><?php echo $this->getGenerationHour();?></span></td>	
	<td>
		<a href="<?php echo PATH_TO_ROOT.'project/'.string2url($name)?>"><?php echo img('page_white_go.png', 'Go to project page')?></a>
		<?php if ($USERAUTH->isAdmin()) { echo img('delete.png', 'Delete the project', 'project-delete-' . $id);}?>
	</td>
</tr>
<?php
		}
	}
?>