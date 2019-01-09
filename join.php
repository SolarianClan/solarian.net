<!DOCTYPE html>
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
								<p class="text-white">Our clan welcomes new Guardians over the age of 16 who play more than five hours per week on PlayStation 4, XBox One, or PC and are seeking to be an active participant in a Destiny community.  If that's you, please complete the form below to apply for an open position!</p>
							</p>
						</div>											
					</div>
				</div>					
			</section>		
			<!-- Start Align Area -->
			<div class="whole-wrap" id="new-member">
				<div class="container" align="center">					
				<?php 
					if (isset($_GET["platform"])) {
							$platform = $_GET['platform'];
					}
					
					//$platform = 4;
					
					if (isset($platform)) {
						switch ($platform) {
								
							case 1:
								$platformName = "XBox One";
								$platformURL = "https://www.bungie.net/en/ClanV2/Index?groupId=2112472";
								break;
							case 2:
								$platformName = "PlayStation 4";
								$platformURL = "https://www.bungie.net/en/ClanV2?groupid=389581";
								break;
							case 4:
								$platformName = "PC";
								$platformURL = "https://www.bungie.net/en/ClanV2?groupid=3241873";
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
									<div class="default-select col-lg-3" align="center">
										<form action="join.php#new-member" method="get">
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
					<?php if (isset($platform)): ?>
					<form action="join.php" method="get">
						
							<input type="hidden" name="platform" value="<?php echo($_GET["platform"]); ?>">
							
							<h3 class="mb-30">New Members Agreement</h3>
									<div class="switch-wrap d-flex justify-content-between">
										<p>I certify that I am at least 16 years of age
										   or have approval of the clan admins.</p>
										<div class="confirm-checkbox">
											<input type="checkbox" id="age-agree" name="age-agree" class="purple" onClick="displayJoinLink()">
											<label for="age-agree" class="purple"></label>
										</div>
									</div>
									<div class="switch-wrap d-flex justify-content-between">
										<p>I understand that if I don't play at least once every 30 days, I am eligible for removal from the clan.</p>
										<div class="confirm-checkbox">
											<input type="checkbox" id="activity-agree" name="activity-agree" class="blue" onClick="displayJoinLink()">
											<label for="activity-agree" class="blue"></label>
										</div>
									</div>
									<div class="switch-wrap d-flex justify-content-between">
										<p>I certify that have read and agree to the <a href="rules.php">Clan Policies &amp; Procedures</a>.</p>
										<div class="confirm-checkbox">
											<input type="checkbox" id="rules-agree" name="rules-agree" class="purple" onClick="displayJoinLink()">
											<label for="rules-agree" class="purple"></label>
										</div>
									</div>
									<div class="switch-wrap d-flex justify-content-between">
										<p>I certify that I have joined <a href="https://discord.gg/APGCjeg">the clan Discord</a> and will be an active contributor to the community.</p>
										<div class="confirm-checkbox">
											<input type="checkbox" id="discord-agree" name="discord-agree" class="blue" onClick="displayJoinLink()">
											<label for="discord-agree" class="blue"></label>
										</div>
									</div>
									<!-- <div class="mt-10">
										In-Game Username: <?php /* if ($platform == "4") { echo("(please include the numeric portion)"); } */?><br>
										<input type="text" name="player_name" placeholder="Player Name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Player Name'" required class="single-input">
									</div>	-->			
			</form>
			<div class="button-group-area mt-10">		
			<a href="<?php echo($platformURL); ?>"><div id="join-button" class="genric-btn primary-border circle" style="display:none">Click to go to clan join page</div></a>
			</div>
			<?php endif; ?>
			<?php if ($platform == 1): ?>
			<!--<div class="whole-wrap">
				<div class="container" align="center">
					<div class="col-sm-6" align="justify"><h4 class="warning-box">
				At this time, we do not have an active XBox clan.  If you would be interested in helping Solarian deploy an XBox clan (utilising our community, website, services, tournaments, challenges, etc.), contact our <a href="mailto:admin@solarian.net">admin team</a>!</h4>
				</div>
				</div>
				</div> -->
			<?php endif; ?>
			

				</div>
		</div>
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
		
			<script language="javascript">
			function displayJoinLink() {
  				// Get the checkboxes
  				var checkBox1 = document.getElementById("age-agree");
				var checkBox2 = document.getElementById("activity-agree");
				var checkBox3 = document.getElementById("rules-agree");
				var checkBox4 = document.getElementById("discord-agree");
  				// Get the output text
  				var linkButton = document.getElementById("join-button");

				// If the checkbox is checked, display the output text
  				if ((checkBox1.checked == true) && (checkBox2.checked == true) && (checkBox3.checked == true) && (checkBox4.checked == true)){
					linkButton.style.display = "block";
  				} else {
					linkButton.style.display = "none";
  				}
				}
			</script>
		
		</body>
	</html>
