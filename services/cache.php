<?php
# define installation path for all root directory of data, services, and more.
defined('INSTALLATION_PATH') or define("INSTALLATION_PATH", '/var/www/solarian.net/');
# define path to data files if not already defined
defined('DATA_PATH') or define("DATA_PATH", INSTALLATION_PATH."data/");
# define path to library files if not already defined
defined('SERVICE_PATH') or define('SERVICE_PATH', INSTALLATION_PATH."services/");

require_once(SERVICE_PATH."solarian.php");
require_once(SERVICE_PATH."destiny.php");

// define path to cache config
define('CACHE_CONFIG_FILE', DATA_PATH."cache.cfg");
// define default endpoint for cache functions
define('DEFAULT_ENDPOINT', '/Platform/Destiny2/Manifest/');
// define default endpoint for cache functions
define('DEFAULT_MAX_AGE', '2 days');
// define debug state
define('DEBUG_FLAG', false);

if (is_readable(CACHE_CONFIG_FILE)) {

	$cfgfp = @fopen(CACHE_CONFIG_FILE, "r");

	# Import, decode, and define CACHE_DB_HOST
	if ($pDB_HOST=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("CACHE_DB_HOST", trim(base64_decode($pDB_HOST)));
		} else {
			define("CACHE_DB_HOST", trim($pDB_HOST));
		}
		unset($pDB_HOST);
	} else {
		error_exit("CACHE_DB_HOST not found");
	}

	# Import, decode, and define CACHE_DB_NAME
	if ($pCACHE_DB_NAME=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("CACHE_DB_NAME", trim(base64_decode($pCACHE_DB_NAME)));
		} else {
			define("CACHE_DB_NAME", trim($pCACHE_DB_NAME));
		}
		unset($pCACHE_DB_NAME);
	} else {
		error_exit("CACHE_DB_NAME not found");
	}

	# Import, decode, and define CACHE_USER
	if ($pCACHE_USER=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("CACHE_USER", trim(base64_decode($pCACHE_USER)));
		} else {
			define("CACHE_USER", trim($pCACHE_USER));
		}
		unset($pCACHE_USER);
	} else {
		error_exit("CACHE_USER not found");
	}

	# Import, decode, and define CACHE_PASS
	if ($pCACHE_PASS=fgets($cfgfp)) {
		if (CFG_ENCODED) {
			define("CACHE_PASS", trim(base64_decode($pCACHE_PASS)));
		} else {
			define("CACHE_PASS", trim($pCACHE_PASS));
		}
		unset($pCACHE_PASS);
	} else {
		error_exit("CACHE_PASS not found");
	}

	fclose($cfgfp);
	unset($cfgfp);
} else {
	error_exit("Cache data file not found");
}

function cacheConnect() {
	
	$link = mysqli_connect(CACHE_DB_HOST, CACHE_USER, CACHE_PASS, CACHE_DB_NAME);
	
	if (!$link) {
    	echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    	exit;
	}
	
	return $link;
	
} // end function cacheConnect()

function cacheDisconnect($link) {
	
	return mysqli_close($link);
	
} // end function cacheDisconnect()

function writeNewToCache($endpoint = DEFAULT_ENDPOINT, $isCacheable = 0, $debugFlag = DEBUG_FLAG) {
	
	$cacheHandle = cacheConnect();

	$endpointDataJSON = mysqli_real_escape_string($cacheHandle, queryAPI($endpoint, true));
	
	$cacheInsertQuery = "INSERT INTO cache VALUES (NULL, '{$endpoint}', {$isCacheable}, 0, NOW(), '{$endpointDataJSON}');";
	
	if (mysqli_real_query($cacheHandle, $cacheInsertQuery) === false) {
		if ($debugFlag === true) {
			echo "Error: Unable to write to cache." . PHP_EOL;
			echo "Debugging errno: " . mysqli_errno($cacheHandle) . PHP_EOL;
			echo "Debugging error: " . mysqli_error($cacheHandle) . PHP_EOL;
			file_put_contents('output.json',$endpointDataJSON);
		}
		$retVal = false;
	} else {		
		$retVal = true;		
	}
	
	cacheDisconnect($cacheHandle);
	
	return($retVal);
	
} // end function writeNewToCache()

function updateInCache($endpoint = DEFAULT_ENDPOINT, $debugFlag = DEBUG_FLAG) {
	
	$cacheHandle = cacheConnect();

	$endpointDataJSON = mysqli_real_escape_string($cacheHandle, queryAPI($endpoint, true));
	
	$cacheUpdateQuery = "UPDATE cache SET data='{$endpointDataJSON}', last_cache=NOW() WHERE endpoint='{$endpoint}';";
	
	if (mysqli_real_query($cacheHandle, $cacheUpdateQuery) === false) {
		if ($debugFlag === true) {
			echo "Error: Unable to update to cache." . PHP_EOL;
			echo "Debugging errno: " . mysqli_errno($cacheHandle) . PHP_EOL;
			echo "Debugging error: " . mysqli_error($cacheHandle) . PHP_EOL;
			file_put_contents('output.json',$endpointDataJSON);
		}
		$retVal = false;
	} else {		
		$retVal = true;		
	}
	
	cacheDisconnect($cacheHandle);
	
	return($retVal);
	
} // end function updateInCache()

function isRefreshRequired($endpoint = DEFAULT_ENDPOINT, $maximumAcceptableAge = DEFAULT_MAX_AGE){
	
	if (isCached($endpoint) === true) {
	
		$cacheHandle = cacheConnect();
		
		$dateQuery = "SELECT * FROM cache WHERE endpoint='{$endpoint}';";
		$tmpResult = mysqli_query($cacheHandle, $dateQuery);
		$dateResults = $tmpResult->fetch_all(MYSQLI_ASSOC);
		$cacheDate = $dateResults[0]['last_cache'];
		
		$expirationDate = date_create("{$cacheDate} +{$maximumAcceptableAge}")->format('Y-m-d H:i:s');
		$now = date_create("now")->format('Y-m-d H:i:s');
		
		$retVal = ($now > $expirationDate) ? true : false;
		
		cacheDisconnect($cacheHandle);
	
	} else {
		$retVal = true;
	} // end if/else isCached
	
	return($retVal);
	
} // end function isRefreshRequired()

function isCached($endpoint = DEFAULT_ENDPOINT) {
	
	$cacheHandle = cacheConnect();
	$checkQuery = "SELECT * FROM cache WHERE endpoint='{$endpoint}';";
	$checkResult = mysqli_query($cacheHandle, $checkQuery);
	$retVal = ($checkResult->num_rows > 0) ? true : false;
	cacheDisconnect($cacheHandle);
	return($retVal);
	
} // end function isCached()

function queryCache($endpoint = DEFAULT_ENDPOINT, $returnJSON = false, $maximumAcceptableAge = DEFAULT_MAX_AGE) {
	
	if (isCached($endpoint) === false) {		
		writeNewToCache($endpoint, 1, DEBUG_FLAG);
		$retVal = ($returnJSON === true) ? queryAPI($endpoint, true) : queryAPI($endpoint);		
	} else { // end if is not cached
		
		if (isRefreshRequired($endpoint, $maximumAcceptableAge) === true) {
			updateInCache($endpoint);
			$retVal = ($returnJSON === true) ? queryAPI($endpoint, true) : queryAPI($endpoint);			
		} else { // end if refresh required
			$cacheHandle = cacheConnect();
			$cacheQuery = "SELECT * FROM cache WHERE endpoint='{$endpoint}';";
			$cacheResult = mysqli_query($cacheHandle, $cacheQuery);
			//print_r($cacheResult);
			$cacheResults = $cacheResult->fetch_all(MYSQLI_ASSOC);
			$retVal = ($returnJSON === true) ? $cacheResults[0]['data'] : json_decode($cacheResults[0]['data'], true);
		} // end if/else refresh not required
	} // end if/else not cached
	
	return($retVal);
	
} // end function queryCache()

?>