<?php
/** 
 * #########################################################################
 * # GPL License                                                           #
 * #                                                                       #
 * # This file is part of the Wordpress Imagetext plugin.                  #
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


/** class for doing content filtering **/
class fpx_imagetext_filter {
	
	/** we using sessions to communicate **/
	static function init() {
	    @session_start();
	}
	
	
	/** content filter function for get the tags
	  * @param $pcContent Content
	**/
	static function filter($pcContent) {
		return preg_replace_callback("!\[imgtxt(.*)\](.*)\[/imgtxt\]!isU", get_class()."::action", $pcContent);
	}
	
    
	/** create action and the image tag
	  * @param $pa Array with founded regular expressions
	  * @return replace image tag or null on error
	**/
	private static function action($pa) {
		if ( (empty($pa)) || (count($pa) != 3) )
			return null;

		// read tag parameter
		$param		= array();
		$tagparam   = explode(" ", $pa[1]);
		foreach($tagparam as $val) {
			$la = explode("=", $val);

			if (count($la) == 2)
				$param[trim($la[0])] = trim($la[1]);
		}

		// if typ-key not exists, abort
		if (!array_key_exists("type", $param))
			return null;

		// read global option and the option for the type
		$option = get_option("fpx_imagetext_option");
		if (!array_key_exists($param["type"], $option))
			return null;
		$optiondata = $option[$param["type"]];

		//if allowed set option
		if ($optiondata["localoverridesglobal"]) {
			foreach($param as $key => $val)
				if (array_key_exists($key, $optiondata))
					$optiondata[$key] = $val;
		}


		// set the option data to URL options
		$urldata = array();
		$data    = mb_convert_encoding(trim($pa[2]), "UTF-8", "auto");
		if ($optiondata["htmldecode"])
			$data    = html_entity_decode(strip_tags($data));
		
		switch ($param["type"]) {	
			case "text"		:
				$urldata["cht"] 	= "p3";
				$urldata["chs"]		= $optiondata["width"]."x".$optiondata["height"];
				$urldata["chtt"] 	= $data;
				$urldata["chts"]	= $optiondata["textcolor"];
				$urldata["chf"]		= $optiondata["alpha"] ? "a,s,FF" : "bg,s,".$optiondata["backgroundcolor"];
				break;

			case "latex"	:
				$urldata["cht"]		= "tx";
				$urldata["chs"]		= $optiondata["width"]."x".$optiondata["height"];
				$urldata["chl"]		= $data;
				$urldata["chco"]	= $optiondata["textcolor"];
				$urldata["chf"]		= $optiondata["alpha"] ? "a,s,FF" : "bg,s,".$optiondata["backgroundcolor"];
				break;

			case "qrcode"	:
				$urldata["cht"] 	= "qr";
				$urldata["chs"]		= $optiondata["size"]."x".$optiondata["size"];
				$urldata["chl"] 	= empty($data) ? get_permalink() : $data;
				$urldata["chf"]		= "a,s,FF";
				$urldata["chld"]	= (($optiondata["errorlevel"] == "L") || ($optiondata["errorlevel"] == "M") || ($optiondata["errorlevel"] == "Q") || ($optiondata["errorlevel"] == "H")) ? $optiondata["errorlevel"] : "L";
				break;
		}

		// create URL and has for the session data
		// the &amp; code creates some errors in the Google call, so we subsitute it to &
		$urldata["chof"]	= "png";
		$urlparameter		= http_build_query($urldata);
		$lcHash				= md5($urlparameter);
		$_SESSION[$lcHash] 	= str_replace("&amp;", "&", "https://chart.googleapis.com/chart?".$urlparameter);

		// create img tag only with hash
		$lcReturn  = "<img src=\"".plugins_url("image.php?h=".$lcHash, __FILE__)."\"";
        if (!empty($optiondata["alttext"]))
            $lcReturn .= " alt=\"".wp_specialchars($optiondata["alttext"])."\"";
		if (!empty($optiondata["cssclass"]))
			$lcReturn .= " class=\"".$optiondata["cssclass"]."\"";
		return $lcReturn." />";
	}
	
}

?>