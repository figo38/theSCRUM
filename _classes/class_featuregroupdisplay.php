<?php
	class FeatureGroupDisplay extends FeatureGroup {

		private $featureGroup = NULL;
		private $displaynone = false;

		function __construct($featureGroup) {
			if (is_numeric($featureGroup)) {
				parent::__construct($featureGroup, true);
			} else {
				$this->setDetails($featureGroup);
			}
		}

		function setDisplayNone($flag) { $this->displaynone = $flag; }

		function render() {
			global $USERAUTH;
			$id = $this->id;
			$name = $this->getName();
			$style = ($this->displaynone) ? ' style="display:none"' : '';
?>
<tr id="featuregrouprow-<?=$id?>"<?php echo $style; ?>>
	<td><span id="featuregroup-name-<?=$id?>"><?=$name?></span></td>
	<td>
    	<a href="<?=PATH_TO_ROOT.'tag/'.string2url($name)?>"><?=img('page_white_go.png', 'See all stories tagged with this tag')?></a>
        <?php if ($USERAUTH->isAdmin()) { echo img('delete.png', 'Delete the tag', 'featuregroup-delete-' . $id); } ?>
	</td>    
</tr>
<?
		}

	}
?>