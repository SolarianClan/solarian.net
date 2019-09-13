<?php
/**
 * Library solarian.php
 * Primary functions for Solarian Websites via SolariaCore Libraries
 *
 */
// Initialise PHP session services (used for Bungie API Auth management)
session_start();
// define installation path for all root directory of data, services, and more.
defined('INSTALLATION_PATH') or define("INSTALLATION_PATH", '/var/www/solarian.net/');
// define path to data files if not already defined
defined('DATA_PATH') or define("DATA_PATH", INSTALLATION_PATH."data/");
// define path to library files if not already defined
defined('SERVICE_PATH') or define('SERVICE_PATH', INSTALLATION_PATH."services/");
// Add library for Destiny API services
require_once(SERVICE_PATH. "destiny.php");
// Add library for API caching services
require_once(SERVICE_PATH. "cache.php");
// Add library for WTFIX Xûr location services
require_once(SERVICE_PATH. "xur.php");
// Add library for Solarian Clan Admin Dashboard services
require_once(SERVICE_PATH. "dashboard.php");
// Add library for banner building services
require_once(SERVICE_PATH. "banner.php");

use function multiclanRoster as solarianRoster;
use function multiclanRosterByJoinDate as solarianRosterByJoinDate;

function leadershipBlock($leadershipJSONFile = DATA_PATH.'leadership.json') {

        $leaders = json_decode(file_get_contents($leadershipJSONFile), TRUE);

		foreach($leaders as $leader => $value) {
			print_r('<div class="single-testimonial item d-flex flex-row">'.PHP_EOL);
			print_r('	<div class="thumb">'.PHP_EOL);
			print_r('		<img class="avatars" src="img/'.$leaders[$leader]["displayname"].'.png" alt="'.$leaders[$leader]["username"].'">'.PHP_EOL);
			print_r('	</div>'.PHP_EOL);
			print_r('	<div class="desc">'.PHP_EOL);
			print_r('		<p>'.PHP_EOL);
			print_r('			'.$leaders[$leader]["description"].PHP_EOL);
			print_r('		</p>'.PHP_EOL);
			print_r('		<h4 mt-30>'.$leaders[$leader]["username"].', '.$leaders[$leader]["role"].'</h4>'.PHP_EOL);
			print_r('		<div class="star">'.PHP_EOL);
			foreach($leaders[$leader]["platform"] as $platforms => $platform) {
					print_r('			<span class="fab fa-'.$platform.'"></span>'.PHP_EOL);
				}
			print_r('		<b>Triumph Score:</b> '.$leaders[$leader]["triumph"].PHP_EOL);
//			print_r('		<b>Triumph Score:</b> '.triumphScoreByName(rawurldecode($leaders[$leader]["username"])).PHP_EOL);
//			print file_get_contents("http://solarian.net/triumphscorebyname.php?name=".rawurlencode($leaders[$leader]["username"]));
			print_r('		</div>'.PHP_EOL);
			print_r('	</div>'.PHP_EOL);
			print_r('</div>'.PHP_EOL);
			}
} // end function leadershipBlock

function newsBlock($newsJSONFile = DATA_PATH.'news.json') {
	
		$news = json_decode(file_get_contents($newsJSONFile), TRUE);
		$totalArticles = 0;
		foreach ($news as $articleCount => $detailCount) { 
				if ($news[$articleCount]['active'] == 1) {
					$totalArticles++;
				}
		}
		$numPerCol = round(($totalArticles / 2),0,PHP_ROUND_HALF_DOWN);
		$articleNum = 1;
		print_r('<!-- numPerCol: '.$numPerCol.' -->'.PHP_EOL);
		print_r('							<div class="col-lg-6 secvice-left">'.PHP_EOL);
		$backNews = array_reverse($news);
		foreach ($backNews as $article => $detail) {
			if ($backNews[$article]['active'] == '1') {
			print_r('							<!-- articleNum: '.$articleNum.' -->'.PHP_EOL);
			print_r('							<div class="single-service d-flex flex-row pb-30">'.PHP_EOL);
			print_r('								<div class="icon">'.PHP_EOL);
			print_r('									<h1>'.date("d M", strtotime($backNews[$article]['date'])).'</h1>'.PHP_EOL);
			print_r('								</div>'.PHP_EOL);
			print_r('								<div class="desc">'.PHP_EOL);
			print_r('									<h4><a href="'.$backNews[$article]['URL'].'">'.$backNews[$article]['title'].'</a></h4>'.PHP_EOL);
			print_r('									<p>'.PHP_EOL);
			print_r('										'.$backNews[$article]['description'].PHP_EOL);
			print_r('									</p>'.PHP_EOL);
			print_r('								</div>'.PHP_EOL);
			print_r('							</div>'.PHP_EOL);
			if ($articleNum == $numPerCol) {
				print_r('					</div>'.PHP_EOL);
				print_r('					<div class="col-lg-6 secvice-right">'.PHP_EOL);
			}
			$articleNum++;	
			}
		}
		print_r('				</div>'.PHP_EOL);
} // end function newsBlock
 
function pageHeader($metaJSONFile = DATA_PATH.'meta.json') {
	
	 $metaTags = json_decode(file_get_contents($metaJSONFile), TRUE);
	
	echo <<<EOT
		<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132047846-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-132047846-1');
		</script>
		<!-- Facebook Pixel Code -->
		<script>
		  !function(f,b,e,v,n,t,s)
		  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		  n.queue=[];t=b.createElement(e);t.async=!0;
		  t.src=v;s=b.getElementsByTagName(e)[0];
		  s.parentNode.insertBefore(t,s)}(window, document,'script',
		  'https://connect.facebook.net/en_US/fbevents.js');
		  fbq('init', '437670443666434');
		  fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		  src="https://www.facebook.com/tr?id=437670443666434&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Facebook Pixel Code -->
EOT;

		foreach ($metaTags as $page => $metaField) {
			
		if (basename($_SERVER['PHP_SELF']) == $metaTags[$page]["filename"]) {
				$pageTitle 	     = $metaTags[$page]["pageTitle"];
				$pageKeywords    = $metaTags[$page]["pageKeywords"];
				$pageDescription = $metaTags[$page]["pageDescription"];
				$filename		 = $metaTags[$page]["filename"];
			}

		}
		
		if (!(isset($pageTitle))) {
					$pageTitle = ucfirst(strstr(basename($_SERVER['PHP_SELF']),'.',true));
					$pageKeywords = "";
					$pageDescription = "";
					$filename = basename($_SERVER['PHP_SELF']);
			};
	
	echo <<<EOT
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="img/favicon.png">
		<!-- Author Meta -->
		<meta name="author" content="Solarian Clan">
		<!-- Meta Description -->
		<meta name="description" content="{$pageDescription}">
		<!-- Meta Keyword -->
		<meta name="keywords" content="Solarian,Clan,Destiny,Destiny 2,soren42,tracon22s,kevdawg,Freak-0-,NotDisliked,Bungie,PS4,PSN,PC,Xbox,One,XB1,XB,Microsoft,Google,Stadia,BattleNet,stats,Guardians,Tournament,challenges,Ghost,game,Activision,player{$pageKeywords}">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>Solarian Clan :: {$pageTitle}</title>

		<!-- Open Graph Tags -->
		<meta property="og:site_name" content="Solarian Clan">
		<meta property="og:url" content="https://solarian.net/{$filename}" />
		<meta property="og:image" content="https://solarian.net/img/sol-discord-icon.png" />
		<meta property="fb:app_id" content="2004099879702862" />
		<meta name="theme-color" content="#9900ff">
		<meta property="og:title" content="{$pageTitle}" />
		<meta property="og:description" content="{$pageDescription}" />


		<!-- No Caching -->
		<!-- <meta http-equiv="Cache-Control" content="no-store" /> -->
		<!-- Google LD Data -->
		<script type="application/ld+json">
			{
			  "@context": "http://schema.org",
			  "@type": "Organization",
			  "url": "https://solarian.net/",
			  "name": "Solarian Clan",
			  "contactPoint": {
				"@type": "ContactPoint",
				"email": "admin@solarian.net",
				"contactType": "Adminstration"
			  }
			}
		</script>
		<link href="/apple-touch-icon.png" rel="apple-touch-icon" />
		<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,400,300,500,600,700" rel="stylesheet"> 
			<!--
			CSS
			============================================= -->
			<link rel="stylesheet" href="css/linearicons.css">
			<link rel="stylesheet" href="css/font-awesome.min.css">
			<link rel="stylesheet" href="css/bootstrap.css">
			<link rel="stylesheet" href="css/magnific-popup.css">
			<link rel="stylesheet" href="css/nice-select.css">					
			<link rel="stylesheet" href="css/animate.min.css">
			<link rel="stylesheet" href="css/owl.carousel.css">
			<link rel="stylesheet" href="css/main.css">
			<link rel="stylesheet" href="css/v4-shims.css">
			<link rel="stylesheet" href="css/all.css">
			<link href="https://fonts.googleapis.com/css?family=Roboto:400,700&effect=anaglyph" rel="stylesheet">
			<link rel="stylesheet" href="css/destiny-font.css">
			<link rel="stylesheet" href="css/countdowncube.css">

	</head>
EOT;
	
} // end function pageHeader

function pageFooter() {
	
	echo <<<EOT
				<!-- start footer Area -->		
			<footer class="footer-area section-gap">
				<div class="container">
					<div class="row">
						<div class="col-lg-5 col-md-6 col-sm-6">
							<div class="single-footer-widget">
								<!-- <h6>Solarian</h6> -->
								<p>
									Solarian is private social organisation.  Solarian is not affliated, sponsored, or endorsed by Bungie, Activision, or any other rights holders referenced. All trademarks are the sole property of their respective owners and used without express permission.<br>
									© Bungie, Inc. All rights reserved. Destiny, the Destiny Logo, Bungie and the Bungie logo are among the trademarks of Bungie, Inc.
								</p>
								
								<p class="footer-text">
								<a href="/terms.php">Terms of Service</a> | <a href="/privacy.php">Privacy Policy</a>
								</p>
								
								<p class="footer-text">
								Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Solarian Clan
								</p>	
							</div>
						</div>
						<div class="col-lg-5  col-md-6 col-sm-6">
							<div class="single-footer-widget">
							   <!-- <h6>Newsletter</h6>
								<p>Stay update with our latest</p>
								<div class="" id="mc_embed_signup">
									<form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01" method="get" class="form-inline">
										<input class="form-control" name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '" required="" type="email">
			                            	<button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
			                            	<div style="position: absolute; left: -5000px;">
												<input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
											</div>

										<div class="info"></div>
									</form>
								</div> -->
							</div>
						</div>						
						<div class="col-lg-2 col-md-6 col-sm-6 social-widget">
							<div class="single-footer-widget">
								<h6>Solarian on Social Media</h6>
								<p>Follow and engage with us!</p>
								<div class="footer-social d-flex align-items-center">
									<a href="https://www.facebook.com/SolarianClan/"><i class="fa fa-facebook"></i></a>
									<a href="https://twitter.com/SolarianClan"><i class="fa fa-twitter"></i></a>
									<a href="https://solarian.net/discord/"><i class="fab fab-discord"></i></a>
									<a href="https://www.youtube.com/channel/UCfE2P25jjIHWKjGpO7F-iNg"><i class="fa fa-youtube"></i></a>
								</div>
							</div>
						</div>							
					</div>
				</div>
			</footer>	
			<!-- End footer Area -->
EOT;

} // end function pageFooter

function javaScripts () {
	
	echo <<<EOT
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
			<script src="js/countdowncube.js"></script>
			<script src="js/jstz.js"></script>
			<script src="js/moment.js"></script>
			<script src="js/moment-timezone.js"></script>
			
EOT;
	
} // end function javaScripts

function menubar() {
	
	echo <<<EOT
		  <header id="header" id="home">
		    <div class="container">
		    	<div class="row align-items-center justify-content-between d-flex">
			      <div id="logo">
			        <a href="/"><img src="img/logo.png" alt="" title="" /></a>
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
							<!--<li><a href="discord.php">Using Our Clan Discord</a></li>-->
				        	<li><a href="leaderboards.php">Leaderboards</a></li>
							<li><a href="links.php">Links</a></li>
							<li><a href="tournaments.php">Tournaments</a></li>
						    <li><a href="challenge.php">Challenges</a></li>
							<li><a href="podcast.php">Podcast</a></li>
							<li><a href="guide.php">New Member's Guide</a></li>
							<li><a href="shop.php">Merchandise</a></li>
							
				      	</ul>
				      </li>
				      <li><a class="ticker-btn" href="join.php">Join Our Clan!</a></li>				          
				    </ul>
			      </nav><!-- #nav-menu-container -->		    		
		    	</div>
		    </div>
		  </header><!-- #header -->
EOT;
	
} // end function menubar

function displayStatus($statusDataFile = DATA_PATH.'status.json') {

	$statusArray = json_decode(file_get_contents($statusDataFile), TRUE);

	echo($statusArray[0]);
	
} // end function displayStatus



?>