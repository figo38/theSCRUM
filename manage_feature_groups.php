<?php 
	$JS = array('featuregroup');
	$pageTitle = 'Tag dashboard';		
	include '_include/header.php';
?>
<h1>Tag dashboard</h1>

<?php if ($USERAUTH->isProductOwner()) { ?>
<div id="actionbar">
	<button type="button" id="addnewobject"><span class="btngAddObject">Add a new tag</span></button>
</div>
<?php } else { ?>
<br/>
<?php } ?>

<table id="sortableprojectlist">
<thead>
<tr>
	<th>Tag name</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody id="featuregroup_tbody">
<?php 
	foreach ($featuregroups as $key => $featuregroup) { 
		$PD = new FeatureGroupDisplay($featuregroup);
		$PD->render();
	} 
?>
</tbody>
</table>

<?php if ($USERAUTH->isProductOwner() || $USERAUTH->isScrumMaster() || isAdmin()) { ?>
<script type="text/javascript">
<!--

var featuregroup = new FeatureGroupMngt();
featuregroup.initAddFeatureGroupButton();
<?php foreach ($featuregroups as $key => $featuregroup) { ?>
featuregroup.enableInteraction(<?=$featuregroup['id']?>);
<? } ?>

-->	
</script>
<?php } ?>