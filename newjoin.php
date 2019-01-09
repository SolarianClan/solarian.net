<?php 
	if (!(isset($_GET["code"]))) {
		header("Location: https://www.bungie.net/en/oauth/authorize?client_id=25146&response_type=code");
	}

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

	function getToken($code, $refresh = true) {
        $ch = curl_init();
		$tokenURL = PROTOCOL.API_SERVER."/Platform/App/OAuth/Token/";
        curl_setopt($ch, CURLOPT_URL, $tokenURL);
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

		$token = getToken($_GET['code']);
        $accessCode = $token->access_token;

function getCurrentUserId($thisToken) {
		unset($ch);
		$ch = curl_init();
		$thisURL = PROTOCOL.API_SERVER."/Platform/User/GetMembershipsForCurrentUser/";
		curl_setopt($ch, CURLOPT_URL, $thisURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-API-Key: '.API_KEY,
                'Origin: '.ORIGIN_HEADER,
                'Authorization: Bearer '.$thisToken
        ]);
        $rawReturn = curl_exec($ch);
        $UserData = json_decode($rawReturn, true);
		curl_close($ch);
		print_r($UserData);
		$thisUser = $UserData["Response"]["destinyMemberships"];
/*		foreach ($thisUser as $accounts) {
			if ($thisUser[$accounts]["membershipType"] == $platform) {
				$thisUserId = $thisUser[$accounts]["membershipId"];
			}
		}
	
		if (!(isset($thisUserId))) { $thisUserId = "0"; }

		return $thisUserId; */
	} 

?><!DOCTYPE html>
	<html lang="en-uk" class="no-js">
	<?php require_once("header.php"); ?>
		<body>

		  <header id="header" id="home">
		    <div class="container">
		    	<div class="row align-items-center justify-content-between d-flex">
			      <div id="logo">
			        <a href="/"><img src="img/logo.png" alt="Solarian" title="" /></a>
			      </div>
			      <nav id="nav-menu-container">
			        <ul class="nav-menu">
				      <li class="menu-active"><a href="/">Home</a></li>
				      <li><a href="/#about">About Us</a></li>
				      <li><a href="/#news">News</a></li>
				      <li><a href="/#updates">Updates</a></li>							
				      <li><a href="/#admins">Admins</a></li>
				      <li class="menu-has-children"><a href="">Pages</a>
				      	<ul>
				      		<li><a href="rules.php">Policies &amp; Procedures</a></li>
				        	<li><a href="leaderboards.php">Leaderboards</a></li>
							<li><a href="tournaments.php">Tournaments</a></li>
				      	</ul>
				      </li>
				      <li><a class="ticker-btn" href="join.php">Join Our Clan!</a></li>				          
				    </ul>
			      </nav><!-- #nav-menu-container -->		    		
		    	</div>
		    </div>
		  </header><!-- #header -->
		  <!-- start banner Area -->
			<section class="banner-area relative" id="home">
				<div class="overlay overlay-bg"></div>	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-center">
						<div class="banner-content col-lg-8">							
							<h1 class="text-white">Join Our Clan!</h1>
								<span class="bng-header bng-tower"></span>
								<p class="text-white">Our clan welcomes new Guardians over the age of 16 who play more than five hours per week on either PlayStation 4 or PC and are seeking to be an active participant in a Destiny community.  If that's you, please complete the form below to apply for an open position!</p>
							</p>
						</div>											
					</div>
				</div>					
			</section>		
			<!-- Start Align Area -->
			<div class="whole-wrap">
				<div class="container" align="center">					
				<?php 
					if (isset($_GET["platform"])) {
						$platform = $_GET["platform"];
						switch ($platform) {
								
							case 1:
								$platformName = "XBox One";
								break;
							case 2:
								$platformName = "PlayStation 4";
								break;
							case 4:
								$platformName = "PC";
								break;
							default:
								print('<script language="javascript"> location.href = location.protocol + "//" + location.hostname + location.pathname; </script>');
						};
						
						print('<div class="button-group-area mt-10">
						<div class="genric-btn primary-border circle">Selected platform: '.$platformName.'</div>
						<div class="platform-select"><a href="join.php" class="genric-button link">Click here to choose a different platform</a></div>
					</div>');						
					} else {
					print('
					<div class="single-element-widget mt-30">
						<h3 class="mb-30">Please select your platform:</h3>
									<div class="default-select col-lg-3" id="default-select" align="center">
										<form action="join.php" method="get">
										<input type="hidden" name="code" value="'.$_GET["code"].'">
										<select name="platform" onChange="submit();">
											<option>--Platform--</option>
											<option value="2">PlayStation 4</option>
											<option value="4">PC</option>
											<option value="1">XBox One</option>
										</select>
										</form>
									</div>
								</div>');
				
					}
					?>
				</div>
			</div>
			<?php if ($platform == 1): ?>
			<!--<div class="whole-wrap">
				<div class="container" align="center">
					<div class="col-sm-6" align="justify"><h4 class="warning-box">
				At this time, we do not have an active XBox clan.  If you would be interested in helping Solarian deploy an XBox clan (utilising our community, website, services, tournaments, challenges, etc.), contact our <a href="mailto:admin@solarian.net">admin team</a>!</h4>
				</div>
				</div>
				</div> -->
			<?php endif; ?>
			
			<?php
				if (isset($platform)) {
					print_r(getCurrentUserId($accessCode));
				}
			?>
		
			<!-- End Generic Start -->		

	<?php require_once("footer.php"); ?>	
			
			<script src="js/vendor/jquery-2.2.4.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			<script src="js/vendor/bootstrap.min.js"></script>			
			<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhOdIF3Y9382fqJYt5I_sswSrEw5eihAA"></script>
  			<script src="js/easing.min.js"></script>			
			<script src="js/hoverIntent.js"></script>
			<script src="js/superfish.min.js"></script>	
			<script src="js/jquery.ajaxchimp.min.js"></script>
			<script src="js/jquery.magnific-popup.min.js"></script>	
			<script src="js/owl.carousel.min.js"></script>			
			<script src="js/jquery.sticky.js"></script>
			<script src="js/jquery.nice-select.min.js"></script>			
			<script src="js/parallax.min.js"></script>	
			<script src="js/mail-script.js"></script>				
			<script src="js/main.js"></script>	
		</body>
	</html>
