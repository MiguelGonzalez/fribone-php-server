<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

define('LOGIN_STATUS_ACTIVATED', 'A');
define('LOGIN_STATUS_NOT_ACTIVATED', 'I');
define('LOGIN_STATUS_BANNED', 'B');
define('LOGIN_INCORRECT_LOGIN', 'login_1');
define('LOGIN_EMAIL_IN_USE', 'login_2');

class Login_auth {

	private $ci = NULL;
	private $error = NULL;

	public function __construct() {
		$this->ci =& get_instance();

		$this->ci->load->library('session');
		$this->ci->load->model('User');
	}

	public function login($email, $password) {
		if ((strlen($email) > 0) AND (strlen($password) > 0)) {
			$user = $this->ci->User->get_user(
				$email
			);

			if (!is_null($user)) {
				$hashPassword = $user->password;

				if(password_verify($password, $hashPassword)) {
					if ($user->state === LOGIN_STATUS_NOT_ACTIVATED) {
						$this->error = LOGIN_STATUS_NOT_ACTIVATED;
					} elseif ($user->state === LOGIN_STATUS_BANNED) {
						$this->error = LOGIN_STATUS_BANNED;
					} else {
						$this->ci->session->set_userdata(array(
							'id'	=> $user->id,
							'email'	=> $user->email,
							'username'	=> $user->username,
							'permission'	=> $user->permission
						));

						$this->ci->User->clear_login_attempts($user->id);

						$this->ci->User->update_login_info(
							$user->id
						);
						return TRUE;
					}
				} else {
					$this->check_attempts($email);
					$this->error = LOGIN_INCORRECT_LOGIN;
					$this->ci->User->increase_login_attempt($email);	
				}
			} else {
				delay(1);
				$this->error = LOGIN_INCORRECT_LOGIN;
				$this->ci->User->increase_login_attempt($email);
			}
		}
		return FALSE;
	}

	private function check_attempts($email) {
		$numAttemps = $this->ci->User->get_login_attempts($email);

		if($numAttemps === NULL) {
			/* Do Nothing */
		} elseif($numAttemps < 10) {
			sleep($numAttemps);
		} elseif($numAttemps < 20) {
			sleep($numAttemps * 2);
		} else {
			sleep(45);
		}
	}

	public function logout() {
		$this->ci->session->set_userdata(
			array(
				'user_id' => '',
				'username' => '',
				'status' => ''
			)
		);

		$this->ci->session->sess_destroy();
	}

	public function is_logged_in() {
		return $this->ci->session->userdata('id');
	}

	public function get_user_id() {
		return $this->ci->session->userdata('user_id');
	}

	public function get_username() {
		return $this->ci->session->userdata('username');
	}

	public function create_user($username, $email, $password) {
		if ((strlen($email) > 0) AND !$this->is_email_available($email)) {
			$this->error = LOGIN_EMAIL_IN_USE;
		} else {
			$data = array(
				'username'	=> $username,
				'password'	=> password_hash($password, PASSWORD_BCRYPT),
				'email'		=> $email,
				'last_ip'	=> $this->ci->input->ip_address(),
			);

			if (!is_null($res = $this->ci->User->create_user($data))) {
				$data['user_id'] = $res['user_id'];

				unset($data['last_ip']);
				unset($data['password']);
				return $data;
			}
		}
		return NULL;
	}

	public function is_email_available($email) {
		return ((strlen($email) > 0) AND $this->ci->User->is_email_available($email));
	}
}