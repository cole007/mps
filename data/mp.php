<?php
	include_once('../inc/mysql.php');
	// header( 'application/javascript' );

	$q = "SELECT date AS date, SUM(value) AS total FROM interests WHERE member_id = 'uk.org.publicwhip/member/".$_GET['mid']."' GROUP BY YEAR(date), MONTH(date) ORDER BY date ASC";
	if ($result = $mysqli->query($q)) {
		while($row = $result->fetch_array()) {
			// $dates = explode('-',$row['date']);
			// $datum['date'] = new Date(2012,0,3);
			$date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date'] . ' 00:00:00'); // your original DTO
			$newFormat = $date->format('Y-m-d'); // your newly formatted date ready to be substituted into JS new Date();
			unset($date);
			$datum['date'] = $newFormat;
			$datum['value'] = intval($row['total']);
			$data[] = $datum;
			// $data[]
		}
		// echo $result->num_rows;
		// while($row = $result->fetch_array()) {
		// 	// print_r($row);
		// 	// $$
		// 	$count = unserialize($row['count']);
		// 	$total = unserialize($row['total']);
		// 	unset($count['total']);
		// 	unset($total['total']);
		// 	// $data[$row['code']]['count'] = $count;
		// 	// $data[$row['code']] = $total;
		// 	// print_r($total);
		// 	foreach($total AS $key => $value) {
		// 		$datum['client'] = $row['code'];
		// 		$datum['date'] = $key;
		// 		$datum['value'] = $value;
		// 		$data[] = $datum;
		// 	}
		// }
	}
	echo json_encode($data);