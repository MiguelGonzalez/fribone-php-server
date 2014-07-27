<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

define('LOGIN_STATUS_ACTIVATED', 'A');
define('LOGIN_STATUS_NOT_ACTIVATED', 'I');
define('LOGIN_STATUS_BANNED', 'B');
define('LOGIN_INCORRECT_LOGIN', 'login_1');
define('LOGIN_EMAIL_IN_USE', 'login_2');
define('CREATE_NOT_SET_ALL_PARAMETERS', 'create_1');

class Login_auth_library {

	private $ci = NULL;
	private $error = NULL;

	public function __construct() {
		$this->ci =& get_instance();

		$this->ci->load->model('user_model');
		$this->ci->load->helper('email');

        $this->ci->load->library('session');

        $this->check_remember_session();
	}

	public function login($email, $password, $remember = FALSE) {
		if (valid_email($email) AND strlen($password) > 0) {
			$user = $this->ci->user_model->get_user(
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
						if($remember) {
							$this->ci->session->set_sess_expiration(63072000);
							$this->ci->session->set_sess_expire_on_close(FALSE);
						} else {
                            $this->ci->session->set_sess_expire_on_close(TRUE);
                        }

						$this->ci->session->set_userdata(array(
							'user_id'	=> $user->id,
							'email'	=> $user->email,
							'username'	=> $user->username,
							'permission'	=> $user->permission,
							'remember' => $remember
						));

						$this->ci->user_model->clear_login_attempts($user->id);

						$this->ci->user_model->update_login_info(
							$user->id
						);
						return TRUE;
					}
				} else {
					$this->check_attempts($email);
					$this->error = LOGIN_INCORRECT_LOGIN;
					$this->ci->user_model->increase_login_attempt($email);
				}
			} else {
				sleep(1);
				$this->error = LOGIN_INCORRECT_LOGIN;
				$this->ci->user_model->increase_login_attempt($email);
			}
		}
		return FALSE;
	}

	public function create_user($email, $username, $password) {
		if(strlen($email) === 0 || strlen($username) === 0 || strlen($password) === 0) {
			$this->error = CREATE_NOT_SET_ALL_PARAMETERS;
		} elseif (!$this->is_email_available($email)) {
			$this->error = LOGIN_EMAIL_IN_USE;
		} else {
			$resCreate = $this->ci->user_model->create_user(
				$email,
				$username,
				$this->get_password_hash($password)
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

	public function password_remember($email) {
		if (valid_email($email)) {
			$user = $this->ci->user_model->get_user(
				$email
			);

			if (!is_null($user)) {
				$token = uniqid('', true);
				$hashed_token = $this->get_password_hash($token);

				$resCreate = $this->ci->user_model->add_token_remember(
					$email,
					$hashed_token
				);

				if ($resCreate) {
					return $token;
				}
			}
		}

		return NULL;
	}

	public function password_remember_change($email, $token, $new_password) {
		if (valid_email($email)) {
			if($this->check_password_remember($email, $token)) {
				$resCreate = $this->ci->user_model->change_password(
					$email,
					$this->get_password_hash($new_password)
				);

				if($resCreate) {
					return TRUE;
				}
			}

			$this->ci->user_model->increase_remember_attemps($email);
		}
		return FALSE;
	}

	public function logout() {
		$this->ci->session->set_userdata(
			array(
				'user_id' => '',
				'email' => '',
				'username' => '',
				'status' => '',
				'remember' => FALSE
			)
		);

		$this->ci->session->sess_destroy();
	}

	public function is_logged_in() {
		return $this->ci->session->userdata('user_id') !== FALSE;
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

	public function is_email_available($email) {
		return ((strlen($email) > 0) AND $this->ci->user_model->is_email_available($email));
	}

    private function check_remember_session() {
        if($this->is_logged_in()) {
            if($this->ci->session->userdata('remember')) {
                $this->ci->session->set_sess_expiration(63072000);
                $this->ci->session->set_sess_expire_on_close(FALSE);
            } else {
                $this->ci->session->set_sess_expire_on_close(TRUE);
            }
        } else {
            $this->ci->session->set_sess_expire_on_close(TRUE);
        }
    }

	public function is_still_active($email, $system_time = NULL) {
		$date_token = $this->ci->user_model->get_token_date_remember(
			$email
		);

		if(!is_null($date_token)) {
			if(is_null($system_time)) {
				$system_time = strtotime(date('Y-m-d H:i:s'));
			}
			if($system_time <= $this->get_date_hours_active($date_token, 1)) {
				return TRUE;
			}
			$this->ci->user_model->invalidate_token_remember($email);
		}
		return FALSE;
	}

	private function check_attempts($email) {
		$numAttemps = $this->ci->user_model->get_login_attempts($email);

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

	private function check_password_remember($email, $token_check) {
		$token_hashed = $this->ci->user_model->get_token_remember(
			$email
		);

		if (!is_null($token_hashed)) {
			if($this->is_password_verify($token_check, $token_hashed)) {
				if($this->is_still_active($email)) {
					$this->ci->user_model->invalidate_token_remember($email);

					return TRUE;
				}
			}
		}

		return FALSE;
	}

	private function get_date_hours_active($date_token, $num_hours) {
		return strtotime($date_token) + 60 * 60 * $num_hours;
	}

	private function get_password_hash($password) {
        $password_hashed = NULL;

        if (strnatcmp(phpversion(),'5.5.0') >= 0) {
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        } else {
            // Original PHP code by Chirp Internet: www.chirp.com.au
            // Please acknowledge use of this code by including this header.
            $salt = "";
            $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
            for($i=0; $i < 22; $i++) {
                $salt .= $salt_chars[array_rand($salt_chars)];
            }
            $password_hashed = crypt($password, sprintf('$2a$%02d$', 7) . $salt);
        }

        return $password_hashed;
    }

	private function is_password_verify($password, $hashed_password) {
		$verify = FALSE;

		if (strnatcmp(phpversion(),'5.5.0') >= 0) {
			$verify = password_verify($password, $hashed_password);
		} else {
			$verify = crypt($password, $hashed_password) == $hashed_password;
		}

		return $verify;
	}
}