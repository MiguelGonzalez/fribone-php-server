<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class FrigorificoTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('fridge_model');

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

    public function getComprasFrigo() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->fridge_model->add_frigorifico_user($idUser, 'Mi primer frigo');
        $idFridge = $res['frigorifico_id'];

        $prods = $this->CI->fridge_model->get_compras_fridge($idUser, $idFridge);
        $this->assertEquals(count($prods), 0);
    }
}