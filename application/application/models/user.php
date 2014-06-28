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
		$this->db->from('user');
		$this->db->where('LOWER(email)', strtolower($email));

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
        	return $query->row();
		}
		return NULL;
	}

	public function create_user($data) {
		$data['permission'] = 1;
		$data['state'] = 'A';
		$data['last_ip'] = $this->input->ip_address();
		$data['last_access'] = date('Y-m-d H:i:s');
		$data['login_attempts'] = 0;

		if ($this->db->insert('user', $data)) {
			$user_id = $this->db->insert_id();
			
			return array('user_id' => $user_id);
		}
		
		return NULL;
	}

	public function update_login_info($user_id) {
		$last_ip = $this->input->ip_address();

		$this->db->set('last_ip', $last_ip);
		$this->db->set('last_access', date('Y-m-d H:i:s'));
		$this->db->where('id', $user_id);
		$this->db->where('state', 'A');

		$this->db->update('user');
		return $this->db->affected_rows() == 1;
	}

	public function increase_login_attempt($email) {
		$login_attempts = $this->get_login_attempts($email);

		if($login_attempts === NULL) {
			$login_attempts = 1;
		} else {
			$login_attempts = $login_attempts + 1;
		}

		$this->db->set('login_attempts', $login_attempts);
		$this->db->where('email', $email);

		$this->db->update('user');
		return $this->db->affected_rows() == 1;
	}

	public function get_login_attempts($email) {
		$this->db->select('login_attempts');
		$this->db->from('user');
		$this->db->where('LOWER(email)', strtolower($email));

		$query = $this->db->get();
		if ($query->num_rows() == 1) {
        	return $query->row()->login_attempts;
		}
		return NULL;
	}

	public function clear_login_attempts($id_user) {
		$this->db->set('login_attempts', 0);
		$this->db->where('id', $id_user);

		$this->db->update('user');
		return $this->db->affected_rows() == 1;
	}

	public function is_email_available($email) {
		$this->db->select('1', FALSE);
		$this->db->from('user');
		$this->db->where('LOWER(email)', strtolower($email));

		$query = $this->db->get();
		return $query->num_rows() == 0;
	}
}