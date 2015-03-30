<?php
	echo 'test';
	$json = file_get_contents('mps.js');
	$obj = json_decode($json);
	foreach($obj AS $value) {
		print_r($value);
	}