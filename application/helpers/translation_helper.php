<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('current_lang')) {
    /**
     * Get active language code ('fr' or 'en')
     */
    function current_lang() {
        $CI =& get_instance();
        if (isset($CI->session) && $CI->session->userdata('site_lang')) {
            $lang = strtolower($CI->session->userdata('site_lang'));
            return $lang === 'en' ? 'en' : 'fr';
        }
        if (isset($_COOKIE['site_lang'])) {
            return strtolower($_COOKIE['site_lang']) === 'en' ? 'en' : 'fr';
        }
        return 'fr';
    }
}

if (!function_exists('t')) {
    /**
     * Return translated string based on current language
     * Default is French ($fr), fallback/switch is English ($en)
     */
    function t($fr, $en = '') {
        if (empty($en)) {
            $en = $fr;
        }
        return current_lang() === 'en' ? $en : $fr;
    }
}
