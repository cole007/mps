<?php
	include_once('../inc/mysql.php');
	// header( 'application/javascript' );

	$q = "SELECT * FROM `parties`";
	if ($result = $mysqli->query($q)) {
		while($row = $result->fetch_array()) {
			// print_r($row);
			// $$
			$count = unserialize($row['count']);
			$total = unserialize($row['total']);
			unset($count['total']);
			unset($total['total']);
			// $data[$row['code']]['count'] = $count;
			// $data[$row['code']] = $total;
			// print_r($total);
			foreach($total AS $key => $value) {
				$datum['client'] = $row['code'];
				$datum['date'] = $key;
				$datum['value'] = $value;
				$data[] = $datum;
			}
		}
	}
	echo json_encode($data);