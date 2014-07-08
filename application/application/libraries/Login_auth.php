<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

define('LOGIN_STATUS_ACTIVATED', 'A');
define('LOGIN_STATUS_NOT_ACTIVATED', 'I');
define('LOGIN_STATUS_BANNED', 'B');
define('LOGIN_INCORRECT_LOGIN', 'login_1');
define('LOGIN_EMAIL_IN_USE', 'login_2');
define('CREATE_NOT_SET_ALL_PARAMETERS', 'create_1');

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

				if($this->is_password_verify($password, $hashPassword)) {
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
				sleep(1);
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
				'email' => '',
				'username' => '',
				'status' => ''
			)
		);

		$this->ci->session->sess_destroy();
	}

	public function is_logged_in() {
		return $this->ci->session->userdata('id') !== FALSE;
	}

	public function get_user_id() {
		return $this->ci->session->userdata('user_id');
	}

	public function get_email() {
		return $this->ci->session->userdata('email');
	}

	public function get_username() {
		return $this->ci->session->userdata('username');
	}

	public function create_user($email, $username, $password) {
		if(strlen($email) === 0 || strlen($username) === 0 || strlen($password) === 0) {
			$this->error = CREATE_NOT_SET_ALL_PARAMETERS;
		} elseif (!$this->is_email_available($email)) {
			$this->error = LOGIN_EMAIL_IN_USE;
		} else {
			$resCreate = $this->ci->User->create_user(
				$email,
				$username,
				$this->get_password_hash($password, PASSWORD_BCRYPT)
			);
			if (!is_null($resCreate)) {
				$data['user_id'] = $resCreate['user_id'];

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

	private function get_password_hash($password) {
		$password = NULL;

		if (strnatcmp(phpversion(),'5.5.0') >= 0) {
			$password = password_hash($password, PASSWORD_BCRYPT);
		} else {
			$password = crypt($password);
		}

		return $password;
	}

	private function is_password_verify($password, $hashed_password) {
		$verify = FALSE;

		if (strnatcmp(phpversion(),'5.5.0') >= 0) {
			$verify = password_verify($password, $hashed_password);
		} else {
			$verify = crypt($password, $hashed_password);
		}

		return $verify;
	}
}