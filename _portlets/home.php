<?php include '_include/header.php'; ?>
</div>

<div id="home">
	<div id="left">
    	<div id="license">
			<div xmlns:cc="http://creativecommons.org/ns#" about="http://www.flickr.com/photos/cdm/2336025560/"><a rel="cc:attributionURL" href="http://www.darkmatterphotography.com/">@</a> / <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/">CC BY-NC-ND 2.0</a></div> 
		</div>       
    </div>
    <div id="right">
    	<img src="<?=PATH_TO_ROOT?>images/thescrum.png" width="349" height="50" alt="theSCRUM"/><br/>
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
            	<a class="project" href="<?=$path?>"><?=$right['projectname']?></a>
                (<a href="<?=$path?>sprintbacklog">Sprint backlog</a> - <a href="<?=$path?>whiteboard">Whiteboard</a>)
            </li>
<?php            
		}
		echo '</ul>';
	} else if ($USERAUTH->isAdmin()) {
?>
		<h3>Welcome to theSCRUM | <a href="<?=PATH_TO_ROOT?>project-dashboard">Create your first project &raquo;</a></h3>
<?php		
	} else {
?>
		<h3>Welcome to theSCRUM</h3>
<?php		
	}
?>
	</div>
    <div class="clear"></div>
</div>

<?php if ($monthreleases) { ?>
<div id="monthlyreleasereport">
<strong>Browse the release monthly reports</strong>
<?php foreach ($monthreleases as $key => $monthrelease) { ?>
- <a href="<?=PATH_TO_ROOT.'release-reports/'.$monthrelease['dt']?>"><?=$monthrelease['dispdt']?></a>    
<?php } ?> 

</div>
<?php } ?>

<div class="page">