<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supermercado extends MY_Controller_User {

    public function __construct() {
        parent::__construct();

        $this->load->library('supermercado_library');
    }

    public function get_supermercados() {
        $supermercados = $this->supermercado_library->get_supermercados();

        $this->_renderJson(array('supermercado' => $supermercados));
    }

    public function get_supermercado($id_supermercado) {
        $supermercado = $this->supermercado_library->get_supermercado($id_supermercado);

        $this->_renderJson($supermercado);
    }

    public function crear_supermercado() {
        $nombre = $this->input->post('nombre');

        $res = $this->supermercado_library->crear_supermercado($nombre);

        if(!is_null($res)) {
            $supermercado = $this->supermercado_library->get_supermercado($res['supermercado_id']);

            $this->_renderJson(array('ok' => true, 'supermercado' => $supermercado));
        } else {
            $this->_renderJson(array('ok' => false));
        }
    }

    public function anadir_producto_supermercado($id_supermercado) {
        $datos = array(
            'titulo' => $this->input->post('titulo'),
            'precio' => $this->input->post('precio'),
            'unidades' => $this->input->post('unidades'),
            'descripcion' => $this->input->post('descripcion'),
            'codigo_barras' => $this->input->post('codigo_barras'),
            'codigo_rfid' => $this->input->post('codigo_rfid')
        );

        $res = $this->supermercado_library->anadir_producto_supermercado($id_supermercado, $datos);

        if(!is_null($res)) {
            $producto = $this->supermercado_library->get_producto_supermercado($res['id_producto']);

            $this->_renderJson(array('ok' => true, 'producto' => $producto));
        } else {
            $this->_renderJson(array('ok' => false));
        }
    }

    public function search_productos_codigo_barras($codigo_barras) {
        $productos = $this->supermercado_library->search_productos_codigo_barras($codigo_barras);

        $this->_renderJson(array('producto' => $productos));
    }
}