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

/** class for doing content filtering **/
class filter {
    
    /** we using sessions to communicate **/
    static function init() {
        @session_start();
    }
    
    
    /** content filter function for get the tags
     * @param $pcContent Content
     **/
    static function run($pcContent) {
        return preg_replace_callback("!\[imgtxt(.*)\](.*)\[/imgtxt\]!isU", "self::action", $pcContent);
    }
    
    
    /** create action and the image tag
     * @param $pa Array with founded regular expressions
     * @return replace image tag or null on error
     **/
    static function action($pa) {
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
        
        return link::get($param["type"], $pa[2], $param);
    }
    
}
    
?>