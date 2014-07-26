<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller_User extends MY_Controller {

    protected $id_user;

    function __construct() {
        parent::__construct();

        if (!$this->login_auth_library->is_logged_in()) {
            redirect(
                site_url(),
                'refresh'
            );
        } else {
            $this->id_user = $this->login_auth_library->get_user_id();
        }
    }
}