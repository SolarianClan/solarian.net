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
	$userId = urldecode($_GET["id"]);
} else {
	$userId = '4611686018430373576';
}

if (isset($_GET["platform"])) {
	$platform = urldecode($_GET["platform"]);
} else {
	$platform = '2';
}

if (isset($_GET["username"])) {
	$username = urldecode($_GET["username"]);
} else {
	$username = 'soren42';
}


//$thisURL = "/Platform/GroupV2/".$clanId."/Members/?memberType=0";

$thisURL = "/Platform/GroupV2/User/".$platform."/".$userId."/0/1/";

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

	$clan = $results["Response"]["results"];

	// print_r($clan);

	if (!(isset($clan[0]))) {
		
		$thisClan["status"] = "nonmember";
		
	} else {
		
		$clan = $results["Response"]["results"][0];
		
		$thisClan["status"] = "member";
		$thisClan["groupId"] = $clan["group"]["groupId"];
		$thisClan["name"] = $clan["group"]["name"];

		switch ($clan["member"]["memberType"]) {
		
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
		$thisClan["role"] = $thisMemberType;
	}

	print_r(json_encode($thisClan));
?>