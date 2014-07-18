<?php

class Frigorifico extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_frigorificos_user($idUser) {
		$this->db->select('user_frigorifico.id');
		$this->db->select('user_frigorifico.titulo');
		$this->db->select('user_frigorifico.fecha_alta');
		$this->db->select('user_frigorifico.fecha_modificacion');
		$this->db->select_sum('compra_producto.unidades', 'num_productos');
		$this->db->from('user_frigorifico');
		$this->db->join('compra', 'compra.id_frigorifico = user_frigorifico.id', 'left');
		$this->db->join('compra_producto', 'compra_producto.id_compra = compra.id', 'left');
		$this->db->where('user_frigorifico.id_user', $idUser);
		$this->db->group_by('user_frigorifico.id');

		$query = $this->db->get();

		return $query->result();
	}

	public function add_frigorifico_user($idUser, $titulo) {
		$data = array(
			'id_user' => $idUser,
			'titulo' => trim($titulo)
		);

		$this->db->insert('user_frigorifico', $data);

		if ($this->db->affected_rows() === 1) {
			$frigorifico_id = $this->db->insert_id();

			return array('frigorifico_id' => $frigorifico_id);
		}

		return NULL;
	}
}