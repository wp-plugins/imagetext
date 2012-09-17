<?php
/**
Plugin Name: Image Text
Plugin URI: http://wordpress.org/extend/plugins/imagetext/
Author URI: http://flashpixx.de/2010/02/wordpress-plugin-imagetext
Description: With this plugin text can be shown as pictures within articles or pages so that they can not be indexed by spambots. The plugin uses the Google Chart API for creating the images. You can create images with text, LaTeX and QR code content.
Author: flashpixx
Version: 0.55


#########################################################################
# GPL License                                                           #
#                                                                       #
# This file is part of the Wordpress Imagetext plugin.                  #
# Copyright (c) 2010-2012, Philipp Kraus, <philipp.kraus@flashpixx.de>  #
# This program is free software: you can redistribute it and/or modify  #
# it under the terms of the GNU General Public License as published by  #
# the Free Software Foundation, either version 3 of the License, or     #
# (at your option) any later version.                                   #
#                                                                       #
# This program is distributed in the hope that it will be useful,       #
# but WITHOUT ANY WARRANTY; without even the implied warranty of        #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         #
# GNU General Public License for more details.                          #
#                                                                       #
# You should have received a copy of the GNU General Public License     #
# along with this program.  If not, see <http://www.gnu.org/licenses/>. #
#########################################################################
**/

namespace de\flashpixx\imagetext;
    
// ==== constant for developing with the correct path of the plugin ================================================================================
//define(__NAMESPACE__."\LOCALPLUGINFILE", __FILE__);
define(__NAMESPACE__."\LOCALPLUGINFILE", WP_PLUGIN_DIR."/imagetext/".basename(__FILE__));
// =================================================================================================================================================

    
    
// ==== plugin initialization ======================================================================================================================
@require_once("render.class.php");
@require_once("filter.class.php");
@require_once("link.class.php");
@require_once("widget.class.php");

// stop direct call
if (preg_match("#" . basename(LOCALPLUGINFILE) . "#", $_SERVER["PHP_SELF"])) { die("You are not allowed to call this page directly."); }

// translation
if (function_exists("load_plugin_textdomain"))
	load_plugin_textdomain("imagetext", false, dirname(plugin_basename(LOCALPLUGINFILE))."/lang");
// =================================================================================================================================================  
    


// ==== create Wordpress Hooks =====================================================================================================================
add_filter("the_content", "de\\flashpixx\\imagetext\\filter::run");
add_action("init", "de\\flashpixx\\imagetext\\filter::init");
register_activation_hook(LOCALPLUGINFILE, "de\\flashpixx\\imagetext\\install");
register_uninstall_hook(LOCALPLUGINFILE, "de\\flashpixx\\imagetext\\uninstall");
add_action("admin_menu", "de\\flashpixx\\imagetext\\render::adminmenu");
add_action("admin_init", "de\\flashpixx\\imagetext\\render::optionfields");
add_action("widgets_init", function(){ return register_widget("de\\flashpixx\\imagetext\\qrcodewidget"); });
// =================================================================================================================================================




// ==== administration function ====================================================================================================================

/** create the default options **/
function install() {
    $lxConfig = get_option("fpx_imagetext_option");
    if (empty($lxConfig))
        update_option("fpx_imagetext_option",
            array(
                "text"		=> array(
                    "alpha" 				=> true,
                    "alttext"				=> __("The text in this space was converted to guard against spam robots into an image", "imagetext"),
                    "backgroundcolor"		=> "FFFFFF",
                    "cssclass"				=> null,
                    "width"					=> 150,
                    "height"				=> 20,
                    "textcolor"				=> "000000",
                    "localoverridesglobal"	=> true,
                    "htmldecode"			=> true
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
                    "localoverridesglobal"	=> true,
                    "htmldecode"			=> true
                )
            )
        );
}

/** uninstall functions **/
function uninstall() {
	unregister_setting("fpx_imagetext_option", "fpx_imagetext_option");
	delete_option("fpx_imagetext_option");
}

// =================================================================================================================================================

?>