<?php
/*
Plugin Name: Image Text
Plugin URI: http://wordpress.org/extend/plugins/imagetext/
Author URI: http://flashpixx.de/2010/02/wordpress-plugin-imagetext
Description: With this plugin text can be shown as pictures within articles or pages so that they can not be indexed by spambots. The views of the pictures are also protected so that only the installed version of Wordpress access is (it use PHP sessions)
Version: 0.2
Stable tag: trunk
Tested up to: 3.0.1
Author: flashpixx
License: GPLv3
*/


// stop direct call
if (preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

// translation
if (function_exists('load_plugin_textdomain'))
	load_plugin_textdomain('fpx_imagetext', false, dirname(plugin_basename(__FILE__))."/lang");



// ==== create Wordpress Hooks =====================================================================================================================
add_filter('the_content', 'fpx_imagetext_filter');
add_action('init', 'fpx_image_session');
register_activation_hook(__FILE__,'fpx_imagetext_install');
register_uninstall_hook(__FILE__, 'fpx_imagetext_uninstall');
add_action('admin_menu', 'fpx_imagetext_adminmenu');
//add_action('admin_init', 'fpx_imagetext_install');
// =================================================================================================================================================



// ==== filter and other functions =================================================================================================================

/** we using sessions to communicate **/
function fpx_image_session() {
    @session_start();
}

/** content filter function for get the tags
  * @param $pcContent Content
**/
function fpx_imagetext_filter($pcContent) {

	if (!fpx_imagetext_requires())
		$lcContent = preg_replace("!\[imgtxt(.*)\](.*)\[/imgtxt\]!isU", "$2", $pcContent);
	else
		$lcContent = preg_replace_callback("!\[imgtxt(.*)\](.*)\[/imgtxt\]!isU", "fpx_imagetext_filteraction", $pcContent);
	
	return $lcContent;
}

/** create action and the image tag
  * @param $pa Array with founded regular expressions
  * @return replace image tag or null on error
**/
function fpx_imagetext_filteraction($pa) {
	if ( (empty($pa)) || (count($pa) != 3) )
		return null;
	
	// get default options
	$laParams = array(
		"alpha"		=> get_option('fpx_imagetext_alpha'),
		"bgcolor"	=> get_option('fpx_imagetext_backgroundcolor'),
		"textcolor"	=> get_option('fpx_imagetext_textcolor'),
		"ttf"		=> get_option('fpx_imagetext_ttffont'),
		"fontsize"	=> get_option('fpx_imagetext_fontsize'),
		"textx"		=> get_option('fpx_imagetext_text_x'),
		"texty"		=> get_option('fpx_imagetext_text_y'),
		"imagex"	=> get_option('fpx_imagetext_image_x'),
		"imagey"	=> get_option('fpx_imagetext_image_y'),
		"text" 		=> null
		);	
	
	// extract information
	if ( (count($pa) == 3) && (empty($pa[1])) )
		$laParams["text"] = $pa[2];
	elseif ( (count($pa) == 3) && (!empty($pa[1])) && (strtolower(get_option('fpx_imagetext_localoverridesglobal')) == 'true') ) {
		
		$laLocalParams = explode(" ", $pa[1]);
		if (is_array($laLocalParams))
			foreach($laLocalParams as $laLocalVar) {
				$la = explode("=", $laLocalVar);
				if ((is_array($la)) && (count($la) == 2) && array_key_exists($la[0], $laParams))
					$laParams[$la[0]] = $la[1];
			}
		
		$laParams["text"] 		= $pa[2];
		$laParams["fontsize"] 	= abs($laParams["fontsize"]);
		$laParams["textx"] 		= abs($laParams["textx"]);
		$laParams["texty"] 		= abs($laParams["texty"]);
		$laParams["imagex"] 	= abs($laParams["imagex"]);
		$laParams["imagey"] 	= abs($laParams["imagey"]);		
		
		if ($laParams["fontsize"] == 0)
			$laParams["fontsize"] = get_option('fpx_imagetext_fontsize');
		if ($laParams["imagex"] == 0)
			$laParams["imagex"] = get_option('fpx_imagetext_image_x');
		if ($laParams["imagey"] == 0)
			$laParams["imagey"] = get_option('fpx_imagetext_image_y');
		
		if (!fpx_image_isColor($laParams["bgcolor"]))
			$laParams["bgcolor"] = get_option('fpx_imagetext_backgroundcolor');
		if (!fpx_image_isColor($laParams["textcolor"]))
			$laParams["textcolor"] = get_option('fpx_imagetext_textcolor');
		if (strtolower($laParams["alpha"] != "true"))
			$laParams["alpha"] = null;
		
	} elseif ( (count($pa) == 3) && (!empty($pa[1])) && (strtolower(get_option('fpx_imagetext_localoverridesglobal')) != 'true') )
		$laParams["text"] = $pa[2];
	else
		return null;
	
	// nothing to do
	if (empty($laParams["text"]))
		return null;
		
		
	// typ cast, cause WP stores everything as string values
	$laParams["fontsize"]	= (int)$laParams["fontsize"];
	$laParams["imagex"] 	= (int)$laParams["imagex"];
	$laParams["imagey"] 	= (int)$laParams["imagey"];
	$laParams["textx"] 		= (int)$laParams["textx"];
	$laParams["texty"] 		= (int)$laParams["texty"];
	$laParams["alpha"]		= (strtolower($laParams["alpha"]) == "true");

	
	// serialize and create hash in session
	$lcHash 			= md5(serialize($laParams));
	$_SESSION[$lcHash] 	= $laParams;
		
	// create img tag only with hash
	$lcClass = get_option('fpx_imagetext_class');
	$lcReturn  = "<img src=\"".plugins_url("image.php?h=".$lcHash, __FILE__)."\"";
	$lcReturn .= " alt=\"".wp_specialchars(get_option('fpx_imagetext_alttext'))."\"";
	if (!empty($lcClass))
		$lcReturn .= " class=\"".$lcClass."\"";
	
	return $lcReturn." />";
}


/** checks the requirements for the plugin
  * @return boolean that they are correct
**/
function fpx_imagetext_requires() {
	if (extension_loaded("gd")) {
		$laGD = gd_info();
		return $laGD["FreeType Support"] && $laGD["PNG Support"];
	}
	return false;
}
// =================================================================================================================================================



// ==== administration function ====================================================================================================================

/** create the default options **/
function fpx_imagetext_install() {
	$laOptions = array(
			'fpx_imagetext_alpha' 					=> 'true',
			'fpx_imagetext_alttext'					=> __('The text in this space was converted to guard against spam robots into an image', 'fpx_imagetext'),
			'fpx_imagetext_backgroundcolor'			=> 'FFFFFF',
			'fpx_imagetext_class'					=> null,
			'fpx_imagetext_fontsize'				=> '8',
			'fpx_imagetext_image_x'					=> '150',
			'fpx_imagetext_image_y'					=> '20',
			'fpx_imagetext_textcolor'				=> '000000',
			'fpx_imagetext_text_x'					=> '5',
			'fpx_imagetext_text_y'					=> '5',
			'fpx_imagetext_ttffont'					=> null,
			'fpx_imagetext_localoverridesglobal'	=> 'true'
		);

	// create key and default values
	foreach ($laOptions as $key => $value) {
		register_setting('fpx_imagetext_option', $key);
		update_option($key, $value);
	}
}


/** creates admin menu **/
function fpx_imagetext_adminmenu() {
	if (is_admin()) 
		// adds admin page call
		add_options_page('Image Text Optionen', 'Image Text', 9, __FILE__, 'fpx_imagetext_option');
}


/** uninstall functions **/
function fpx_imagetext_uninstall() {
	$laOptions = array(
			'fpx_imagetext_alpha',
			'fpx_imagetext_alttext',
			'fpx_imagetext_backgroundcolor',
			'fpx_imagetext_class',
			'fpx_imagetext_fontsize',
			'fpx_imagetext_image_x',
			'fpx_imagetext_image_y',
			'fpx_imagetext_textcolor',
			'fpx_imagetext_text_x',
			'fpx_imagetext_text_y',
			'fpx_imagetext_ttffont',
			'fpx_imagetext_localoverridesglobal'
		);
	
	// remove plugin options
	foreach ($laOptions as $lcOption) {
		unregister_setting('fpx_imagetext_option', $lcOption);
		delete_option($lcOption);
	}
}


/** help function for checking POST variable 
  * @param $pc name of var
  * @return boolean if it is correct
**/
function fpx_image_checkpost($pc) {
	return isset($_POST[$pc]) && (!empty($_POST[$pc]));
}

/** checks string if it is a color code
  * @param $pc string with code
  * @return boolean if it is a color code
**/
function fpx_image_isColor($pc) {
	return !empty($pc) && ((strlen($pc) == 6) || (strlen($pc) == 3)) && preg_match("/^[A-F0-9]+$/i",$pc);
}


/** shows the admin panel with actions **/
function fpx_imagetext_option() {
	echo "<div class=\"wrap\"><h2>Image Text ".__("Configuration and Usage", 'fpx_imagetext')."</h2>\n";
	echo "<p>".__("If you like the plugin and want to support my work, I'd appreciate a donation through the following link very much. A donation encouraged me to develop my work further and to thank you again and again so the community.", 'fpx_imagetext')."\n";
	echo "<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"> \n";
	echo "<input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\"/> \n";
	echo "<input type=\"hidden\" name=\"hosted_button_id\" value=\"RMZVY3WHSL6ZY\"/> \n";
	echo "<input type=\"image\" src=\"https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online.\"/></form></p>\n\n"; 

	echo "<hr> \n\n";
	if (!fpx_imagetext_requires())
		echo "<span style=\"font-size: 1.1em; font-weight: bold; color: #FF0000\">".__("Warning: It seems as if the PHP extension GD, Freetype module and / or the PNG support is not available!", 'fpx_imagetext')."</span>\n\n";
	else {
		
		// clear file cache and set font path
		clearstatcache();	
		$lcFontDir = plugin_dir_path(__FILE__)."fonts";
		
		// --- we run into update (we do it manually because of the ttf upload otherwise we should use add_settings_field)
		if (isset($_POST["submit"])) {
			// update for local option override global options
			update_option('fpx_imagetext_localoverridesglobal', ((isset($_POST["fpx_imagetext_localoverridesglobal"])) ? strtolower($_POST["fpx_imagetext_localoverridesglobal"]) : null));
			
			// update image dimension
			if ( fpx_image_checkpost("fpx_imagetext_image_x") && (abs($_POST["fpx_imagetext_image_x"]) > 0) )
				update_option("fpx_imagetext_image_x", abs($_POST["fpx_imagetext_image_x"]));
			if ( fpx_image_checkpost("fpx_imagetext_image_y") && (abs($_POST["fpx_imagetext_image_y"]) > 0) )
				update_option("fpx_imagetext_image_y", abs($_POST["fpx_imagetext_image_y"]));
				
			// update text position
			if ( fpx_image_checkpost("fpx_imagetext_text_x") && (abs($_POST["fpx_imagetext_text_x"]) > 0) && (abs($_POST["fpx_imagetext_text_x"]) < get_option('fpx_imagetext_image_x')) )
				update_option('fpx_imagetext_text_x', abs($_POST["fpx_imagetext_text_x"]));
			else
				update_option('fpx_imagetext_text_x', '1');
			
			if ( fpx_image_checkpost("fpx_imagetext_text_y") && (abs($_POST["fpx_imagetext_text_y"]) > 0) && (abs($_POST["fpx_imagetext_text_y"]) < (int)get_option('fpx_imagetext_image_y')) )
				update_option('fpx_imagetext_text_y', abs($_POST["fpx_imagetext_text_y"]));
			else
				update_option('fpx_imagetext_text_y', '1');
				
			// update image color
			if ( fpx_image_checkpost("fpx_imagetext_backgroundcolor") && fpx_image_isColor($_POST["fpx_imagetext_backgroundcolor"]) )
				update_option('fpx_imagetext_backgroundcolor', $_POST["fpx_imagetext_backgroundcolor"]);
			if ( fpx_image_checkpost("fpx_imagetext_textcolor") && fpx_image_isColor($_POST["fpx_imagetext_textcolor"]) )
				update_option('fpx_imagetext_textcolor', $_POST["fpx_imagetext_textcolor"]);
			update_option('fpx_imagetext_alpha', ((isset($_POST["fpx_imagetext_alpha"])) ? strtolower($_POST["fpx_imagetext_alpha"]) : null));

			// update layout style
			if (isset($_POST["fpx_imagetext_class"]))
				update_option('fpx_imagetext_class', $_POST["fpx_imagetext_class"]);
			if (isset($_POST["fpx_imagetext_alttext"]))
				update_option('fpx_imagetext_alttext', $_POST["fpx_imagetext_alttext"]);
			if ( fpx_image_checkpost("fpx_imagetext_fontsize") && (abs($_POST["fpx_imagetext_fontsize"]) > 0) )
				update_option('fpx_imagetext_fontsize', round(abs($_POST["fpx_imagetext_fontsize"])));
				
			// font files
			if (isset($_POST["ttfdel"])) {
				$lc = $_POST["ttfdel"];
				if ( file_exists($lcFontDir."/".$lc) && is_writeable($lcFontDir."/".$lc) )
					@unlink($lcFontDir."/".$lc);
			}
			
			$lc = null;
			if (isset($_POST["ttffile"])) {
				$lc = $_POST["ttffile"];
				if ( (!file_exists($lcFontDir."/".$lc)) || (!is_readable($lcFontDir."/".$lc)) )
					$lc = null;
			}
			update_option('fpx_imagetext_ttffont', $lc);
			
			
			// ttf file upload
			if ( is_writeable($lcFontDir) && (isset($_FILES["ttfupload"])) ) {
				$laSource	= $_FILES["ttfupload"];
				$lcDest 	= $lcFontDir."/".$laSource["name"];
				if (!file_exists($lcDest)) {
					move_uploaded_file($laSource["tmp_name"], $lcDest);
				    chmod($lcDest, 0644);
				}
			}
		}


		// --- create admin panel
		echo "<h3>".__("Usage", 'fpx_imagetext')."</h3> \n";
		echo "<p>".__("The command <strong>[imgtxt]your content[/imgtxt]</strong> pastes the text as an image in your document.", 'fpx_imagetext')."<br/> \n";
		echo __("The following options may also be passed as parameters to the opening tag to adjust the current formatting. If no parameters are specified in the tags, the global values are used. In parenthesis the parameter name is specified. The use is then: <strong>[imgtxt parameter=value parameter=value ...]</strong>", 'fpx_imagetext')."</p> \n";
 
		echo "<form method=\"post\" action=\"\" enctype=\"multipart/form-data\">\n";
		echo "<fieldset> \n";
		settings_fields('fpx_imagetext_option');
		echo "<table width=\"65%\"> \n";
		
		// local option override global options
		echo "<tr><td><label for=\"localoverridesglobal\">".__("tag options can override global options", 'fpx_imagetext').":</label></td><td><input type=\"checkbox\" id=\"localoverridesglobal\" name=\"fpx_imagetext_localoverridesglobal\" value=\"true\"".((strtolower(get_option('fpx_imagetext_localoverridesglobal')) == 'true') ? " checked " : null)." /></td></tr> \n";
		
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
		
		// image dimension
		echo "<tr><td><label for=\"imagex\">".__('image x-dimension', 'fpx_imagetext')." (imagex):</label></td><td><input type=\"text\" id=\"imagex\" name=\"fpx_imagetext_image_x\" value=\"".get_option('fpx_imagetext_image_x')."\" /></td></tr>\n"; 
		echo "<tr><td><label for=\"imagey\">".__('image y-dimension','fpx_imagetext')." (imagey):</label></td><td><input type=\"text\" id=\"imagey\" name=\"fpx_imagetext_image_y\" value=\"".get_option('fpx_imagetext_image_y')."\" /></td></tr> \n";
	
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
	
		// text position
		echo "<tr><td><label for=\"textx\">".__("text x-position", 'fpx_imagetext')." (textx):</label></td><td><input type=\"text\" id=\"textx\" name=\"fpx_imagetext_text_x\" value=\"".get_option('fpx_imagetext_text_x')."\" /></td></tr> \n";
		echo "<tr><td><label for=\"texty\">".__("text y-position", 'fpx_imagetext')." (texty):</label></td><td><input type=\"text\" id=\"texty\" name=\"fpx_imagetext_text_y\" value=\"".get_option('fpx_imagetext_text_y')."\" /></td></tr> \n";
	
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
	
		// image color
		echo "<tr><td><label for=\"bgcolor\">".__("background color", 'fpx_imagetext')." (bgcolor):</label></td><td><input type=\"text\" id=\"bgcolor\" name=\"fpx_imagetext_backgroundcolor\" value=\"".get_option('fpx_imagetext_backgroundcolor')."\" /></td></tr> \n";
		echo "<tr><td><label for=\"textcolor\">".__("text color", 'fpx_imagetext')." (textcolor):</label></td><td><input type=\"text\" id=\"textcolor\" name=\"fpx_imagetext_textcolor\" value=\"".get_option('fpx_imagetext_textcolor')."\" /></td></tr> \n";
		echo "<tr><td><label for=\"alpha\">".__("background color transparent", 'fpx_imagetext')." (alpha):</label></td><td><input type=\"checkbox\" id=\"alpha\" name=\"fpx_imagetext_alpha\" value=\"true\"".((strtolower(get_option('fpx_imagetext_alpha')) == 'true') ? " checked " : null)." /></td></tr> \n";
	
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
	
		// layout style
		echo "<tr><td><label for=\"class\">".__("class for img tag", 'fpx_imagetext')." (class):</label></td><td><input type=\"text\" id=\"class\" name=\"fpx_imagetext_class\" value=\"".get_option('fpx_imagetext_class')."\" /></td></tr> \n";
		echo "<tr><td><label for=\"alttext\">".__("alternativ description for img tag", 'fpx_imagetext')." (alt):</label></td><td><input type=\"text\" id=\"alttext\" name=\"fpx_imagetext_alttext\" value=\"".get_option('fpx_imagetext_alttext')."\" /></td></tr> \n";
		echo "<tr><td><label for=\"fontsize\">".__("fontsize", 'fpx_imagetext')." (fontsize):</label></td><td><input type=\"text\" id=\"fontsize\" name=\"fpx_imagetext_fontsize\" value=\"".get_option('fpx_imagetext_fontsize')."\" /></td></tr> \n";
		
		
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
		
		// read font files
		$laFontFiles  = array();
		$llExists2Del = false;
		if ($handle = opendir($lcFontDir)) {
			while (false !== ($file = readdir($handle)))
				if ( is_file($lcFontDir."/".$file) && is_readable($lcFontDir."/".$file) ) {
					array_push($laFontFiles, array("file" => $file, "writeable" => is_writeable($lcFontDir."/".$file)));
					$llExists2Del = $llExists2Del || is_writeable($lcFontDir."/".$file);
				}
		    closedir($handle);
		}
		
		// font file layout
		echo "<tr><td><label for=\"ttffile\">".__("used font file", 'fpx_imagetext')." (ttf):</label></td><td>";
		echo "<select name=\"ttffile\">\n";
		echo "<option value=\"\">".__("default font", 'fpx_imagetext')."</option>\n";
		foreach($laFontFiles as $laFile)
			echo "<option value=\"".$laFile["file"]."\" ".((get_option('fpx_imagetext_ttffont')==$laFile["file"]) ? "selected" : null).">".$laFile["file"]."</option>\n";
	    echo "</select>\n";
		echo "</td></tr>\n";
		
		echo "<tr><td><label for=\"ttfupload\">".__("upload font file", 'fpx_imagetext').":</label></td><td>";	
		if (is_writeable($lcFontDir))
			echo "<input name=\"ttfupload\" id=\"ttfupload\" type=\"file\"/>";
		else
			echo __("You can not upload any file. Please change the permission to 777 (rwx rwx rwx) for the fonts-directory in plugin path", 'fpx_imagetext')." <em>".wp_specialchars($lcFontDir)."</em>";
		echo "</td></tr>\n";
		
		if (is_writeable($lcFontDir) && $llExists2Del) {
			echo "<tr><td><label for=\"ttfdel\">".__("delete font file", 'fpx_imagetext').":</label></td><td>";
			echo "<select name=\"ttfdel\">\n";
			echo "<option value=\"\">".__("no deletion", 'fpx_imagetext')."</option>\n";
			foreach($laFontFiles as $laFile)
				if ($laFile["writeable"])
					echo "<option value=\"".$laFile["file"]."\">".$laFile["file"]."</option>\n";
		    echo "</select>\n";			
			echo "</td></tr>\n";
		}
		
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr> \n";
		
		echo "<tr><td><input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"".__('Save Changes')."\"/></td></tr>\n";
		
		echo "</table> \n";
		echo "</fieldset> \n";
		echo "</form></div>\n";
	}
}
// =================================================================================================================================================

?>