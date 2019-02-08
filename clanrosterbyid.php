<?php

# define base protocol used to query API
define("PROTOCOL", "https://");
# define server and FQDN to access API from
define("API_SERVER", "www.bungie.net");
# define origin header
define("ORIGIN_HEADER", "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
# define application version
define("VERSION_NUMBER", "0.1");
# define release number
define("RELEASE_NUMBER", date("YmdHis", filemtime(".")));
# define application name
define("APPLICATION_NAME", "Solarian-Clan");
# define complete User Agent
define("USER_AGENT", APPLICATION_NAME." ".VERSION_NUMBER."(".RELEASE_NUMBER.")");


define("CLIENT_ID", "25146");
define("API_KEY", "4b586d1833ee491c8b4f6ede97afa9f1");
define("SECRET", "c-lNmHuN8kOkIiVtdeg5SD.4H9880gPdIVLbb.g51nE");


if (isset($_GET["id"])) {
	$clanId = urldecode($_GET["id"]);
} else {
	$clanId = '389581';
}

$thisURL = "/Platform/GroupV2/".$clanId."/Members/?memberType=0";

	$pQueryHandle = curl_init();
    $pQueryURL = PROTOCOL.API_SERVER.$thisURL;

    curl_setopt($pQueryHandle, CURLOPT_URL,$pQueryURL);
    curl_setopt($pQueryHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
                		'X-API-Key: '.API_KEY,
                        'Origin: '.ORIGIN_HEADER,
                        'User-Agent: '.USER_AGENT
                        ]);
	curl_setopt($pQueryHandle, CURLOPT_HTTPGET, true);

	$pQueryReturn = curl_exec($pQueryHandle);

    curl_close($pQueryHandle);

    $results = json_decode($pQueryReturn, true);

	// print_r($results);

	$roster = $results["Response"]["results"];

foreach ($roster as $member=>$attribute) {
	
	$isOnline = empty($roster[$member]["isOnline"]) ? "0" : "1";
	switch ($roster[$member]["memberType"]) {
		
		case 0:
			$thisMemberType = "None";
			break;
		case 1:
			$thisMemberType = "Beginner";
			break;
		case 2:
			$thisMemberType = "Member";
			break;
		case 3:
			$thisMemberType = "Admin";
			break;
		case 4:
			$thisMemberType = "Acting Founder";
			break;
		case 5:
			$thisMemberType = "Founder";
			break;
		default:
			$thisMemberType = "Unknown";
		
	}
	
	
	$thisUser = $roster[$member]["destinyUserInfo"]["membershipId"];
	$userData[$thisUser]["userId"] = (string) $roster[$member]["destinyUserInfo"]["membershipId"];
	$userData[$thisUser]["platform"] = (string) $roster[$member]["destinyUserInfo"]["membershipType"];
	$userData[$thisUser]["username"] = $roster[$member]["destinyUserInfo"]["displayName"];
	$userData[$thisUser]["isOnline"] = $isOnline;
	$userData[$thisUser]["memberType"] = $thisMemberType;
	$userData[$thisUser]["clanId"] = $roster[$member]["groupId"];
	$userData[$thisUser]["joinDate"] = $roster[$member]["joinDate"];
	
	//print_r("id=".$roster[$member]["destinyUserInfo"]["membershipId"]."&p=".$roster[$member]["destinyUserInfo"]["membershipType"]."&name=".urlencode($roster[$member]["destinyUserInfo"]["displayName"])."&isOnline=".$isOnline."&joinDate=".urlencode($roster[$member]["joinDate"])."&memberType=".$roster[$member]["memberType"]."&clanId=".$roster[$member]["groupId"]."\n");
	
}
	$userDataJSON = json_encode($userData);

	print_r($userDataJSON);

?>
