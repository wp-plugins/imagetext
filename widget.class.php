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

/** widget class **/
class qrcodewidget extends \WP_Widget {
    
    /** constructor **/
    function __construct() {
        parent::__construct( "fpx_imagetext_option", "Image Text QR Code", array("description" => __("creates an image with the QR code of the page url", "imagetext")) );	
    }
    
    /** overloaded widget method with output
     * @param $args arguments
     * @param $instance widget instance
    **/
    function widget($args, $instance) {
        if (!is_singular())
            return;

        extract( $args );

        $urldata 			= array();
        $urldata["cht"] 	= "qr";
        $urldata["chs"]		= $instance["size"]."x".$instance["size"];
        $urldata["chl"] 	= get_permalink();
        $urldata["chf"]		= "a,s,FF";
        $urldata["chld"]	= $instance["errorlevel"];

        echo "<img src=\"https://chart.googleapis.com/chart?".http_build_query($urldata)."\" ";
        if (!empty($instance["alttext"]))
            echo "alttext=\"".$instance["alttext"]."\" ";
        if (!empty($instance["cssclass"]))
            echo "class=\"".$instance["cssclass"]."\" ";
        echo "/>";
    }
    
    /** update method for form data
     * @param $new_instance new widget instance
     * @param $old_instance old widget instance
    **/
    function update($new_instance, $old_instance) {				
        $old_instance["size"]        = abs( intval($new_instance["size"]) );
        $old_instance["alttext"]     = $new_instance["alttext"];
        $old_instance["cssclass"]    = $new_instance["cssclass"];
        $old_instance["errorlevel"]  = $new_instance["errorlevel"];
        return $old_instance;
    }

    /** render form data
     * @param $instance widget instance
    **/
    function form($instance) {
        echo "<p><label for=\"".$this->get_field_id("size")."\">".__("image size", "imagetext").":</label><br/><input name=\"".$this->get_field_name("size")."\" type=\"text\" value=\"".esc_attr($instance["size"])."\" /></p>";
        echo "<p><label for=\"".$this->get_field_id("alttext")."\">".__("alternate image text", "imagetext").":</label><br/><input name=\"".$this->get_field_name("alttext")."\" type=\"text\" value=\"".esc_attr($instance["alttext"])."\" /></p>";
        echo "<p><label for=\"".$this->get_field_id("cssclass")."\">".__("CSS class", "imagetext").":</label><br/><input name=\"".$this->get_field_name("cssclass")."\" type=\"text\" value=\"".esc_attr($instance["cssclass"])."\" /></p>";
        echo "<p><label for=\"".$this->get_field_id("errorlevel")."\">".__("error level", "imagetext").":</label><br/><select name=\"".$this->get_field_name("errorlevel")."\"><option value=\"L\" ".(esc_attr($instance["errorlevel"]) == "L" ? "selected" : null).">L (".__("recovery of up to 7% data loss", "imagetext").")</option><option value=\"M\" ".(esc_attr($instance["errorlevel"]) == "M" ? "selected" : null).">M (".__("recovery of up to 15% data loss", "imagetext").")</option><option value=\"Q\" ".(esc_attr($instance["errorlevel"]) == "Q" ? "selected" : null).">Q (".__("recovery of up to 25% data loss", "imagetext").")</option><option value=\"H\" ".(esc_attr($instance["errorlevel"]) == "H" ? "selected" : null).">H (".__("recovery of up to 30% data loss","imagetext").")</option></select></p>";
    }   

}


?>