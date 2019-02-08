<?php
		require_once("services/destiny.php");

        $leaders = json_decode(file_get_contents("leadership.json"), TRUE);

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
			print_r('		<b>Triumph Score:</b> '.triumphScoreByName(rawurldecode($leaders[$leader]["username"])).PHP_EOL);
//			print file_get_contents("http://solarian.net/triumphscorebyname.php?name=".rawurlencode($leaders[$leader]["username"]));
			print_r('		</div>'.PHP_EOL);
			print_r('	</div>'.PHP_EOL);
			print_r('</div>'.PHP_EOL);
			}
	
?>