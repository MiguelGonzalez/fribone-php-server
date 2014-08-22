<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rest extends MY_Controller_User {

    public function __construct() {
        parent::__construct();

        $this->load->library('lector_library');
        $this->load->library('supermercado_library');
        $this->load->library('fridge_library');
    }

    public function index() {
        redirect(
            site_url(),
            'refresh'
        );
    }

    public function activar($access_key_1, $access_key_2) {
        $res = $this->lector_library->activar_lector($access_key_1, $access_key_2);

        if(!is_null($res)) {
            $public_key = $res['public_key'];

            echo $public_key;
        } else {
            echo "ERROR";
        }
    }

    public function entrar($token_user, $id_fridge, $codigo) {
        $agregado = FALSE;
        $id_user = $this->lector_library->get_user_public_key($token_user);

        if($id_user !== NULL) {
            $productos = $this->supermercado_library->search_productos_codigo_barras($codigo);

            if(is_null($producto) || count($productos) > 1) {
                $productos = $this->supermercado_library->search_productos_rfid($codigo);
            }

            $id_producto = NULL;
            if(count($productos) === 1) {
                $id_producto = $productos[0]->id;
            }

            if(!is_null($id_producto)) {
                $fridge = $this->fridge_library->get_fridge($id_user, $id_fridge);

                if(!is_null($fridge)) {
                    $res = $this->fridge_library->anadir_producto_compra($id_user, $id_fridge, $id_producto);

                    if(!is_null($res)) {
                        $agregado = TRUE;
                    }
                }
            }
        }
        if($agregado) {
            echo "OK";
        } else {
            echo "ERROR";
        }
    }

    public function sacar($token_user, $id_fridge, $codigo) {
        echo "ERROR";
    }
}