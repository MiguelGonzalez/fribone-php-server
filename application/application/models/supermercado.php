<?php

class Supermercado extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_supermercados() {
		$this->db->select('supermercado.id');
		$this->db->select('supermercado.titulo');
		$this->db->select('supermercado.fecha_alta');
		$this->db->select('supermercado.fecha_modificacion');
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
			'titulo' => trim($titulo)
		);

		$this->db->insert('supermercado', $data);

		if ($this->db->affected_rows() === 1) {
			$supermercado_id = $this->db->insert_id();

			return array('supermercado_id' => $supermercado_id);
		}
		
		return NULL;
	}

	public function get_supermercado_productos($idSupermercado) {
		if(!$this->exist_supermercado($idSupermercado)) {
			return NULL;
		}
		$this->db->select('supermercado_producto.id');
		$this->db->select('supermercado_producto.titulo');
		$this->db->select('supermercado_producto.codigo_barras');
		$this->db->select('supermercado_producto.codigo_rfid');
		$this->db->select('supermercado_producto.descripcion');
		$this->db->select('supermercado_producto.precio');
		$this->db->select('supermercado_producto.unidades');
		$this->db->select('supermercado_producto.fecha_alta');
		$this->db->select('supermercado_producto.fecha_modificacion');
		$this->db->from('supermercado_producto');
		$this->db->where('state','A');
		$this->db->where('id', $idSupermercado);

		$query = $this->db->get();

    	return $query->result();
	}

	public function add_supermercado_producto($idSupermercado, $datosProducto) {
		$data = array(
			'id_supermercado' => $idSupermercado
		);
		$data = array_merge($data, $datosProducto);

		$this->db->insert('supermercado_producto', $data);

		if ($this->db->affected_rows() === 1) {
			$id_producto = $this->db->insert_id();

			return array('id_producto' => $id_producto);
		}
		
		return NULL;
	}

	private function exist_supermercado($idSupermercado) {
		$this->db->select('1', FALSE);
		$this->db->from('supermercado');
		$this->db->where('state','A');
		$this->db->where('id', $idSupermercado);

		$query = $this->db->get();

		return $query->num_rows() === 1;
	}
}