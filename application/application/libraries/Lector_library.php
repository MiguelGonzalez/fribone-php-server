<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lector_library {

    private $ci = NULL;

    public function __construct() {
        $this->ci =& get_instance();

        $this->ci->load->model('lector_model');
    }

    public function create_lector($id_user, $titulo) {
        if(strlen($titulo) !== 0) {
            $res = $this->ci->lector_model->add_lector_user($id_user, $titulo);

            return $res;
        }
        return NULL;
    }

    public function get_lectores($id_user) {
        $fridges = $this->ci->lector_model->get_lectores_user($id_user);

        return $fridges;
    }

    public function get_lector($id_user, $id_lector) {
        $fridge = $this->ci->lector_model->get_lector_user($id_user, $id_lector);

        return $fridge;
    }

    public function generar_lector_token($id_user, $id_lector) {
        $this->ci->lector_model->desactivar_lector($id_user, $id_lector);

        $fecha_alta_token = date('Y-m-d H:i:s');
        $access_key_1 = (string)(strtotime($fecha_alta_token) + $id_user);
        $access_key_1 =  str_pad($access_key_1, 12, '0',  STR_PAD_LEFT);
        $access_key_2 = $this->generate_token();

        $res = $this->ci->lector_model->anadir_lector_token(
                $id_user,
                $id_lector,
                $access_key_1,
                $access_key_2,
                $fecha_alta_token
        );

        if(!is_null($res)) {
            return $res;
        }

        return NULL;
    }

    public function desactivar_lector($id_user, $id_lector) {
        $res = $this->ci->desactivar_lector($id_user, $id_lector);

        return $res;
    }

    private function generate_token($length = 12) {
        $token = "";
        $token_chars = array_merge(range(0,9));
        for($i=0; $i < $length; $i++) {
            $token .= $token_chars[array_rand($token_chars)];
        }
        return $token;
    }
}