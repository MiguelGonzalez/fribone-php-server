<?php

class User extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_user($email) {
		$this->db->select('id');
		$this->db->select('password');
		$this->db->select('email');
		$this->db->select('username');
		$this->db->select('permission');
		$this->db->select('state');
		$this->db->select('last_access');
		$this->db->from('user');
		$this->db->where('LOWER(email)', strtolower(trim($email)));

		$query = $this->db->get();

		if ($query->num_rows() === 1) {
        	return $query->row();
		}
		return NULL;
	}

	public function create_user($email, $username, $password) {
		$data = array(
			'email' => trim($email),
			'username' => $username,
			'password' => $password,
			'permission' => 1,
			'last_ip' => $this->input->ip_address()
		);		

		$this->db->insert('user', $data);

		if ($this->db->affected_rows() === 1) {
			$user_id = $this->db->insert_id();

			return array('user_id' => $user_id);
		}

		return NULL;
	}

	public function change_password($email, $password) {
		$this->db->set('password', $password);
		$this->db->where('LOWER(email)', strtolower(trim($email)));
		$this->db->where('state','A');

		$this->db->update('user');

		return $this->db->affected_rows() === 1;
	}

	public function update_login_info($user_id) {
		$last_ip = $this->input->ip_address();

		$data = array(
			'last_ip' => $this->input->ip_address(),
			'last_access' => date('Y-m-d H:i:s')
		);
		$this->db->where('id', $user_id);
		$this->db->update('user', $data);
		$this->db->where('state','A');

		return $this->db->affected_rows() === 1;
	}

	public function increase_login_attempt($email) {
		$login_attempts = $this->get_login_attempts($email);

		if($login_attempts === NULL) {
			$login_attempts = 1;
		} else {
			$login_attempts = $login_attempts + 1;
		}

		$this->db->set('login_attempts', $login_attempts);
		$this->db->where('LOWER(email)', strtolower(trim($email)));
		$this->db->where('state','A');

		$this->db->update('user');
		return $this->db->affected_rows() === 1;
	}

	public function get_login_attempts($email) {
		$this->db->select('login_attempts');
		$this->db->from('user');
		$this->db->where('state','A');
		$this->db->where('LOWER(email)', strtolower(trim($email)));

		$query = $this->db->get();
		if ($query->num_rows() === 1) {
        	return $query->row()->login_attempts;
		}
		return NULL;
	}

	public function clear_login_attempts($id_user) {
		$this->db->set('login_attempts', 0);
		$this->db->where('id', $id_user);
		$this->db->where('state','A');
		$this->db->update('user');
		
		return $this->db->affected_rows() === 1;
	}

	public function is_email_available($email) {
		$this->db->select('1', FALSE);
		$this->db->from('user');
		$this->db->where('LOWER(email)', strtolower(trim($email)));

		$query = $this->db->get();
		return $query->num_rows() === 0;
	}

	public function add_token_remember($email, $token) {
		$this->pause_for_add_token_remember();

		$this->invalidate_token_remember($email);

		$data = array(
			'email' => trim($email),
			'token' => $token
		);		

		$this->db->insert('user_remember_token', $data);

		if ($this->db->affected_rows() === 1) {
			return TRUE;
		}

		return FALSE;
	}

	private function pause_for_add_token_remember() {
		sleep(1);
	}

	public function get_token_remember($email) {
		$this->db->select('token');
		$this->db->from('user_remember_token');
		$this->db->where('LOWER(email)', strtolower(trim($email)));
		$this->db->where('state','A');

		$query = $this->db->get();
		if ($query->num_rows() === 1) {
        	return $query->row()->token;
		}

		return NULL;
	}

	public function get_token_date_remember($email) {
		$this->db->select('creation_date');
		$this->db->from('user_remember_token');
		$this->db->where('LOWER(email)', strtolower(trim($email)));
		$this->db->where('state','A');

		$query = $this->db->get();
		if ($query->num_rows() === 1) {
        	return $query->row()->creation_date;
		}

		return NULL;
	}

	public function invalidate_token_remember($email) {
		$this->db->set('state', 'I');
		$this->db->where('email', $email);
		$this->db->where('state','A');

		$this->db->update('user_remember_token');
		return $this->db->affected_rows() !== 0;
	}

	public function increase_remember_attemps($email) {
		$attempts = $this->get_remember_attemps($email);

		if(!is_null($attempts)) {
			$attempts++;

			if($attempts === 3) {
				$this->invalidate_token_remember($email);
			} else {
				$this->db->set('attempts', $attempts);
				$this->db->where('email', $email);
				$this->db->where('state','A');

				$this->db->update('user_remember_token');
				return $this->db->affected_rows() !== 0;
			}
		}
	}

	private function get_remember_attemps($email) {
		$this->db->select('attempts');
		$this->db->from('user_remember_token');
		$this->db->where('LOWER(email)', strtolower(trim($email)));
		$this->db->where('state','A');

		$query = $this->db->get();
		if ($query->num_rows() === 1) {
			return $query->row()->attempts;
		}

		return NULL;
	}
}