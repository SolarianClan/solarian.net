<?php
function date_compare($a, $b)
{
    $t1 = strtotime($a['joinDate']);
    $t2 = strtotime($b['joinDate']);
    return $t1 - $t2;
}    

$clanJSON = file_get_contents("https://solarian.net/solarianroster.php");
$clanList = json_decode($clanJSON, TRUE);

usort($clanList, 'date_compare');

//print_r(json_encode($clanList));

//print_r($clanList);

foreach($clanList as $member=>$value) {
	
		//print_r($clanList[$member]['username']."	");
	
		$thisJoinDate = date_parse($clanList[$member]["joinDate"]);
		$compositeJoinDate = $thisJoinDate['year']."-".$thisJoinDate['month']."-".$thisJoinDate['day'];
		$campaignStartDate = "2018-12-29";
	
		$compositeJoinDateObj = date_create($compositeJoinDate);
		$campaignStartDateObj = date_create($campaignStartDate);
	
		$interval = date_diff($campaignStartDateObj, $compositeJoinDateObj);
		if ($interval->invert == 0) {
				switch ($clanList[$member]['platform']) {
					case "1":
						$thisPlatform = 'xbox';
						break;
					case "2":
						$thisPlatform = 'ps';
						break;
					case "4":
						$thisPlatform = 'pc';
						break;
				}
			
				print_r($clanList[$member]['username']."	".$thisPlatform."	".$compositeJoinDate."\n");
		}
	
}

?>