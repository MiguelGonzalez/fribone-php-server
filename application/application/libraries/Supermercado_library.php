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
}