<?php

	$clanJSON = file_get_contents("solarian.json");
	$clanList = json_decode($clanJSON, TRUE);

	print_r("{");
	
	$i = 0;

	foreach ($clanList as $clan=>$value) {
			 if ($i > 0) { print_r("},"); };		 
			 print(ltrim(rtrim(file_get_contents("https://solarian.net/clanrosterbyid.php?id=".$clanList[$clan]["id"]),"}"),"{"));	$i++;		 
			 }
	print_r("}}");


?>