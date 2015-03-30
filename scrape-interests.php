<?php


	$mysqli = new mysqli("localhost", "root", "monkey00", "mps");

	/* check connection */
	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
	}


function xml2array( $xmlObject, $out = array()) {
	foreach ( (array)  $xmlObject as $index => $node)
		$out[$index] = ( is_object ($node)) ? xml2array ( $node) : $node;
	return $out;
}
error_reporting(-1);
$dir    = 'data';
// $files1 = scandir($dir);
$files2 = scandir($dir, 1);

// print_r($files2);
// $xml = simplexml_load_file('data/regmem2013-09-09.xml') or die('cannot load file data/regmem2013-09-09.xml');
// exit;

$q = "SELECT * FROM `interests`";
if ($result = $mysqli->query($q)) {
	while($row = $result->fetch_array()) {
		$rows[] = $row['person_id'].$row['member_id'].$row['date'].$row['file'];
	}

}
$i = 0;
foreach($files2 AS $key => $value) {

	$q = "SELECT * FROM `interests` WHERE `file` = '".$value."'";
	if ($result = $mysqli->query($q)) {
		if ($result->num_rows == 0) {
			$xml = simplexml_load_file($dir . '/' . $value) or die('cannot load file ' . $value);
			print $value . "\n";
			$vals = $xml->regmem;
			foreach ($vals AS $key2 => $value2) {
				$attr = $value2->attributes();
				$personid = $attr['personid'];
				$memberid = $attr['memberid'];
				$membername = $attr['membername'];
				$date = $attr['date'];
				echo 'working';
				if (isset($val)) unset($val);
				foreach($value2->children() AS $children) {
					// then category
					$attr = $children->attributes();
					$category = $attr['name'][0];
					
					foreach($children->item AS $item) {
						// foreach($record AS $item) {
							if (preg_match('/£([0-9])+(,.)*([0-9])*/', $item, $m)) {
								if (isset($import)) unset($import);
								$int = str_replace(',','',substr($m[0],2));
								$import['person_id'] = $personid;
								$import['member_id'] = $memberid;
								$import['member_name'] = $membername;
								$import['date'] = $date;
								$import['category'] = $category;
								$import['item'] = $item;
								$import['value'] = $int;
								$import['file'] = $value;

								$test = $import['person_id'].$import['member_id'].$import['date'].$import['file'];
								// if (!in_array($test,$rows)) {
									echo 'doesnt exist ('.$test.')';
									if (isset($cols)) unset($cols);
									if (isset($data)) unset($data);
									foreach ($import AS $key => $value) {
										$cols[] = $key;
										$data[] = $value;
									}
									$i = "INSERT INTO `interests` (`" . implode('`,`',$cols) . "`) VALUES ('" . implode("','",$data) . "')";
									// echo $i;
									if ($result = $mysqli->query($i)) {
										echo $import['file'] . " added\n";
									} else {
										echo $import['file'] . " not added\n";
									}								
								// } 
								
							}
						// }
					}
					
				}
			}
			$i++;
			if ($i == 1) exit;
		} else {
			echo $value . ' (' . $result->num_rows . ')';
			echo "\n";
		}
	}
	// exit;
			
}


// if (is_dir($dir)) {
//     if ($dh = opendir($dir)) {
//         while (($file = readdir($dh)) !== false) {
//             echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
//         }
//         closedir($dh);
//     }
// }
	mysqli_close($conn);