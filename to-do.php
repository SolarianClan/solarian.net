<?php require_once('services/solarian.php'); ?><!DOCTYPE html>
	<html lang="en-uk" class="no-js" prefix="og: http://ogp.me/ns#">
	<?php pageHeader("meta.json"); ?>
		<body>
			 <?php menubar(); ?>
			<section class="todo-area relative" id="home">
				<div class="overlay overlay-bg"></div>	
				<div class="container">
					<div class="row d-flex align-items-center justify-content-center">
						<div class="banner-content col-lg-8">
							<br /><br />
							<h1 class="text-white">Clan To-Do List</h1>
								<span class="bng-header bng-loading"></span>
								<p class="text-white">Rough Draft of my current to-do list, in no particular order.</p>
							</p>
						</div>											
					</div>
				</div>					
			</section>		
			<!-- Start Align Area -->
			<div class="whole-wrap">
				<div class="container">
					<div class="section-top-border">
						<h1 id="solarian-clan-to-do-list">Solarian Clan To-Do List</h1>
							<ul class="unordered-list">
							<li>Website updates:
							<ul class="unordered-list">
							<li>About our Discord (How to Disco) :: discord.php
							<ul class="unordered-list">
							<li>Finish core channel descriptions</li>
							<li>Add helpful stats commands</li>
							</ul></li>
							<li>WTFIX Page :: wtfix.php</li>
							<li>Fa-Q page :: faq.php
							<ul class="unordered-list">
							<li>Clan Identity &amp; History</li>
							<li>Using the Discord</li>
							<li>Registering</li>
							<li>Leadership Structure</li>
							<li>Clan/Platform Structure</li>
							<li>Clan Polices &amp; Procedures</li>
							<li>Tournaments &amp; Challenges</li>
							<li>Recruiting</li>
							</ul></li>
							<li>Important Links &amp; Tools :: links.php
							<ul class="unordered-list">
							<li>Clan Links
							<ul class="unordered-list">
							<li>Twitter</li>
							<li>Facebook</li>
							<li>Instagram</li>
							<li>Subreddit</li>
							<li>PSN Community</li>
							<li>XBox Club</li>
							<li>BattleNet Group</li>
							</ul></li>
							<li>Important Tools
							<ul class="unordered-list">
							<li>DIM</li>
							<li>Braytech</li>
							<li>Lowlidev</li>
							<li>Destiny Sets</li>
							<li>WTFIX</li>
							<li>Destiny Tracker</li>
							<li>Guardian.GG</li>
							<li>Light.GG</li>
							<li>Raid.Report</li>
							<li>Trials.Report</li>
							<li>RaidDad</li>
							<li>DCW</li>
							<li>Guardian.Theatre</li>
							<li>Warmind.io</li>
							</ul></li>
							<li>Destiny Communities
							<ul class="unordered-list">
							<li>Subreddits
							<ul class="unordered-list">
							<li>/r/DtG</li>
							<li>/r/D2</li>
							<li>/r/RaidSecrets</li>
							<li>/r/CruciblePlaybook</li>
							<li>/r/DestinySherpa</li>
							<li>/r/DestinyFashion</li>
							<li>/r/ShardItKeepIt</li>
							<li>/r/DestinyLore</li>
							<li>/r/DestinyDadJokes</li>
							<li>/r/DestinyClanLeaders</li>
							<li>/r/DestinyPrivateMatches</li>
							<li>/r/DestinyItemManager</li>
							<li>/r/LowSodiumDestiny</li>
							<li>/r/TheCryptarchs</li>
							<li>/r/TheMountaintop</li>
							</ul></li>
							<li>Bungie Forums</li>
							<li>Bungie Employee Twitter List</li>
							<li>Community Creators Twitter List</li>
							<li>Bungie Artists Artstation Links</li>
							<li>Bungie Artists DeviantArt Links</li>
							</ul></li>
							</ul></li>
							<li>Clan Videos (organised by subject, then date?) :: videos.php</li>
							<li>Update clan homepage :: index.php
							<ul class="unordered-list">
							<li>Create function to generate leader profiles</li>
							<li>Create JSON file with leader profiles</li>
							</ul></li>
							<li>Clan Roster (PHP/API) :: roster.php</li>
							<li>Clan Player Profile Page (PHP/API) :: player.php</li>
							<li>Clan Leaderboards (PHP/API) :: leaderboards.php</li>
							<li>Join Our Clan (On-site Joins &mdash; PHP/API/OAuth) :: join.php</li>
							<li>Clan Tournament Engine (MAJOR CODING!) :: /tournaments</li>
							<li>Clan Challenges Engine (merge w/ Tournaments?)</li>
							<li>Create or deploy clan survey engine on-site</li>
							<li>Privacy Policy :: privacy.php &amp; privacy.xml</li>
							<li>Accessibility Policy</li>
							</ul></li>
							<li>Post weekly in Reddit recruiting thread</li>
							<li>Video creation and updates:
							<ul class="unordered-list">
							<li>Revise New Member's Video</li>
							<li>add pinned vid to #welcome on how to register and what to mute
							<ul class="unordered-list">
							<li>Create both desktop &amp; mobile clips</li>
							</ul></li>
							<li>add how-to #stats vid</li>
							<li>Final Recruiting Campaign Update</li>
							<li>Start prepping videos for:
							<ul class="unordered-list">
							<li>Clan-wide PvP Tournament (name?)</li>
							<li>End-of-Season Update</li>
							</ul></li>
							</ul></li>
							<li>Develop membership maintenance scripts
							<ul class="unordered-list">
							<li>Auto-notify on new joins for approvals</li>
							<li>Monitor Beginners
							<ul class="unordered-list">
							<li>Notify on Day 10 that they need to convert</li>
							<li>Auto-kick on Day 15 (keep JSON exemption list)</li>
							<li>Auto-promote if they complete an activity with an existing clan member</li>
							</ul></li>
							<li>Monitor Members
							<ul class="unordered-list">
							<li>Notify on Day 30 if haveb't completed an activity</li>
							<li>Auto-kick on Day 45 if they haven't played (keep JSON exemption list)</li>
							</ul></li>
							</ul></li>
							<li>Recruit for two new clan-wide types of Captain/Guide Roles:
							<ul class="unordered-list">
							<li>Social Media
							<ul class="unordered-list">
							<li>Social Media Captain
							<ul class="unordered-list">
							<li>Helps maintains clan social media accounts, promotes clan activities on our social media accounts, and promotes our social media presence to members</li>
							</ul></li>
							<li>Per-channel "Guide"(?)
							<ul class="unordered-list">
							<li>e.g., Facebook Guide, Tweeter Guide, InstaGuide, etc.</li>
							<li>These roles are <em>maybes</em>...</li>
							</ul></li>
							</ul></li>
							<li>Tournament
							<ul class="unordered-list">
							<li>Tournament Captain
							<ul class="unordered-list">
							<li>Manages clan PvP Tournaments from soup to nuts &mdash;  promotion, registration, arrangement, and execution, across all platforms</li>
							</ul></li>
							<li>Per-platform Tournament "Guides" (?)
							<ul class="unordered-list">
							<li>Assist Captain with planning and executing PvP tournament on each platform</li>
							</ul></li>
							</ul></li>
							</ul></li>
							</ul>
						</div>
					</div>
			</div>
			<!-- End Generic Start -->		

			<?php pageFooter(); ?>	
			<?php javaScripts(); ?>
		</body>
	</html>
