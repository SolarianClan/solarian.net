<?php require_once('services/solarian.php'); ?><!DOCTYPE html>
	<html lang="en-uk" class="no-js" prefix="og: http://ogp.me/ns#">
	<?php pageHeader("meta.json"); ?>
		<body>
			 <?php menubar(); ?>
		  <!-- start banner Area -->
			<section class="banner-area relative" id="home">
				<div class="overlay overlay-bg"></div>	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-center align-middle">
						<div class="banner-content col-lg-8">
							<h1 class="text-white">Using Our Clan Discord</h1>
								<span class="fab bng-header fab-discord"></span>
								<p class="text-white">Our clan's Discord server has many amazing and useful features.  Here's how to use them properly and take full advantage of their services.</p>
							</p>
						</div>											
					</div>
				</div>					
			</section>		
			<!-- Start Align Area -->
			
			<div class="whole-wrap">
				<div class="container">
					<div class="section-top-border">
						<h3 class="mb-30">About our clan's Discord</h3>
						<div class="row">
							<div class="col-md-5">
								<iframe src="https://discordapp.com/widget?id=226205496149934081&theme=light" width="350" height="800" allowtransparency="true" frameborder=0></iframe>
								<!--<iframe src="https://titanembeds.com/embed/226205496149934081?defaultchannel=373865192322236416&lang=en_UK&scrollbartheme=rounded-dots&theme=DiscordDark&username=Solarian%20Guest" height="500" width="350" frameborder="0"></iframe>-->
							</div>
							<div class="col-md-7 mt-sm-20">
								<p align="justify">Our clan's Discord server is our primary method of communinication and for arranging game sessions.  It is open to not only all our clan members across all three platforms, but also to the larger community.</p>
								<p align="justify">Our server is broken up into various channels by topic and platform.  Here is a list of all the key channels and what they are intended to be used for:</p>
								<ul class="unordered-list">
									<li><b>#announcements</b><br />
									This channel is used by the admin team to push clan announcments and used by the bots to share game updates, such as reset information.  Regular users can only read this channel, not speak in it.</li>
									<li><b>#general</b><br />
									This is our general chat channel for users on all platforms. Here are the rules for this channel:
										<ul class="unordered-list">
											<li><span>Images, links, and GIFs are prohibited</span></li>
											<li><span>Looking for fireteam members is not permitted</span></li>
											<li><span>Advertising your stream, Discord, website, or other personal project is not permitted without prior approval from an admin</span></li>
										</ul>
									</li>
									<li><b>#stats</b><br />
									This is our channel for users to issue bot commands and pull statistics. While we don't block the bots from responding to commands in any channel (so that stats and bots can be used, when appropriate, in the context of a conversation), we ask that users restrict the bulk of their interaction with the bots to this channel.
									</li>
									<li><b>#rules-and-policies</b><br />
									This channel outlines our clan rules, as well as some basic helpful hints for using our Discord.  This is a read-only channel.
									</li>
									<li><b>#memes</b><br />
									This channel is for posting memes related to Destiny, our clan, the community, or a conversation with a clanmate.  It also tends to be used for posting animal and kid pics.  <i>This channel runs in slowmode, meaning users can only send one message every 90 seconds.</i>
									</li>
									<li><b>#nsfw</b><br />
									The <b>n</b>ot-<b>s</b>afe-<b>f</b>or-<b>w</b>ork channel is intended for members to use when they are going to swear excessively or posting something Destiny-related, but potentially offensive.  This channel is <B>NOT</B> exempt from the decency rules, they're just more relaxed here.
									</li>
									<li><b>#other-games</b><br />
									This channel is intended for the discussion of games besides Destiny and Destiny 2.
									</li>
									<li><b>#welcome</b><br />
									This is the channel that new users arrive in. It is intended for them to register and self-select their platform.  Once this is done, this channel should not be used again.
									</li>
									<hr class="rule">
									<li><b>#fireteam-text-chat</b><br />
									This channel is intended for use by fireteams not using voice comms to communicate.  Rather than clutter up <b>#general</b>, fireteams should move their chat to this channel, located just above the voice channels.  <b>Most users will want to mute this channel, so that fireteams do not disturb you with their conversations.</b>
									</li>
									<hr class="rule">
									<li><b>#spoilers</b>
									The <b>#spoilers</b> channel is intended for the discussion of any Destiny story, quest, mission, or lore details that may ruin another player's experience if shared before they experience it.  <b>Any spoilers posted in any other channel will be deleted and the user will be warned.</b>
									</li>
									<li><b>#lore</b><br />
									This channel is for discussion of lore theories, asking lore-related questions, reflecting on the past story (anything not currently under a <b>#spoilers</b> embargo), and otherwise talking about the rich backstory of the Destiny universe.
									</li>
									<li><b>#destiny-videos</b><br />
									This channel is for posting videos and video links to Destiny-related content.  This includes Destiny Twitch streams, Destiny YouTube content creators, and clan member gameplay streams and videos.
									</li>
									<li><b>#destiny-artwork</b><br />
									This channel is for posting Destiny-related artwork, including (but not limited to) art produced by Bungie™ artists, original third-party art (a.k.a. fanart), and original artwork created by clan members.
									</li>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h3 class="text-heading">Bot Commands</h3>
							  <p class="sample-text">
										Our Discord relies on several bots to help with the smooth running of chat, as well as providing key services to our community.  <br><br>
										<b>UNLESS OTHERWISE NOTED, THESE COMMANDS SHOULD BE RUN IN THE #STATS CHANNEL!</b><br>
										Here is a rundown of how to interact with the bots in the most effective and useful ways:
									</p>
									<ul>
										<li><b>?register</b><br />
											This command registers players with the stats bot, syncs a player's in-game username with their Discord username, adds you to the community leaderboards, and assigns you the correct roles based on your playstyle.  This command <b>must</b> be executed before any others will work.
									  </li>
										<li><b>?pvp</b><br />
											Gives a summary of a player's PvP (Crucible) statistics.
										</li>
							  </ul>
							</div>
						</div>
					</div>
				</div>
		</div>
		
			<!-- End Generic Start -->		

			<?php pageFooter(); ?>	
			<?php javaScripts(); ?>
		</body>
	</html>
