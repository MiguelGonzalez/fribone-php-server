<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablon extends MY_Controller_User {

	public function __construct() {
		parent::__construct();

		$this->data['username'] = $this->login_auth->get_username();
		$this->template = 'tablon';
	}

	public function index() {
		$this->_render('tablon');
	}

	public function logout() {
		$this->login_auth->logout();

		redirect(
			site_url(),
            'refresh'
        );
	}
}