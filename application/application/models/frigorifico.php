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
		$this->db->select_count('compra_producto.id', 'num_productos');
		$this->db->from('user_frigorifico');
		$this->db->join('compra', 'compra.id_frigorifico = user_frigorifico.id', 'left');
		$this->db->join('compra_producto', 'compra_producto.id_compra = compra.id', 'left');
		$this->db->where('user_frigorifico.id_user', $idUser);
		$this->db->where('compra_producto.unidades > ', 0);
		$this->db->group_by('compra_producto.id'); 

		$query = $this->db->get();

		return $query->result();
	}

}