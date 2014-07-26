<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fridge_library {

	private $ci = NULL;

	public function __construct() {
		$this->ci =& get_instance();

		$this->ci->load->model('fridge_model');
        $this->ci->load->library('compra_library');
	}

    public function create_fridge($id_user, $titulo) {
        if(strlen($titulo) !== 0) {
            $res = $this->ci->fridge_model->add_frigorifico_user($id_user, $titulo);

            return $res;
        }
        return NULL;
    }

	public function get_fridges($id_user) {
		$fridges = $this->ci->fridge_model->get_frigorificos_user($id_user);

		return $fridges;
	}

    public function get_fridge($id_user, $id_fridge) {
        $fridge = $this->ci->fridge_model->get_fridge_user($id_user, $id_fridge);

        return $fridge;
    }

    public function get_productos_fridge($id_user, $id_fridge) {
        $items = $this->ci->fridge_model->get_productos_fridge($id_user, $id_fridge);

        return $items;
    }

    public function get_item_fridge($id_user, $id_fridge, $id_producto_compra) {
        $item = $this->ci->fridge_model->get_item_fridge($id_user, $id_fridge, $id_producto_compra);

        return $item;
    }

    public function anadir_producto_compra($id_user, $id_fridge, $id_producto) {
        $res = $this->ci->compra_library->anadir_producto(
            $id_user,
            $id_fridge,
            $id_producto
        );

        if(!is_null($res)) {
            $id_producto_compra = $res['producto_compra_id'];

            $res = $this->ci->fridge_model->anadir_producto_compra(
                $id_user,
                $id_fridge,
                $id_producto_compra
            );

            return $res;
        }

        return NULL;
    }

    public function get_compras($id_user, $id_fridge) {
        $compras = $this->ci->fridge_model->get_compras_fridge($id_user, $id_fridge);

        return $compras;
    }

    public function get_compra($id_user, $id_compra) {
        $compra = $this->ci->fridge_model->get_compra($id_user, $id_compra);

        if(!is_null($compra)) {
            $compra->producto = $this->ci->fridge_model->get_compra_productos($id_user, $id_compra);

            foreach($compra->producto as &$producto) {
                $producto->precio_total = $producto->num_productos * $producto->precio;
            }
        }

        return $compra;
    }
}