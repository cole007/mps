<?php
	include_once('../inc/mysql.php');
	// clean me
	if (!isset($member) && isset($_GET['member'])) $member = $_GET['member'];

	$q = "SELECT * FROM `mp` WHERE CONCAT(`firstname`, ' ', `lastname`) LIKE '%".$member."%' OR `constituency` LIKE '%".$member."%'";
	if ($result = $mysqli->query($q)) {
		if ($result->num_rows > 0) {					
			echo '<table>';
			while($row = $result->fetch_array()) {
				// print_r($row);	
				$pattern = '/' . $member . '/i';
				$replacement = '<b>$0</b>';
				$name = preg_replace($pattern, $replacement, $row['firstname'] . ' ' . $row['lastname']);
				$constituency = preg_replace($pattern, $replacement, $row['constituency']);
				$mid = substr($row['mys_id'],strrpos($row['mys_id'],'/')+1);

				// echo strrpos($row['mys_id'],'/');

				$url = '/?mid=' . $mid;
				// echo $url;
				echo '<tr>';	
				echo '<td><a href="' . $url . '" class="mp" data-mid="'.$mid.'">' . $name . '</a></td>';
				echo '<td><a href="' . $url . '" class="mp" data-mid="'.$mid.'">' . $constituency . '</a></td>';
				echo '</tr>';					
			}
			echo '</table>';
		} else {
			echo 'no matches';
		}
	} 