<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Switch application language
     */
    public function switch_lang($lang = 'fr') {
        $lang = strtolower($lang);
        if ($lang !== 'en') {
            $lang = 'fr';
        }

        // Set session
        $this->session->set_userdata('site_lang', $lang);

        // Set cookie for 1 year
        setcookie('site_lang', $lang, time() + (86400 * 365), '/');

        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => 'success', 'lang' => $lang]);
            return;
        }

        // Redirect back to referrer or home
        $referrer = $this->input->server('HTTP_REFERER');
        if ($referrer) {
            redirect($referrer);
        } else {
            redirect(base_url());
        }
    }
}
