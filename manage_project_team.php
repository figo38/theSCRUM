<?php
	/**
	  * Manage the following views:
	  * - build the project team
	  * - manage team allocation for the current sprint
	  *
	  * @param $viewtype Decide which page to display (set up by index.php controller)
	  */

	$menu = 4;
	
	$JS = array('project_people');
	$pageTitle = 'Team management';
	include '_portlets/project_header.php';

	$cond = ($USERAUTH->isScrumMasterOf($projectId) || $USERAUTH->isAdmin());
?>

<div id="subsubmenuheader">
	<div class="subsubmenu">
		<ul>
			<li class="selected">
				<a href="">Build your project team</a>
			</li>
		</ul>
	</div>
</div>

<div class="page">

<?php
	$USERS = $P->getAllUsers();

	function displayUsers($USERS, $type) {
		global $cond;
		foreach ($USERS as $key => $user) { 
			if ($user['role'] == $type) { 
				$login = $user['login'];
			?>
		<div class="lineitem" id="user_<?php echo $login?>"><div class="innerlineitem"><?php echo $login?></div></div>
<?php 
			}
		}
		if ($cond) {
?>
		<div class="lineitem placeholder">Drag user here</div>
<?php
		} 
	}
?>
<div id="usermanagement">
	<div id="allteam">	
		<div class="section">
			<div class="innerSection" id="productowners">
				<h3 class="handle">Product owner</h3>
<?php if ($cond) { ?>
				<p>You should assign only one product owner to the project; however, it may be useful defining two product owners in case of holidays...</p>
<?php } displayUsers($USERS, 'P'); ?>
			</div>
		</div>

		<div class="section">
			<div class="innerSection" id="scrummasters">
				<h3 class="handle">Scrum master</h3>
<?php if ($cond) { ?>				
				<p>You should assign only one scrum master to the project; however, it may be useful defining two scrum masters in case of holidays...</p>
<?php } displayUsers($USERS, 'S'); ?>
			</div>
		</div>

		<div class="section last">
			<div class="innerSection" id="team">
				<h3 class="handle">Team</h3>
<?php if ($cond) { ?>					
				<p>You can assign as many people as you want to this project; however, keep in mind the most efficient agile teams have between 2 and 7 people.</p>
<?php } displayUsers($USERS, 'T'); ?>
			</div>
		</div>

		<div class="clear"></div>
	</div>
	
	<div class="innerSection" id="allusers">
		<h3 class="handle">All users</h3>
<?php if ($cond) { ?>		
		<p>Drag &amp; drop the users you want define as product owners, scrum masters or team members for this project.</p>
<?php } displayUsers($USERS, 'N'); ?>
		<div style="clear:both"></div>
	</div>	
</div>

<?php if ($cond) { ?>
<script type="text/javascript">
<!--
var projectpeople = new ProjectPeople();
projectpeople.init(<?php echo $projectId?>);
-->
</script>
<?php } ?>