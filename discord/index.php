<?php
	$inviteFile = "./invite.json";
	$inviteCode = 'fznrvW2';
	$discordName = 'Solarian Clan';
	$discordURL = 'https://solarian.net/discord';
	$discordKeywords = 'Solarian,Clan,Destiny,Destiny 2,soren42,tracon22s,kevdawg,Freak-0,NotDisliked,Bungie,PS4,PSN,PC,BattleNet,stats,Guardians,Tournament,challenges,Ghost,game,Activision,player';
	$discordDescriptionURL = 'https://solarian.net/membercountforbadge.php';
	$discordIcon = 'https://solarian.net/img/sol-discord-icon.png';
	$favIcon = 'https://solarian.net/img/favicon.png';


	if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
		$inviteCode = $_SERVER['QUERY_STRING'];
	} elseif (is_readable($inviteFile)) {
			$invite = json_decode(file_get_contents($inviteFile), TRUE);
			if ((isset($invite['invite'])) && ($invite['invite'] != "")) {
				$inviteCode = $invite['invite'];
			}
			
	}
		
	if(stristr($_SERVER['HTTP_USER_AGENT'], 'discord') === FALSE) {
    	header('Location: https://discord.gg/'.$inviteCode); 
		exit(0);  
	} 

	$memberCount = file_get_contents($discordDescriptionURL);

?><!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
  <head>       
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1.0" />  
    <meta name="description" content="<?php print_r($discordName."

".$memberCount); ?>" />
    <meta name="keywords" content="<?php print_r($discordKeywords); ?>" />
    <link rel="shortcut icon" href="<?php print_r($favIcon); ?>" type="image/png"><link rel="icon" href="<?php print_r($favIcon); ?>" type="image/png">
    <!-- Open Graph Tags -->
    <meta property="og:site_name" content="<?php print_r($discordName); ?>">
	<meta property="og:url" content="<?php print_r($discordURL); ?>" />
	<meta property="og:image" content="<?php print_r($discordIcon); ?>" />
	<meta name="theme-color" content="#9900ff">
	<title><?php print_r($discordName); ?></title>
	<meta property="og:title" content="<?php print_r($discordName); ?>" />
	<meta property="og:description" content="­
<?php print_r($memberCount); ?>" />
	</head>
	 <body id="body">
		     <div id="app-mount"></div><script nonce="MTE4LDE0MiwxNDAsMTksMjE3LDI2LDE3NCwxODk=">window.__OVERLAY__ = /overlay/.test(location.pathname)</script><script nonce="MTE4LDE0MiwxNDAsMTksMjE3LDI2LDE3NCwxODk=">window.GLOBAL_ENV = {
      API_ENDPOINT: '//discordapp.com/api',
      WEBAPP_ENDPOINT: '//discordapp.com',
      CDN_HOST: 'cdn.discordapp.com',
      ASSET_ENDPOINT: 'https://discordapp.com',
      WIDGET_ENDPOINT: '//discordapp.com/widget',
      INVITE_HOST: 'discord.gg',
      GIFT_CODE_HOST: 'discord.gift',
      MARKETING_ENDPOINT: '//discordapp.com',
      NETWORKING_ENDPOINT: '//router.discordapp.net',
      RELEASE_CHANNEL: 'stable',
      BRAINTREE_KEY: 'production_5st77rrc_49pp2rp4phym7387',
      STRIPE_KEY: 'pk_live_CUQtlpQUF0vufWpnpUmQvcdi',
    };</script><script nonce="MTE4LDE0MiwxNDAsMTksMjE3LDI2LDE3NCwxODk=">!function(){if(null!=window.WebSocket){var n=function(n){try{var e=localStorage.getItem(n);return null==e?null:JSON.parse(e)}catch(n){return null}},e=n("token"),o=n("gatewayURL");if(e&&o){var r=null!=window.DiscordNative||null!=window.require?"etf":"json",t=o+"/?encoding="+r+"&v=6";void 0!==window.Uint8Array&&(t+="&compress=zlib-stream"),console.log("[FAST CONNECT] "+t+", encoding: "+r+", version: 6");var a=new WebSocket(t);a.binaryType="arraybuffer";var i=Date.now(),s={open:!1,gateway:t,messages:[]};a.onopen=function(){console.log("[FAST CONNECT] connected in "+(Date.now()-i)+"ms"),s.open=!0},a.onclose=a.onerror=function(){window._ws=null},a.onmessage=function(n){s.messages.push(n)},window._ws={ws:a,state:s}}}}();</script><script src="/assets/9ff6295a2efaf5f35e7b.js" integrity="sha256-eQLreep9lLMNWCc6/a8kSCJh80grhYLekKd4DI4ai0o= sha512-l37q392v+OigHhxmmUMGuwV0x2Lb7YJbnhRCldfe6LPQbBA/oWBy/IP7Ge9WxbWBBALurWUJWT9TAdUI7pP5iQ=="></script><script src="/assets/9f9ecbc356f431f4fb19.js" integrity="sha256-8+IPFCOX1/oCoJJug7kYDM8+DtqDyeEHvtp1eevoKsY= sha512-rOXmXG9fpAf/xb0QyR01FCJAafoBgNlnEiIe9ToACMgbIPIMUYiDOP5YMN9FGuGeP/So9/DtSgIKp0WGr45Qcg=="></script><script src="/assets/337a0973cc4b85d4d1ce.js" integrity="sha256-VNTuFdsHru4CYPRycBzQ2JZG9XxeL3iaZD0fh5yV5dM= sha512-QWIcGNKKlecrC+b/79OAVqifqLNCizMTfjfV3djzFv6GlxNxEvujIqLvm1YzlkLiKfoGknRMZQHOnrUHQvtQag=="></script>
	</body>
</html>
