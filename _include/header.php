<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php if (isset($pageTitle)) { echo $pageTitle . ' - '; }?>theSCRUM</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript">
<!-- 
var PATH_TO_ROOT = '<?php echo PATH_TO_ROOT?>';
-->
</script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/protoaculous.1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_PROTOTIP?>js/prototip/prototip.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_PROTOTIP?>js/prototip/styles.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_LIGHTVIEW?>js/lightview.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/sortable.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/calendarview.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/js/common.js"></script>
<!--[if IE]>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/excanvas.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/base64.js"></script>
<![endif]--> 
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/canvas2image.js"></script> 
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/flotr-0.2.0-alpha.js"></script> 
<?php if (isset($JS)) { foreach ($JS as $key => $val) { ?>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/js/<?php echo $val?>.js?ver=<?php echo THESCRUM_VERSION?>"></script>
<?php }} ?>
<link rel="stylesheet" href="<?php echo PATH_TO_PROTOTIP?>css/prototip.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo PATH_TO_LIGHTVIEW?>css/lightview.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo PATH_TO_ROOT?>_include/styles.css?ver=<?php echo THESCRUM_VERSION?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo PATH_TO_ROOT?>_include/calendarview.css" type="text/css" media="screen" />
</head>
<body>

<div id="navcontainer">
	<ul id="nav">
		<li><a class="one" href="<?php echo PATH_TO_ROOT?>"><img src="<?php echo PATH_TO_ROOT?>images/s.png" width="16" height="16" alt="Home" title="Home" /></a></li>
		<li><a class="one" href="<?php echo PATH_TO_ROOT?>roadmap">Roadmap</a>
		<li><a class="one" href="<?php echo PATH_TO_ROOT?>project-dashboard">Projects</a>
			<ul class="under">
<?php if ($projects) { foreach ($projects as $key => $project) { ?>
				<li><a href="<?php echo PATH_TO_ROOT.'project/'.string2url($project['name'])?>"><?php echo $project['name']?></a></li>
<?php }} else { ?>
				<li><span>No project yet</span></li>
<?php } ?>
			</ul>
		</li>
		<li><a class="one" href="<?php echo PATH_TO_ROOT?>tag-dashboard">Tags</a>
			<ul class="under">
<?php if ($featuregroups) { foreach ($featuregroups as $key => $featuregroup) { ?>
				<li><a href="<?php echo PATH_TO_ROOT.'tag/'.string2url($featuregroup['name'])?>"><?php echo $featuregroup['name']?></a></li>
<?php }} else { ?>
				<li><span>No tag yet</span></li>
<?php } ?>
			</ul>
		</li>
		<li><a class="one" href="<?php echo PATH_TO_ROOT?>release-dashboard">Releases</a>
			<ul class="under">
<?php if ($releases) { ?>
				<li><span>Only releases being worked on are displayed in this menu.</span></li>
<?php foreach ($releases as $key => $release) { ?>
				<li><a href="<?php echo PATH_TO_ROOT.'release/'.string2url($release['fullname'])?>"><?php echo $release['fullname']?></a></li>
<?php } ?>				
				<li class="sep"><span>Browse the monthly reports</span></li>
<?php if ($monthreleases) { foreach ($monthreleases as $key => $monthrelease) { ?>
				<li><a href="<?php echo PATH_TO_ROOT.'release-reports/'.$monthrelease['dt']?>"><?php echo $monthrelease['dispdt']?></a></li>
<?php }}} else { ?>
				<li><span>No release yet</span></li>
<?php } ?>
			</ul>
		</li>
		<li><a class="one" href="#" style="color:#000; background-color:#fafafa"><?php echo $USERAUTH->getUserLogin(); ?></a>
			<ul class="under">
<?php 
	if ($USERRIGHTS) {
		$currentRole = '';
		foreach ($USERRIGHTS as $key => $right) {
			$label = '&laquo;' . $right['projectname'] . '&raquo; ';
			$href = null;
	
			$hassprint = false;
			foreach ($projects as $key => $project) {
				if ($project['id'] == $right['projectid'] && $project['hassprints'] == 1) {
					$hassprint = true;
				}
			}
			
			if (strcmp($currentRole, $right['role']) != 0){
				$currentRole = $right['role'];											
				$currentRoleName = '';
				switch ($currentRole) {
					case 'P':
						$currentRoleName = 'product owner';					
						break;
					case 'S':
						$currentRoleName = 'scrum master';
						break;
					case 'T':
						$currentRoleName = 'team member';
						break;
				}
?>
				<li><span>As <?php echo $currentRoleName;?>:</span></li>
<?php											
			}

			switch ($right['role']) {
				case 'P':
					$href = PATH_TO_ROOT . 'project/' . string2url($right['projectname']);
					$label .= 'product backlog';
					break;
				case 'S':
					if ($hassprint) {
						$href = PATH_TO_ROOT . 'project/' . string2url($right['projectname']) . '/sprintbacklog';
						$label .= 'sprint backlog';
					}	
					break;
				case 'T':
					if ($hassprint) {
						$href = PATH_TO_ROOT . 'project/' . string2url($right['projectname']) . '/whiteboard';
						$label .= 'whiteboard';
					}	
					break;
			}
			if ($href != null) {
?>
				<li><a href="<?php echo $href?>"><?php echo $label?></a></li>
<?php }}} else { ?>
				<li><span>You only have read access on theSCRUM.</span></li>
<?php } ?>
			</ul>			
		</li>
<?php if ($USERAUTH->isAdmin()) { ?>
		<li><a class="one" href="<?php echo PATH_TO_ROOT?>user-management">User management</a></li>
<?php } ?>
	</ul>
</div>

<div class="page">
