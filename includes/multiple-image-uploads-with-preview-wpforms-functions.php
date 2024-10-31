<?php
/**
 * Functions File
 * 
 * PHP version 7 
 * 
 * @category Plugin
 * @package  Multiple_Image_Uploads_With_Preview_For_WPForms
 * @author   P5Cure <meetp5cure@gmail.com>
 * @license  GPLv2 or later
 * @link     http://www.gnu.org/licenses/gpl-2.0.html
 */

defined("ABSPATH") or exit;

if (!function_exists("miuwp_wpf_generate_data")) {

    /**
     * Generate data
     * 
     * @param $str string
     * 
     * @return array
     */
    function miuwp_wpf_generate_data($str)
    {
        $str = str_replace("\\", "", $str);
        $str = str_replace("[", "", $str);
        $str = str_replace("]", "", $str);
        $str = str_replace("\"\"", "", $str);
        $str = array_unique(explode(",", $str));
        return explode(",", implode(",", $str));
    }
}
