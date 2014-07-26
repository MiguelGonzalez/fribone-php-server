<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class Fridge_libraryTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('compra_model');
        $this->CI->load->model('supermercado_model');

        $this->CI->load->library('fridge_library');
        $this->CI->load->library('compra_library');
        $this->CI->load->library('login_auth_library');
    }

    public function testCreateFridge() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_library->create_fridge($idUser, 'Mi frigorífico');
        $this->assertTrue($res !== NULL);

        $res = $this->CI->fridge_library->create_fridge($idUser, 'Mi frigorífico');
        $this->assertNull($res);
    }

    public function testGetFridges() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_library->create_fridge($idUser, 'Mi frigorífico');
        $this->assertTrue($res !== NULL);

        $idFridge = $res['frigorifico_id'];

        $res = $this->CI->fridge_library->get_fridges($idUser);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->id, $idFridge);

        $res = $this->CI->fridge_library->get_fridge($idUser, $idFridge);
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res->titulo, 'Mi frigorífico');
    }

    public function testFridgeEmptyItems() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_library->create_fridge($idUser, 'Mi frigorífico');
        $idFridge = $res['frigorifico_id'];

        $res = $this->CI->fridge_library->get_productos_fridge($idUser, $idFridge);
        $this->assertEquals(count($res), 0);

        $res = $this->CI->fridge_library->get_item_fridge($idUser, $idFridge, 1);
        $this->assertNull($res);

        $res = $this->CI->fridge_library->get_compras($idUser, $idFridge);
        $this->assertEquals(count($res), 0);

        $res = $this->CI->fridge_library->get_compra($idUser, $idFridge, 1);
        $this->assertNull($res);
    }

    public function testFridgeAItem() {
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

        $res = $this->CI->fridge_library->anadir_producto_compra($idUser, $idFridge, $idProducto);
        $this->assertTrue($res !== NULL);

        $idProductoCompra = $res['id_producto_compra'];

        $res = $this->CI->fridge_library->get_item_fridge($idUser, $idFridge, $idProductoCompra);
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res->titulo, 'Mi producto');

        $res = $this->CI->fridge_library->get_compras($idUser, $idFridge);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->total, $datosProducto['precio']);
    }
}