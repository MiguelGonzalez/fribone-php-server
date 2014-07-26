<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fridge extends MY_Controller_User {

	public function __construct() {
		parent::__construct();

        $this->load->library('fridge_library');
	}

    public function create_fridge() {
        $titulo = $this->input->post('titulo');

        $res = $this->fridge_library->create_fridge(
            $this->id_user,
            $titulo
        );

        if(!is_null($res)) {
            $frigorifico = $this->fridge_library->get_fridge($this->id_user, $res['frigorifico_id']);

            $this->_renderJson(array('ok' => true, 'frigorifico' => $frigorifico));
        } else {
            $this->_renderJson(array('ok' => false));
        }
    }

	public function get_fridge($id_fridge) {
		$fridge = $this->fridge_library->get_fridge(
            $this->id_user,
            $id_fridge
        );

        $this->_renderJson($fridge);
	}

    public function get_productos_fridge($id_fridge) {
        $items = $this->fridge_library->get_productos_fridge(
            $this->id_user,
            $id_fridge
        );

        $this->_renderJson(array('producto' => $items));
    }

    public function anadir_producto($id_fridge, $id_producto) {
        $res_compra = $this->compra_library->anadir_producto(
            $this->id_user,
            $id_fridge,
            $id_producto
        );

        $res = NULL;
        if(!is_null($res_compra)) {
            $res = $this->fridge_library->anadir_producto_compra(
                $this->id_user,
                $id_fridge,
                $res_compra['producto_compra_id'],
                $res_compra['unidades']
            );
        }

        if(!is_null($res)) {
            $producto = $this->fridge_library->get_item_fridge(
                $this->id_user,
                $id_fridge,
                $res['id_producto_compra']
            );

            $this->_renderJson(array('ok' => true, 'producto' => $producto));
        } else {
            $this->_renderJson(array('ok' => false));
        }
    }

    public function get_compras($id_fridge) {
        $compras = $this->fridge_library->get_compras($this->id_user, $id_fridge);

        $this->_renderJson(array('compra' => $compras));
    }

    public function get_compra($id_compra) {
        $compra = $this->fridge_library->get_compra($this->id_user, $id_compra);

        $this->_renderJson($compra);
    }
}