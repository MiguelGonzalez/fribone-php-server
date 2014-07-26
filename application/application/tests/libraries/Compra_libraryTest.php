<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class Compra_libraryTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('supermercado_model');

        $this->CI->load->library('compra_library');
        $this->CI->load->library('fridge_library');
        $this->CI->load->library('login_auth_library');
    }

    public function testAddProductoCompra() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_library->create_fridge($idUser, 'Mi frigorífico');
        $idFridge = $res['frigorifico_id'];

        $datosProducto = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => '9876431',
            'codigo_rfid' => '123654789',
            'id_supermercado' => 1
        );

        $res = $this->CI->supermercado_model->crear_supermercado('Mi Supermercado');
        $idSupermercado = $res['supermercado_id'];

        $res = $this->CI->supermercado_model->add_supermercado_producto($idSupermercado, $datosProducto);
        $idProducto = $res['id_producto'];

        // Dos compras
        $res = $this->CI->compra_library->anadir_producto($idUser, $idFridge, $idProducto);
        $this->assertTrue($res !== NULL);

        $res = $this->CI->compra_library->anadir_producto($idUser, $idFridge, $idProducto);
        $this->assertTrue($res !== NULL);

        $res = $this->CI->fridge_library->get_compras($idUser, $idFridge);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->total, $datosProducto['precio'] * 2);
    }
}