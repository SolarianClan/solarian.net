	<!DOCTYPE html>
	<html lang="en-uk" class="no-js">
	<?php require_once("header.php"); ?>
		<body>

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
							<h1 class="text-white">Community Crucible Tournaments</h1>
								<span class="bng-header bng-clanbanner"></span>
								<p class="text-white">Solarian hosts seasonal player versus player tournaments for all the members of our community.  The format and rules of these competitions changes from season to season, but all pit our members in fun, light-hearted contest against each other in private Crucible matches.  Tournament winners receive in-game prizes such as emblems and emotes, as well as unique titles featured in our Discord and eternal glory!</p>
							</p>
						</div>											
					</div>
				</div>					
			</section>		
			<!-- Start Align Area -->
			<div class="whole-wrap">
				<div class="container">
					<!-- Begin Tournaments -->
					<div class="section-top-border">
						<!-- Outlaws Rising -->
						<h3 class="mb-30"><a href="outlaws-rising.php">Outlaws Rising</a></h3>
						<div class="row">
							<div class="col-md-3">
								<img src="img/outlaws-rising.png" alt="" class="img-fluid">
							</div>
							<div class="col-md-9 mt-sm-20">
								<p>Solarian's Fall/Winter PvP Tournament, Outlaws Rising, runs from November 2018 through February 2019.  This is the longest, most ambitious, and most fun tournament we've ever held.  We're holding one scheduled match and multiple ad hoc matches each week match each week to allow schedule flexibility.  Competitors earn points by finishing in the top five scoring players in the week's Rumble matches. Loadouts, maps, and scoring vary from week to week. Good luck to all the competitors!</p>
							</div>
						</div>
						<div><br /></div>
					<!-- Breakthrough the Lines -->
						<h3 class="mb-30">Breakthrough the Lines</h3>
						<div class="row">
							<div class="col-md-9">
								<p class="text-right">In Solarian's Summer 2018 PvP Tournament, four player teams faced off, head-to-head, in anything-goes Breakthough!  The winning team then faced each other in a single round of bows-only Rumble!</p>
								<p class="text-right"><b>Winner:</b> <a href="singlePlayerProfile.php?username=BlaCKxNOVA430&platform=2">BlaCKxNOVA430</a><br />
								<b>Runners-Up:</b> <a href="singlePlayerProfile.php?username=Deadshot601&platform=2">Deadshot601</a><br /><a href="singlePlayerProfile.php?username=OsmiumKing">OsmiumKing</a></p>
							</div>
							<div class="col-md-3">
								<img src="img/breakthrough-the-lines.png" alt="" class="img-fluid">
							</div>
						</div>
						<div><br /></div>
					<!-- Equinox of Legends -->
						<h3 class="mb-30">Equinox of Legends</h3>
						<div class="row">
							<div class="col-md-3">
								<img src="img/equinox-of-legends.png" alt="" class="img-fluid">
							</div>
							<div class="col-md-9 mt-sm-20">
								<p style="padding-left: 10px;">Solarian's second PvP Tournament, Equinox of Legends, featured a series of one-on-one match-ups where competitors were forced to wield a curated loadout consisting of an exotic pulse rifle and a legendary auto rifle.  14 competitors faced off in this tournament.</p>
								<p style="padding-left: 10px;"><b>No winner was declared in this tournament due to elimination by forfeit in the final round.</b></p>
							</div>
						</div>
						<div><br /></div>
						<!-- A Gunslinger Rises -->
						<h3 class="mb-30">A Gunsliner Rises</h3>
						<div class="row">
							<div class="col-md-9">
								<p class="text-right">In Solarian's inaugural PvP Tournament, nine clan members met in the Crucible in a series of one-on-one head-to-head anything-goes matches!  This double elmination tournament ran for six weeks during June and July of 2018 to great community interest and acclaim.</p>
								<p class="text-right"><b>Winner:</b> <a href="singlePlayerProfile.php?username=Freak-0-&platform=2">Freak-0-</a><br />
								<b>Runner-Up:</b> <a href="singlePlayerProfile.php?username=the_pro_guys123&platform=2">the_pro_guys123</a></p>
							</div>
							<div class="col-md-3">
								<img src="img/a-gunslinger-rises.png" alt="" class="img-fluid">
							</div>
						</div>
						<div><br /></div>
					</div>
					<!-- End Tournaments -->
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
		</body>
	</html>
