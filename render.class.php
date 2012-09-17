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


/** class for creating all visual options **/
class render {
	
	/** creates admin menu **/
	static function adminmenu() {
		add_options_page("Image Text Optionen", "Image Text", "administrator", "fpx_imagetext_option", get_class()."::renderMain");
	}
	
	
	/** shows the admin panel with actions **/
	static function optionfields() {
		register_setting("fpx_imagetext_option", "fpx_imagetext_option", get_class()."::validate");
		
        
		add_settings_section("fpx_imagetext_option",  __("Text Options", "imagetext"),                            get_class()."::render_textsection",              "fpx_imagetext_optiontext");
		add_settings_field("text_alttext",            __("alternate image text", "imagetext")." (alttext)",       get_class()."::render_textalttext",              "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_bgcol",              __("background color", "imagetext")." (backgroundcolor)",   get_class()."::render_textbgcol",                "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_bgalpha",            __("transparant background color", "imagetext")." (alpha)", get_class()."::render_textalpha",                "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_textcol",            __("text color", "imagetext")." (textcolor)",               get_class()."::render_textcol",                  "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_css",                __("CSS class", "imagetext")." (cssclass)",                 get_class()."::render_textcss",                  "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_width",              __("image width", "imagetext")." (width)",                  get_class()."::render_textwidth",                "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_height",             __("image height", "imagetext")." (height)",                get_class()."::render_textheight",               "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_htmldecode",         __("HTML code remove", "imagetext"). " (htmldecode)",       get_class()."::render_texthtmldecode",           "fpx_imagetext_optiontext",      "fpx_imagetext_option");
		add_settings_field("text_localglobal",        __("options can be overwritten", "imagetext"),              get_class()."::render_textlocaloverridesglobal", "fpx_imagetext_optiontext",      "fpx_imagetext_option");

        
		add_settings_section("fpx_imagetext_option",  __("LaTeX Options", "imagetext"),                           get_class()."::render_latexsection",              "fpx_imagetext_optionlatex");
		add_settings_field("latex_alttext",           __("alternate image text", "imagetext")." (alttext)",       get_class()."::render_latexalttext",              "fpx_imagetext_optionlatex",    "fpx_imagetext_option");
		add_settings_field("latex_bgcol",             __("background color", "imagetext")." (backgroundcolor)",   get_class()."::render_latexbgcol",                "fpx_imagetext_optionlatex",    "fpx_imagetext_option");
		add_settings_field("latex_bgalpha",           __("transparant background color", "imagetext")." (alpha)", get_class()."::render_latexalpha",                "fpx_imagetext_optionlatex",    "fpx_imagetext_option");
		add_settings_field("latex_textcol",           __("text color", "imagetext")." (textcolor)",               get_class()."::render_latexcol",                  "fpx_imagetext_optionlatex",    "fpx_imagetext_option");
		add_settings_field("latex_css",               __("CSS class", "imagetext")." (cssclass)",                 get_class()."::render_latexcss",                  "fpx_imagetext_optionlatex",    "fpx_imagetext_option");
		add_settings_field("latex_width",             __("image width", "imagetext")." (width)",                  get_class()."::render_latexwidth",                "fpx_imagetext_optionlatex",    "fpx_imagetext_option");
		add_settings_field("latex_height",            __("image height", "imagetext")." (height)",                get_class()."::render_latexheight",               "fpx_imagetext_optionlatex",    "fpx_imagetext_option");
		add_settings_field("latex_localglobal",       __("options can be overwritten", "imagetext"),              get_class()."::render_latexlocaloverridesglobal", "fpx_imagetext_optionlatex",    "fpx_imagetext_option");		


		add_settings_section("fpx_imagetext_option",  __("QR Code Options", "imagetext"),                         get_class()."::render_qrcodesection",              "fpx_imagetext_optionqrcode");
		add_settings_field("qrcode_alttext",          __("alternate image text", "imagetext")." (alttext)",       get_class()."::render_qrcodealttext",              "fpx_imagetext_optionqrcode",  "fpx_imagetext_option");
		add_settings_field("qrcode_css",              __("CSS class", "imagetext")." (cssclass)",                 get_class()."::render_qrcodecss",                  "fpx_imagetext_optionqrcode",  "fpx_imagetext_option");
		add_settings_field("qrcode_size",             __("image size", "imagetext")." (size)",                    get_class()."::render_qrcodesize",                 "fpx_imagetext_optionqrcode",  "fpx_imagetext_option");
		add_settings_field("qrcode_errlevel",         __("error level", "imagetext")." (errorlevel)",             get_class()."::render_qrcodeerrlevel",             "fpx_imagetext_optionqrcode",  "fpx_imagetext_option");
		add_settings_field("qrcode_htmldecode",       __("HTML code remove", "imagetext"). " (htmldecode)",       get_class()."::render_qrcodehtmldecode",           "fpx_imagetext_optionqrcode",  "fpx_imagetext_option");
		add_settings_field("qrcode_localglobal",      __("options can be overwritten", "imagetext"),              get_class()."::render_qrcodelocaloverridesglobal", "fpx_imagetext_optionqrcode",  "fpx_imagetext_option");		

	}
	
	
	/** checks string if it is a color code
	  * @param $pc string with code
	  * @return boolean if it is a color code
	**/
	private static function isColor($pc) {
		return !empty($pc) && ((strlen($pc) == 6) || (strlen($pc) == 3)) && preg_match("/^[A-F0-9]+$/i",$pc);
	}
	
    
    /** validate the form input 
     * @param $pa form data
     * @return validated data
     **/
	static function validate($pa) {
		$options = get_option("fpx_imagetext_option");
		
		// text options
		$options["text"]["alttext"] 				= $pa["text_alttext"];
		$options["text"]["backgroundcolor"]			= self::isColor($pa["text_bgcol"]) ? $pa["text_bgcol"] : $options["text"]["backgroundcolor"];
		$options["text"]["alpha"]					= !empty($pa["text_alpha"]);
		$options["text"]["textcolor"]				= self::isColor($pa["text_col"]) ? $pa["text_col"] : $options["text"]["textcolor"];
		$options["text"]["cssclass"]				= $pa["text_css"];
		$options["text"]["width"]					= abs(intval($pa["text_width"]));
		$options["text"]["height"]					= abs(intval($pa["text_height"]));
		$options["text"]["localoverridesglobal"]	= !empty($pa["text_localoverridesglobal"]);
		$options["text"]["htmldecode"]				= !empty($pa["text_htmldecode"]);
		
		// latex options
		$options["latex"]["alttext"] 				= $pa["latex_alttext"];
		$options["latex"]["backgroundcolor"]		= self::isColor($pa["latex_bgcol"]) ? $pa["latex_bgcol"] : $options["latex"]["backgroundcolor"];
		$options["latex"]["alpha"]					= !empty($pa["latex_alpha"]);
		$options["latex"]["textcolor"]				= self::isColor($pa["latex_col"]) ? $pa["latex_col"] : $options["latex"]["textcolor"];
		$options["latex"]["cssclass"]				= $pa["latex_css"];
		$options["latex"]["width"]					= abs(intval($pa["latex_width"]));
		$options["latex"]["height"]					= abs(intval($pa["latex_height"]));
		$options["latex"]["localoverridesglobal"]	= !empty($pa["latex_localoverridesglobal"]);
		
		//qr codes options
		$options["qrcode"]["alttext"] 				= $pa["qrcode_alttext"];
		$options["qrcode"]["cssclass"]				= $pa["qrcode_css"];
		$options["qrcode"]["errorlevel"]			= $pa["qrcode_errlevel"];
		$options["qrcode"]["size"]					= abs(intval($pa["qrcode_size"]));
		$options["qrcode"]["localoverridesglobal"]	= !empty($pa["qrcode_localoverridesglobal"]);
		$options["qrcode"]["htmldecode"]			= !empty($pa["qrcode_htmldecode"]);
		
		return $options;
	}


	/** render the option page **/
	static function renderMain() {
		echo "<div class=\"wrap\"><h2>Image Text ".__("Configuration and Usage", "imagetext")."</h2>\n";
        echo "<p>".__("The following options may also be passed as parameters to the opening tag to adjust the current formatting. If no parameters are specified in the tags, the global values are used. In parenthesis the parameter name is specified. The use is then: <strong>[imgtxt parameter=value parameter=value ...]</strong>.", "imagetext")."</p>";
		echo "<form method=\"post\" action=\"options.php\">";
		settings_fields("fpx_imagetext_option");
		do_settings_sections("fpx_imagetext_optiontext");
		do_settings_sections("fpx_imagetext_optionlatex");
		do_settings_sections("fpx_imagetext_optionqrcode");

		echo "<p class=\"submit\"><input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"".__("Save Changes")."\"/></p>\n";
		echo "</form></div>\n";
	}
    
    
    
    static function render_textsection() {
        echo __("This options can be set with the call <strong>[imgtxt type=text]your content[/imgtxt]</strong>", "imagetext");
    }
    
    static function render_textalttext() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_alttext]\" size=\"30\" type=\"text\" value=\"".$options["text"]["alttext"]."\" />";
    }
    
    static function render_textbgcol() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_bgcol]\" size=\"10\" type=\"text\" value=\"".$options["text"]["backgroundcolor"]."\" />";
    }
    
    static function render_textalpha() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_alpha]\" type=\"checkbox\" value=\"1\" ".($options["text"]["alpha"] ? "checked" : null)." />";
    }
    
    static function render_textcol() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_col]\" size=\"10\" type=\"text\" value=\"".$options["text"]["textcolor"]."\" />";
    }
    
    static function render_textcss() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_css]\" size=\"10\" type=\"text\" value=\"".$options["text"]["cssclass"]."\" />";
    }
    
    static function render_textheight() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_height]\" size=\"10\" type=\"text\" value=\"".$options["text"]["height"]."\" />";        
    }
    
    static function render_textwidth() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_width]\" size=\"10\" type=\"text\" value=\"".$options["text"]["width"]."\" />";        
    }

    static function render_texthtmldecode() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_htmldecode]\" type=\"checkbox\" value=\"1\" ".($options["text"]["htmldecode"] ? "checked" : null)." />";
    }
    
    static function render_textlocaloverridesglobal() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[text_localoverridesglobal]\" type=\"checkbox\" value=\"1\" ".($options["text"]["localoverridesglobal"] ? "checked" : null)." />";
    }




    static function render_latexsection() {
        echo __("This options can be set with the call <strong>[imgtxt type=latex]your content[/imgtxt]</strong>", "imagetext");
    }

    static function render_latexalttext() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_alttext]\" size=\"30\" type=\"text\" value=\"".$options["latex"]["alttext"]."\" />";
    }
    
    static function render_latexbgcol() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_bgcol]\" size=\"10\" type=\"text\" value=\"".$options["latex"]["backgroundcolor"]."\" />";
    }
    
    static function render_latexalpha() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_alpha]\" type=\"checkbox\" value=\"1\" ".($options["latex"]["alpha"] ? "checked" : null)." />";
    }
    
    static function render_latexcol() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_col]\" size=\"10\" type=\"text\" value=\"".$options["latex"]["textcolor"]."\" />";
    }
    
    static function render_latexcss() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_css]\" size=\"10\" type=\"text\" value=\"".$options["latex"]["cssclass"]."\" />";
    }
    
    static function render_latexheight() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_height]\" size=\"10\" type=\"text\" value=\"".$options["latex"]["height"]."\" />";        
    }
    
    static function render_latexwidth() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_width]\" size=\"10\" type=\"text\" value=\"".$options["latex"]["width"]."\" />";        
    }
    
    static function render_latexlocaloverridesglobal() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[latex_localoverridesglobal]\" type=\"checkbox\" value=\"1\" ".($options["latex"]["localoverridesglobal"] ? "checked" : null)." />";
    }




    static function render_qrcodesection() {
        echo __("This options can be set with the call <strong>[imgtxt type=qrcode]your content[/imgtxt]</strong>. If the content is empty, the permalink (page url) is used", "imagetext");
    }

    static function render_qrcodealttext() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[qrcode_alttext]\" size=\"30\" type=\"text\" value=\"".$options["qrcode"]["alttext"]."\" />";
    }

    static function render_qrcodecss() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[qrcode_css]\" size=\"10\" type=\"text\" value=\"".$options["qrcode"]["cssclass"]."\" />";
    }

    static function render_qrcodesize() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[qrcode_size]\" size=\"10\" type=\"text\" value=\"".$options["qrcode"]["size"]."\" />";        
    }

    static function render_qrcodeerrlevel() {
        $options = get_option("fpx_imagetext_option");
        echo "<select name=\"fpx_imagetext_option[qrcode_errlevel]\">";
        echo "<option value=\"L\" ".($options["qrcode"]["errorlevel"] == "L" ? "selected" : null).">L (".__("recovery of up to 7% data loss", "imagetext").")</option>";
        echo "<option value=\"M\" ".($options["qrcode"]["errorlevel"] == "M" ? "selected" : null).">M (".__("recovery of up to 15% data loss", "imagetext").")</option>";
        echo "<option value=\"Q\" ".($options["qrcode"]["errorlevel"] == "Q" ? "selected" : null).">Q (".__("recovery of up to 25% data loss", "imagetext").")</option>";
        echo "<option value=\"H\" ".($options["qrcode"]["errorlevel"] == "H" ? "selected" : null).">H (".__("recovery of up to 30% data loss", "imagetext").")</option>";
        echo "</select>";
    }

    static function render_qrcodehtmldecode() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[qrcode_htmldecode]\" type=\"checkbox\" value=\"1\" ".($options["qrcode"]["htmldecode"] ? "checked" : null)." />";
    }

    static function render_qrcodelocaloverridesglobal() {
        $options = get_option("fpx_imagetext_option");
        echo "<input name=\"fpx_imagetext_option[qrcode_localoverridesglobal]\" type=\"checkbox\" value=\"1\" ".($options["qrcode"]["localoverridesglobal"] ? "checked" : null)." />";
    }
}

?>