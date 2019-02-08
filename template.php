<?php require_once('services/solarian.php'); ?><!DOCTYPE html>
	<html lang="en-uk" class="no-js" prefix="og: http://ogp.me/ns#">
	<?php pageHeader("meta.json"); ?>
		<body>
			 <?php menubar(); ?>
			<section class="banner-area relative" id="home">
				<div class="overlay overlay-bg"></div>	
				<div class="container">
					<div class="row fullscreen d-flex align-items-center justify-content-center">
						<div class="banner-content col-lg-8">
							<br /><br />
							<h1 class="text-white">Page Title</h1>
								<span class="bng-header bng-clanbanner"></span>
								<p class="text-white">Description</p>
							</p>
						</div>											
					</div>
				</div>					
			</section>		
			<!-- Start Align Area -->
			
		CONTENT
		
			<!-- End Generic Start -->		

			<?php pageFooter(); ?>	
			<?php javaScripts(); ?>
		</body>
	</html>
