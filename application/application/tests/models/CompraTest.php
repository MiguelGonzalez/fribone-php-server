<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class CompraTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('compra_model');
        $this->CI->load->model('fridge_model');
        $this->CI->load->model('supermercado_model');

        $this->CI->load->library('login_auth_library');
    }

    public function testCrearObtenerCompra() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $idFridge = $res['frigorifico_id'];

        $resCompra = $this->CI->compra_model->create_nueva_compra($idUser,$idFridge);
        $this->assertTrue($resCompra !== NULL);

        $idCompra = $resCompra['compra_id'];

        $compra = $this->CI->compra_model->get_ultima_compra($idUser, $idCompra);
        $this->assertEquals($compra->total, 0);

        $compraNula = $this->CI->compra_model->get_ultima_compra($idUser, 100);
        $this->assertNull($compraNula);
    }

    public function testAnadirProductoCompra() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $idFridge = $res['frigorifico_id'];

        $resCompra = $this->CI->compra_model->create_nueva_compra($idUser,$idFridge);
        $this->assertTrue($resCompra !== NULL);

        $idCompra = $resCompra['compra_id'];

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

        $objectDatosProducto = $this->arrayToObject($datosProducto);
        $objectDatosProducto->id = $res['id_producto'];

        $addProduto = $this->CI->compra_model->anadir_producto_compra($idUser, $idCompra, $objectDatosProducto);
        $this->assertTrue($addProduto !== NULL);

        $ultimaCompra = $this->CI->compra_model->get_ultima_compra($idUser, $idCompra);
        $this->assertEquals($ultimaCompra->total, $objectDatosProducto->precio);
    }

    public function testGetProductoComprado() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $idFridge = $res['frigorifico_id'];

        $res = $this->CI->supermercado_model->crear_supermercado('Mi Supermercado');
        $idSupermercado = $res['supermercado_id'];

        $resCompra = $this->CI->compra_model->create_nueva_compra($idUser,$idFridge);
        $idCompra = $resCompra['compra_id'];

        $datosProducto = array(
            'titulo' => 'Mi producto',
            'descripcion' => 'Mi descripción del producto para prueba',
            'unidades' => 1,
            'precio' => 1.23,
            'codigo_barras' => '9876431',
            'codigo_rfid' => '123654789',
            'id_supermercado' => 1
        );

        $res = $this->CI->supermercado_model->add_supermercado_producto($idSupermercado, $datosProducto);
        $id_producto = $res['id_producto'];

        $objectDatosProducto = $this->arrayToObject($datosProducto);
        $objectDatosProducto->id = $res['id_producto'];
        $this->CI->compra_model->anadir_producto_compra($idUser, $idCompra, $objectDatosProducto);

        $res = $this->CI->compra_model->obtener_producto_compra($idUser, $idFridge, $datosProducto['codigo_barras'], 'codigo_barras');
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res->id, $id_producto);
    }
}