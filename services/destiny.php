<?php

define("DATA_PATH", "./data/");

# define whether the application configuration is encoded
define("CFG_ENCODED", TRUE);
# define location of file containing application identifiers
define("APP_DATA_FILE", DATA_PATH."appdata.cfg");

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


# define("CLIENT_ID", "25146");
# define("API_KEY", "4b586d1833ee491c8b4f6ede97afa9f1");
# define("SECRET", "c-lNmHuN8kOkIiVtdeg5SD.4H9880gPdIVLbb.g51nE");

if (is_readable(APP_DATA_FILE)) {

	$cfgfp = @fopen(APP_DATA_FILE, "r");

	# Import, decode, and define CLIENT_ID
	if ($pCLIENT_ID=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("CLIENT_ID", trim(base64_decode($pCLIENT_ID)));
		} else {
			define("CLIENT_ID", trim($pCLIENT_ID));
		}
		unset($pCLIENT_ID);
	} else {
		error_exit("CLIENT_ID not found");
	}

	# Import, decode, and define API_KEY
	if ($pAPI_KEY=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("API_KEY", trim(base64_decode($pAPI_KEY)));
		} else {
			define("API_KEY", trim($pAPI_KEY));
		}
		unset($pAPI_KEY);
	} else {
		error_exit("API_KEY not found");
	}

	# Import, decode, and define SECRET
	if ($pSECRET=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("SECRET", trim(base64_decode($pSECRET)));
		} else {
			define("SECRET", trim($pSECRET));
		}
		unset($pSECRET);
	} else {
		error_exit("SECRET not found");
	}

	fclose($cfgfp);
	unset($cfgfp);
} else {
	error_exit("Application data file not found");
}

function error_exit( $error_code = "undefined" ) {
	print_r(error_get_last().":".$error_code."\n");
	exit(1);

}


function triumphScoreByName($username = "soren42", $platform = "-1") {


	// Obtain the users' ID and Platform(if not provided) based on username
	
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

	// Obtain Triumph Score based on queried ID
	
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

	return $results2->Response->profileRecords->data->score;

} //end function triumphScoreByName()

function triumphScore($playerID = '4611686018430373576', $platform = 2) {
	
$thisURL = "/Platform/Destiny2/".$platform."/Profile/".$playerID."/?components=900";


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

    $results = json_decode($pQueryReturn);

	return $results->Response->profileRecords->data->score;

} //end function triumphScore()

?>
