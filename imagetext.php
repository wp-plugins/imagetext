<?php
/*
Plugin Name: Image Text
Plugin URI: http://wordpress.org/extend/plugins/imagetext/
Author URI: http://flashpixx.de/2010/02/wordpress-plugin-imagetext
Description: With this plugin text can be shown as pictures within articles or pages so that they can not be indexed by spambots. The plugin uses the Google Chart API for creating the images. You can create images with text, LaTeX and QR code content.
Version: 0.3
Stable tag: trunk
Tested up to: 3.1.3
Author: flashpixx
License: GPLv3
*/


@require_once("render.class.php");
@require_once("filter.class.php");
@require_once("widget.class.php");

// stop direct call
if (preg_match("#" . basename(__FILE__) . "#", $_SERVER["PHP_SELF"])) { die("You are not allowed to call this page directly."); }

// translation
if (function_exists("load_plugin_textdomain"))
	load_plugin_textdomain("fpx_imagetext", false, dirname(plugin_basename(__FILE__))."/lang");



// ==== create Wordpress Hooks =====================================================================================================================
add_filter("the_content", "fpx_imagetext_filter::filter");
add_action("init", "fpx_imagetext_filter::init");
register_activation_hook(__FILE__,"fpx_imagetext_install");
register_uninstall_hook(__FILE__, "fpx_imagetext_uninstall");
add_action("admin_menu", "fpx_imagetext_render::adminmenu");
add_action("admin_init", "fpx_imagetext_render::optionfields");
add_action("widgets_init",  create_function("", "return register_widget(\"fpx_imagetext_qrcodewidget\");"));
// =================================================================================================================================================




// ==== administration function ====================================================================================================================

/** create the default options **/
function fpx_imagetext_install() {
	update_option("fpx_imagetext_option",
	 	array(
			"text"		=> array(
				"alpha" 				=> true,
				"alttext"				=> __("The text in this space was converted to guard against spam robots into an image", "fpx_imagetext"),
				"backgroundcolor"		=> "FFFFFF",
				"cssclass"				=> null,
				"width"					=> 150,
				"height"				=> 20,
				"textcolor"				=> "000000",
				"localoverridesglobal"	=> true
				),
				
			"latex"		=> array(
				"alpha" 				=> true,
				"alttext"				=> __("LaTeX formula"),
				"cssclass"				=> null,
				"localoverridesglobal"	=> true,
				"textcolor"				=> "000000",
				"backgroundcolor"		=> "FFFFFF",
				"width"					=> 150,
				"height"				=> 20
			),
			
			"qrcode"	=> array(
				"alttext"				=> __("QR code"),
				"cssclass"				=> null,
				"size"					=> 150,
				"errorlevel"			=> "L",
				"localoverridesglobal"	=> true
			)
	));
}

/** uninstall functions **/
function fpx_imagetext_uninstall() {
	unregister_setting("fpx_imagetext_option", "fpx_imagetext_option");
	delete_option("fpx_imagetext_option");
}

// =================================================================================================================================================

?>