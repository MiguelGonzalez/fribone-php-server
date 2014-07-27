<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lector extends MY_Controller_User {

    public function __construct() {
        parent::__construct();

        $this->load->library('lector_library');
    }

    public function crear_lector() {
        $titulo = $this->input->post('titulo');

        $res = $this->lector_library->create_lector(
            $this->id_user,
            $titulo
        );

        if(!is_null($res)) {
            $lector = $this->lector_library->get_lector($this->id_user, $res['lector_id']);

            $this->_renderJson(array('ok' => true, 'lector' => $lector));
        } else {
            $this->_renderJson(array('ok' => false));
        }
    }

    public function get_lector($id_lector) {
        $lector = $this->lector_library->get_lector(
            $this->id_user,
            $id_lector
        );

        $this->_renderJson($lector);
    }

    public function generar_lector_token($id_lector) {
        $res = $this->lector_library->generar_lector_token(
            $this->id_user,
            $id_lector
        );

        if(!is_null($res)) {
            $this->_renderJson(array('ok' => true, 'token' => $res));
        } else {
            $this->_renderJson(array('ok' => false));
        }
    }
}