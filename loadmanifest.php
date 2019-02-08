<?php

# define base protocol used to query API
define("PROTOCOL", "https://");
# define server and FQDN to access API from
define("API_SERVER", "www.bungie.net");
# define origin header
define("ORIGIN_HEADER", "http".(!empty($_SERVER['HTTPS'])?"s":"")."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
# define application version
define("VERSION_NUMBER", "0.1𝛼");
# define release number
define("RELEASE_NUMBER", date("YmdHis", filemtime(".")));
# define application name
define("APPLICATION_NAME", "Solarian");
# define complete User Agent
define("USER_AGENT", APPLICATION_NAME." ".VERSION_NUMBER."(".RELEASE_NUMBER.")");
# define client id
define("CLIENT_ID", "25146");
# define API key
define("API_KEY", "4b586d1833ee491c8b4f6ede97afa9f1");
# define secret
define("SECRET", "c-lNmHuN8kOkIiVtdeg5SD.4H9880gPdIVLbb.g51nE");
	
function error_exit( $error_code = "undefined" ) {
	print_r(error_get_last().":".$error_code."\n");
	exit(1);

}

#
# String functions
#

    function after ($pthis, $inthat)
    {
        if (!is_bool(strpos($inthat, $pthis)))
        return substr($inthat, strpos($inthat,$pthis)+strlen($pthis));
    };

    function after_last ($pthis, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $pthis)))
        return substr($inthat, strrevpos($inthat, $pthis)+strlen($pthis));
    };

    function before ($pthis, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $pthis));
    };

    function before_last ($pthis, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $pthis));
    };

    function between ($pthis, $that, $inthat)
    {
        return before ($that, after($pthis, $inthat));
    };

    function between_last ($pthis, $that, $inthat)
    {
     return after_last($pthis, before_last($that, $inthat));
    };

function pRawQuery($pQueryPath, &$pQueryReturn, $RequiresAuth=FALSE, $token = NULL) {
	$pQueryHandle = curl_init();
	$pQueryURL = PROTOCOL.API_SERVER.$pQueryPath;

    curl_setopt($pQueryHandle, CURLOPT_URL,$pQueryURL);
    curl_setopt($pQueryHandle, CURLOPT_RETURNTRANSFER, true);
	if ($RequiresAuth) {
    	curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
    		'X-API-Key: '.API_KEY,
			'Origin: '.ORIGIN_HEADER,
			'User-Agent: '.USER_AGENT,
			'Authorization: Bearer '.$token
			]);
        } else {
    	curl_setopt($pQueryHandle, CURLOPT_HTTPHEADER, [
    		'X-API-Key: '.API_KEY,
			'Origin: '.ORIGIN_HEADER,
			'User-Agent: '.USER_AGENT
			]);
        }

        $pQueryReturn = curl_exec($pQueryHandle);

		// print_r($pQueryReturn);

        curl_close($pQueryHandle);

    return json_decode($pQueryReturn, TRUE);


}

// https://raw.githubusercontent.com/Bungie-net/api/master/openapi.json
// /Destiny2/Manifest/
// print_r(pRawQuery("/Platform/User/GetMembershipsById/4611686018430373576/2/", $pQueryResults, FALSE, NULL));
//$resultArray=pRawQuery("/Platform/User/GetMembershipsById/4611686018430373576/2/", $pQueryResults, FALSE, NULL);
$resultArray=pRawQuery("/Platform/Destiny2/Manifest/", $pQueryResults, FALSE, NULL);

print_r($pQueryResults);


?>