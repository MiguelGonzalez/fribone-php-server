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
}