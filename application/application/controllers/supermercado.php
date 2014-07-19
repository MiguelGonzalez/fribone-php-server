<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Supermercado extends MY_Controller_User {

    public function __construct() {
        parent::__construct();

        $this->load->library('supermercado_library');
    }

    public function get_supermercados() {
        $supermercados = $this->supermercado_library->get_supermercados();

        $this->_renderJson(array('supermercado' => $supermercados));
    }
}