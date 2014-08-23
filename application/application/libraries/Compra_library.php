<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Compra_library {

    private $ci = NULL;

    public function __construct() {
        $this->ci =& get_instance();

        $this->ci->load->model('supermercado_model');
        $this->ci->load->model('compra_model');
    }


    public function anadir_producto($id_user, $id_fridge, $id_producto) {
        $compra_activa = $this->get_compra_activa($id_user, $id_fridge);
        $producto = $this->ci->supermercado_model->get_producto_supermercado(
            $id_producto);

        if(is_null($compra_activa) && !is_null($producto)) {
            $res = $this->crear_nueva_compra($id_user, $id_fridge);

            if(!is_null($res)) {
                $compra_activa = $this->get_compra_activa($id_user, $id_fridge);
            }
        }

        if(!is_null($compra_activa) && !is_null($producto)) {
            $res = $this->ci->compra_model->anadir_producto_compra(
                    $id_user,
                    $compra_activa->id,
                    $producto);

            return $res;
        }

        return NULL;
    }

    public function obtener_producto_compra($id_user, $id_fridge, $code, $by = 'codigo_barras') {
        $producto = $this->ci->compra_model->obtener_producto_compra($id_user, $id_fridge, $code, $by);

        return $producto;
    }

    private function get_compra_activa($id_user, $id_fridge) {
        $ultima_compra = $this->ci->compra_model->get_ultima_compra($id_user, $id_fridge);

        if(!is_null($ultima_compra)) {
            $date_compra = $ultima_compra->fecha_compra;
            $system_time = strtotime(date('Y-m-d H:i:s'));


            if($system_time <= strtotime($date_compra) + 60 * 30) {
                return $ultima_compra;
            }
        }

        return NULL;
    }

    private function crear_nueva_compra($id_user, $id_fridge) {
        $nueva_compra = $this->ci->compra_model->create_nueva_compra($id_user, $id_fridge);

        return $nueva_compra;
    }
}