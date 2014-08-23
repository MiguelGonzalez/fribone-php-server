<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rest extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('lector_library');
        $this->load->library('supermercado_library');
        $this->load->library('fridge_library');
        $this->load->library('compra_library');
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
            echo 'ERROR';
        }
    }

    public function entrar($token_user, $id_fridge, $codigo) {
        $agregado = FALSE;
        $id_user = $this->get_id_user_token($token_user);

        if($id_user !== NULL) {
            $productos = $this->supermercado_library->search_productos_codigo_barras($codigo);

            if(is_null($productos) || count($productos) > 1) {
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
            echo 'OK';
        } else {
            echo 'ERROR';
        }
    }

    public function sacar($token_user, $id_fridge, $code, $by = 'codigo_barras') {
        $id_user = $this->get_id_user_token($token_user);
        $sacado = FALSE;

        if($id_user !== NULL) {
            $fridge = $this->fridge_library->get_fridge($id_user, $id_fridge);

            if(!is_null($fridge)) {
                $producto_compra = $this->compra_library->obtener_producto_compra($id_user, $id_fridge, $code, $by);
                if(!is_null($producto_compra)) {
                    $id_producto_compra = $producto_compra->id;

                    $res = $this->fridge_library->sacar_producto_compra($id_user, $id_fridge, $id_producto_compra);

                    if($res) {
                        $sacado = TRUE;
                    }
                }
            }
        }

        if($sacado) {
            echo "OK";
        } else {
            echo "ERROR";
        }
    }

    public function desvincular($token_user) {
        echo 'ERROR';
    }

    public function fridges($token_user) {
        $id_user = $this->get_id_user_token($token_user);

        if(!is_null($id_user)) {
            $fridges = $this->fridge_library->get_fridges($id_user);

            echo count($fridges);
            foreach($fridges as $fridge) {
                echo '\n' . $fridge->id . '\n' . $fridge->titulo;
            }
        } else {
            echo 'ERROR';
        }
    }

    private function get_id_user_token($token_user) {
        return $this->lector_library->get_user_public_key($token_user);
    }
}