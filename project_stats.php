<?php
	$JS = array('stats');	
	$menu = 6;
	$pageTitle = 'Project statistics';
	include '_portlets/project_header.php';
?>

<div id="statsContainer">

	<div class="graph">
		<h4>Worked hours by sprint</h4>
		<div id="container"></div>
	</div>
    
 	<div class="graph">
		<h4><?php echo $P->getDisplayUnit(); ?> by sprint</h4>
		<div id="container2"></div>
	</div>

	<div class="graph">
		<h4>Productivity by sprint (<?php echo $P->getDisplayUnit(); ?> / worked)* 100</h4>
		<div id="container3"></div>
	</div>
    
    <div class="clear"></div>   
    
</div>   

<table>
<thead>
<tr>
	<th>Sprint</th>
    <th>Worked</th>
    <th><?php echo $P->getDisplayUnit(); ?></th>
    <th>Productivity</th>    
</tr>
</thead>
<tbody>
<?php 
	foreach ($P->getStatsPerSprint() as $key => $val) { 
		$productivity = ($val['worked'] == 0) ? 0 : round(100*$val['storypointsnb']/$val['worked'], 2);
?>
<tr>
	<td>Sprint #<span class="sprintno"><?php echo $val['sprintnb']?></span></td>
    <td><span id="worked-<?php echo $val['sprintnb']?>"><?php echo $val['worked']?></span></td>
    <td><span id="storypoints-<?php echo $val['sprintnb']?>"><?php echo $val['storypointsnb']?></span><?php echo $P->getUnit(); ?></td>
    <td><span id="productivity-<?php echo $val['sprintnb']?>"><?php echo $productivity?></span></td>    
</tr>
<?php 
	} 
?>
</tbody>
</table>

<script type="text/javascript">
<!--
var ST = new Stats();
ST.draw('worked-', 'container', 'worked hours', 'Hours');
ST.draw('storypoints-', 'container2', 'story points', 'Points');
ST.draw('productivity-', 'container3', 'productivity', '');
//-->
</script>