<?php

class Item_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_item($search, $by = 'codigo_barras') {
        $this->db->select('supermercado_producto.id');
        $this->db->select('supermercado_producto.titulo');
        $this->db->select('supermercado_producto.codigo_barras');
        $this->db->select('supermercado_producto.codigo_rfid');
        $this->db->select('supermercado_producto.descripcion');
        $this->db->select('supermercado_producto.precio');
        $this->db->select('supermercado_producto.unidades');
        $this->db->select('supermercado_producto.fecha_alta');
        $this->db->select('supermercado_producto.fecha_modificacion');
        $this->db->select('supermercado_producto.id_supermercado');

        $this->db->from('supermercado_producto');

        $this->db->where('supermercado_producto.state', 'A');
        if($by === 'codigo_barras') {
            $this->db->where('supermercado_producto.codigo_barras', $search);
        } else if($bi === 'codigo_rfid') {
            $this->db->where('supermercado_producto.codigo_rfid', $search);
        } else {
            $this->db->where('supermercado_producto.id', $search);
        }

        $query = $this->db->get();

        return $query->result();
    }
}