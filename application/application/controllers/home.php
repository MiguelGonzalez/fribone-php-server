<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		if ($this->login_auth->is_logged_in()) {
			$this->data['logged'] = TRUE;
		} else {
			$this->data['logged'] = FALSE;
		}

		$this->_render('home');
	}

	public function login_error() {
		if (!$this->login_auth->is_logged_in()) {
			$this->data['logged'] = FALSE;
			$this->_render('error_login');	
		} else {
			redirect(site_url(
	            array('home')),
	            'refresh'
	        );
		}
	}

	public function register() {
		if (!$this->login_auth->is_logged_in()) {
			$this->_render('register');	
		} else {
			redirect(site_url(
	            array('home')),
	            'refresh'
	        );
		}
	}

	public function register_error() {
		if (!$this->login_auth->is_logged_in()) {
			$this->_render('register_error');	
		} else {
			redirect(site_url(
	            array('home')),
	            'refresh'
	        );
		}
	}

	public function register_submit() {
		if (!$this->login_auth->is_logged_in()) {
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$idUser = $this->login_auth->create_user($username, $email, $password);

			if(!is_null($idUser)) {
				redirect(site_url(
		            array('home')),
		            'refresh'
		        );
			} else {
				redirect(site_url(
		            array('home/register_error')),
		            'refresh'
		        );
			}
		} else {
			redirect(site_url(
	            array('home','login_error')),
	            'refresh'
	        );
		}
	}

	public function login() {
		if (!$this->login_auth->is_logged_in()) {
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			if($this->login_auth->login($email, $password)) {
				echo "LOGIN";
				redirect(site_url(
		            array('home')),
		            'refresh'
		        );
			} else {
				redirect(site_url(
		            array('home','login_error')),
		            'refresh'
		        );
			}
		} else {
			redirect(site_url(
	            array('home')),
	            'refresh'
	        );
		}
	}

	public function logout() {
		$this->login_auth->logout();

		redirect(site_url(
            array('home')),
            'refresh'
        );
	}
}