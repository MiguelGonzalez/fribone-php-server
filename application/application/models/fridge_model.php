<?php

class Fridge_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function get_frigorificos_user($idUser) {
		$this->db->select('user_frigorifico.id');
		$this->db->select('user_frigorifico.titulo');
		$this->db->select('user_frigorifico.fecha_alta');
		$this->db->select('user_frigorifico.fecha_modificacion');
		$this->db->from('user_frigorifico');
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

    public function get_fridge_user($id_fridge) {
        $this->db->select('user_frigorifico.id');
        $this->db->select('user_frigorifico.titulo');
        $this->db->select('user_frigorifico.fecha_alta');
        $this->db->select('user_frigorifico.fecha_modificacion');
        $this->db->from('user_frigorifico');
        $this->db->where('user_frigorifico.id', $id_fridge);

        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            return $query->row();
        }
        return NULL;
    }

	public function get_items_fridge($id_fridge) {
		if(!$this->exist_fridge($id_fridge)) {
			return NULL;
		}
		$this->db->select('compra_producto.id');

		$this->db->from('compra_producto');
		$this->db->join('compra', 'compra.id = compra_producto.id_compra');
		$this->db->join('user_frigorifico', 'user_frigorifico.id = compra.id_frigorifico');

		$this->db->where('compra_producto.unidades >', 0);
		$this->db->where('user_frigorifico.id', $id_fridge);

		$query = $this->db->get();

    	return $query->result();
	}

	private function exist_fridge($id_fridge) {
		$this->db->select('1', FALSE);
		$this->db->from('my_user_frigorifico');
		$this->db->where('state','A');
		$this->db->where('id', $id_fridge);

		$query = $this->db->get();

		return $query->num_rows() === 1;
	}
}