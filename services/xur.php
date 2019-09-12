<?php
/**
 * Library xur.php
 * WTFIX API functions for SolariaCore Libraries
 *
 */

# define installation path for all root directory of data, services, and more.
defined('INSTALLATION_PATH') or define("INSTALLATION_PATH", '/var/www/solarian.net/');
# define path to data files if not already defined
defined('DATA_PATH') or define("DATA_PATH", INSTALLATION_PATH."data/");
# define path to library files if not already defined
defined('SERVICE_PATH') or define('SERVICE_PATH', INSTALLATION_PATH."services/");
require_once(SERVICE_PATH."solarian.php");

define('XÛR_PROTOCOL', "https://");
define('XÛR_SERVER', "wherethefuckisxur.com");
define('XÛR_API_PATH', "/api/xur/");
define('VENDOR_API_PATH', "/api/vendor/");

function queryXûrAPI($thisURL = XÛR_API_PATH) {

    $pQueryHandle = curl_init();
    $pQueryURL = XÛR_PROTOCOL.XÛR_SERVER.$thisURL;
	
    curl_setopt($pQueryHandle, CURLOPT_URL,$pQueryURL);
    curl_setopt($pQueryHandle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
                        'Origin: '.ORIGIN_HEADER,
                        'User-Agent: '.USER_AGENT
                        ]);
    curl_setopt($pQueryHandle, CURLOPT_HTTPGET, true);

    $pQueryReturn = curl_exec($pQueryHandle);

    curl_close($pQueryHandle);
	
    $pResults = json_decode($pQueryReturn, true);
	
	return($pResults);
	
} // end function queryXûrAPI()

function queryOrUse($xûrData = null) {
	
	if ($xûrData == null) {
		$xûrData = queryXûrAPI();
	} // end if no Xûr data already
	
	return($xûrData);
	
} // end function queryOrUse()

function isXûrPresent($xûrData = null) {
	
	$xûrData = queryOrUse($xûrData);
	
	if ($xûrData['present'] === false) {
		$retVal = false;
	} else {
		$retVal = true;
	} // end if/else present set to false
	
	return($retVal);
	
} // end function isXûrPresent()

function isXûrFound($xûrData = null) {
	
	$xûrData = queryOrUse($xûrData);
	
	if (isXûrPresent($xûrData) === false) {
		$retVal = false;
	} else {

		if ($xûrData['found'] === false) {
			$retVal = false;
		} else {
			$retVal = true;
		} // end if/else found set to false
		
	} // end if/else Xûr not present
	
	return($retVal);
	
} // end function isXûrFound()

function xûrPlanet($xûrData = null) {
	
	$xûrData = queryOrUse($xûrData);
	
	if (isXûrFound($xûrData) === false) {
		$xûrPlanet = '';
	} else {
		$xûrPlanet = $xûrData['planet'];
	} // end if/else is Xûr found
	
	return($xûrPlanet);
	
} // end function xûrPlanet()

function xûrZone($xûrData = null) {
	
	$xûrData = queryOrUse($xûrData);
	
	if (isXûrFound($xûrData) === false) {
		$xûrZone = '';
	} else {
		$xûrZone = $xûrData['zone'];
	} // end if/else is Xûr found
	
	return($xûrZone);
	
} // end function xûrZone()

function xûrMessage($xûrData = null) {
	
	$xûrData = queryOrUse($xûrData);
	
	$xûrMessage = '';
	
	if (isXûrPresent($xûrData) === false) {
		$xûrMessage = "Xûr is not currently present.";
	} elseif (isXûrFound($xûrData) === false) {
		$xûrMessage = "Xûr is present, but has not been located yet.";
	} else {
		$xûrMessage = "Xûr is located at ".xûrZone($xûrData)." on ".xûrPlanet($xûrData).".";
	} // end if/elseif/else Xûr Present/Found/Location
	
	return($xûrMessage);
	
} // end function xûrMessage()

?>