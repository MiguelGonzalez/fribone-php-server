<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class BarCode_library {

    private $ci = NULL;

    public function __construct() {
        require_once('BarCode/barcode.php');
    }

    public function generar_barcode($number) {
        $barCode = new Barcode($number);
        $barCode->display();
    }
}