<?php

/**
 * Library tournament.php
 * Tournament management functions for SolariaCore Libraries
 *
 * Requires: destiny.php
 *
 */

# define installation path for all root directory of data, services, and more.
defined('INSTALLATION_PATH') or define("INSTALLATION_PATH", '/var/www/solarian.net/');
# define path to data files if not already defined
defined('DATA_PATH') or define("DATA_PATH", INSTALLATION_PATH."data/");
# define path to library files if not already defined
defined('SERVICE_PATH') or define('SERVICE_PATH', INSTALLATION_PATH."services/");

require_once(SERVICE_PATH . "solarian.php");

	$tourneyConfigFilename = DATA_PATH . 'tournament.cfg';
	$tourneyConfig = json_decode(file_get_contents($tourneyConfigFilename), TRUE);
	$c = base64_decode($tourneyConfig['password']);
	$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$tourneyConfigPassword = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
	//$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
	
	define('TOURNEY_DB_HOST', $tourneyConfig['host']);
	define('TOURNEY_DB_USER', $tourneyConfig['username']);	
	define('TOURNEY_DB_PASSWORD', $tourneyConfigPassword);
	define('TOURNEY_DB_NAME', $tourneyConfig['dbname']);
	
function tournament_connect() {
	
	$link = mysqli_connect(TOURNEY_DB_HOST, TOURNEY_DB_USER, TOURNEY_DB_PASSWORD, TOURNEY_DB_NAME);
	
	if (!$link) {
    	echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    	exit;
	}
	
	return $link;
	
} // end tournament_connect

function tournament_close($link) {
	
	return mysqli_close($link);
	
} // end tournament_close

function getScheduleIdByWeekAndMatch($week = 1, $match = 1) {
	
	$tournamentDBHandle = tournament_connect();
	
	$scheduleQuery = "SELECT * FROM tournament.schedule sch WHERE tournament.sch.week=".$week." AND tournament.sch.match=".$match.";";
	
	$scheduleResult = mysqli_query($tournamentDBHandle, $scheduleQuery);
	
	$thisSchedule = $scheduleResult->fetch_all(MYSQLI_ASSOC);
	
	$thisId = $thisSchedule[0]["id"];
	
	tournament_close($tournamentDBHandle);
	
	return($thisId);
	
} // end function getScheduleIdByWeekAndMatch

function getSignupDataByScheduleAndUser($scheduleId = 1, $userId = 1) {
	
	$tournamentDBHandle = tournament_connect();
	
	$signupQuery = "SELECT * FROM tournament.signup su WHERE tournament.su.scheduleid=".$scheduleId." AND tournament.su.userid=".$userId.";";
	
	$signupResult = mysqli_query($tournamentDBHandle, $signupQuery);
	
	$thisSignup = $signupResult->fetch_all(MYSQLI_ASSOC);
	
	$thisSignupData = $thisSignup[0];
	
	tournament_close($tournamentDBHandle);
	
	return($thisSignupData);
	
} // end function getSignupDataByScheduleAndUser

function getMembershipIdByTournamentId($thisUserId = 1) {
	
	$tournamentDBHandle = tournament_connect();
	
	$membershipIdQuery = "SELECT tournament.players.memberid FROM tournament.players WHERE tournament.players.id=".$thisUserId.";";
	
	$membershipResult = mysqli_query($tournamentDBHandle, $membershipIdQuery);
	
	$thisUserData = $membershipResult->fetch_all(MYSQLI_ASSOC);
	
	$thisMembershipId = $thisUserData[0]["memberid"];
	
	tournament_close($tournamentDBHandle);
	
	return($thisMembershipId);
	
} // end function getMembershipIdByTournamentId

function getUsernameByTournamentId($thisUserId = 1) {
	
	$tournamentDBHandle = tournament_connect();
	
	$membershipIdQuery = "SELECT tournament.players.username FROM tournament.players WHERE tournament.players.id=".$thisUserId.";";
	
	$membershipResult = mysqli_query($tournamentDBHandle, $membershipIdQuery);
	
	$thisUserData = $membershipResult->fetch_all(MYSQLI_ASSOC);
	
	$thisUsername = $thisUserData[0]["username"];
	
	tournament_close($tournamentDBHandle);
	
	return($thisUsername);

} // end function getUsernameByTournamentId()

function displayPGCRData($matchData) {
	
	$playerCount = 0;
	
	$summaryData = array();
	
	foreach ($matchData["entries"] as $thisMatch=>$values) {
		
		$summaryData[$thisMatch]["playerName"] = $matchData["entries"][$thisMatch]["player"]["destinyUserInfo"]["displayName"];
		$summaryData[$thisMatch]["characterClass"] =  $matchData["entries"][$thisMatch]["player"]["characterClass"];
		$summaryData[$thisMatch]["score"] = $matchData["entries"][$thisMatch]["score"]["basic"]["value"];
		$playerCount += 1;
	}
	
	$tableHTMLTop = <<< HTML
	
	<table width=50% border=1 cellspacing=1 cellpadding=5>
		<tr>
			<td><b>Player</b></td>
			<td><b>Score</b></td>
		</tr>
HTML;
	
	print($tableHTMLTop);
	foreach ($summaryData as $thisEntry=>$value) {		
		echo "<tr>";
		echo "<td align = left>".$summaryData[$thisEntry]["playerName"]." (".$summaryData[$thisEntry]["characterClass"].")</td>";
		echo "<td align = right>".$summaryData[$thisEntry]["score"]."</td>";
		echo "</tr>";		
	}
	
	echo "</table>";
	
	return($playerCount);
	
} // end function displayPGCRData()

function performMatchInsert($thisInstanceId, $thisPlatform, $thisPlayerCount, $thisWeek) {
	
	$tournamentDBHandle = tournament_connect();
	
	$matchInsertQuery = "INSERT INTO tournament.matches VALUE (NULL, NOW(), '{$thisPlatform}', {$thisPlayerCount}, '{$thisInstanceId}', {$thisWeek} );";
	
	$insertResult = mysqli_query($tournamentDBHandle, $matchInsertQuery);
	
	tournament_close($tournamentDBHandle);
	
	return(true);
	
} // end function performMatchInsert()

function isMatchIsAlreadyRecorded($thisInstanceId) {
	
	$tournamentDBHandle = tournament_connect();
	
	$matchCheckQuery = "SELECT * FROM tournament.matches WHERE bid='{$thisInstanceId}';";
	
	$matchCheckResult = mysqli_query($tournamentDBHandle, $matchCheckQuery);
	
	tournament_close($tournamentDBHandle);
	
	if ($matchCheckQuery->num_rows > 0) {
		$retVal = true;
	} else {
		$retVal = false;
	}
	
	return($retVal);
	
} // end function isMatchIsAlreadyRecorded()

function addScoresToMatchScores($matchData) {
	
	$playerCount = 0;
	
	$summaryData = array();
	
	foreach ($matchData["entries"] as $thisMatch=>$values) {
		
		$summaryData[$thisMatch]["playerName"] = $matchData["entries"][$thisMatch]["player"]["destinyUserInfo"]["displayName"];
		$summaryData[$thisMatch]["characterClass"] =  $matchData["entries"][$thisMatch]["player"]["characterClass"];
		$summaryData[$thisMatch]["score"] = $matchData["entries"][$thisMatch]["score"]["basic"]["value"];
		$summaryData[$thisMatch]["efficiency"] = $matchData["entries"][$thisMatch]["values"]["efficiency"]["basic"]["value"];
		$playerCount += 1;
	}

	$tournamentDBHandle = tournament_connect();
	
	$thisInstanceId = $matchData["activityDetails"]["instanceId"];
	
	$matchCheckQuery = "SELECT * FROM tournament.matches WHERE bid='{$thisInstanceId}';";
	
	$matchCheckResult = mysqli_query($tournamentDBHandle, $matchCheckQuery);
	
	$matchIdResults = $matchCheckResult->fetch_all(MYSQLI_ASSOC);
	
	$thisMatchId = $matchIdResults[0]["id"];
	$thisMatchWeek = $matchIdResults[0]["weekid"];
	
	$matchCheckResult->close;
	
	foreach ($summaryData as $thisEntry=>$values) {
		
		$thisPlayerIdQuery = "SELECT * FROM tournament.players p WHERE tournament.p.username LIKE '{$summaryData[$thisEntry]["playerName"]}%';";
		
		$thisPlayerResult = mysqli_query($tournamentDBHandle, $thisPlayerIdQuery);
		
		$thisPlayerResults = $thisPlayerResult->fetch_all(MYSQLI_ASSOC);
		
		$thisPlayerId = $thisPlayerResults[0]["id"];
		
		//print("playerId: {$thisPlayerId} ");
		
		$thisPlayerResult->close;
		
		$thisInsertQuery = "INSERT INTO tournament.matchscores VALUES (NULL, '{$thisMatchId}', '{$thisPlayerId}', '{$summaryData[$thisEntry]["score"]}', '{$summaryData[$thisEntry]["efficiency"]}', {$thisMatchWeek});";
		
		//print("<BR>Insert query: {$thisInsertQuery}<BR>");
		
		$thisInsertResult = mysqli_query($tournamentDBHandle, $thisInsertQuery);
		
		$thisInsertResult->close;
	} // end foreach score, insert
	
} // end function addScoresToMatchScores()

function isMatchAlreadyRecorded($thisInstanceId) {
	$tournamentDBHandle = tournament_connect();
	
	$matchCheckQuery = "SELECT * FROM tournament.matches WHERE bid='{$thisInstanceId}';";
	
	$matchCheckResult = mysqli_query($tournamentDBHandle, $matchCheckQuery);
	
	$matchIdResults = $matchCheckResult->fetch_all(MYSQLI_ASSOC);
	
	$thisMatchId = $matchIdResults[0]["id"];
	
	$matchCheckResult->close;
	
	$recordedCheckQuery = "SELECT * FROM tournament.matchscores WHERE matchid='{$thisMatchId}';";
	
	$recordedCheckResults = mysqli_query($tournamentDBHandle, $recordedCheckQuery);
	
	tournament_close($tournamentDBHandle);
	
	if ($recordedCheckResults->num_rows > 0) {
		$retVal = true;
	} else {
		$retVal = false;
	}
	
	return($retVal);
	
} // end function isMatchAlreadyRecorded()

function getCurrentBracket() {
	
	$tournamentDBHandle = tournament_connect();
	
	$bracketQuery = "SELECT DISTINCT tournament.matchscores.weekid FROM tournament.matchscores ORDER BY tournament.matchscores.weekid DESC LIMIT 1;";
	
	$bracketResult = mysqli_query($tournamentDBHandle, $bracketQuery);
	
	$bracketResults = $bracketResult->fetch_all(MYSQLI_ASSOC);
	
	tournament_close($tournamentDBHandle);
	
	return($bracketResults[0]["weekid"]);
	
} // end function getCurrentBracket()

function getPreviousPointsLeaderScore($currentBracket = 1) {
	
	if ($currentBracket == 1) {
		
		$returnScore = 0;
		
	} else {
	
		$tournamentDBHandle = tournament_connect();

		$previousBracket = $currentBracket - 1;

		$previousBracketQuery = "SELECT * FROM tournament.pointsleaders pl WHERE tournament.pl.weekid={$previousBracket};";
		
		// print($previousBracketQuery);
		
		$previousBracketResult = mysqli_query($tournamentDBHandle, $previousBracketQuery);

		if ($previousBracketResult->num_rows == 1) {

			
			$previousBracketResults = $previousBracketResult->fetch_all(MYSQLI_ASSOC);
			$returnScore = $previousBracketResults[0]["points"];
			
		} else {

			print("ERROR: Incorrect points leader results returned, using 0.");
			$returnScore = 0;
			
		}
		
		tournament_close($tournamentDBHandle);
		
	}
	
	return($returnScore);

} // end function getPointLeaderScore()

function getScoringModel($currentBracket = 1) {
	
	$tournamentDBHandle = tournament_connect();
	
	$scoringModelQuery = "SELECT * FROM tournament.schedule s WHERE tournament.s.week={$currentBracket};";
	
	$scoringModelResult = mysqli_query($tournamentDBHandle, $scoringModelQuery);
	
	$interimResults = $scoringModelResult->fetch_all(MYSQLI_ASSOC);
	
	//print_r($interimResults);
	
	$modelArray = array();
	
	foreach ($interimResults as $thisResult=>$value) {
		
		$modelArray[$thisResult] = $interimResults[$thisResult]["scoring"];
		
	}
	
	$tempScoringModel = array_unique($modelArray);
	
	if (count($tempScoringModel) == 1) {
		
		$scoringModel = $tempScoringModel[0];
		
	} else {
		
		print("ERROR: Incorrect scoring model query detected, using STANDARD.");
		$scoringModel = "STANDARD";
		
	}
	
	tournament_close($tournamentDBHandle);
	
	return($scoringModel);
} // end function getScoringModel()

function createBracketLeaderboard($currentBracket, $scoringModel, $previousPointsLeaderScore) {
	
	$tournamentDBHandle = tournament_connect();
	
	$bracketQuery = "SELECT * FROM tournament.matchscores ms WHERE tournament.ms.weekid={$currentBracket} ORDER BY tournament.ms.score DESC, tournament.ms.efficiency DESC;";
	
	$tmpResult = mysqli_query($tournamentDBHandle, $bracketQuery);
	$bracketScores = $tmpResult->fetch_all(MYSQLI_ASSOC);
	
	$leaderboard = array();
	
	foreach ($bracketScores as $thisScore=>$value) {
		
		if (isset($leaderboard[$bracketScores[$thisScore]["playerid"]])) {
			
			if ($leaderboard[$bracketScores[$thisScore]["playerid"]]["score"] < $bracketScores[$thisScore]["score"]) {
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["score"] = $bracketScores[$thisScore]["score"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["efficiency"] = $bracketScores[$thisScore]["efficiency"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["playerid"] = $bracketScores[$thisScore]["playerid"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["matchid"] = $bracketScores[$thisScore]["matchid"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["weekid"] = $bracketScores[$thisScore]["weekid"];
			}
			
		} else {
			
			$leaderboard[$bracketScores[$thisScore]["playerid"]]["score"] = $bracketScores[$thisScore]["score"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["efficiency"] = $bracketScores[$thisScore]["efficiency"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["playerid"] = $bracketScores[$thisScore]["playerid"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["matchid"] = $bracketScores[$thisScore]["matchid"];
				$leaderboard[$bracketScores[$thisScore]["playerid"]]["weekid"] = $bracketScores[$thisScore]["weekid"];
			
		}
				
	}
	
	tournament_close($tournamentDBHandle);
	
	$unscoredLeaderboard = array_slice($leaderboard, 0, 10);
	
	$scoredLeaderboard = array();
	
	foreach ($unscoredLeaderboard as $rank=>$value) {
		
		$scoredLeaderboard[$rank]["rank"] = $rank + 1;
		$scoredLeaderboard[$rank]["playerid"] = $unscoredLeaderboard[$rank]["playerid"];
		$scoredLeaderboard[$rank]["score"] = $unscoredLeaderboard[$rank]["score"];
		$scoredLeaderboard[$rank]["efficiency"] = $unscoredLeaderboard[$rank]["efficiency"];
		$scoredLeaderboard[$rank]["matchid"] = $unscoredLeaderboard[$rank]["matchid"];
		$scoredLeaderboard[$rank]["weekid"] = $unscoredLeaderboard[$rank]["weekid"];
		$scoredLeaderboard[$rank]["points"] = calculatePlayerPoints($scoringModel, $previousPointsLeaderScore, $rank);
		
	}
	
	return($scoredLeaderboard);
	
} // end function createBracketLeaderboard()

function calculatePlayerPoints($scoringModel, $previousPointsLeaderScore, $playerRank) {
	
	$scoringModelData = json_decode(file_get_contents(DATA_PATH . "scoring.json"), TRUE);

	$thisScoringModel = $scoringModelData[$scoringModel];
	
	$thisScore = (((10-$playerRank) * $thisScoringModel["rankStepDown"] * $thisScoringModel["topScore"]) + 
				 ((10-$playerRank) * $thisScoringModel["catchupMultiplerStepdown"] * $previousPointsLeaderScore));
	
	$playerScore = (int) round($thisScore);
	
	return($playerScore);
	
} // end function calculatePlayerPoints()

function isBracketAlreadyInserted($currentBracket) {
	
	$tournamentDBHandle = tournament_connect();
	
	$bracketQuery = "SELECT * FROM tournament.bracket WHERE tournament.bracket.weekid={$currentBracket};";
	
	$bracketResult = mysqli_query($tournamentDBHandle, $bracketQuery);
	
	if ($bracketResult->num_rows > 0) {
		$retVal = true;
	} else {
		$retVal = false;
	}
	
	tournament_close($tournamentDBHandle);
	
	return($retVal);
	
} // end function isBracketAlreadyInserted()

function insertBracketLeaderboard($bracketLeaderboard) {
	
	$tournamentDBHandle = tournament_connect();
	
	foreach ($bracketLeaderboard as $rank=>$value) {
				
		$thisPlayerQuery = "INSERT INTO tournament.bracket VALUES (NULL, {$bracketLeaderboard[$rank]["weekid"]}, {$bracketLeaderboard[$rank]["playerid"]}, {$bracketLeaderboard[$rank]["matchid"]}, {$bracketLeaderboard[$rank]["score"]}, {$bracketLeaderboard[$rank]["efficiency"]}, '{$bracketLeaderboard[$rank]["rank"]}', {$bracketLeaderboard[$rank]["points"]});";
		
//		print($thisPlayerQuery . PHP_EOL);	
		
		$thisPlayerResult = mysqli_query($tournamentDBHandle, $thisPlayerQuery);
		
		}
	
	tournament_close($tournamentDBHandle);
	
} // end function insertBracketLeaderboard()

function addBracketToLeaderboard($bracketLeaderboard) {
	
	$tournamentDBHandle = tournament_connect();
	
	foreach ($bracketLeaderboard as $rank=>$value) {
	
		$checkIfPlayerOnLeaderboardQuery = "SELECT * FROM tournament.leaderboards l WHERE tournament.l.playerid={$bracketLeaderboard[$rank]["playerid"]};";
		
		$thisPlayerResult = mysqli_query($tournamentDBHandle, $checkIfPlayerOnLeaderboardQuery);
		
		if ($thisPlayerResult->num_rows == 1) {

			$thisPlayerResults = $thisPlayerResult->fetch_all(MYSQLI_ASSOC);
			$priorScore = $thisPlayerResults[0]["points"];
			$newScore = $priorScore + $bracketLeaderboard[$rank]["points"];

			$updateNewScoreQuery = "UPDATE tournament.leaderboards l SET tournament.l.points={$newScore} WHERE tournament.l.playerid={$thisPlayerResults[0]["playerid"]};";

			$updateNewScoreResult = mysqli_query($tournamentDBHandle, $updateNewScoreQuery);

		} else {

			$insertScoreQuery = "INSERT INTO tournament.leaderboards VALUES (NULL, {$bracketLeaderboard[$rank]["playerid"]}, {$bracketLeaderboard[$rank]["points"]});";

			$insertScoreResult = mysqli_query($tournamentDBHandle, $insertScoreQuery);

		}
		
	}
	
	tournament_close($tournamentDBHandle);
	
} // end function addBracketToLeaderboard()

function addLeaderToPointsLeaders($currentWeek = 1) {
	
	$tournamentDBHandle = tournament_connect();
	
	$pointsLeaderQuery = "SELECT * FROM tournament.leaderboards l ORDER BY tournament.l.points DESC LIMIT 1;";
	
	$pointsLeaderResult = mysqli_query($tournamentDBHandle, $pointsLeaderQuery);
	
	$pointsLeaderResults = $pointsLeaderResult->fetch_all(MYSQLI_ASSOC);
	
	$thisPointsLeaderId = $pointsLeaderResults[0]["playerid"];
	$thisPointsLeaderPoints = $pointsLeaderResults[0]["points"];
	
	$pointsLeaderInsertQuery = "INSERT INTO tournament.pointsleaders VALUES (null, {$thisPointsLeaderId}, {$currentWeek}, {$thisPointsLeaderPoints});";
	
	$pointsLeaderInsertResult = mysqli_query($tournamentDBHandle, $pointsLeaderInsertQuery);
	
	tournament_close($tournamentDBHandle);
	
} // end function addLeaderToPointsLeaders()

?>