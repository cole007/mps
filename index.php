<?php
	include_once('inc/mysql.php');
	include_once('inc/header.php');
	// clean out content stuff here
	if (isset($_GET['member'])) $member = $_GET['member'];
	if (isset($_GET['mid'])) $mid = $_GET['mid'];
	// include search for MP
	// include search for party

	?>
	<style>
	.chart {
	  font-family: Arial, sans-serif;
	  font-size: 10px;
	}

	.axis path, .axis line {
	  fill: none;
	  stroke: #000;
	  shape-rendering: crispEdges;
	}

	.bar {
	  fill: steelblue;
	}
	</style>
	
	<form method="GET" id="search">
		<p>Please enter your MP or constituency</p>
		<input id="member" name="member" type="text" <?php
			if (isset($member)) echo ' value="'.$member.'"';
		?>/>
	</form>
	<div class="results">
	<?php
		if (isset($mid)) {
			include_once('inc/data.php');
		} elseif (isset($member)) {
			include_once('inc/member.php');
		}
	echo '</div>';	
	
	include_once('inc/footer.php');