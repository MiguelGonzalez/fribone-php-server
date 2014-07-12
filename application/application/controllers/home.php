<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->library('My_PHPMailer');
	}

	public function index($logged_error = FALSE) {
		if (defined('ENVIRONMENT') && ENVIRONMENT == 'testing') {
		} else if ($this->login_auth->is_logged_in()) {
			$this->login_ok();
		} else {
			$this->data['error_login'] = $logged_error;
			$this->_render('home');
		}
	}

	public function login() {
		$login_ok = FALSE;
		if (!$this->login_auth->is_logged_in()) {
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$login_ok = $this->login_auth->login($email, $password);
		}
		if($login_ok) {
			$this->login_ok();
		} else {
			$this->login_error();
		}
	}

	private function login_ok() {
		redirect(
			site_url('tablon'),
            'refresh'
        );
	}

	private function login_error() {
		$this->index(TRUE);	
	}

	public function register() {
		if ($this->login_auth->is_logged_in()) {
			$this->login_ok();
			return;
		}
		$register_ok = FALSE;
		if (!$this->login_auth->is_logged_in()) {
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$idUser = $this->login_auth->create_user($email, $username, $password);
			$register_ok = !is_null($idUser);
		}
		if($register_ok) {
			$this->_renderJson(array('result' => true));
		} else {
			$this->_renderJson(array('result' => false));
		}
	}

	public function remember() {
		if ($this->login_auth->is_logged_in()) {
			$this->login_ok();
			return;
		}
		$remember_ok = FALSE;
		if (!$this->login_auth->is_logged_in()) {
			$email = $this->input->post('email');
			
			$token = $this->login_auth->password_remember($email);

			if(!is_null($token)) {
				$data_token['remember_token'] = $token;
		        $remember_ok = $this->my_phpmailer->send_email(
	        		$email,
	        		'Remember password',
	        		$data_token,
	        		'remember_password');
			}
		}
		if($remember_ok) {
			$this->_renderJson(array('result' => true));
		} else {
			$this->_renderJson(array('result' => false));
		}	
	}

	public function validation_email() {
		$email = $this->input->post('email');

		if($this->login_auth->is_email_available($email, $password)) {
			$this->_renderJson(array('value' => $email,'valid' => true, 'message' => ''));
		} else {
			$this->_renderJson(array('value' => $email,'valid' => false, 'message' => 'Correo electrónico en uso'));
		}
	}
}