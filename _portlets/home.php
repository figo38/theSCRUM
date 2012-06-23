<?php 
	include '_include/header.php'; 
	include '_include/intropageHeader.php'; 

	if ($USERRIGHTS) {
?>
		<h3>Your projects:</h3>
		<ul>
<?php        
		foreach ($USERRIGHTS as $key => $right) {
			$path = PATH_TO_ROOT . 'project/' . string2url($right['projectname']) . '/';
?>			
			<li>
            	<a class="project" href="<?php echo $path?>"><?php echo $right['projectname']?></a>
                (<a href="<?php echo $path?>sprintbacklog">Sprint backlog</a> - <a href="<?php echo $path?>whiteboard">Whiteboard</a>)
            </li>
<?php            
		}
		echo '</ul>';
	} else if ($USERAUTH->isAdmin()) {
?>
		<h3>Welcome to theSCRUM | <a href="<?php echo PATH_TO_ROOT?>project-dashboard">Create your first project &raquo;</a></h3>
<?php		
	} else {
?>
		<h3>Welcome to theSCRUM</h3>
<?php		
	}
	
	include '_include/intropageFooter.php';

	$latestSprints = Project::getLatestSprints();
	if ($latestSprints) {
?>
<h3>Current sprints</h3>

<table>
<thead>
<tr>
	<th>Project</th>
    <th>Current sprint / sprint goal</th>
    <th>Running from</th>
    <th>To</th>
    <th>&nbsp;</th>
</tr>
</thead>
<?php
		foreach ($latestSprints as $key => $val) {
			$path = PATH_TO_ROOT . 'project/' . string2url($val['projectname']) . '/';
?>
<tr>
	<td><a href="<?php echo $path; ?>"><?php echo $val['projectname']; ?></a></td>
	<td><span style="color:#999">Sprint #<?php echo $val['sprintnb']; ?>:</span> <?php echo nl2br($val['sprintgoal']); ?></td>
    <td><?php echo HelpersDate::getFormattedFullDateTime($val['startdate']); ?></td>
    <td><?php echo HelpersDate::getFormattedFullDateTime($val['enddate']); ?></td>
    <td><a href="<?php echo $path; ?>"><?php echo img('page_white_go.png', 'Go to product backlog'); ?></a></td>
</tr>
<?php
		}
?>
</table>
<?php
	}
?>

<?php if ($monthreleases) { ?>
<h3>Monthly release reports</h3>
<div id="monthlyreleasereport">
<strong>Latest reports</strong>
<?php foreach ($monthreleases as $key => $monthrelease) { ?>
- <a href="<?php echo PATH_TO_ROOT.'release-reports/'.$monthrelease['dt']?>"><?php echo $monthrelease['dispdt']?></a>    
<?php } ?> 

</div>
<?php } ?>
</div>

<div class="page">