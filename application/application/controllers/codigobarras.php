<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Codigobarras extends MY_Controller_User {

    public function __construct() {
        parent::__construct();

        $this->load->library('barcode_library');
    }

    public function generar($digitos) {
        $codeBar = $this->barcode_library->generar_barcode($digitos);
        $codeBar->display();
    }
}