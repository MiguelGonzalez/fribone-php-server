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

	public function add_frigorifico_user($id_user, $titulo) {
        if($this->exist_user_fridge($id_user, $titulo, 'titulo')) {
            return NULL;
        }
		$data = array(
			'id_user' => $id_user,
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
        $this->db->select('compra_producto.titulo');
        $this->db->select('compra_producto.descripcion');
        $this->db->select('compra_producto.precio');
        $this->db->select('user_frigorifico_producto.unidades');
        $this->db->select('compra_producto.fecha_entrada');
        $this->db->select('compra_producto.codigo_barras');
        $this->db->select('compra_producto.codigo_rfid');

		$this->db->from('compra_producto');
		$this->db->join('user_frigorifico_producto', 'user_frigorifico_producto.id_producto_compra = compra_producto.id');
		$this->db->where('user_frigorifico_producto.unidades >', 0);
		$this->db->where('user_frigorifico_producto.id_frigorifico', $id_fridge);

		$query = $this->db->get();

    	return $query->result();
	}

    public function get_item_fridge($id_fridge, $id_producto_compra) {
        if(!$this->exist_fridge($id_fridge)) {
            return NULL;
        }
        $this->db->select('compra_producto.id');

        $this->db->from('compra_producto');
        $this->db->where('compra_producto.unidades >', 0);
        $this->db->where('compra_producto.id', $id_producto_compra);

        $query = $this->db->get();

        if ($this->db->affected_rows() === 1) {
            return $query->row();
        }

        return NULL;
    }

    public function anadir_producto_compra($id_user, $id_fridge, $id_producto_compra, $unidades) {
        if(!$this->exist_fridge($id_fridge)) {
            return NULL;
        }

        $data = array(
            'id_frigorifico' => $id_fridge,
            'id_producto_compra' => $id_producto_compra,
            'unidades' => $unidades
        );

        $this->db->insert('user_frigorifico_producto', $data);

        if ($this->db->affected_rows() === 1) {
            return $data;
        }

        return NULL;
    }

	private function exist_fridge($search, $by = 'id') {
		$this->db->select('1', FALSE);
		$this->db->from('my_user_frigorifico');
		$this->db->where('state','A');
        if($by === 'id') {
            $this->db->where('id', $search);
        } else if ($by === 'titulo') {
            $this->db->where('titulo', $search);
        }

		$query = $this->db->get();
		return $query->num_rows() === 1;
	}

    private function exist_user_fridge($id_user, $search, $by = 'id') {
        $this->db->select('1', FALSE);
        $this->db->from('my_user_frigorifico');
        $this->db->where('state','A');
        $this->db->where('id_user',$id_user);
        if($by === 'id') {
            $this->db->where('id', $search);
        } else if ($by === 'titulo') {
            $this->db->where('titulo', $search);
        }

        $query = $this->db->get();
        return $query->num_rows() === 1;
    }
}