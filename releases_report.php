<?php
	$DMR = Release::getDistinctMonthReleases();	

	if (!isset($yearMonth)) {
		$yearMonth = isset($DMR[0]['dt']) ? $DMR[0]['dt'] : 0;
	}

	$releasesByMonth = Release::getAllPlannedReleasesbyYearMonth($yearMonth);

	// Retrieve the display name of the month to display
	$displayName = '';
	foreach ($DMR as $key => $val) {
		if ($yearMonth==$val['dt']) {
			$displayName = $val['dispdt'];
		}
	}

	$JS = array('report');
	$pageTitle = $displayName . ' monthly report';
	include '_include/header.php';	
?>

<h1><?php echo $displayName?> release report</h1>

<form method="post" id="filtering" action="<?php echo PATH_TO_ROOT?>release-reports">
<div id="filteringoptions">
	<div class="filters">
		<label for="yearmonth">View monthly report for:</label>
		<select name="yearmonth" id="yearmonth">
<?php foreach ($DMR as $key => $val) { ?>
			<option value="<?php echo $val['dt']?>"<?php if ($yearMonth==$val['dt']) echo ' selected="selected"';?>><?php echo $val['dispdt']?></option>
<?php } ?>
		</select>
		<button type="submit" id="submitform">Show</button>
	</div>
</div>
</form>

<h2 style="color:#000;">Released this month:</h2>

<div id="list">
<?php foreach ($releasesByMonth as $key => $release) { ?>
	<span class="releaseTag" id="anchorrel-<?php echo $release['id']?>"><?php echo $release['type']?> <?php echo $release['name']?></span>
<?php } ?>
</div>

<?php
	foreach ($releasesByMonth as $key => $release) {
?>
</div>

<ul class="submenu" style="margin-top:40px;" id="release-<?php echo $release['id']?>">
	<li class="title"><?php echo $release['type']?> <?php echo $release['name']?></li>
	<li><?php if ($release['deployeddate']) { ?><span class="status-deployed">Deployed <?php echo $release['deployeddate']?></span><?php } 
	else { ?><span class="status-planned">Planned for <?php echo $release['planneddate']?></span><?php } ?></li>
</ul>

<div class="page">

<?php if (isset($release['comment']) && strlen($release['comment']) >0) { ?>
<div class="releaseinfo"><?php echo nl2br($release['comment'])?></div>
<?php } ?>

<?php
	$R = new Release($release['id']);
	$storiesList = $R->getStories();
?>

<table>
<thead>
<tr>
	<th>#</th>
	<th>Project</th>
	<th>%</th>
	<th>User story</th>
	<th>Acceptance criteria</th>
</tr>
</thead>
<tbody>
<?php
	if ($storiesList) {
		foreach ($storiesList as $key => $story) {
			$S = new StoryDisplay($story);
			$S->renderSimpleView();	
		}
	} else {
?>
<tr>
	<td colspan="5">No stories.</td>
</tr>
<?php	
	}
?>
</tbody>
</table>

<?php
	}
?>

<script type="text/javascript">
<!--
new ReleaseReport();
-->
</script>