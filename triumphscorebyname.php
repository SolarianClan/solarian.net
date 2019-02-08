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


if (isset($_GET["name"])) {
	$username = urldecode($_GET["name"]);
} else {
	$username = 'soren42';
}

if (isset($_GET["p"])) {
	$platform = $_GET["p"];
} else {
	$platform = '-1';
}


$thisURL = "/Platform/Destiny2/SearchDestinyPlayer/".$platform."/".rawurlencode($username)."/";

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

//	print_r($results->Response->displayName."	".$results->Response->membershipId."	".$results->Response->membershipType);

// print_r($results["Response"]["0"]["displayName"]."	".$results["Response"]["0"]["membershipId"]."	".$results["Response"]["0"]["membershipType"]);

$thisURL = "/Platform/Destiny2/".$results["Response"]["0"]["membershipType"]."/Profile/".$results["Response"]["0"]["membershipId"]."/?components=900";


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

    $results2 = json_decode($pQueryReturn);

	// print_r($results["Response"]["profileRecords"]["data"]["score"]);
	print_r($results2->Response->profileRecords->data->score);

?>