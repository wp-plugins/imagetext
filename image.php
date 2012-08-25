<?php
/** 
 * #########################################################################
 * # GPL License                                                           #
 * #                                                                       #
 * # This file is part of the Wordpress Imagetext plugin.                  #
 * # Copyright (c) 2010-2012, Philipp Kraus, <philipp.kraus@flashpixx.de>  #
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


// ==== read get parameter and decode =======================================
@session_start();


// hash not exists, then die
if (!isset($_GET["h"]))
	die("error");
$lcHash = $_GET["h"];

if (!isset($_SESSION[$lcHash]))
    die("error");

$url = $_SESSION[$lcHash];
if ( (!is_string($url)) && (empty($url)) )
	die("error");

// ==========================================================================


// get PNG and send it back
header ("Content-type: image/png");
$fp = fopen($url, "rb");
fpassthru($fp);

?>