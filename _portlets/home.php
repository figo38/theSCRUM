<?php include '_include/header.php'; ?>
</div>

<div id="home">
    <div id="left">
    	<img src="<?php echo PATH_TO_ROOT?>images/thescrum.png" width="349" height="81" alt="theSCRUM"/><br/>
	</div>
    <div id="middle">        
<?php 
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
?>
    </div>
	<div id="right">
    	<div id="license">
			<div xmlns:cc="http://creativecommons.org/ns#" about="http://www.flickr.com/photos/cdm/2336025560/"><a rel="cc:attributionURL" href="http://www.darkmatterphotography.com/">@</a> / <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/">CC BY-NC-ND 2.0</a></div> 
		</div>       
    </div>    
    <div class="clear"></div>
</div>

<div class="page">
<?php
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
    <td><?php echo $val['startdate']; ?></td>
    <td><?php echo $val['enddate']; ?></td>
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