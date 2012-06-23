<?php
	$S = new Sprint($sprintId, true);
	
	$projectId = $S->getProjectId();
	$P = new Project($projectId);
	
	$TEAM = $P->getTeam();	
	$ALLOCATION = $S->getTeamAllocation();	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Team allocation</title>
<script type="text/javascript">
<!-- 
var PATH_TO_ROOT = '<?php echo PATH_TO_ROOT?>';
-->
</script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/protoaculous.1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_PROTOTIP?>js/prototip/prototip.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/js/common.js"></script>
<script type="text/javascript" src="<?php echo PATH_TO_ROOT?>_include/js/teamallocation.js"></script>
<link rel="stylesheet" href="<?php echo PATH_TO_ROOT?>_include/styles.css" type="text/css" media="screen" />
<style>
<!--
body { background: none;}
-->
</style>
</head>
<body>
<br/>

<?php include '_portlets/teamallocation/team.php'; ?>

</body>
</html>