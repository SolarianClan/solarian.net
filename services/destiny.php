<?php
/**
 * Library destiny.php
 * Bungie's Destiny API functions for SolariaCore Libraries
 *
 */
# define installation path for all root directory of data, services, and more.
defined('INSTALLATION_PATH') or define("INSTALLATION_PATH", '/var/www/solarian.net/');
# define path to data files if not already defined
defined('DATA_PATH') or define("DATA_PATH", INSTALLATION_PATH."data/");
# define path to library files if not already defined
defined('SERVICE_PATH') or define('SERVICE_PATH', INSTALLATION_PATH."services/");
# define path to log files if not already defined
defined('LOG_PATH') or define('LOG_PATH', INSTALLATION_PATH."logs/");
# define whether the application configuration is encoded(encrypted)
define("CFG_ENCODED", TRUE);
# define location of file containing application identifiers
define("APP_DATA_FILE", DATA_PATH."appdata.cfg");
# define clan data file
defined('CLAN_DATA_FILE') or define('CLAN_DATA_FILE', DATA_PATH . "solarian.json");
# define log file
defined('LOG_FILE') or define('LOG_FILE', LOG_PATH . "SolariaCore.log");
# define base protocol used to query API
define("PROTOCOL", "https://");
# define server and FQDN to access API from
define("API_SERVER", "www.bungie.net");
# define server and FQDN to access PGCR API from
define("STATS_SERVER", "stats.bungie.net");
# define origin header
//define("ORIGIN_HEADER", "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
define("ORIGIN_HEADER", "http".(($_SERVER['HTTP_X_FORWARDED_PROTO']=='https')?"s":(($_SERVER['REQUEST_SCHEME']=='https')?"s": ""))."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
# define application version
define("VERSION_NUMBER", "1.1");
# define release number
define("RELEASE_NUMBER", date("YmdHis", filemtime(".")));
# define application name
define("APPLICATION_NAME", "Solaria");
# define Bungie Application Name
define("BUNGIE_APP_NAME", "Solarian");
# define primary website for Bungie apllication
define("PRIMARY_SITE", "solarian.net");
# define primary contact for Bungie application
define("CONTACT_ADDRESS", "admin@solarian.net");
# define authentication redirect URL for Bungie Application
define("BUNGIE_APP_AUTH_REDIRECT_URL", PROTOCOL.PRIMARY_SITE."/auth.php");
# define the manifest directory
define("MANIFEST_DIRECTORY", INSTALLATION_PATH."manifest/");
# define default manifest language
define("MANIFEST_LANGUAGE", "en");
# define default username for API functions (to prevent no input errors)
define("TEST_USER", "soren42");
# define default player membershipId for API functions (to prevent no input errors)
define("TEST_USER_ID", '4611686018430373576');
# define default player platform number for API functions (must be correlated to the TEST_USER and TEST_USER_ID)
define("TEST_USER_PLATFORM", 2);
# define default player characterId for API functions (must be correlated to the TEST_USER, TEST_USER_ID, and TEST_USER_PLATFORM)
define("TEST_USER_CHARACTER", '2305843009260457933');
# define default clan ID for API functions (to prevent no input errors)
define("TEST_CLAN_ID", '389581');
# define default Post-Game Carnage Report instance ID for API functions
define("TEST_PGCR_ID", '3758712628');
# define default player membershipId for API functions (to prevent no input errors)
define("TEST_BUSER_ID", '4316634');
# define the number of recent activities to query for "Recent" functions MAX VALID VALUE: 250
define("QUERY_RECENT_GAMES", '250');
# define length of time (in days) to be considered "idle" and be eligible for clan removal
defined("DEFAULT_IDLE_KICK_TIMEOUT") or define("DEFAULT_IDLE_KICK_TIMEOUT", "60");
# define idle clan member exemption data file
defined('IDLE_CLAN_MEMBER_DATA_FILE') or define('IDLE_CLAN_MEMBER_DATA_FILE', DATA_PATH . "idle-exempt.json");
# define the number of seconds to wait between attempts to re-query the API
defined("DEFAULT_REQUERY_TIME") or define("DEFAULT_REQUERY_TIME", "1");

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

# define complete User Agent
# Per Bungie:
# “AppName/Version AppId/appIdNum (+webUrl;contactEmail)”
# For example: User-Agent: Really Cool App/1.0 AppId/##### (+www.example.com;contact@example.com)
define("USER_AGENT", APPLICATION_NAME."/".VERSION_NUMBER." ".BUNGIE_APP_NAME."/".CLIENT_ID." (+".PRIMARY_SITE.";".CONTACT_ADDRESS.")");
# Old define:
// define("USER_AGENT", APPLICATION_NAME." ".VERSION_NUMBER."(".RELEASE_NUMBER.")");


function error_exit( $error_code = "undefined" ) {
	print_r(error_get_last().":".$error_code."\n");
	exit(1);

} // end function error_exit()

function log_action( $actionMessage = "undefined", $actionUser = "undefined", $logFile = LOG_FILE) {
	
	if (!is_writable($logFile)) {
		
		//print('Unable to write to log file');
		$retVal = false;
		
	} else {
	$now = date_create('now')->format('c');
	$logEntry = "[{$now}] ({$actionUser}) {$actionMessage}\n";
	
	file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
	$retVal = true;
	}
	
	return($retVal);
} // end function log_action()

function queryAuthAPI($thisURL, $returnJSON = false) {

	$querySuccess = false;
	$attemptCount = 0;
	while (($querySuccess === false) && ($attemptCount < 5)) {
    $pQueryHandle = curl_init();
    $pQueryURL = PROTOCOL.API_SERVER.$thisURL;

    curl_setopt($pQueryHandle, CURLOPT_URL,$pQueryURL);
    curl_setopt($pQueryHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
                        'X-API-Key: '.API_KEY,
                        'Origin: '.ORIGIN_HEADER,
                        'User-Agent: '.USER_AGENT,
						'Authorization: Bearer '.$_SESSION['solAccessToken']
                        ]);
    curl_setopt($pQueryHandle, CURLOPT_HTTPGET, true);

    $pQueryReturn = curl_exec($pQueryHandle);
	$pQueryReturnArray = json_decode($pQueryReturn, true);
    curl_close($pQueryHandle);
		
	if ($pQueryReturnArray['ErrorCode'] == 1) {
		$querySuccess = true;
	} else {
		$attemptCount++;
		sleep(DEFAULT_REQUERY_TIME);
	}
	
	} // end query attempts
	
	$pQueryReturnArray['solAttemptCount'] = $attemptCount;
	$pQueryReturnArray['solQuerySucceeded'] = $querySuccess;
	
    if ($returnJSON === true) {
		$results = $pQueryReturn;
	} else {
    	$results = $pQueryReturnArray;
	}
	
	return($results);
	
} // end function queryAuthAPI()

function queryAPI($thisURL, $returnJSON = false) {

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

	if ($returnJSON === true) {
		$results = $pQueryReturn;
	} else {
    	$results = json_decode($pQueryReturn, true);
	}
	
	return($results);
	
} // end function queryAPI()

function queryStatsAPI($thisURL) {

    $pQueryHandle = curl_init();
    $pQueryURL = PROTOCOL.STATS_SERVER.$thisURL;

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
	
	return($results);
	
} // end function queryStatsAPI()

function addTrailingSlash($directory) {

	if (substr($directory, -1) != "/") {
		$direcrory = $directory."/";
		
		} // end if no trailing slash in directory	

	return($directory);
} // end function addTrailingSlash()

function unsigned2signed($value) {
    $i = (int) $value;

	if (PHP_INT_SIZE == 8) { // 64bit native
	  $temp_int = (int)(0x7FFFFFFF & $i);
	  $temp_int |= (int)(0x80000000 & ($i >> 32));
	  $i = $temp_int;
	}

	return($i);
	
} // end function unsigned2signed()

function isAuthSessionSet() {
	
	if (isset($_SESSION['solAccessToken'])) {
		$retVal = true;
	} else {
		$retVal = false;
	}
	
	return($retVal);
	
} // end function isAuthSessionSet()

function getNewAuthorisation() {
	
	$_SESSION['solReferralURL'] = ORIGIN_HEADER;
	header('Location: '.BUNGIE_APP_AUTH_REDIRECT_URL);
	
} // end function getNewAuthorisation()

function isRefreshTokenExpired() {
	
	if (!isset($_SESSION['solRefreshTokenExpiresAt'])) {
		
		$retVal = true;
		
	} else {
		
		if (strtotime($_SESSION['solRefreshTokenExpiresAt']) < strtotime(date_create("now")->format('Y-m-d H:i:s'))) {
			
			$retVal = true;
			
		} else {
			$retVal = false;
		}
		
	}
	
	return($retVal);
	
} // end function isRefreshTokenExpired()

function getAuthTokenFromCode($authCode) {
	
	$authCode = $_GET['code'];
	
	if (isset($authCode) && ($authCode != "")) {
	
		$authURL = PROTOCOL.API_SERVER."/Platform/App/OAuth/Token/";

		$pQueryHandle = curl_init();
		curl_setopt($pQueryHandle, CURLOPT_URL, $authURL);
		curl_setopt($pQueryHandle, CURLOPT_POST, 1);
		curl_setopt($pQueryHandle, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&code='.$authCode);
		curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
					'Authorization: Basic '.base64_encode(CLIENT_ID.':'.SECRET),
					"Origin: ".ORIGIN_HEADER,
					'Content-Type: application/x-www-form-urlencoded'
					]);
		curl_setopt($pQueryHandle, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($pQueryHandle);
		curl_close($pQueryHandle);

		$newToken = json_decode($result);

		$_SESSION['solAccessToken'] = $newToken->access_token;
		$_SESSION['solRefreshToken'] = $newToken->refresh_token;
		$_SESSION['solTokenType'] = $newToken->token_type;
		$_SESSION['solTokenExpiresIn'] = $newToken->expires_in;
		$_SESSION['solRefreshTokenExpiresIn'] = $newToken->refresh_expires_in;
		$_SESSION['solMembershipId'] = $newToken->membership_id;
		//$tokenExpiration = date_create("+{$newToken->refresh_expires_in} seconds")->format('Y-m-d H:i:s');
		$expSeconds = $newToken->refresh_expires_in;
		if (!is_int($expSeconds)) { $expSeconds = 600; }
		$tokenTime = "now +".$expSeconds." seconds";
		$tokenExpiration = date_create($tokenTime)->format('Y-m-d H:i:s');
		$_SESSION['solRefreshTokenExpiresAt'] = $tokenExpiration;
		
	} else {
		
		getNewAuthorisation();
		
	}
	
	return(json_decode($result, true));
	
} // end function getAuthTokenFromCode()

function refreshAuthToken() {
	
	$refreshSuccess = false;
	
	while ($refreshSuccess === false) {
	
	$authURL = PROTOCOL.API_SERVER."/Platform/App/OAuth/Token/";
	
	$pQueryHandle = curl_init();
	curl_setopt($pQueryHandle, CURLOPT_URL, $authURL);
	curl_setopt($pQueryHandle, CURLOPT_POST, 1);
	curl_setopt($pQueryHandle, CURLOPT_POSTFIELDS, 'grant_type=refresh_token&refresh_token='.$_SESSION['solRefreshToken']);
	curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
                'Authorization: Basic '.base64_encode(CLIENT_ID.':'.SECRET),
                "Origin: ".ORIGIN_HEADER,
                'Content-Type: application/x-www-form-urlencoded'
        		]);
	curl_setopt($pQueryHandle, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($pQueryHandle);
    curl_close($pQueryHandle);
	
	$newToken = json_decode($result);
	
	if ($newToken->error != "server_error") { $refreshSuccess = true; } else { sleep(DEFAULT_REQUERY_TIME); }
	
	}
	$_SESSION['solAccessToken'] = $newToken->access_token;
	$_SESSION['solRefreshToken'] = $newToken->refresh_token;
	$_SESSION['solTokenType'] = $newToken->token_type;
	$_SESSION['solTokenExpiresIn'] = $newToken->expires_in;
	$_SESSION['solRefreshTokenExpiresIn'] = $newToken->refresh_expires_in;
	$_SESSION['solMembershipId'] = $newToken->membership_id;
	$expSeconds = $newToken->refresh_expires_in;
	if (!is_int($expSeconds)) { $expSeconds = 600; }
	$tokenTime = "now +".$expSeconds." seconds";
	$tokenExpiration = date_create($tokenTime)->format('Y-m-d H:i:s');
	//$tokenExpiration = date_create("now +".$newToken->refresh_expires_in." seconds")->format('Y-m-d H:i:s');
	$_SESSION['solRefreshTokenExpiresAt'] = $tokenExpiration;
	
	return(json_decode($result, true));
	
} // end function refreshAuthToken()

function authenticateToBungie() {
	
	if (isAuthSessionSet() === false) {

		getNewAuthorisation();
	
	} else {
	
		if (isRefreshTokenExpired() === false) {
			
			refreshAuthToken();
		
		} else {
		
			getNewAuthorisation();
		}
	}
	
} //end function authenticateToBungie()

function downloadSQLManifest($thisURL) {
	
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
	
	return($pQueryReturn);
	
} // end function downloadSQLManifest()

function getManifestVersion() {
	
	$manifestSummary = queryAPI("/Platform/Destiny2/Manifest/");
	
	return($manifestSummary["Response"]["version"]);
	
} // end function getManifestVersion()

function saveManifestVersion($directory = MANIFEST_DIRECTORY) {
	
	$directory = addTrailingSlash($directory);
	$versionFile = $directory . "manifest.version";
	
	$writeVal = file_put_contents($versionFile, getManifestVersion());
	
	if ($writeVal === false) {
		$retVal = false;
	} else {
		$retVal = true;
	} // end if write worked
	
	return($retVal);
	
} // end function saveManifestVersion()

function getLocalManifestVersion($directory = MANIFEST_DIRECTORY) {
	
	$directory = addTrailingSlash($directory);
	
	return(file_get_contents($directory . "manifest.version"));
	
} //end function getLocalManigestVersion()

function getJSONManifestURL($lang = MANIFEST_LANGUAGE) {

	$manifestSummary = queryAPI("/Platform/Destiny2/Manifest/");
	
	$manifestURL = $manifestSummary["Response"]["jsonWorldContentPaths"][$lang];

	return($manifestURL);
	
} // end function getJSONManifestURL()

function getJSONManifest($lang = MANIFEST_LANGUAGE) {

	return(queryAPI(getJSONManifestURL($lang)));
	
} // end function getJSONManifest()

function getSQLManifestURL($lang = MANIFEST_LANGUAGE) {
	
	$manifestSummary = queryAPI("/Platform/Destiny2/Manifest/");
	
	$manifestURL = $manifestSummary["Response"]["mobileWorldContentPaths"][$lang];
	
	return($manifestURL);
	
} // end function getSQLManifestURL()

function getSQLBannerManifestURL($lang = MANIFEST_LANGUAGE) {
	
	$manifestSummary = queryAPI("/Platform/Destiny2/Manifest/");
	
	$manifestURL = $manifestSummary["Response"]["mobileClanBannerDatabasePath"];
	
	return($manifestURL);
	
} // end function getSQLBannerManifestURL()


function getSQLManifest($directory = MANIFEST_DIRECTORY, $lang = MANIFEST_LANGUAGE) {
	
	$manifestURL = getSQLManifestURL($lang);
	$directory = addTrailingSlash($directory);
	
	$manifestFilename = $directory."world_sql_content-".$lang.".content";
	file_put_contents($manifestFilename, downloadSQLManifest($manifestURL));

	$zip = new ZipArchive;
	$res = $zip->open($manifestFilename);
	if ($res === TRUE) {
  		$zip->extractTo($directory);
		$uncompressedManifest = $zip->getNameIndex(0);
  		$zip->close();
  		unlink($manifestFilename);
		} else {
  			echo 'Error: Could not decompress manifest';
		} // end if decompression possible
	
	$currentFile = $directory . "current.manifest";
	file_put_contents($currentFile, $uncompressedManifest);
	
	return($uncompressedManifest);

//	return($manifestFilename);
} // end function getSQLManifest()

function getSQLBannerManifest($directory = MANIFEST_DIRECTORY, $lang = MANIFEST_LANGUAGE) {
	
	$manifestURL = getSQLBannerManifestURL($lang);
	$directory = addTrailingSlash($directory);
	
	$manifestFilename = $directory."clanbanner_sql_content_".$lang.".content";
	file_put_contents($manifestFilename, downloadSQLManifest($manifestURL));

	$zip = new ZipArchive;
	$res = $zip->open($manifestFilename);
	if ($res === TRUE) {
  		$zip->extractTo($directory);
		$uncompressedManifest = $zip->getNameIndex(0);
  		$zip->close();
  		unlink($manifestFilename);
		} else {
  			echo 'Error: Could not decompress manifest';
		} // end if decompression possible
	
	$currentFile = $directory . "current.banner";
	file_put_contents($currentFile, $uncompressedManifest);
	
	return($uncompressedManifest);

} // end function getSQLBannerManifest()

function isManifestNew($directory = MANIFEST_DIRECTORY) {
	
	$directory = addTrailingSlash($directory);
	
	$manifestFilename = $directory."world_sql_content-".$lang.".content";
	
//	$localManifestVersion = file_get_contents($manifestFilename);
	$localManifestVersion = getLocalManifestVersion();
	$bungieManifestVersion = getManifestVersion();
	
	if ($bungieManifestVersion == $localManifestVersion) {
		$retVal = false;
	} else {
		$retVal = true;
	} // end if version compare
	
	return($retVal);
	
} // end function isManifestNew()

function getSQLManifestIfNew($directory = MANIFEST_DIRECTORY, $lang = MANIFEST_LANGUAGE) {
	
	$directory = addTrailingSlash($directory);
	
	if (isManifestNew() === true) {
		
		$manifestFilename = getSQLManifest($directory, $lang);
		$bannerFilename = getSQLBannerManifest($directory, $lang);
		saveManifestVersion();
		
	} else {
		
		$manifestFilename = file_get_contents($directory . "current.manifest");
		
	}

	return($manifestFilename);
	
} // end function getSQLManifestIfNew()

function getSQLBannerManifestIfNew($directory = MANIFEST_DIRECTORY, $lang = MANIFEST_LANGUAGE) {
	
	$directory = addTrailingSlash($directory);
	
	if (isManifestNew() === true) {
		
		$manifestFilename = getSQLManifest($directory, $lang);
		$bannerFilename = getSQLBannerManifest($directory, $lang);
		saveManifestVersion();
		
	} else {
		
		$manifestFilename = file_get_contents($directory . "current.manifest");
		$bannerFilename = file_get_contents($directory . "current.banner");
		
	}

	return($bannerFilename);
	
} // end function getSQLBannerManifestIfNew()

function accessSQLManifest($directory = MANIFEST_DIRECTORY, $lang = MANIFEST_LANGUAGE) {

	$directory = addTrailingSlash($directory);
	
	$dbFilename = $directory . getSQLManifestIfNew($directory, $lang);
	
	$manifestDB = new SQLite3($dbFilename, SQLITE3_OPEN_READONLY);
	
	return($manifestDB);
		
} // end function accessSQLManifest()

function accessSQLBannerManifest($directory = MANIFEST_DIRECTORY, $lang = MANIFEST_LANGUAGE) {

	$directory = addTrailingSlash($directory);
	
	$dbFilename = $directory . getSQLBannerManifestIfNew($directory, $lang);
	
	$manifestDB = new SQLite3($dbFilename, SQLITE3_OPEN_READONLY);
	
	return($manifestDB);
		
} // end function accessSQLBannerManifest()

function getManifestTable($thisTable = "DestinyActivityDefinition") {
	
	$manifestDB = accessSQLManifest();
	
	$manifestTableData = array();

	$tableData = $manifestDB->query("SELECT * FROM ".$thisTable.";");

    while ($thisTableData = $tableData->fetchArray(SQLITE3_ASSOC)) {

		$thisId = (int) unsigned2signed( (int) $thisTableData['id']);
		$manifestTableData[$thisId] = json_decode($thisTableData['json'], TRUE);
    }

	//print_r(json_encode($manifestTableData));
	
	$manifestDB->close();		
	
	return($manifestTableData);
	
} // end function produceManifestTable()

function getBannerManifestTable($thisTable = "Gonfalons") {
	
	$manifestDB = accessSQLBannerManifest();
	
	$manifestTableData = array();

	$tableData = $manifestDB->query("SELECT * FROM ".$thisTable.";");

    while ($thisTableData = $tableData->fetchArray(SQLITE3_ASSOC)) {

		$thisId = (int) unsigned2signed( (int) $thisTableData['id']);
		$manifestTableData[$thisId] = json_decode($thisTableData['json'], TRUE);
    }

	//print_r(json_encode($manifestTableData));
	
	$manifestDB->close();		
	
	return($manifestTableData);
	
} // end function produceManifestTable()

function triumphScoreByName($username = TEST_USER, $platform = "-1") {

	$functionStartTime = microtime(true);
	// Obtain the users' ID and Platform(if not provided) based on username
	$thisPlayer = idByName($username, $platform);

	if ($thisPlayer["displayName"] == NULL) {
		
		return(array("Error" => "Profile not Found"));
		exit(1);
		
	}	

	$thisScore = triumphScore($thisPlayer["membershipId"], $thisPlayer["membershipType"]);

	if ($thisScore["score"] == NULL) {
		
		return(array("Error" => "Score not Found"));
		exit(1);
		
	}
	
	return(array("membershipId" => $thisScore["membershipId"], "score" => $thisScore["score"], "displayName" => $thisPlayer["displayName"], "execTime" => microtime(true)-$functionStartTime));
	
} //end function triumphScoreByName()

function triumphScore($playerID = TEST_USER_ID, $platform = TEST_USER_PLATFORM) {
	
	$thisURL = "/Platform/Destiny2/".$platform."/Profile/".$playerID."/?components=900";
	
	$results = queryAPI($thisURL);
	
	if ($results["Response"]["profileRecords"]["data"]["score"] == NULL) {
		
		return(array("Error" => "Score not Found"));
		exit(1);
		
	}

	return(array("membershipId" => $playerID, "score" => $results["Response"]["profileRecords"]["data"]["score"]));
	
} //end function triumphScore()

function getLatestTWABLink() {
	
	$thisURL = "/Platform/Trending/Categories/";

	$results = queryAPI($thisURL);
	
	$catagories = $results['Response']['categories'];
	
	foreach ($catagories as $key => $value) {
	
		$maxWeight = 0;
		
		if ($catagories[$key]['categoryName'] == 'News') {
			
			//print_r($catagories[$key]['entries']['results']);
			
			$thisEntry = $catagories[$key]['entries']['results'];
			
			foreach ($thisEntry as $entryKey => $entryValue) {
			
				if ($thisEntry[$entryKey]['weight'] > $maxWeight) {
					$maxWeight = $thisEntry[$entryKey]['weight'];
					$currentTWABURI = $thisEntry[$entryKey]['link'];
					};
			};
		};
		
	};
	
	$returnURL = PROTOCOL.API_SERVER.$currentTWABURI;
	
	return $returnURL;
	
} // end function getLatestTWABLink

function clanRosterById($clanId = TEST_CLAN_ID) {
	// returns an array given a Bungie clanId
	
	$thisURL = "/Platform/GroupV2/".$clanId."/Members/?memberType=0";

	$results = queryAPI($thisURL);
	
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

		} // end switch memberType
	
	
	$thisUser = $roster[$member]["destinyUserInfo"]["membershipId"];
	$userData[$thisUser]["userId"] = (string) $roster[$member]["destinyUserInfo"]["membershipId"];
	$userData[$thisUser]["platform"] = (string) $roster[$member]["destinyUserInfo"]["membershipType"];
	$userData[$thisUser]["username"] = $roster[$member]["destinyUserInfo"]["displayName"];
	$userData[$thisUser]["isOnline"] = $isOnline;
	$userData[$thisUser]["memberType"] = $thisMemberType;
	$userData[$thisUser]["clanId"] = $roster[$member]["groupId"];
	$userData[$thisUser]["joinDate"] = $roster[$member]["joinDate"];
	$userData[$thisUser]["lastOnlineStatusChange"] = date("c", $roster[$member]["lastOnlineStatusChange"]);
	
	} // end foreach roster iteration
	
	return($userData);
	
} // end function clanRosterById()

function multiclanRoster($clanDataFile = CLAN_DATA_FILE) {
	
	$clanJSON = file_get_contents($clanDataFile);
	$clanList = json_decode($clanJSON, TRUE);
	
	$i = 0;
	
	$clanArray = array();
	
	foreach ($clanList as $clan=>$value) {
		
	$thisClan = clanRosterById($clanList[$clan]["id"]);
			
	$newList = array_merge($clanArray, $thisClan);
	$clanArray = $newList;
		
	}
	
	return($clanArray);
	
} // end function multiclanRoster()

function joinDateCompare($a, $b) {
	$t1 = strtotime($a['joinDate']);
	$t2 = strtotime($b['joinDate']);
	return $t1 - $t2;
} // end function joinDateCompare()

function idleDateCompare($a, $b) {
	$t1 = strtotime($a['lastOnlineStatusChange']);
	$t2 = strtotime($b['lastOnlineStatusChange']);
	return $t1 - $t2;
} // end function idleDateCompare()


function multiclanRosterByJoinDate() {

$clanList = multiclanRoster();

usort($clanList, 'joinDateCompare');
	
return($clanList);

} // end function multiclanRosterByJoinDate()

function multiclanRosterByIdleDate() {

$clanList = multiclanRoster();

usort($clanList, 'idleDateCompare');
	
return($clanList);

} // end function multiclanRosterByJoinDate()

function clanBeginners() {
	
	$fullRoster = multiclanRosterByJoinDate();
	$beginnerArray = array();
	
	$i = 0;
	
	foreach ($fullRoster as $member=>$attribute) {
		
		if ($fullRoster[$member]["memberType"] == "Beginner") {
			$beginnerArray[$i] = $fullRoster[$member];
			$i++;			
		} // end if beginner
		
	} //end foreach member
	
	return($beginnerArray);
	
} // end function clanBeginners()

function multiclanAdmins() {
	
	$fullRoster = multiclanRosterByJoinDate();
	$adminArray = array();
	
	$i = 0;
	
	foreach ($fullRoster as $member=>$attribute) {
		
		if (($fullRoster[$member]["memberType"] == "Admin") or ($fullRoster[$member]["memberType"] == "Founder") or ($fullRoster[$member]["memberType"] == "Acting Founder")) {
			$adminArray[$i] = $fullRoster[$member];
			$i++;			
		} // end if beginner
		
	} //end foreach member
	
	return($adminArray);
	
} // end function multiclanAdmins()

function clanFullMembers() {
	
	$fullRoster = multiclanRosterByJoinDate();
	$memberArray = array();
	
	$i = 0;
	
	foreach ($fullRoster as $member=>$attribute) {
		
		if ($fullRoster[$member]["memberType"] != "Beginner") {
			$memberArray[$i] = $fullRoster[$member];
			$i++;			
		} // end if beginner
		
	} //end foreach member
	
	return($memberArray);
	
} // end function clanFullMembers()

function clanOnline() {
		
	$fullRoster = multiclanRosterByJoinDate();
	$onlineArray = array();
	
	$i = 0;
	
	foreach ($fullRoster as $member=>$attribute) {
		
		if ($fullRoster[$member]["isOnline"] == "1") {
			
			$onlineArray[$i] = $fullRoster[$member];
			$i++;			
		} // end if isOnline
		
	} //end foreach member
	
	return($onlineArray);
	
} // end function clanOnline()

function idByName($username = TEST_USER, $platform = "-1") {

	$thisURL = "/Platform/Destiny2/SearchDestinyPlayer/".$platform."/".rawurlencode($username)."/";

	$results = queryAPI($thisURL);
	
	$thisPlayer = array();
	
	$thisPlayer["displayName"] = $results["Response"]["0"]["displayName"];
	$thisPlayer["membershipId"] = $results["Response"]["0"]["membershipId"];
	$thisPlayer["membershipType"] = $results["Response"]["0"]["membershipType"];
	
	if ($thisPlayer["displayName"] == NULL) {
		
		return(array("Error" => "Profile not Found"));
		exit(1);
		
	} // end if error
	
	return($thisPlayer);
	
} // end function ifByName()

function profileByName($username = TEST_USER, $platform = "-1") {
	
	$thisPlayer = idByName($username, $platform);

	if ($thisPlayer["displayName"] == NULL) {
		
		return(array("Error" => "Profile not Found"));
		exit(1);
		
	} // end if profile not found

	return(profileById($thisPlayer["membershipId"], $thisPlayer["membershipType"]));
	
} // end function profileByName()

function profileById($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$thisURL = "/Platform/Destiny2/".$playerPlatform."/Profile/".$playerId."/?components=200";
	
	$results = queryAPI($thisURL);
	
    $characters = $results["Response"]["characters"]["data"];

	return($characters);
	
} // end function profileById()

function getCharactersById($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$fullProfile = profileById($playerId, $playerPlatform);
	
	$characterIndex = 0;
	$thisCharacter = array();
	
	foreach ($fullProfile as $character=>$value) {
		
			$thisCharacter[$characterIndex]["membershipId"] = $fullProfile[$character]["membershipId"];
			$thisCharacter[$characterIndex]["membershipType"] = $fullProfile[$character]["membershipType"];
			$thisCharacter[$characterIndex]["characterId"] = $fullProfile[$character]["characterId"];
			$thisCharacter[$characterIndex]["dateLastPlayed"] = $fullProfile[$character]["dateLastPlayed"];
			$thisCharacter[$characterIndex]["light"] = $fullProfile[$character]["light"];
			$thisCharacterClassLookup = lookupInManifest($fullProfile[$character]["classHash"], "DestinyClassDefinition");
			$thisCharacter[$characterIndex]["className"] = $thisCharacterClassLookup["displayProperties"]["name"];
			$thisCharacterClassLookup = lookupInManifest($fullProfile[$character]["raceHash"], "DestinyRaceDefinition");
			$thisCharacter[$characterIndex]["raceName"] = $thisCharacterClassLookup["displayProperties"]["name"];
			$thisCharacterClassLookup = lookupInManifest($fullProfile[$character]["genderHash"], "DestinyGenderDefinition");
			$thisCharacter[$characterIndex]["genderName"] = $thisCharacterClassLookup["displayProperties"]["name"];
			$characterIndex += 1;
		} // end foreach character
	
	return($thisCharacter);
	
	
} // end function getCharactersById

function getCharactersByName($username = TEST_USER, $playerPlatform = TEST_USER_PLATFORM) {
	
	$thisUser = idByName($username, $playerPlatform);
	$thisUserId = $thisUser["membershipId"];
	
	return(getCharactersById($thisUserId, $playerPlatform));	
	
} // end function getCharactersByName

function activityById($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$thisURL = "/Platform/Destiny2/".$playerPlatform."/Profile/".$playerId."/?components=204";

	$results = queryAPI($thisURL);

	$activityData = $results["Response"]["characterActivities"]["data"];
	
//	print_r($activityData);

	foreach ( $activityData as $character=>$value ) {

		if ( $activityData[$character]["currentActivityHash"] != 0 ) {
			$currentActivityHash = $activityData[$character]["currentActivityHash"];
			$currentActivityModeHash = $activityData[$character]["currentActivityModeHash"];
			$currentActivityModeType = $activityData[$character]["currentActivityModeType"];
			} // end if is current activity
	} // foreach activity loop
	
	if (!isset($currentActivityHash)) {
		$currentActivity["isActive"] = 0;
	} else {
		$currentActivity["isActive"] = 1;
		$currentActivity["currentActivityHash"] = $currentActivityHash;
		$currentActivity["currentActivityModeHash"] = $currentActivityModeHash;
		$currentActivity["currentActivityModeType"] = $currentActivityModeType;
	}
		
	return($currentActivity);
	
} // end function activityById()

function activityWithNameById($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$thisURL = "/Platform/Destiny2/".$playerPlatform."/Profile/".$playerId."/?components=204";

	$results = queryAPI($thisURL);

	$destinyActivityDefinition = getManifestTable("DestinyActivityDefinition");
	$destinyActivityTypeDefinition = getManifestTable("DestinyActivityTypeDefinition");
	$destinyActivityModeDefinition = getManifestTable("DestinyActivityModeDefinition");
	
	$activityData = $results["Response"]["characterActivities"]["data"];
	
//	print_r($activityData);

	foreach ( $activityData as $character=>$value ) {

		if ( $activityData[$character]["currentActivityHash"] != 0 ) {
			$currentActivityHash = $activityData[$character]["currentActivityHash"];
			$currentActivityModeHash = $activityData[$character]["currentActivityModeHash"];
			$currentActivityModeType = $activityData[$character]["currentActivityModeType"];
			} // end if is current activity
	} // foreach activity loop
	
	if (!isset($currentActivityHash)) {
		$currentActivity["isActive"] = 0;
	} else {
		$currentActivity["isActive"] = 1;
		$currentActivity["currentActivityHash"] = $currentActivityHash;
		$currentActivity["currentActivityModeHash"] = $currentActivityModeHash;
		$currentActivity["currentActivityModeType"] = $currentActivityModeType;
		$currentActivity["currentActivityName"] = $destinyActivityDefinition[$currentActivityHash]["displayProperties"]["name"];
		$currentActivity["currentActivityModeName"] = $destinyActivityModeDefinition[$currentActivityModeHash]["displayProperties"]["name"];
		$currentActivity["currentActivityTypeName"] = $destinyActivityTypeDefinition[$currentActivityModeType]["displayProperties"]["name"];
	}
		
	return($currentActivity);
	
} // end function activityWithNameById()

function onlineActivities() {
	
	$onlineMembers = clanOnline();
	
	foreach ($onlineMembers as $member=>$value) {
		$thisMemberActivity = activityWithNameById($onlineMembers[$member]["userId"], $onlineMembers[$member]["platform"]);
		$onlineMembers[$member]["currentActivityHash"] = $thisMemberActivity["currentActivityHash"];
		$onlineMembers[$member]["currentActivityModeHash"] = $thisMemberActivity["currentActivityModeHash"];
		$onlineMembers[$member]["currentActivityModeType"] = $thisMemberActivity["currentActivityModeType"];
		$onlineMembers[$member]["currentActivityName"] = $thisMemberActivity["currentActivityName"];
		$onlineMembers[$member]["currentActivityModeName"] = $thisMemberActivity["currentActivityModeName"];
		$onlineMembers[$member]["currentActivityTypeName"] = $thisMemberActivity["currentActivityTypeName"];
		
	} // end foreach member
	
	return($onlineMembers);
	
} // end function onlineActivities()

function getPGCR($instanceId = TEST_PGCR_ID) {
	
	$PGCR = queryStatsAPI('/Platform/Destiny2/Stats/PostGameCarnageReport/'.$instanceId.'/');
	
	return($PGCR["Response"]);
	
} // end function getPGCR()

function lastPrivateMatchByCharacter($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM, $characterId = TEST_USER_CHARACTER) {
	
	$playerMatchData = queryAPI('/Platform/Destiny2/' . $playerPlatform . '/Account/' . $playerId . '/Character/' . $characterId . '/Stats/Activities/?count=1&mode=32');
	
	$matchId = $playerMatchData["Response"]["activities"][0]["activityDetails"]["instanceId"];
	// 3855516336
	
	//print_r($matchId);
	
	$PGCR = getPGCR($matchId);
	
	return($PGCR);
	
} // end function lastPrivateMatchByCharacter()
	
function lastActivitiesById($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$thisPlayer = profileById($playerId, $playerPlatform);
	
	foreach ( $thisPlayer as $character=>$attribute ) {
		
		
	} // end foreach character
	
} // end function lastActivitiesById()

function lookupInManifest($manifestId, $manifestTable='DestinyClassDefinition') {
	
	$thisManifestTable = getManifestTable($manifestTable);
	
	return($thisManifestTable[$manifestId]);
	
} // end function lookupInManifest()

function lookupInBannerManifest($manifestId, $manifestTable='Gonfalons') {
	
	$thisManifestTable = getBannerManifestTable($manifestTable);
	
	return($thisManifestTable[$manifestId]);
	
} // end function lookupInBannerManifest()

function getMostRecentCharacterPlayedById($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$characters = getCharactersById($playerId, $playerPlatform);
	
	function compareByLastPlayed($char1, $char2) { 
		
		$time1 = $char1["dateLastPlayed"];
		$time2 = $char2["dateLastPlayed"];
		if (strtotime($time1) < strtotime($time2)) 
			return 1; 
		else if (strtotime($time1) > strtotime($time2))  
			return -1; 
		else
			return 0; 
	} // end subfunction compareByLastPlayed 
  
	// sort array with given user-defined function 
	usort($characters, "compareByLastPlayed");
	
	return($characters[0]);
	
} // end function getMostRecentCharacterPlayedById()

function getMostRecentCharacterPlayedByName($username = TEST_USER, $playerPlatform = TEST_USER_PLATFORM) {

	$thisUser = idByName($username, $playerPlatform);
	$thisUserId = $thisUser["membershipId"];
	
	return(getMostRecentCharacterPlayedById($thisUserId, $playerPlatform));
	
} // end function getMostRecentCharacterPlayedByName()

function getMostRecentPrivateMatchById($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$recentCharacter = getMostRecentCharacterPlayedById($playerId, $playerPlatform);
	
	return(lastPrivateMatchByCharacter($playerId, $playerPlatform, $recentCharacter["characterId"]));
} // end function getMostRecentPrivateMatchById()

function getMostRecentPrivateMatchByName($username = TEST_USER, $playerPlatform = TEST_USER_PLATFORM) {
	
	$thisUser = idByName($username, $playerPlatform);
	$thisUserId = $thisUser["membershipId"];
	
	return(getMostRecentPrivateMatchById($thisUserId, $playerPlatform));
	
} // end function getMostRecentPrivateMatchByName()

function getClanBasicInfo($clanId = TEST_CLAN_ID) {
	
	$thisClan = queryAPI("/Platform/GroupV2/{$clanId}/");
	
	return($thisClan['Response']);
	
} // end function getClanBasicInfo()

function getClanBannerData($clanId = TEST_CLAN_ID) {
	
	$thisClan = queryAPI("/Platform/GroupV2/{$clanId}/");
	
	$thisBannerData = $thisClan["Response"]["detail"]["clanInfo"]["clanBannerData"];
	//$thisBannerData = $thisClan;
	


	$thisBannerData["decalId"] = lookupInBannerManifest($thisBannerData["decalId"], "Decals");
	$thisBannerData["decalColorId"] = lookupInBannerManifest($thisBannerData["decalColorId"], "DecalPrimaryColors");
	$thisBannerData["decalBackgroundColorId"] = lookupInBannerManifest($thisBannerData["decalBackgroundColorId"], "DecalSecondaryColors");
	$thisBannerData["gonfalonId"] = lookupInBannerManifest($thisBannerData["gonfalonId"], "Gonfalons");
	$thisBannerData["gonfalonColorId"] = lookupInBannerManifest($thisBannerData["gonfalonColorId"], "GonfalonColors");
	$thisBannerData["gonfalonDetailId"] = lookupInBannerManifest($thisBannerData["gonfalonDetailId"], "GonfalonDetails");
	$thisBannerData["gonfalonDetailColorId"] = lookupInBannerManifest($thisBannerData["gonfalonDetailColorId"], "GonfalonDetailColors");
	
	return($thisBannerData);
	
} // end function getClanBannerData()

function getClanMembershipBreakdown($clanId = TEST_CLAN_ID) {
	
	$fullRoster = clanRosterById($clanId);
	
	$beginnerArray = array();
	$memberArray = array();
	$adminArray = array();
	$actingFounderArray = array();
	$founderArray = array();
	$otherArray = array();
	
	$beginnerCount = 0;
	$memberCount = 0;
	$adminCount = 0;
	$actingFounderCount = 0;
	$founderCount = 0;
	$otherCount = 0;
	
	foreach ($fullRoster as $member=>$attribute) {
		
		if ($fullRoster[$member]["memberType"] == "Beginner") {
			$beginnerArray[$beginnerCount] = $fullRoster[$member];
			$beginnerCount++;			
		} // end if beginner
		
		if ($fullRoster[$member]["memberType"] == "Member") {
			$memberArray[$memberCount] = $fullRoster[$member];
			$memberCount++;			
		} // end if member
		
		if ($fullRoster[$member]["memberType"] == "Admin") {
			$adminArray[$adminCount] = $fullRoster[$member];
			$adminCount++;			
		} // end if admin
		
		if ($fullRoster[$member]["memberType"] == "Acting Founder") {
			$actingFounderArray[$actingFounderCount] = $fullRoster[$member];
			$actingFounderCount++;			
		} // end if acting founder
		
		if ($fullRoster[$member]["memberType"] == "Founder") {
			$founderArray[$founderCount] = $fullRoster[$member];
			$founderCount++;			
		} // end if founder
		
		if ($fullRoster[$member]["memberType"] == "Unknown") {
			$otherArray[$otherCount] = $fullRoster[$member];
			$otherCount++;			
		} // end if unknown
		
		if ($fullRoster[$member]["memberType"] == "None") {
			$otherArray[$otherCount] = $fullRoster[$member];
			$otherCount++;			
		} // end if unknown
		
	} //end foreach member
	
	if (count($beginnerArray) > 0) $fullClan["beginners"] = $beginnerArray;
	if (count($memberArray) > 0) $fullClan["members"] = $memberArray;
	if (count($adminArray) > 0) $fullClan["admins"] = $adminArray;
	if (count($actingFounderArray) > 0) $fullClan["actingFounders"] = $actingFounderArray;
	if (count($founderArray) > 0) $fullClan["founders"] = $founderArray;
	if (count($otherArray) > 0) $fullClan["others"] = $otherArray;
	
	$fullClan["summaryData"]["beginnerCount"] = $beginnerCount;
	$fullClan["summaryData"]["memberCount"] = $memberCount;
	$fullClan["summaryData"]["adminCount"] = $adminCount;
	$fullClan["summaryData"]["actingFounderCount"] = $actingFounderCount;
	$fullClan["summaryData"]["founderCount"] = $founderCount;
	$fullClan["summaryData"]["otherCount"] = $otherCount;
	$fullClan["summaryData"]["totalCount"] = $beginnerCount + $memberCount + $adminCount + $actingFounderCount + $founderCount + $otherCount;
	
	
	return($fullClan);
	
} // end function getClanMembershipBreakdown()

function getFullClanInfo($clanId = TEST_CLAN_ID) {
	
	$basicData = getClanBannerData($clanId);
	
	
	
} // end function getFullClanInfo()

function getClanWeeklyEngrams($clanId = TEST_CLAN_ID) {
	
	$clanData = queryAPI("/Platform/Destiny2/Clan/{$clanId}/WeeklyRewardState/");
	//$rewardManifest = getManifestTable('DestinyMilestoneDefinition');
	
	//lookupInManifest()
	

	
	$allEngrams = $clanData['Response']['rewards'];
	
	foreach ($allEngrams as $thisGroup=>$value) {
		
		if ($allEngrams[$thisGroup]['rewardCategoryHash'] == '1064137897') {
			
			$engramStates = $allEngrams[$thisGroup]['entries'];
			
		} // end if correct group
		
	} // end foreach engram group
	
	foreach ($engramStates as $thisState=>$value) {
		
		switch ($engramStates[$thisState]['rewardEntryHash']) {
				
			case '3789021730': 
				$nightfallEngram = $engramStates[$thisState]['earned'];
				break;
			case '248695599':
				$gambitEngram = $engramStates[$thisState]['earned'];
				break;
			case '2043403989':
				$raidEngram = $engramStates[$thisState]['earned'];
				break;
			case '964120289':
				$crucibleEngram = $engramStates[$thisState]['earned'];
				break;
				
		}
		
	}
	
	$weeklyClanEngrams = array('crucible'=>$crucibleEngram, 'nightfall'=>$nightfallEngram, 'raid'=>$raidEngram, 'gambit'=>$gambitEngram);
	
	return($weeklyClanEngrams);
	
} // end function getClanWeeklyEngrams()

function getClanMembershipIds($clanId = TEST_CLAN_ID) {
	
	$clanRoster = clanRosterById($clanId);
	
	$clanMembers = array();
	
	$count = 0;
	
	foreach ($clanRoster as $thisPlayer=>$value) {
		
		$clanMembers[$count] = $clanRoster[$thisPlayer]["userId"];
		$count++;
		
	} // end foreach member
	
	return($clanMembers);
	
} // end function getClanMembershipIds()

function getFullClanMembershipIds() {
	
	$clanRoster = multiclanRoster();
	
	$clanMembers = array();
	
	$count = 0;
	
	foreach ($clanRoster as $thisPlayer=>$value) {
		
		$clanMembers[$count] = $clanRoster[$thisPlayer]["userId"];
		$count++;
		
	} // end foreach member
	
	return($clanMembers);
	
} // end function getFullClanMembershipIds()

function getSingleClanMembershipIds($thisClan = TEST_CLAN_ID) {
	
	$clanRoster = clanRosterById($thisClan);
	
	$clanMembers = array();
	
	$count = 0;
	
	foreach ($clanRoster as $thisPlayer=>$value) {
		
		$clanMembers[$count] = $clanRoster[$thisPlayer]["userId"];
		$count++;
		
	} // end foreach member
	
	return($clanMembers);
	
} // end function getSingleClanMembershipIds()

function getRecentActivitiesByCharacter($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM, $characterId = TEST_USER_CHARACTER, $numActivitiesToQuery = QUERY_RECENT_GAMES) {
	
	$fullActivities=queryAPI("/Platform/Destiny2/{$playerPlatform}/Account/{$playerId}/Character/{$characterId}/Stats/Activities/?count={$numActivitiesToQuery}&mode=0");
	
	$recentActivities=$fullActivities['Response']['activities'];
	
	$count = 0;
	
	$instanceArray = array();
	
	foreach ($recentActivities as $thisActivity=>$value) {
		
		$instanceArray[$count] = $recentActivities[$thisActivity]['activityDetails']['instanceId'];
		$count++;
	}
	
	return($instanceArray);
	
} // end function getRecentActivitiesByCharacter()

function getPlayerIdsByPGCR($instanceId = TEST_PGCR_ID) {
	
	$fullPGCR = getPGCR($instanceId);
	
	$playerEntries = $fullPGCR['entries'];
	
	$count = 0;
	
	$playerIdArray = array();
	
	foreach ($playerEntries as $thisEntry=>$value) {
		
		$playerIdArray[$count] = $playerEntries[$thisEntry]['player']['destinyUserInfo']['membershipId'];
		$count++;
				
	}
	
	return($playerIdArray);
	
} // end function getPlayerIdsByPGCR()

function getRecentPlayerIdsByCharacter($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM, $characterId = TEST_USER_CHARACTER, $numActivitiesToQuery = QUERY_RECENT_GAMES) {
	
	$recentActivities = getRecentActivitiesByCharacter($playerId, $playerPlatform, $characterId, $numActivitiesToQuery);
	
	$count = 0;
	
	$playerIdArray = array();
	
	foreach ($recentActivities as $thisActivity=>$value) {
		
		$thisActivityPlayerIds = getPlayerIdsByPGCR($value);
		
		foreach ($thisActivityPlayerIds as $thisPlayer=>$playerValue){
			//if (!in_array($playerValue, $playerIdArray)) {
				$playerIdArray[$count] = $playerValue;
				$count++;
			//}
		}
	}
	
	$uniquePlayerIdArray = array_values(array_unique($playerIdArray));
	
	return($uniquePlayerIdArray);
	
} // end function getRecentPlayerIdsByCharacter()

function getRecentPlayerIdsByPlayer($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM, $numActivitiesToQuery = QUERY_RECENT_GAMES) {
	
	$characters = getCharactersById($playerId, $playerPlatform);
	
	$fullPlayerIds = array();
	
	foreach ($characters as $thisCharacter=>$value){
		
		$fullPlayerIds = array_merge($fullPlayerIds, getRecentPlayerIdsByCharacter($playerId, $playerPlatform, $characters[$thisCharacter]['characterId'], $numActivitiesToQuery));
		
	}
	
	$uniquePlayerIdArray = array_values(array_unique($fullPlayerIds));
	
	return($uniquePlayerIdArray);
	
} // end function getRecentPlayerIdsByPlayer()

function hasPlayedWithClanmates($playerId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM, $numActivitiesToQuery = QUERY_RECENT_GAMES) {
	
	$clanMembers = getFullClanMembershipIds();
	
	$recentPlayers = getRecentPlayerIdsByPlayer($playerId, $playerPlatform, $numActivitiesToQuery);
	
	$retVal = false;
	
	foreach ($recentPlayers as $thisPlayer=>$value) {
		if (in_array($value, $clanMembers)) {
			$retVal = true;
		}
	}
	
	return($retVal);
	
} // end function hasPlayedWithClanmates()

function eligibleForPromotion($numActivitiesToQuery = QUERY_RECENT_GAMES) {
	
	$beginnerList = clanBeginners();
	
	$promotionList = array();
	
	$count = 0;
	
	foreach ($beginnerList as $thisBeginner=>$value) {
		
		if (hasPlayedWithClanmates($beginnerList[$thisBeginner]['userId'],$beginnerList[$thisBeginner]['platform'],$numActivitiesToQuery) === true) {
			
			$promotionList[$count] = $beginnerList[$thisBeginner];
			$count++;
		}
		
	}
	
	return($promotionList);
	
} // end function eligibleForPromotion()

function getClanNameByIdFromLocal($clanId = TEST_CLAN_ID, $clanDataFile = CLAN_DATA_FILE) {
	
	if (is_readable($clanDataFile) === false) {
		
		error_exit('Cannot read data file: '.$clanDataFile);
		
	} else {
		
		$multiClanData = json_decode(file_get_contents($clanDataFile), true);
		
	}
	
	$clanFound = false;
	$thisClanName = "NOT FOUND";
	
	foreach ($multiClanData as $thisClan=>$value) {
		
		if ($multiClanData[$thisClan]["id"] == $clanId) {
			
			$thisClanName = $multiClanData[$thisClan]["name"];
			$clanFound = true;
			
		} // end if clan match found
		
	} // end foreach clan
	
	return($thisClanName);
	
} // end function getClanNameByIdFromLocal()

function getSingleClanIdleMembers($clanId = TEST_CLAN_ID, $honourExemptions = true, $idleExemptDataFile = IDLE_CLAN_MEMBER_DATA_FILE) {
	
	$clanRoster = getSingleClanMembershipIds($clanId);
	
} // end function getSingleClanIdleMembers()

function getDestinyIdsByBungieId($bungieId) {
	
	if (($bungieId == '') or ($bungieId == NULL)) {
		
		error_exit('Unable to determine Bungie Membership ID');
	}
	
	$fullMembershipData = queryAPI("/Platform/User/GetMembershipsById/{$bungieId}/-1/");
	
	$playerMemberships = $fullMembershipData['Response']['destinyMemberships'];
	
	$playerData = array();
	
	foreach ($playerMemberships as $thisMember=>$value) {
		
		$playerData[$playerMemberships[$thisMember]['membershipType']]['membershipType'] = $playerMemberships[$thisMember]['membershipType'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['membershipId'] = $playerMemberships[$thisMember]['membershipId'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['displayName'] = $playerMemberships[$thisMember]['displayName'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['crossSaveOverride'] = $playerMemberships[$thisMember]['crossSaveOverride'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['dateLastPlayed'] = getLastPlayedDateById($playerMemberships[$thisMember]['membershipId'], $playerMemberships[$thisMember]['membershipType']);
		
	}
	
	return($playerData);
	
} // end function getDestinyIdByBungieId()

function getDestinyIdsForCurrentUser() {
	
	$fullMembershipData = queryAuthAPI("/Platform/User/GetMembershipsForCurrentUser/");
	
	if ($fullMembershipData['solQuerySucceeded'] === false) {
		error_exit('Could not obtain data for current user from Bungie API.');
	}
	
	$playerMemberships = $fullMembershipData['Response']['destinyMemberships'];
	
	$playerData = array();
	
	foreach ($playerMemberships as $thisMember=>$value) {
		
		$playerData[$playerMemberships[$thisMember]['membershipType']]['membershipType'] = $playerMemberships[$thisMember]['membershipType'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['membershipId'] = $playerMemberships[$thisMember]['membershipId'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['displayName'] = $playerMemberships[$thisMember]['displayName'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['crossSaveOverride'] = $playerMemberships[$thisMember]['crossSaveOverride'];
		$playerData[$playerMemberships[$thisMember]['membershipType']]['dateLastPlayed'] = getLastPlayedDateById($playerMemberships[$thisMember]['membershipId'], $playerMemberships[$thisMember]['membershipType']);
		
	}
	
	return($playerData);
	
} // end function getDestinyIdsForCurrentUser()


function getLastPlayedDateById($membershipId = TEST_USER_ID, $platform = TEST_USER_PLATFORM) {
	
	$playerProfile = queryAPI("/Platform/Destiny2/{$platform}/Profile/{$membershipId}/?components=100");
	
	if ($playerProfile['ErrorCode'] == 1) {
	
		$lastPlayedDate = $playerProfile['Response']['profile']['data']['dateLastPlayed'];
	
	} else {
		
		$lastPlayedDate = "1970-01-01T00:00:00Z";
		
	}
	return($lastPlayedDate);
	
} // end function getLastPlayedDateById()

function getMostRecentlyPlayedDestinyIdbyBungieId($bungieId) {
	
	if (($bungieId == '') or ($bungieId == NULL)) {
		
		error_exit('Unable to determine Bungie Membership ID');
	}
	
	$playerMembershipProfiles = getDestinyIdsByBungieId($bungieId);
	
	$overallLastPlayedDate = date_create("1970-01-01T00:00:00Z")->format('Y-m-d H:i:s');
	
	foreach ($playerMembershipProfiles as $thisProfile=>$value) {
		$thisPlayerLastPlayedDate = date_create($playerMembershipProfiles[$thisProfile]['dateLastPlayed'])->format('Y-m-d H:i:s');
		if ($thisPlayerLastPlayedDate > $overallLastPlayedDate) {
			$overallLastPlayedDate = $thisPlayerLastPlayedDate;
			$lastPlayedMembershipId = $playerMembershipProfiles[$thisProfile]['membershipId'];
			$lastPlayedMembershipType = $playerMembershipProfiles[$thisProfile]['membershipType'];
			$lastPlayedDisplayName = $playerMembershipProfiles[$thisProfile]['displayName'];
		} // end if most recent played character		
	} // end foreach profile
	
	return(array('membershipId'=>$lastPlayedMembershipId, 'membershipType'=>$lastPlayedMembershipType, 'displayName'=>$lastPlayedDisplayName, 'dateLastPlayed'=>$overallLastPlayedDate));
	
} // end function getMostRecentlyPlayedDestinyIdbyBungieId()

function getMostRecentlyPlayedDestinyIdForCurrentUser() {
	
	$playerMembershipProfiles = getDestinyIdsForCurrentUser();
	
	$overallLastPlayedDate = date_create("1970-01-01T00:00:00Z")->format('Y-m-d H:i:s');
	
	foreach ($playerMembershipProfiles as $thisProfile=>$value) {
		$thisPlayerLastPlayedDate = date_create($playerMembershipProfiles[$thisProfile]['dateLastPlayed'])->format('Y-m-d H:i:s');
		if ($thisPlayerLastPlayedDate > $overallLastPlayedDate) {
			$overallLastPlayedDate = $thisPlayerLastPlayedDate;
			$lastPlayedMembershipId = $playerMembershipProfiles[$thisProfile]['membershipId'];
			$lastPlayedMembershipType = $playerMembershipProfiles[$thisProfile]['membershipType'];
			$lastPlayedDisplayName = $playerMembershipProfiles[$thisProfile]['displayName'];
		} // end if most recent played character		
	} // end foreach profile
	
	return(array('membershipId'=>$lastPlayedMembershipId, 'membershipType'=>$lastPlayedMembershipType, 'displayName'=>$lastPlayedDisplayName, 'dateLastPlayed'=>$overallLastPlayedDate));
	
} // end function getMostRecentlyPlayedDestinyIdForCurrentUser()

function getMulticlanIdleTooLongList($idleTimeout = DEFAULT_IDLE_KICK_TIMEOUT) {
	
	$multiclanRoster = multiclanRosterByIdleDate();
	
	$idleList = array();
	$idleCount = 0;
	
	foreach ($multiclanRoster as $thisPlayer=>$value) {
		
		$dateLastPlayed = getLastPlayedDateById($multiclanRoster[$thisPlayer]['userId'], $multiclanRoster[$thisPlayer]['platform']);
		$multiclanRoster[$thisPlayer]['usingLastOnlineStatusChangeAsDateLastPlayed'] = false;
		if ($dateLastPlayed == "1970-01-01T00:00:00Z") {
			$dateLastPlayed = $multiclanRoster[$thisPlayer]['lastOnlineStatusChange'];
			$multiclanRoster[$thisPlayer]['usingLastOnlineStatusChangeAsDateLastPlayed'] = true;
		} // end if unable to obtain dateLastPlayed
		
		$lastPlayed = new DateTime($dateLastPlayed);
		$now = new DateTime("now");
		if ($lastPlayed->diff($now)->days > $idleTimeout) {
			$idleList[$idleCount] = $multiclanRoster[$thisPlayer];
			$idleList[$idleCount]['dateLastPlayed'] = $dateLastPlayed;
			$idleCount++;			
		} // end if idle too long
		
	} // end foreach Member
	
	return($idleList);
	
} // end function getMulticlanIdleTooLongList()

function getClanIdByMembershipId($membershipId = TEST_USER_ID, $playerPlatform = TEST_USER_PLATFORM) {
	
	$playerClanData = queryAuthAPI("/Platform/GroupV2/User/{$playerPlatform}/{$membershipId}/0/1/");
	
	$clanInfo = $playerClanData['Response']['results'][0]['group'];
	
	$thisClan['clanId'] = $clanInfo['groupId'];
	$thisClan['clanName'] = $clanInfo['name'];
	$thisClan['clanTag'] = $clanInfo['clanInfo']['clanCallsign'];
	
	return($thisClan);
	
} // end function getClanIdByMembershipId()

function getClanAdminIdList($clanId = TEST_CLAN_ID) {
	
	$fullClanAdminData = queryAPI("/Platform/GroupV2/{$clanId}/AdminsAndFounder/");
	$adminListings = $fullClanAdminData['Response']['results'];
	
	$adminIds = array();
	$count = 0;
	
	
	foreach ($adminListings as $thisAdmin=>$value) {
		
		$adminIds[$count] = $adminListings[$thisAdmin]['destinyUserInfo']['membershipId'];
		$count++;
		
	} // end foreach admin
	
	return($adminIds);
	
} // end function getClanAdminIdList()

function getMulticlanAdminIdList($clanDataFile = CLAN_DATA_FILE) {
	
	$clanList = json_decode(file_get_contents($clanDataFile), true);
	
	$adminIds = array();
	
	foreach ($clanList as $clan=>$value) {
		
		$thisClanAdminIds = getClanAdminIdList($clanList[$clan]["id"]);
			
		$newList = array_merge($adminIds, $thisClanAdminIds);
		$adminIds = $newList;
		
	}
	
	return($adminIds);
	
}

function isCurrentUserClanAdmin($clanDataFile = CLAN_DATA_FILE) {

	$destinyIds = getDestinyIdsForCurrentUser();

	$adminIds = getMulticlanAdminIdList($clanDataFile);
	
	$retVal = false;
	
	foreach ($destinyIds as $thisId=>$value) {
		if (in_array($destinyIds[$thisId]['membershipId'], $adminIds)) {
			$retVal = true;
		} // end if is in admin array
	} // end foreach id
	
	return($retVal);
	
} // end function isCurrentUserClanAdmin()

?>
