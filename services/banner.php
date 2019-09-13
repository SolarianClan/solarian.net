<?php
/**
 * Library banner.php
 * Bungie's Destiny API Clan Banner Image functions for SolariaCore Libraries
 *
 */
# define installation path for all root directory of data, services, and more.
defined('INSTALLATION_PATH') or define("INSTALLATION_PATH", '/var/www/solarian.net/');
# define path to data files if not already defined
defined('DATA_PATH') or define("DATA_PATH", INSTALLATION_PATH."data/");
# define path to library files if not already defined
defined('SERVICE_PATH') or define('SERVICE_PATH', INSTALLATION_PATH."services/");

require_once(SERVICE_PATH . "solarian.php");

defined("BANNER_WIDTH") or define('BANNER_WIDTH', 402);
defined("BANNER_HEIGHT") or define('BANNER_HEIGHT', 594);
define("BANNER_SERVER", "www.bungie.net");
define("ASSET_URL", PROTOCOL.BANNER_SERVER."/");

function imageAlphaMask(&$picture, $mask)
{
    // Get sizes and set up new picture
    $xSize = imagesx($picture);
    $ySize = imagesy($picture);
    $newPicture = imagecreatetruecolor($xSize, $ySize);
    imagesavealpha($newPicture, true);
    imagefill($newPicture, 0, 0, imagecolorallocatealpha($newPicture, 0, 0, 0, 127));

    // Resize mask if necessary
    if ($xSize != imagesx($mask) || $ySize != imagesy($mask)) {
        $tempPic = imagecreatetruecolor($xSize, $ySize);
        imagecopyresampled($tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx($mask), imagesy($mask));
        imagedestroy($mask);
        $mask = $tempPic;
    }

    // Perform pixel-based alpha map application
    for ($x = 0; $x < $xSize; $x++) {
        for ($y = 0; $y < $ySize; $y++) {
            $alpha = imagecolorsforindex($mask, imagecolorat($mask, $x, $y));
            $alpha = $alpha['alpha'];
            $color = imagecolorsforindex($picture, imagecolorat($picture, $x, $y));
            imagesetpixel($newPicture, $x, $y, imagecolorallocatealpha($newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha));
        }
    }

    // Copy back to original picture
    imagedestroy($picture);
    $picture = $newPicture;
} // end function imageAlphaMask()

function createBannerPng($clanId = TEST_CLAN_ID) {
	
	$bannerData = getClanBannerData($clanId);
	
	   $layerDefs = [
        [
            "url" => ASSET_URL . $bannerData["gonfalonId"]["foregroundImagePath"],
            "color" => $bannerData["gonfalonColorId"]
        ],
        [
            "url" => ASSET_URL . $bannerData["gonfalonDetailId"]["foregroundImagePath"],
            "color" => $bannerData["gonfalonDetailColorId"]
        ],
        [
            "url" => ASSET_URL . $bannerData["decalId"]["backgroundImagePath"],
            "color" => $bannerData["decalBackgroundColorId"]
        ],
        [
            "url" => ASSET_URL . $bannerData["decalId"]["foregroundImagePath"],
            "color" => $bannerData["decalColorId"]
        ]
    ]; // end layer definition array
	
// Create base image, transparent
$png = imagecreatetruecolor(BANNER_WIDTH, BANNER_HEIGHT);
imagesavealpha($png, true);

$trans_colour = imagecolorallocatealpha($png, 0, 0, 0, 127);
imagefill($png, 0, 0, $trans_colour);

// Colorize and add layers

foreach ($layerDefs as $layerDef) {
    if ($layerDef["color"]["alpha"] == 0) {
        continue;
    }
    $imageData = file_get_contents($layerDef['url']);
    $size = getimagesizefromstring($imageData);
    $layer = imagecreatefromstring($imageData);
    imagealphablending($layer, false);
    imagefilter(
        $layer,
        IMG_FILTER_COLORIZE,
        $layerDef["color"]["red"] - 255,
        $layerDef["color"]["green"] - 255,
        $layerDef["color"]["blue"] - 255,
        $layerDef["color"]["alpha"] - 255
    );
    imagecopyresampled($png, $layer, 0, 0, 0, 0, BANNER_WIDTH, BANNER_HEIGHT, $size[0], $size[1]);
}

$texture = imagecreatefrompng('https://www.bungie.net/img/bannercreator/flag_overlay.png');
imageAlphaMask($png, $texture);

$mask = imagecreatefrompng($layerDefs[0]["url"]);
imageAlphaMask($png, $mask);

	
$retVal = imagepng($png);
// Send Image to Browser
//imagepng($png);
// Clear Memory
imagedestroy($png);	
	
return($retVal);
} // end function createBannerPng()

function createBannerCanvas($clanId = TEST_CLAN_ID) {
	
	// Start output buffering
	ob_start();
	// Generate PNG
	createBannerPng();
	// Capture PNG from buffer and End Buffering without display
	$bannerPng = ob_get_clean();
	
	$bannerCanvas = 'data:image/png;base64,' . base64_encode($bannerPng);
	
	return($bannerCanvas);
	
} // end function createBannerCanvas()


?>