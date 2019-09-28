<?php

require_once("services/solarian.php");

function getToken($code, $refresh = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.bungie.net/Platform/App/OAuth/Token/');
        curl_setopt($ch, CURLOPT_POST, 1);
        if($refresh) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=refresh_token&refresh_token='.$code);
        } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&code='.$code);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Basic '.base64_encode(CLIENT_ID.':'.SECRET),
                'Origin: Solarian',
                'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
}

function refreshToken($token) {
	
//	$ch = curl_init();
//	curl_setopt($ch, CURLOPT_URL, 'https://www.bungie.net/Platform/App/OAuth/Token/');
//   curl_setopt($ch, CURLOPT_POST, 1);
//	curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=refresh_token&refresh_token='.$token->refresh_token);
//	curl_setopt($ch, CURLOPT_HTTPHEADER, [
//                'Authorization: Basic '.base64_encode(CLIENT_ID.':'.SECRET),
//                'Origin: Solarian',
//                'Content-Type: application/x-www-form-urlencoded'
//        ]);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $result = curl_exec($ch);
//        curl_close($ch);
	$refreshSuccess = false;
	
	while ($refreshSuccess === false) {
	
	$authURL = PROTOCOL.API_SERVER."/Platform/App/OAuth/Token/";
	
	$pQueryHandle = curl_init();
	curl_setopt($pQueryHandle, CURLOPT_URL, $authURL);
	curl_setopt($pQueryHandle, CURLOPT_POST, 1);
	curl_setopt($pQueryHandle, CURLOPT_POSTFIELDS, 'grant_type=refresh_token&refresh_token='.$token->refresh_token);
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
	
	
        return $newToken;
	
	
}

if(!isset($_SESSION['solRefreshToken'])) {

if(!isset($_GET['code'])) {
        header('Location: https://www.bungie.net/en/oauth/authorize?client_id='.CLIENT_ID.'&response_type=code');
} else {
        $token = getToken($_GET['code']);
		$_SESSION['solAccessToken'] = $token->access_token;
		$_SESSION['solRefreshToken'] = $token->refresh_token;
		$_SESSION['solTokenType'] = $token->token_type;
		$_SESSION['solTokenExpiresIn'] = $token->expires_in;
		$_SESSION['solRefreshTokenExpiresIn'] = $token->refresh_expires_in;
		$_SESSION['solMembershipId'] = $token->membership_id;
		$expSeconds = $newToken->refresh_expires_in;
		if (!is_int($expSeconds)) { $expSeconds = 600; }
		$tokenTime = "now +".$expSeconds." seconds";
		$tokenExpiration = date_create($tokenTime)->format('Y-m-d H:i:s');
		//$tokenExpiration = date_create("+{$token->refresh_expires_in} seconds")->format('Y-m-d H:i:s');
		$_SESSION['solRefreshTokenExpiresAt'] = $tokenExpiration;
        $accessCode = $token->access_token;
		$newToken = refreshToken($token);
	if(isset($_SESSION['solReferralURL'])) {
		
		header("Location: {$_SESSION['solReferralURL']}");
		
	} else {
		
		header("Location: https://solarian.net/");
		
	}

}
	
} else {
	$currentToken = new stdClass();
	$currentToken->access_token = $_SESSION['solAccessToken'];
	$currentToken->refresh_token = $_SESSION['solRefreshToken'];
	$newToken = refreshToken($currentToken);	
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
	//$tokenExpiration = date_create("+{$newToken->refresh_expires_in} seconds")->format('Y-m-d H:i:s');
	$_SESSION['solRefreshTokenExpiresAt'] = $tokenExpiration;

	if(isset($_SESSION['solReferralURL'])) {
		
		header("Location: {$_SESSION['solReferralURL']}");
		
	} else {
		
		header("Location: https://solarian.net/");
		
	}
}