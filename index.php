<?php
	include_once 'global.php';
	include_once '_classes/classloader.php';

	if (!defined('THESCRUM_VERSION')) {
		define('THESCRUM_VERSION', '0.70');
	}

	define('DISPLAY_DATE_FORMAT', 'M d, Y');
	define('DISPLAY_DATETIME_FORMAT', 'M d, Y g:i');

	$popup = false;

	$projects = Project::getAllProjects();
	$featuregroups = FeatureGroup::getAllTags();
	$releases = Release::getAllActiveReleases();
	$monthreleases = Release::getDistinctMonthReleases(3);	

	$uri = getRequestParameter('uri');

/*
	$urlDefinitions = array(
		// Project dashboard
		'project-dashboard' => array(
			'destination' => 'manage_projects.php'
		),
		// Release dashboard
		'release-dashboard' => array(
			'destination' => 'manage_releases.php'
		),
		'release-reports' => array(
			'destination' => 'releases_report.php',
			'params' => array(
				'1' => 'yearMonth'
			)			
		),
		// The Roadmap view							
		'roadmap' => array(
			'destination' => 'roadmap.php'
		),	
		// Tag dashboard
		'tag-dashboard' => array(
			'destination' => 'manage_tags.php'
		),		
		// The User management page - reserved to ADMIN
		'user-management' => array(
			'destination' => 'user.php',
			'params' => array(
				'1' => 'sortBy'
			)
		),			
	);
*/

	if ($uri == NULL) {
		// No URI means displaying the homepage
		include '_portlets/home.php';
	} else {
		// Removing the latest "/" and exploding the URL
		if (substr($uri, -1) == '/') { $uri = substr($uri, 0, -1);};
		$tab = explode('/', $uri);
		if (isset($tab[0])) {
			// This case should always happen considering we have looked at empty URI from the start.
			switch($tab[0]) {
				
				// NOT SUPPORTED IN 0.70 YET
				case 'login':
					echo getRequestParameter('loginFormLogin');
					include '_portlets/login.php';
					break;

				// Direct access to snapshot generation
				// Primarly used for development
				case 'snapshots':
					$noFooter = true;
					include 'generate_sprint_snapshots.php';
					break;
					
				// Basic pages
				case 'roadmap': 
					include 'roadmap.php'; 
					break;
				
				// USER MANAGEMENT
				case 'user-management':
					if (isset($tab[1])) {
						$sortby = $tab[1];
					}
					include 'user.php'; 
					break;
				case 'project-dashboard': include 'manage_projects.php'; break;
				case 'tag-dashboard': include 'manage_tags.php'; break;
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
							if ($tab[1] == $name && !$projectFound) {
								$projectFound = true;
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

										case 'roadmap': 
											$viewtype = 3; 
											include 'product_backlog.php'; 
											break;

										// ------------------------------------- Sprint backlog views										
										// Logic shared for the different views of the Sprint Backlog
										case 'sprints':  
											$viewtype = 4;
											$lastSprint = $P->getLastSprint();
											$sprintNumber = $lastSprint['nb'];
											$isConfigured = $lastSprint['configured'];
											include 'sprint_backlog.php';
											break;										
										
										case 'firstsprint':
											if (!$P->hasSprints()) {
												// If the project is not configured to manage sprint planning, then we redirect to the homepage
												redirectToHomepage();
											} else {
												// Retrieve the latest sprint nr
												$lastSprint = $P->getLastSprint();
												$sprintNumber = $lastSprint['nb'];										
												if ($sprintNumber > 0) {
													$redirectUrl = $_SERVER['REQUEST_URI'];
													if (substr($redirectUrl, -1) == '/') { $redirectUrl = substr($redirectUrl, 0, -1);};
													$redirectUrl .= '/' . $sprintNumber;
													$host  = $_SERVER['HTTP_HOST'];
													header("Location: http://$host" . PATH_TO_ROOT . 'project/' . $name . '/sprintbacklog/' . $sprintNumber);
													exit;													
												} else {
													$viewtype = 5; 
													include 'sprint_backlog.php';													
												}
											}
											break;

										// URL Structure: /project/<projectname>/<page>/[<additionalinfo>]
										// I.e: /project/seo-project/sprintbacklog/5/
										// I.e: /project/seo-project/configuration/5/team-allocation
										// $tab[3] = '5'
										// $tab[4] = 'team-allocation'
										case 'configuration':
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
															// So the sprint number matches an existing sprint
															$S = new Sprint($sprintId, true);
															$ALLOCATION = $S->getTeamAllocation();
															$TEAM = $P->getTeam();
															
															$isConfigured = $S->getConfigured();
															
															if ($tab[2] == 'configuration') {
																$viewtype = 6;
																
																if ($tab[4]) {
																	switch ($tab[4]) {
																		case 'team-allocation': $subviewtype = 1; break;
																		case 'days-selection': $subviewtype = 2; break;
																		case 'copy-tasks': $subviewtype = 3; break;
																		default:
																			$host  = $_SERVER['HTTP_HOST'];
																			header("Location: http://$host" . PATH_TO_ROOT . 'project/' . $name . '/configuration/' . $sprintNumber . '/team-allocation');
																			break;
																	}
																} else {
																	$host  = $_SERVER['HTTP_HOST'];
																	header("Location: http://$host" . PATH_TO_ROOT . 'project/' . $name . '/configuration/' . $sprintNumber . '/team-allocation');
																}
																include 'sprint_backlog.php';																
															} else if ($isConfigured == 0) {
																// Sprint not configured yet
																switch ($tab[2]) {
																	case 'sprintbacklog':
																	case 'whiteboard':
																	case 'burndown':
																		$host  = $_SERVER['HTTP_HOST'];
																		header("Location: http://$host" . PATH_TO_ROOT . 'project/' . $name . '/configuration/' . $sprintNumber . '/team-allocation');
																		break;
																}
															} else {
																// Sprint configured
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
										case 'team':
											include 'manage_project_team.php'; 
											break;
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
	
	if (!$popup && !$noFooter) {
		include '_include/footer.php'; 
	}
?>