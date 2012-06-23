<?php
	class ReleaseDisplay extends Release {

		private $displaynone = false;

		function __construct($release) {
			if (is_numeric($release)) {
				parent::__construct($release, true);
			} else {
				$this->setDetails($release);
			}
		}

		function setDisplayNone($flag) { $this->displaynone = $flag; }

		function render($custom = 1) {
			global $USERAUTH;
		
			$id = $this->getId();
			$name = $this->getName();
			$type = $this->getType();
			$isactive = $this->isActive();

			$planneddate = $this->getPlannedDate();
			$deployeddate = $this->getDeployedDate();
			$comment = nl2br($this->getComment());
			$style = ($this->displaynone) ? ' style="display:none"' : '';
			$pc = ($isactive == 0) ? ' class="storydone"' : '';
?>
<tr id="releaserow-<?php echo $id?>">
	<td<?php echo $pc?>><span id="release-type-<?php echo $id?>"><?php echo $type?></span></td>	
	<td<?php echo $pc?>><span id="release-release-<?php echo $id?>"><?php echo $name?></span></td>
	<td<?php echo $pc?>>
		<div id="release-planneddate-<?php echo $id?>" class="editable release-planneddate-<?php echo $id?>"><?php if (!empty($planneddate)) { echo $planneddate;} else { echo '(Click to edit...)'; } ?></div>	 
	</td>
	<td<?php echo $pc?>>
		<div id="release-deployeddate-<?php echo $id?>" class="editable release-deployeddate-<?php echo $id?>"><?php if (!empty($deployeddate)) { echo $deployeddate;} else { echo '(Click to edit...)'; } ?></div>
	</td>
	<td<?php echo $pc?>>
			<span id="release-comment-<?php echo $id?>"><?php echo $comment?></span>
	</td>
<?php if ($custom == 1) { ?>
	<td<?php echo $pc?>>
		<a href="<?php echo PATH_TO_ROOT.'release/'.string2url($this->getDisplayName())?>"><img src="images/icons/page_white_go.png" width="16" height="16" title="Go to release page" alt="Go to release page"/></a>
	<?php if ($USERAUTH->isProductOwner()) { ?>
		<img id="release-delete-<?php echo $id?>" src="images/icons/delete.png" title="Delete the release" alt="Delete the release" class="icon" width="16" height="16"/>
<?php } ?>
	</td>
<?php } ?>
</tr>
<?php
		}

	}
?>