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

namespace de\flashpixx\imagetext;

/** class for link creation **/
class link {
    
    /** method creates the link with 
     * @param $pcType image type eg: text, latex, qrcode
     * @param $pcData input data
     * @param $paOption associative array with options
     * @return href link
     **/
    static function get($pcType, $pcData, $paOption = null) {
        
        if ( (empty($pcType)) || (empty($pcData)) )
            return null;
        
        
		// read global option and the option for the type
		$option = get_option("fpx_imagetext_option");
		if (!array_key_exists($pcType, $option))
			return null;
		$optiondata = $option[$pcType];
        
		//if allowed set option
        if ( (is_array($paOption)) && ($optiondata["localoverridesglobal"]) ) {
            foreach($paOption as $key => $val)
                if (array_key_exists($key, $optiondata))
                    $optiondata[$key] = $val;
        }
        
        
        // set the option data to URL options
		$urldata = array();
		$data    = mb_convert_encoding(trim($pcData), "UTF-8", "auto");
		if ($optiondata["htmldecode"])
			$data    = html_entity_decode(strip_tags($data));
		
		switch ($pcType) {	
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
		$lcReturn  = "<img src=\"".plugins_url("image.php?h=".$lcHash, LOCALPLUGINFILE)."\"";
        if (!empty($optiondata["alttext"]))
            $lcReturn .= " alt=\"".wp_specialchars($optiondata["alttext"])."\"";
		if (!empty($optiondata["cssclass"]))
			$lcReturn .= " class=\"".$optiondata["cssclass"]."\"";
		return $lcReturn." />";
    }
    

}

?>