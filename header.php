	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-132047846-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-132047846-1');
		</script>
		<?php
			switch (basename($_SERVER['PHP_SELF'])) {
				case "index.php":
					$pageTitle = "Home";
					$pageKeywords = "";
					break;
				case "rules.php":
					$pageTitle = "Policies & Procedures";
					$pageKeywords = ",polices,procedures,rules";
					break;
				case "tournaments.php":
					$pageTitle = "Tournaments";
					$pageKeywords = ",Breakthrough the Lines,Equinox of Legends,A Gunslinger Rises,Outlaws Rising";
					break;
				case "join.php":
					$pageTitle = "Join Our Clan";
					$pageKeywords = ",join,register,apply,signup,sign-up";
					break;
				case "roster.php":
					$pageTitle = "Clan Roster";
					$pageKeywords = "";
					break;
				case "singlePlayerProfile.php":
					$pageTitle = "Player Profile";
					$pageKeywords = "";
					break;
				case "discord.php":
					$pageTitle = "Using Our Clan Discord";
					$pageKeywords = ",discord,bots,Charlemagne,Rahool,Solaria";
					break;
				case "outlaws-rising.php":
					$pageTitle = "Outlaws Rising";
					$pageKeywords = ",Outlaws Rising";
					break;
				case "leaderboards.php":
					$pageTitle = "Leaderboards";
					$pageKeywords = ",rankings,stats,leaderboards";
					break;
				case "challenge.php":
					$pageTitle = "Challenges";
					$pageKeywords = ",challenge,challenges,pve,strikes";
					break;
				case "404.php":
					$pageTitle = "Not Found";
					$pageKeywords = ",error,404,not found";
					break;
				default:
					$pageTitle = ucfirst(strstr(basename($_SERVER['PHP_SELF']),'.',true));
					$pageKeywords = "";
					break;
			} ?>
		<!-- Mobile Specific Meta -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Favicon-->
		<link rel="shortcut icon" href="img/favicon.png">
		<!-- Author Meta -->
		<meta name="author" content="Solarian Clan">
		<!-- Meta Description -->
		<meta name="description" content="Solarian Clan, a home for casual but active Guardians">
		<!-- Meta Keyword -->
		<meta name="keywords" content="Solarian,Clan,Destiny,Destiny 2,soren42,tracon22s,kevdawg,Freak-0,NotDisliked,Bungie,PS4,PSN,PC,BattleNet,stats,Guardians,Tournament,challenges,Ghost,game,Activision,player<?php
						echo($pageKeywords);			   
									   ?>">
		<!-- meta character set -->
		<meta charset="UTF-8">
		<!-- Site Title -->
		<title>Solarian Clan :: <?php
			echo($pageTitle);
			?></title>

		<!-- No Caching -->
		<meta http-equiv="Cache-Control" content="no-store" />
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
		</head>
