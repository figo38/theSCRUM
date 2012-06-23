<?php 
	$JS = array('tag');
	$pageTitle = 'Tags';	
	include '_include/header.php';
	
	$hasRights = $USERAUTH->isProductOwner() || $USERAUTH->isAdmin() || $USERAUTH->isScrumMaster();	
?>
<h1>Tags</h1>

<?php if ($hasRights) { ?>
<div id="actionbar">
	<button type="button" id="addnewobject"><span class="btngAddObject">Add a new tag</span></button>
</div>
<?php } else { ?>
<br/>
<?php } ?>

<table>
<thead>
<tr>
	<th>Tag name</th>
	<th>&nbsp;</th>
</tr>
</thead>
<tbody id="featuregroup_tbody">
<?php 
	foreach ($featuregroups as $key => $tag) { 
		$PD = new FeatureGroupDisplay($tag);
		$PD->render();
	} 
?>
</tbody>
</table>

<?php if ($hasRights) { ?>
<script type="text/javascript">
<!--

var featuregroup = new TagMngt();
featuregroup.initAddFeatureGroupButton();
<?php foreach ($featuregroups as $key => $featuregroup) { ?>
featuregroup.enableInteraction(<?php echo $featuregroup['id']?>);
<?php } ?>

-->	
</script>
<?php } ?>