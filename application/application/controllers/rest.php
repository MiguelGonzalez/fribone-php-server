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

            if(is_null($productos) || count($productos) != 1) {
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
        $id_user = $this->get_id_user_token($token_user);
        $desactivado = FALSE;

        if($id_user !== NULL) {
            $id_lector = $this->lector_library->get_id_lector($token_user);

            if(!is_null($id_lector)) {
                $res = $this->lector_library->desactivar_lector($id_user, $id_lector);

                if(!is_null($res)) {
                    $desactivado = TRUE;
                }
            }
        }

        if($desactivado) {
            echo "OK";
        } else {
            echo "ERROR";
        }
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

    public function next_fridge($token_user, $actual_fridge) {
        $id_user = $this->get_id_user_token($token_user);

        if(!is_null($id_user)) {
            $fridges = $this->fridge_library->get_fridges($id_user);

            if(count($fridges) === 0) {
                echo '-1';
            } else {
                if($actual_fridge === -1) {
                    echo $fridges[0]->id . '|' . substr($fridges[0]->titulo, 0, 40);
                } else {
                    $enc_id = FALSE;
                    $imp_fridge = FALSE;
                    foreach($fridges as $fridge) {
                        if(!$enc_id) {
                            if($fridge->id === $actual_fridge) {
                                $enc_id = TRUE;
                            }
                        } else {
                            if(!$imp_fridge) {
                                $imp_fridge = TRUE;
                                echo $fridge->id . '|' . substr($fridge->titulo, 0, 40);
                            }
                        }
                    }

                    if(!$enc_id || !$imp_fridge) {
                        echo $fridges[0]->id . '|' . substr($fridges[0]->titulo, 0, 40);
                    }
                }
            }
        } else {
            echo 'ERROR';
        }
    }

    private function get_id_user_token($token_user) {
        return $this->lector_library->get_user_public_key($token_user);
    }
}