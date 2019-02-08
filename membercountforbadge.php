<?php

$clanJSON = file_get_contents("https://solarian.net/solarianroster.php");
$clanList = json_decode($clanJSON, TRUE);

$onlineCount = 0;
$memberCount = 0;

foreach($clanList as $member => $value) {
	
	$memberCount++;
	if ($clanList[$member]['isOnline'] === "1") { $onlineCount++; }
	
}

print_r("Total Clan Members: ".$memberCount." ⚫️  ❖  Playing Now: ".$onlineCount." ⚪️");

?>