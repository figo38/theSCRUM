<?php
	/**
	  * Manage the following views:
	  * - product backlog
	  * - whiteboard
	  * - roadmap view
	  *
	  * @param $viewtype Decide which page to display (set up by index.php controller)
	  */

	$menu = 1;

	// Decide which portlet to display, which is the page title and javascripts to load
	switch ($viewtype) {
		case '1':
			$includePortlet = '_portlets/product_backlog/view_product_backlog.php';
			$JS = array('productbacklog', 'story');
			$pageTitle = 'Product backlog';
			break;
		case '2':
			$includePortlet = '_portlets/product_backlog/view_story_map.php';
			$JS = array('productbacklog', 'story', 'resizingpostits');
			$pageTitle = 'Story map';
			break;
		case '3':
			$includePortlet = '_portlets/product_backlog/view_roadmap.php';
			$JS = array('story','project');
			$pageTitle = 'Roadmap management';
			break;			
	}
	include '_portlets/project_header.php';

	// Only the product owner and scrum master have right access to the product backlog
	$flagHasRight = $USERAUTH->isProductOwnerOf($projectId) || $USERAUTH->isScrumMasterOf($projectId);

	// Load the product backlog
	$showCompletedStories = getRequestIntParameter('showCompletedStories', 0);
	$stories = $P->getAllStories(array(
		'includecompleted' => $showCompletedStories
	));
?>

<div id="subsubmenuheader">
<?php if (strlen($P->getGoal()) > 0 || $flagHasRight) { ?>
	<div id="goals">
		<strong>Project goal:</strong>
		<span id="project-goal-<?php echo $projectId;?>"><?php echo $P->getGoal()?></span>
	</div>
<?php } ?>
	<div class="subsubmenu">
		<ul>
			<li<?php if ($viewtype==1) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>">The product backlog</a>
			</li>
			<li<?php if ($viewtype==2) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>/storymap">View it as a story map</a>
			</li>
<?php /* Only display if user is scrum master or product owner */ if ($flagHasRight) { ?>            
			<li<?php if ($viewtype==3) echo ' class="selected"';?>>
				<a href="<?php echo $projectUrl?>/roadmap">Define the roadmap view</a>
			</li>
<?php } ?>
		</ul>
	</div>
</div>

<div class="page">

<?php
	// If an admin is logged, check if there is a product owner ; if not, then display an help message
	if ($USERAUTH->isAdmin()) {
		if (!$P->hasProductOwner()) {
			HelpersControl::infoMsg('There is no product owner assigned to this project. <a href="' . $projectUrl . '/team">You should choose someone &raquo;</a>');
		}
	}
	
	// Include the main portlet
	include $includePortlet; 
?>