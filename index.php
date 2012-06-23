<?php
	include_once 'global.php';
	include_once '_classes/classloader.php';

	$popup = false;

	$projects = Project::getAllProjects();
	$featuregroups = FeatureGroup::getAllFeatureGroups();
	$releases = Release::getAllActiveReleases();
	$monthreleases = Release::getDistinctMonthReleases(3);	

	$USERRIGHTS = $USERAUTH->getRights();
		
	$uri = trim(isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '');

	if ($uri == '') {
		// No URI means displaying the homepage
		include '_portlets/home.php';
	} else {
		// Removing the latest "/" and exploding the URL
		if (substr($uri, -1) == '/') { $uri = substr($uri, 0, -1);};
		$tab = explode('/', $uri);
		if (isset($tab[0])) {
			// This case should always happen considering we have looked at empty URI from the start.
			switch($tab[0]) {		
				// Basic pages
				case 'roadmap': include 'roadmap.php'; break;
				case 'user-management': include 'user.php'; break;
				case 'project-dashboard': include 'manage_projects.php'; break;
				case 'tag-dashboard': include 'manage_feature_groups.php'; break;
				case 'release-dashboard': include 'manage_releases.php'; break;
				// Release pages
				case 'release-reports': 
					if (isset($tab[1])) {
						$yearMonth = $tab[1];
					}
					include 'releases_report.php';
					break;
				// Tag pages
				case 'tag':
					$matched = false;
					if (isset($tab[1])) {
						foreach ($featuregroups as $key => $featuregroup) {
							$name = string2url($featuregroup['name']);
							if ($tab[1] == $name) {
								$matched = true;
								$groupId = $featuregroup['id'];
								include 'feature_group_backlog.php';
							}
						}
					}
					if (!$matched) { include '_portlets/404.php';}			
					break;
				case 'release':
					$allReleases = Release::getAllReleases();
					$matched = false;
					if (isset($tab[1])) {
						foreach ($allReleases as $key => $release) {
							$name = string2url($release['fullname']);
							if ($tab[1] == $name) {
								$matched = true;
								$releaseId = $release['id'];
								include 'release_detail.php';
							}
						}
					}
					if (!$matched) { include '_portlets/home.php';}				
					break;
				// -------------------------------------
				// ------------------------------------- PROJECT MANAGEMENT
				// -------------------------------------
				// URL Structure: /project/<projectname>/<page>/[<additionalinfo>]
				// I.e: /project/seo-project/sprintbacklog/5/
				case 'project':
					$matched = false;
					if (isset($tab[1])) {
						foreach ($projects as $key => $project) {
							$name = string2url($project['name']);
							if ($tab[1] == $name) {
								$matched = true;
								$projectId = $project['id'];
								$P = new Project($projectId, true);
								
								if (isset($tab[2])) {
									switch ($tab[2]) {
										// ------------------------------------- Product backlog views
										// Story map view
										case 'storymap':
											$viewtype = 2; 
											include 'product_backlog.php'; 
											break;										

										// ------------------------------------- Sprint backlog views										
										// Logic shared for the different views of the Sprint Backlog
										case 'sprintbacklog':
										case 'whiteboard':
										case 'burndown':
											if (!$P->hasSprints()) {
												// If the project is not configured to manage sprint planning, then we redirect to the homepage
												redirectToHomepage();
											} else {
												// Project configured to manage sprint planning
												if (isset($tab[3])) {
													// Checking if the number of sprint is passed into the URL
													if (is_numeric($tab[3])) {																												
														$sprintNumber = $tab[3];
														$sprintId = $P->getSprintIdFromNumber($sprintNumber);
														if ($sprintId > 0) {
															$S = new Sprint($sprintId, true);															
															$ALLOCATION = $S->getTeamAllocation();
															$TEAM = $P->getTeam();
															
															if ($ALLOCATION == NULL) {
																include 'sprint_backlog_fill_allocation.php';
															} else {															
																switch ($tab[2]) {
																	case 'sprintbacklog': $viewtype = 1; break;
																	case 'whiteboard': $viewtype = 2; break;
																	case 'burndown': $viewtype = 3; break;
																}
																include 'sprint_backlog.php';
															}															
														} else {
															include '_portlets/404.php';
														}
													} else {
														include '_portlets/404.php';													
													}
												} else {
													// If not, then retrieve the latest sprint nr
													$lastSprint = $P->getLastSprint();
													$sprintId = $lastSprint['sprintid'];
													$sprintNumber = $lastSprint['nb'];
										
													if ($sprintNumber > 0) {													
														$redirectUrl = $_SERVER['REQUEST_URI'];
														if (substr($redirectUrl, -1) == '/') { $redirectUrl = substr($redirectUrl, 0, -1);}; 	
														$redirectUrl .= '/' . $sprintNumber;
														$host  = $_SERVER['HTTP_HOST'];
														header("Location: http://$host$redirectUrl");
														exit;
													} else {
														include 'sprint_backlog_no_sprint.php';
													}
												}
											}
											break;
											
										// ------------------------------------- Secondary pages
										case 'team': include 'manage_project_team.php'; break;
										case 'roadmap': include 'manage_roadmap.php'; break;
										case 'sprints': include 'project_configuration.php'; break;
										case 'stats': include 'project_stats.php'; break;
										
										// By default, we redirect the unknown page to a 404 page
										default: 
											include '_portlets/404.php'; 
											break;
									}
								} else {
									// No extra parameter: default view for the project is the Product Backlog
									$viewtype = 1;
									include 'product_backlog.php';
								}
							}
						}
					}
					if (!$matched) { include '_portlets/404.php';}			
					break;
				case 'notes':
					$popup = true;
					if (isset($tab[1]) && is_numeric($tab[1])) {
						$storyId = $tab[1];
						include 'story_notes.php';
					}
					break;
				case 'teamallocation':
					$popup = true;
					if (isset($tab[1]) && is_numeric($tab[1])) {
						$sprintId = $tab[1];
						include 'team_allocation.php';
					}					
					break;
				default:
					include '_portlets/404.php';
					break;
			}
		} else {
			// This case should not happen...
			include '_portlets/404.php';
			break;			
		}
	}
	
	if (!$popup) {
		include '_include/footer.php'; 
	}
?>
