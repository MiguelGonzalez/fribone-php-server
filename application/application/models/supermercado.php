<?php

class Supermercado extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_supermercados() {
		$this->db->select('supermercado.id');
		$this->db->select('supermercado.titulo');
		$this->db->select('supermercado.fecha_alta');
		$this->db->select_count('supermercado_producto.id');
		$this->db->from('supermercado');
		$this->db->join('supermercado_producto', 'supermercado.id = supermercado_producto.id_supermercado', 'left');
		$this->db->group_by('my_supermercado_producto.id'); 

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
        	return $query->result();
		}
		return NULL;
	}

	public function add_supermercado($titulo) {
		$data = array(
			'titulo' => trim($titulo),
			'fecha_alta' => date('Y-m-d H:i:s')
		);

		$this->db->insert('supermercado', $data);

		if ($this->db->affected_rows() === 1) {
			$supermercado_id = $this->db->insert_id();

			return array('supermercado_id' => $supermercado_id);
		}
		
		return NULL;
	}
}