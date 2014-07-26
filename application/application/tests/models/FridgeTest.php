<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class FrigorificoTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('fridge_model');
        $this->CI->load->model('compra_model');
        $this->CI->load->model('supermercado_model');

        $this->CI->load->library('login_auth_library');
    }

	public function testFrigorifico() {
		$resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $frigorificos = $this->CI->fridge_model->get_frigorificos_user($idUser);
        $this->assertEquals(0, count($frigorificos));
	}

    public function testAddFrigorifico() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $this->assertTrue($res !== NULL);

        $frigorificos = $this->CI->fridge_model->get_frigorificos_user($idUser);
        $this->assertEquals(1, count($frigorificos));
        $this->assertEquals('Mi primer frigo', $frigorificos[0]->titulo);
    }

    public function testDuplicateFridges() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $this->assertNull($res);
    }

    public function testGetFridgeUser() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $idFridge = $res['frigorifico_id'];

        $fridge = $this->CI->fridge_model->get_fridge_user($idUser, $idFridge);
        $this->assertEquals($fridge->titulo, 'Mi primer frigo');
    }

    public function testGetProductosFrigo() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $idFridge = $res['frigorifico_id'];

        $prods = $this->CI->fridge_model->get_productos_fridge($idUser, $idFridge);
        $this->assertEquals(count($prods), 0);
    }

    public function testGetComprasFrigo() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $idFridge = $res['frigorifico_id'];

        $prods = $this->CI->fridge_model->get_compras_fridge($idUser, $idFridge);
        $this->assertEquals(count($prods), 0);
    }

    public function testAnadirProductoCompra() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
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

        $resCompra = $this->CI->compra_model->create_nueva_compra($idUser, $idFridge);
        $this->assertTrue($resCompra !== NULL);

        $idCompra = $resCompra['compra_id'];

        $res = $this->CI->supermercado_model->add_supermercado_producto($idSupermercado, $datosProducto);
        $idProducto = $res['id_producto'];

        $objectDatosProducto = $this->arrayToObject($datosProducto);
        $objectDatosProducto->id = $idProducto;
        $this->CI->compra_model->anadir_producto_compra($idUser, $idCompra, $objectDatosProducto);

        $res = $this->CI->fridge_model->anadir_producto_compra($idUser, $idFridge, $idProducto, 1);
        $this->assertTrue($res !== NULL);

        $res = $this->CI->fridge_model->get_compras_fridge($idUser, $idFridge);
        $this->assertEquals(count($res), 1);

        $idCompra = $res[0]->id;

        $this->CI->compra_model->recalcular_total_compra($idUser, $idCompra);

        $res = $this->CI->fridge_model->get_compra($idUser, $idCompra);
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res->total, 1.23);

        $res = $this->CI->fridge_model->get_compra_productos($idUser, $idCompra);
        $this->assertTrue($res !== NULL);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->titulo, 'Mi producto');
    }
}