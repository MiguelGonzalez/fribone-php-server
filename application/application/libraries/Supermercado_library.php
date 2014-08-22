<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Supermercado_library {

    private $ci = NULL;

    public function __construct() {
        $this->ci =& get_instance();

        $this->ci->load->model('supermercado_model');
    }

    public function get_supermercados() {
        $supermercados = $this->ci->supermercado_model->get_supermercados();

        return $supermercados;
    }

    public function crear_supermercado($nombre) {
        if(strlen($nombre) === 0) {
            return NULL;
        } else {
            $res = $this->ci->supermercado_model->crear_supermercado($nombre);

            return $res;
        }
    }

    public function get_supermercado($id_supermercado) {
        $supermercado = $this->ci->supermercado_model->get_supermercado($id_supermercado);

        if(!is_null($supermercado)) {
            $supermercado->producto = $this->ci->supermercado_model->get_supermercado_productos($supermercado->id);
        }

        return $supermercado;
    }

    public function anadir_producto_supermercado($id_supermercado, $datos) {
        if(strlen($datos['titulo']) !== 0 && strlen($datos['precio']) !== 0 &&
                strlen($datos['unidades']) !== 0 && strlen($datos['descripcion']) !== 0 &&
                strlen($datos['codigo_barras']) !== 0 ) {

            if(is_numeric($datos['precio']) && $datos['precio'] > 0 &&
                    is_numeric($datos['unidades']) && $datos['unidades'] > 0 &&
                    is_numeric($datos['codigo_barras'])) {

                $res = $this->ci->supermercado_model->add_supermercado_producto($id_supermercado, $datos);

                return $res;
            }

        }

        return NULL;
    }

    public function get_producto_supermercado($id_producto) {
        $producto = $this->ci->supermercado_model->get_producto_supermercado($id_producto);

        return $producto;
    }

    public function search_productos_codigo_barras($codigo_barras) {
        if(strlen($codigo_barras) > 0 && is_numeric($codigo_barras)) {
            $productos = $this->ci->supermercado_model->search_productos_codigo_barras($codigo_barras);

            return $productos;
        }
        return NULL;
    }

    public function search_productos_rfid($codigo_rfid) {
        if(strlen($codigo_rfid) > 0 && is_numeric($codigo_rfid)) {
            $productos = $this->ci->supermercado_model->search_productos_rfid($codigo_rfid);

            return $productos;
        }
        return NULL;
    }
}