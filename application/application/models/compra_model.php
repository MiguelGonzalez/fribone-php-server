<?php

class Compra_model extends CI_Model {

    public function create_nueva_compra($id_user, $id_fridge) {
        $data = array(
            'id_frigorifico' => $id_fridge,
            'id_user' => $id_user
        );

        $this->db->insert('compra', $data);

        if ($this->db->affected_rows() === 1) {
            $compra_id = $this->db->insert_id();

            return array('compra_id' => $compra_id);
        }

        return NULL;
    }

    public function get_ultima_compra($id_user, $id_fridge) {
        $this->db->select('compra.id');
        $this->db->select('compra.fecha_compra');
        $this->db->select('compra.total');
        $this->db->from('compra');
        $this->db->where('compra.id_user', $id_user);
        $this->db->where('compra.id_frigorifico', $id_fridge);
        $this->db->order_by('compra.fecha_compra', 'desc');
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            return $query->row();
        }
        return NULL;
    }

    public function anadir_producto_compra($id_user, $id_compra, $producto) {

        $data = array(
            'titulo' => $producto->titulo,
            'descripcion' => $producto->descripcion,
            'unidades' => $producto->unidades,
            'precio' => $producto->precio,
            'codigo_barras' => $producto->codigo_barras,
            'codigo_rfid' => $producto->codigo_rfid,
            'id_compra' => $id_compra,
            'id_producto' => $producto->id,
            'id_supermercado' => $producto->id_supermercado,
        );

        $this->db->insert('compra_producto', $data);

        if ($this->db->affected_rows() === 1) {
            $producto_compra_id = $this->db->insert_id();

            return array(
                'producto_compra_id' => $producto_compra_id,
                'unidades' => $producto->unidades);
        }

        return NULL;
    }

    public function recalcular_total_compra($id_user, $id_compra) {
        $total_compra = $this->get_total_compra($id_user, $id_compra);

        $data = array(
            'total' => $total_compra
        );

        $this->db->where('id', $id_compra);
        $this->db->where('id_user', $id_user);

        $this->db->update('compra', $data);
    }

    private function get_total_compra($id_user, $id_compra) {
        $this->db->select_sum('compra_producto.precio', 'total_compra');
        $this->db->join('compra', 'compra.id = compra_producto.id_compra');
        $this->db->from('compra_producto');
        $this->db->where('compra.id_user', $id_user);
        $this->db->where('compra_producto.id_compra', $id_compra);

        $query = $this->db->get();

        if ($query->num_rows() === 1) {
            return $query->row()->total_compra;
        }
        return 0;
    }
}