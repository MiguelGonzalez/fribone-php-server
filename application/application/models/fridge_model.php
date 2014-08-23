<?php

class Fridge_model extends CI_Model {

	public function get_frigorificos_user($id_user) {
		$this->db->select('user_frigorifico.id');
		$this->db->select('user_frigorifico.titulo');
		$this->db->select('user_frigorifico.fecha_alta');
		$this->db->select('user_frigorifico.fecha_modificacion');
		$this->db->from('user_frigorifico');
		$this->db->where('user_frigorifico.id_user', $id_user);
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

    public function get_fridge_user($id_user, $id_fridge) {
        $this->db->select('user_frigorifico.id');
        $this->db->select('user_frigorifico.titulo');
        $this->db->select('user_frigorifico.fecha_alta');
        $this->db->select('user_frigorifico.fecha_modificacion');
        $this->db->from('user_frigorifico');
        $this->db->where('user_frigorifico.id_user', $id_user);
        $this->db->where('user_frigorifico.id', $id_fridge);

        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            return $query->row();
        }
        return NULL;
    }

	public function get_productos_fridge($id_user, $id_fridge) {
		if(!$this->exist_fridge($id_fridge)) {
			return NULL;
		}

        $this->db->select('compra_producto.id');
        $this->db->select('compra_producto.titulo');
        $this->db->select('compra_producto.descripcion');
        $this->db->select('compra_producto.precio');
        $this->db->select_count('compra_producto.codigo_barras', 'unidades');
        $this->db->select('compra_producto.fecha_entrada');
        $this->db->select('compra_producto.codigo_barras');
        $this->db->select('compra_producto.codigo_rfid');

		$this->db->from('compra_producto');
		$this->db->join('user_frigorifico_producto', 'user_frigorifico_producto.id_producto_compra = compra_producto.id');
        $this->db->where('user_frigorifico_producto.id_user', $id_user);
		$this->db->where('user_frigorifico_producto.id_frigorifico', $id_fridge);

        $this->db->group_by('compra_producto.codigo_barras');

		$query = $this->db->get();

    	return $query->result();
	}

    public function get_item_fridge($id_user, $id_fridge, $id_producto_compra) {
        if(!$this->exist_fridge($id_fridge)) {
            return NULL;
        }
        $this->db->select('compra_producto.id');
        $this->db->select('compra_producto.titulo');
        $this->db->select('compra_producto.descripcion');
        $this->db->select('compra_producto.unidades');
        $this->db->select('compra_producto.precio');
        $this->db->select('compra_producto.codigo_barras');
        $this->db->select('compra_producto.codigo_rfid');
        $this->db->select('compra_producto.fecha_entrada');
        $this->db->select('supermercado.titulo as titulo_supermercado');
        $this->db->join('supermercado', 'supermercado.id = compra_producto.id_supermercado');

        $this->db->from('compra_producto');
        $this->db->join('compra', 'compra.id = compra_producto.id_compra');
        $this->db->where('compra.id_user', $id_user);
        $this->db->where('compra_producto.unidades >', 0);
        $this->db->where('compra_producto.id', $id_producto_compra);

        $query = $this->db->get();

        if ($this->db->affected_rows() === 1) {
            return $query->row();
        }

        return NULL;
    }

    public function anadir_producto_compra($id_user, $id_fridge, $id_producto_compra) {
        if(!$this->exist_fridge($id_fridge)) {
            return NULL;
        }

        $data = array(
            'id_user' => $id_user,
            'id_frigorifico' => $id_fridge,
            'id_producto_compra' => $id_producto_compra
        );

        $this->db->insert('user_frigorifico_producto', $data);

        if ($this->db->affected_rows() === 1) {
            return $data;
        }

        return NULL;
    }

    public function sacar_producto_compra($id_user, $id_fridge, $id_producto_compra) {
        $this->db->where('id_user', $id_user);
        $this->db->where('id_frigorifico', $id_fridge);
        $this->db->where('id_producto_compra', $id_producto_compra);

        $this->db->delete('user_frigorifico_producto');

        if ($this->db->affected_rows() === 1) {
            return TRUE;
        }

        return FALSE;
    }

    public function get_compras_fridge($id_user, $id_fridge) {
        $this->db->select('compra.id');
        $this->db->select('compra.fecha_compra');
        $this->db->select('compra.total');
        $this->db->select('compra.id_frigorifico');
        $this->db->select_count('compra_producto.id', 'num_productos');
        $this->db->from('compra');
        $this->db->join('compra_producto', 'compra_producto.id_compra = compra.id', 'left');
        $this->db->where('compra.id_user', $id_user);
        $this->db->where('compra.id_frigorifico', $id_fridge);
        $this->db->group_by('compra.id');

        $query = $this->db->get();

        return $query->result();
    }

    public function get_compra($id_user, $id_compra) {
        $this->db->select('compra.id');
        $this->db->select('compra.fecha_compra');
        $this->db->select('compra.total');
        $this->db->select('compra.id_frigorifico');
        $this->db->select_count('compra_producto.id', 'num_productos');
        $this->db->from('compra');
        $this->db->join('compra_producto', 'compra_producto.id_compra = compra.id', 'left');
        $this->db->where('compra.id', $id_compra);
        $this->db->where('compra.id_user', $id_user);
        $this->db->group_by('compra.id');

        $query = $this->db->get();

        if ($this->db->affected_rows() === 1) {
            return $query->row();
        }

        return NULL;
    }

    public function get_compra_productos($id_user, $id_compra) {
        $this->db->select('compra_producto.titulo');
        $this->db->select('compra_producto.descripcion');
        $this->db->select_count('compra_producto.id', 'num_productos');
        $this->db->select_sum('compra_producto.unidades', 'unidades');
        $this->db->select('compra_producto.precio');
        $this->db->select('compra_producto.codigo_barras');
        $this->db->select('compra_producto.codigo_rfid');
        $this->db->select('compra_producto.fecha_entrada');
        $this->db->select('supermercado.titulo as titulo_supermercado');
        $this->db->from('compra_producto');
        $this->db->join('compra', 'compra.id = compra_producto.id_compra');
        $this->db->join('supermercado', 'supermercado.id = compra_producto.id_supermercado');
        $this->db->where('compra.id_user', $id_user);
        $this->db->where('compra_producto.id_compra', $id_compra);
        $this->db->group_by('compra_producto.codigo_barras');

        $query = $this->db->get();

        return $query->result();
    }

	private function exist_fridge($search, $by = 'id') {
		$this->db->select('1', FALSE);
		$this->db->from('user_frigorifico');
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
        $this->db->from('user_frigorifico');
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