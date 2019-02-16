	<?php require_once('services/solarian.php'); ?><!DOCTYPE html>
	<html lang="en-uk" class="no-js" prefix="og: http://ogp.me/ns#">
		<?php pageHeader(DATA_PATH."meta.json"); ?>
		<body>
			 <?php menubar(); ?>
			<!-- start banner Area -->
			<section class="banner-area relative" id="home">
				<div class="overlay overlay-bg"></div>	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-center">
						<div class="banner-content col-lg-8">
							<h1 class="text-white">
								Solarian Clan				
							</h1>
							<p class="pt-20 pb-20 text-white">
								A group of casual &mdash; but very active &mdash; adults who play Destiny
								and Destiny 2.  We're a friendly bunch, always looking for more Guardians to
								rally to our banner!
							</p>
						</div>											
					</div>
				</div>					
			</section>
			<!-- End banner Area -->
			<!--<section class="wtfix-banner">
				<div class="container-fluid">
					<div class="row d-flex justify-content-center">
							<div class="wtfix-banner inner" align="center">
							<img src="img/wtfix.png" style="max-height: 75px; max-width: 75px; min-width: 75px; min-height: 75px;"> 
							<a href="https://wherethefuckisxur.com/">Solarian is the official clan of wherethefuckxur.com</a>.
							</div>
					</div>
				</div>
			</section>-->
			<!-- Start feature Area -->
			<section class="feature-area pb-100">
				<div class="container-fluid">
					<!-- <div class="row mockup-container">
						<img class="mx-auto d-block img-fluid" src="img/laptop.png" alt="">
					</div> -->
					<div class="row d-flex justify-content-center">
						<div class="menu-content pt-100 pb-60 col-lg-10">
							<div class="title text-center" id="about">
								<h1 class="mb-10">Engage with the Community</h1>
								<p>Solarian Clan is inclusive community of adult Guardians founded on principle that as a game, Destiny should be enjoyable and accessible for all our members.  We are respectful, laid-back, honest, considerate, patient, and helpful players who constantly work together to foster a sense of support and encouragement with the organisation and other players who join us.  We seek to recruit active players to our clan by hosting tournaments, challenges, and clan events on a regular basis.  We ask that our membership be engaged with the game and with each other, but otherwise, just have fun!</p>
							</div>
						</div>
					</div>						
					<div class="row">
						<div class="col-lg-4">
							<div class="single-feature">
								<span class="fab fab-discord"></span>
								<h4>
									<a href="https://solarian.net/discord">Clan Chat</a>
								</h4>
								<p class="text-description">
									Stay in contact with other members of the community, schedule games, or just banter using the Discord messaging app. Our Discord guild is open to any members of the community, not just clan members!
								</p>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="single-feature">
								<span class="bng bng-clanbanner"></span>
								<h4>
									<a href="tournaments.php">Tournaments</a>
								</h4>
								<p class="text-description">
									We host seasonal player vs. player tournaments for our clan and larger community members.  These private matches vary in style, but the winners receive special clan recognition and in-game prizes!
								</p>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="single-feature">
								<span class="bng bng-guidedgames"></span>
								<h4>
									Mentoring
								</h4>
								<p class="text-description">
									Our clan features Captains who ensure we earn all possible rewards each week, as well as guides who teach high-level strategy and tactics for endgame activities such as raids, crucible, and Gambit.
								</p>
							</div>
						</div>												
					</div>
				</div>	
			</section>
			<!-- End feature Area -->
			
			<!-- Start about Area -->
			<section class="about-area section-gap">
				<div class="container">
					<div class="row d-flex justify-content-center">
						<div class="menu-content pb-60 col-lg-10">
							<div class="title text-center">
								<h1 class="mb-10">Multiple Platforms, One Community</h1>
								<p>Our clan spans several in-game clans on PC, XBox, and PS4</p>
							</div>
						</div>
					</div>						
					<div class="row align-items-center">
						<div class="col-lg-6 about-left">
							<h6>Active Guardians always welcome</h6>
							<h1>
								Come join a clan <br>
								that plays together!
							</h1>
							<p>
								<span>
									If you play a lot of Destiny and are looking for a community of likeminded Guardians to adventure with, join us
								</span>
							</p>
							<p class="text-description">
								Our clan has been active in-game since 2014, with more and more members contributing to our ever-growing community.  Even with this amazing growth, we're still just a friendly, welcoming group of Guardians &mdash; always open to new members.
							</p>
							<a class="primary-btn" href="join.php">Apply to Join Our Clan</a>
						</div>
						<div class="col-lg-6 about-right">
							<div class="active-about-carusel">
								<div class="single-carusel item">
									<img class="img-fluid" src="img/about-1.png" alt="">
								</div>
								<div class="single-carusel item">
									<img class="img-fluid" src="img/about-2.png" alt="">
								</div>
								<div class="single-carusel item">
									<img class="img-fluid" src="img/about-3.png" alt="">
								</div>																
							</div>
						</div>
					</div>
				</div>	
			</section>
			<!-- End about Area -->
			
			<!-- Start service Area -->
			<section class="service-area section-gap" id="news">
				<div class="container">
					<div class="row d-flex justify-content-center">
						<div class="menu-content pb-60 col-lg-10">
							<div class="title text-center">
								<h1 class="mb-10">Latest Clan Updates</h1>
								<p>The most recent clan news and announcements</p>
							</div>
						</div>
					</div>					
					<div class="row">
						
							<?php newsBlock(DATA_PATH.'news.json'); ?>														
												
					</div>
				</div>	
			</section>
			<!-- End service Area -->
				

			<!-- Start callto-action Area -->
			<section class="callto-action-area relative section-gap">
				<div class="overlay overlay-bg"></div>	
				<div class="container">
					<div class="row d-flex justify-content-center">
						<div class="menu-content col-lg-9">
							<div class="title text-center">
								<h1 class="mb-10 text-white">Find out more about our community</h1>
								<p class="text-white">If you'd like to consider joining Solarian, please take a moment to review our Policies &amp; Procedures and to look over our Member's Agreement!  While everything sounds very formal, we're actually very laidback if you simply participate in the community.</p>
								<a class="primary-btn" href="rules.php">Polices &amp; Procedures</a>
							</div>
						</div>
					</div>	
				</div>	
			</section>
			<!-- End calto-action Area -->
			

			<!-- Start home-video Area -->
			<section class="home-video-area" id="updates">
				<div class="container-fluid">
					<div class="row justify-content-end align-items-center">
						<div class="col-lg-4 no-padding video-right">
							<p class="top-title">Introduction Video</p>
							<h1>New Members<br />
							Video</h1>
							<p><span>A little more about our community, culture, and rules</span></p>
							<p>
								This short video is an introduction to our community, policies, procedures, and mission.  It is intended as a friendly overview of our what we expect from members and what members can expect from us!
							</p>
						</div>
						<section class="video-area col-lg-6">
							<div class="overlay overlay-bg"></div>
							<div class="container">
								<div class="video-content">
									<a href="https://www.youtube.com/watch?v=IoYmWJMkQGo" class="play-btn"><img src="img/play-btn.png" alt=""></a>
								</div>
							</div>
						</section>											
					</div>
				</div>	
			</section>
			<!-- End home-aboutus Area -->
			<section class="home-video-area">
				<div class="container-fluid">
					<div class="row justify-content-end align-items-center">
						<div class="col-lg-4 no-padding video-right">
							<p class="top-title">Latest Update Video</p>
							<h1>Season Four End-of-Season<br />Update &amp; Leaderboards</h1>
							<p><span>Grab your quarters and head to Solarian arcade</span></p>
							<p>
								Our End-of-Season annoucements, clan updates, latest stats, and member leaderboards!  We took a fresh approach to the format that should be familiar to any fans of vintage arcade gaming...
							</p>
						</div>
						<section class="video-area2 col-lg-6">
							<div class="overlay overlay-bg"></div>
							<div class="container">
								<div class="video-content">
									<a href="https://www.youtube.com/watch?v=hfG_Gs7ObLI" class="play-btn"><img src="img/play-btn.png" alt=""></a>
								</div>
							</div>
						</section>											
					</div>
				</div>	
			</section>

			<!-- Start home-aboutus Area -->
<!--			<section class="home-aboutus-area">
				<div class="container-fluid">
					<div class="row justify-content-center align-items-center">
						<div class="col-lg-8 no-padding about-left relative">
							<div class="overlay overlay-bg"></div>	
							<div class="container">
								<div class="video-content2">
									<a href="https://www.youtube.com/watch?v=IoYmWJMkQGo" class="play-btn"><img src="img/play-btn.png" alt=""></a>
								</div>
							</div>
						</div>
						<div class="col-lg-4 no-padding about-right">
							<p class="top-title">Latest Update Video</p>
							<h1>Season Four End-of-Season<br />Update &amp; Leaderboards</h1>
							<p><span>Grab your quarters and head to Solarian arcade</span></p>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore. magna aliqua. Ut enim ad minim. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore. magna aliqua. Ut enim ad minim.
							</p>
						</div>
					</div>
				</div>	
			</section>  
-->			<!-- End home-aboutus Area -->			
		
			<!-- Start price Area
			<section class="price-area section-gap">
				<div class="container">
					<div class="row d-flex justify-content-center">
						<div class="menu-content pb-60 col-lg-8">
							<div class="title text-center">
								<h1 class="mb-10">Choose the best pricing for you</h1>
								<p>Who are in extremely love with eco friendly system.</p>
							</div>
						</div>
					</div>						
					<div class="row">
						<div class="col-lg-4">
							<div class="single-price no-padding">
								<div class="price-top">
									<h4>Real basic</h4>
								</div>
								<ul class="lists">
									<li>2.5 GB Space</li>
									<li>Secure Online Transfer</li>
									<li>Unlimited Styles</li>
									<li>Customer Service</li>
								</ul>
								<div class="price-bottom">
									<div class="price-wrap d-flex flex-row justify-content-center">
										<span class="price">$</span><h1> 39 </h1><span class="time">Per <br> Month</span>
									</div>
									<a href="#" class="primary-btn header-btn">Get Started</a>
								</div>
								
							</div>
						</div>
						<div class="col-lg-4">
							<div class="single-price no-padding">
								<div class="price-top">
									<h4>Real Standred</h4>
								</div>
								<ul class="lists">
									<li>2.5 GB Space</li>
									<li>Secure Online Transfer</li>
									<li>Unlimited Styles</li>
									<li>Customer Service</li>
								</ul>
								<div class="price-bottom">
									<div class="price-wrap d-flex flex-row justify-content-center">
										<span class="price">$</span><h1> 69 </h1><span class="time">Per <br> Month</span>
									</div>
									<a href="#" class="primary-btn header-btn">Get Started</a>
								</div>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="single-price no-padding">
								<div class="price-top">
									<h4>Real Ultimate</h4>
								</div>
								<ul class="lists">
									<li>2.5 GB Space</li>
									<li>Secure Online Transfer</li>
									<li>Unlimited Styles</li>
									<li>Customer Service</li>
								</ul>
								<div class="price-bottom">
									<div class="price-wrap d-flex flex-row justify-content-center">
										<span class="price">$</span><h1> 99 </h1><span class="time">Per <br> Month</span>
									</div>
									<a href="#" class="primary-btn header-btn">Get Started</a>
								</div>
							</div>				
						</div>								
					</div>
				</div>	
			</section>
		     End price Area -->

			<!-- Start testimonial Area -->
			<section class="testimonial-area relative section-gap" id="admins">
				<div class="container">
					<div class="row d-flex justify-content-center">
						<div class="menu-content pb-60 col-lg-8">
							<div class="title text-center">
								<h1 class="mb-10">Community Leadership</h1>
								<p>We're here to serve our Guardians and adminster the clans</p>
							</div>
						</div>
					</div>						
					<div class="row">
						<div class="active-testimonial">
					<?php leadershipBlock(DATA_PATH.'leadership.json'); ?>
						</div>
					</div>
				</div>	
			</section>
			<!-- End testimonial Area -->
			
			
			<!-- Start latest-blog Area -->
<!--			<section class="latest-blog-area section-gap" id="blog">
				<div class="container">
					<div class="row d-flex justify-content-center">
						<div class="menu-content pb-60 col-lg-8">
							<div class="title text-center">
								<h1 class="mb-10">Latest News from our Blog</h1>
								<p>Who are in extremely love with eco friendly system.</p>
							</div>
						</div>
					</div>					
					<div class="row">
						<div class="col-lg-6 single-blog">
							<img class="img-fluid" src="img/b1.png" alt="">
							<ul class="tags">
								<li><a href="#">Travel</a></li>
								<li><a href="#">Life style</a></li>
							</ul>
							<a href="#"><h4>Portable latest Fashion for young women</h4></a>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore  et dolore.
							</p>
							<p class="post-date">31st January, 2018</p>
						</div>
						<div class="col-lg-6 single-blog">
							<img class="img-fluid" src="img/b2.png" alt="">
							<ul class="tags">
								<li><a href="#">Travel</a></li>
								<li><a href="#">Life style</a></li>
							</ul>
							<a href="#"><h4>Portable latest Fashion for young women</h4></a>
							<p>
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore  et dolore.
							</p>
							<p class="post-date">31st January, 2018</p>
						</div>						
					</div>
				</div>	
			</section>
-->
			<!-- End latest-blog Area -->
			

	<?php pageFooter(); ?>	
	<?php javaScripts(); ?>

		</body>
</html>



