<?php
/** 
 * #########################################################################
 * # GPL License                                                           #
 * #                                                                       #
 * # This file is part of the Wordpress Imagetext Plugin.                  #
 * # Copyright (c) 2010, Philipp Kraus, <philipp.kraus@flashpixx.de>       #
 * # This program is free software: you can redistribute it and/or modify  #
 * # it under the terms of the GNU General Public License as published by  #
 * # the Free Software Foundation, either version 3 of the License, or     #
 * # (at your option) any later version.                                   #
 * #                                                                       #
 * # This program is distributed in the hope that it will be useful,       #
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of        #
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         #
 * # GNU General Public License for more details.                          #
 * #                                                                       #
 * # You should have received a copy of the GNU General Public License     #
 * # along with this program.  If not, see <http://www.gnu.org/licenses/>. #
 * #########################################################################
**/

define('WP_DEBUG', false);
@require_once("../../../wp-config.php");

// ==== read get parameter and decode =======================================
if (!@session_id())
	return;


// hash not exists, then die
if (!isset($_GET["h"]))
	echo "ex";
$lcHash = $_GET["h"];

$laData = $_SESSION[$lcHash];
if ( (!is_array($laData)) || (!array_key_exists("text", $laData)) )
	return;

// ==========================================================================



// ==== create png and send the image =======================================
// get default options
$laParams = array(
	"alpha"		=> true,
	"bgcolor"	=> "FFFFFF",
	"textcolor"	=> "000000",
	"ttf"		=> null,
	"fontsize"	=> 8,
	"textx"		=> 5,
	"texty"		=> 5,
	"imagex"	=> 100,
	"imagey"	=> 20,
	"text" 		=> null
	);

// add deserialize data to Parameters
foreach ($laData as $lcKey => $lxValue)
	if ( (array_key_exists($lcKey, $laParams)) && (!empty($lxValue)) )
		$laParams[$lcKey] = $lxValue;
		
// some parameters can be null, so we must add manually
$laParams["alpha"] = $laData["alpha"];


// create image
$loImage = @imagecreatetruecolor($laParams["imagex"], $laParams["imagey"]) or die();


// create colors
$lnTextColor = @imagecolorallocate($loImage, 
					hexdec('0x' . substr($laParams["textcolor"], 0, 2)),
					hexdec('0x' . substr($laParams["textcolor"], 2, 2)),
					hexdec('0x' . substr($laParams["textcolor"], 4, 2))
				);
$lnBGColor   = @imagecolorallocate($loImage, 
					hexdec('0x' . substr($laParams["bgcolor"], 0, 2)),
					hexdec('0x' . substr($laParams["bgcolor"], 2, 2)),
					hexdec('0x' . substr($laParams["bgcolor"], 4, 2))
				);


// background & text
@imagefilledrectangle($loImage, 0, 0, $laParams["imagex"]-1, $laParams["imagey"]-1, $lnBGColor);

if (empty($laParams["ttf"]) || (!@file_exists("./fonts/".$laParams["ttf"])) ) {
	$lc = html_entity_decode(utf8_decode($laParams["text"]));
	@imagestring($loImage, $laParams["fontsize"], $laParams["textx"],$laParams["texty"], $lc, $lnTextColor);
} else
	@imagettftext($loImage, $laParams["fontsize"], 0, $laParams["textx"],$laParams["texty"], $lnTextColor, "./fonts/".$laParams["ttf"], $laParams["text"]);

// set opaque for backgroudn
if ($laParams["alpha"])
	@imagecolortransparent($loImage, $lnBGColor);


// send png
header("Content-type: image/png");
@imagepng($loImage);
@imagedestroy($loImage);

?>