<?php

class Supermercado_model extends CI_Model {

	public function get_supermercados() {
		$this->db->select('supermercado.id');
		$this->db->select('supermercado.titulo');
		$this->db->select('supermercado.fecha_alta');
		$this->db->select('supermercado.fecha_modificacion');
		$this->db->select_count('supermercado_producto.id', 'num_productos');
		$this->db->from('supermercado');
		$this->db->join('supermercado_producto', 'supermercado.id = supermercado_producto.id_supermercado', 'left');
		$this->db->group_by('supermercado.id');

		$query = $this->db->get();

    	return $query->result();
	}

    public function get_supermercado($id_supermercado) {
        $this->db->select('supermercado.id');
        $this->db->select('supermercado.titulo');
        $this->db->select('supermercado.fecha_alta');
        $this->db->select('supermercado.fecha_modificacion');
        $this->db->select_count('supermercado_producto.id', 'num_productos');
        $this->db->from('supermercado');
        $this->db->join('supermercado_producto', 'supermercado.id = supermercado_producto.id_supermercado', 'left');
        $this->db->where('supermercado.id', $id_supermercado);
        $this->db->group_by('supermercado.id');

        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            return $query->row();
        }
        return NULL;
    }

	public function crear_supermercado($titulo) {
        if(!$this->exist_supermercado($titulo, 'titulo')) {
    		$data = array(
    			'titulo' => trim($titulo)
    		);

    		$this->db->insert('supermercado', $data);

    		if ($this->db->affected_rows() === 1) {
    			$supermercado_id = $this->db->insert_id();

    			return array('supermercado_id' => $supermercado_id);
    		}
        }

		return NULL;
	}

	public function get_supermercado_productos($id_supermercado) {
		if(!$this->exist_supermercado($id_supermercado)) {
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
		$this->db->where('supermercado_producto.state','A');
		$this->db->where('supermercado_producto.id_supermercado', $id_supermercado);

		$query = $this->db->get();

    	return $query->result();
	}

	public function get_producto_supermercado($id_producto) {
		if(!$this->exist_producto($id_producto)) {
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
		$this->db->select('supermercado.titulo as titulo_supermercado');
        $this->db->select('supermercado.id as id_supermercado');
		$this->db->from('supermercado_producto');
		$this->db->join('supermercado', 'supermercado_producto.id_supermercado = supermercado.id');
		$this->db->where('supermercado_producto.state','A');
		$this->db->where('supermercado_producto.id', $id_producto);

		$query = $this->db->get();

    	if ($query->num_rows() === 1) {
        	return $query->row();
		}
		return NULL;
	}

	public function add_supermercado_producto($id_supermercado, $datos_producto) {
        if($this->exist_supermercado($id_supermercado)) {
    		$data = array(
    			'id_supermercado' => $id_supermercado
    		);
    		$data = array_merge($data, $datos_producto);

            if(strlen($data['codigo_rfid']) === 0) {
                $data['codigo_rfid'] = NULL;
            }

            if(!$this->exist_producto($data['codigo_barras'],'codigo_barras') &&
                ( $data['codigo_rfid'] === NULL || !$this->exist_producto($data['codigo_rfid'],'codigo_rfid') )) {
                $this->db->insert('supermercado_producto', $data);

                if ($this->db->affected_rows() === 1) {
                    $id_producto = $this->db->insert_id();

                    return array('id_producto' => $id_producto);
                }
            }
        }

		return NULL;
	}

    public function search_productos_codigo_barras($codigo_barras) {
        return $this->search_productos_by($codigo_barras, 'codigo_barras');
    }

    public function search_productos_rfid($codigo_rfid) {
        return $this->search_productos_by($codigo_rfid, 'codigo_rfid');
    }

    private function search_productos_by($codigo, $tipo = 'codigo_barras') {
        $this->db->select('supermercado_producto.id');
        $this->db->select('supermercado_producto.titulo');
        $this->db->select('supermercado_producto.codigo_barras');
        $this->db->select('supermercado_producto.codigo_rfid');
        $this->db->select('supermercado_producto.descripcion');
        $this->db->select('supermercado_producto.precio');
        $this->db->select('supermercado_producto.unidades');
        $this->db->select('supermercado_producto.fecha_alta');
        $this->db->select('supermercado_producto.fecha_modificacion');
        $this->db->select('supermercado.titulo as titulo_supermercado');
        $this->db->select('supermercado.id as id_supermercado');
        $this->db->from('supermercado_producto');
        $this->db->join('supermercado', 'supermercado.id = supermercado_producto.id_supermercado');
        $this->db->where('supermercado_producto.state','A');
        if($tipo === 'codigo_barras') {
            $this->db->like('supermercado_producto.codigo_barras', $codigo, 'after');
        } else if ($tipo === 'codigo_rfid') {
            $this->db->like('supermercado_producto.codigo_rfid', $codigo, 'after');
        }
        $this->db->limit(10);

        $query = $this->db->get();

        return $query->result();
    }

	private function exist_supermercado($search, $by = 'id') {
		$this->db->select('1', FALSE);
		$this->db->from('supermercado');
		$this->db->where('state','A');
        if($by === 'id') {
            $this->db->where('id', $search);
        } elseif($by === 'titulo') {
            $this->db->where('titulo', $search);
        }

		$query = $this->db->get();

		return $query->num_rows() === 1;
	}

	private function exist_producto($search, $by = 'id') {
		$this->db->select('1', FALSE);
		$this->db->from('supermercado_producto');
		$this->db->where('state','A');
        if($by === 'id') {
            $this->db->where('id', $search);
        } else if($by === 'codigo_barras') {
            $this->db->where('codigo_barras', $search);
        } else if($by === 'codigo_rfid') {
            $this->db->where('codigo_rfid', $search);
        }

		$query = $this->db->get();

		return $query->num_rows() === 1;
	}
}