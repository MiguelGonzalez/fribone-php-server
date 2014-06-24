<?php
class User extends CI_Model {

	const IS_INVITED = 0;
	const IS_ADMIN = 2;

	protected $userData = Array();

	public function __construct() {
		parent::__construct();

		$this->load->library('session');
		$this->load->database();

		$this->inicializateUserData();
	}

	private function inicializateUserData() {
		$this->userData["idUser"] = 0;
		$this->userData["permission"] = 0;

		$idUser = $this->session->userdata('idUser');
		if(isset($idUser)) {
			$this->db->select('permission');
			$this->db->from('my_user');
			$this->db->where('state', 'A');
			$this->db->where('idUser', $idUser);

			$queryUser = $this->db->get();

			if ($queryUser->num_rows() > 0) {
				$this->userData["permission"] = $query->row()->permissions;
			}
		}
	}

	public function getIdUser() {
        return $this->userData["idUser"];
    }

    public function isLogged() {
    	return $this->userData["permission"] != self::IS_INVITED;
    }

   	public function isAdmin() {
   		return $this->userData["permission"] == self::IS_ADMIN;
   	}
}