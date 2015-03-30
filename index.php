<?php
	include_once('inc/mysql.php');


	// include search for MP
	// include search for party

	$q = "SELECT party, COUNT(party) AS c, SUM(value) AS v FROM `mp`, `interests` WHERE `interests`.`member_id` = `mp`.`mys_id` GROUP BY party ORDER BY c DESC";
	if ($result = $mysqli->query($q)) {
		while($row = $result->fetch_array()) {
			// print_r($row);
			$rows[$row['party']]['c']['total'] = $row['c'];
			$rows[$row['party']]['v']['total'] = $row['v'];
			// $qD = "SELECT SUM(value) FROM mp AS m, interests AS `i` WHERE party = '".$row['party']."' GROUP BY party";
			// if ($resultD = $mysqli->query($qD)) {
			// 	echo $resultD->num_rows;
			// 	while($rowD = $resultD->fetch_array()) {
			// 		echo '<hr />';
			// 		print_r($rowD);
			// 	}
			// }
		}
	}

	for ($i = 2010; $i <= 2015; $i++) {
		
		$q = "SELECT party, COUNT(party) AS c, SUM(value) AS v FROM `mp`, `interests` WHERE `interests`.`member_id` = `mp`.`mys_id` AND `date` LIKE '" . $i . "-%' GROUP BY party ORDER BY c DESC";
		if ($result = $mysqli->query($q)) {
			while($row = $result->fetch_array()) {
				// print_r($row);
				$rows[$row['party']]['c'][$i] = $row['c'];
				$rows[$row['party']]['v'][$i] = $row['v'];
			}
		}
	}

	foreach($rows AS $key => $value) {
		$i = "INSERT INTO `parties` (`id`,`code`,`count`,`total`) VALUES ('','".$key."','".serialize($value['c'])."','".serialize($value['v'])."')";
		// echo $i;
		if ($result = $mysqli->query($i)) {
			echo $import['file'] . " added\n";
		} else {
			echo $import['file'] . " not added\n";
		}								
	}


	print_r($rows);
