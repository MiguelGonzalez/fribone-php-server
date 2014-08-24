<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class Lector_libraryTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('lector_model');

        $this->CI->load->library('login_auth_library');
        $this->CI->load->library('lector_library');
    }

    public function testCreateLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_library->create_lector($idUser, 'Mi lector');
        $this->assertTrue($res !== NULL);

        $res = $this->CI->lector_library->create_lector($idUser, 'Mi lector');
        $this->assertNull($res);
    }

    public function testGetLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_library->get_lectores($idUser);
        $this->assertEquals(count($res), 0);

        $res = $this->CI->lector_library->create_lector($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_library->get_lectores($idUser);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->titulo, 'Mi lector');

        $res = $this->CI->lector_library->get_lector($idUser, $idLector);
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res->titulo, 'Mi lector');

        $resNull = $this->CI->lector_library->get_lector($idUser, -1);
        $this->assertNull($resNull);
    }

    public function testGenerarToken() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_library->create_lector($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_library->generar_lector_token($idUser, $idLector);
        $this->assertTrue($res !== NULL);

        $access_key_1 = $res['access_key_1'];
        $access_key_2 = $res['access_key_2'];

        $res = $this->CI->lector_model->check_lector_token($access_key_1, $access_key_2);
        $this->assertTrue($res !== NULL);
    }

    public function testActivarLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_library->create_lector($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_library->generar_lector_token($idUser, $idLector);
        $access_key_1 = $res['access_key_1'];
        $access_key_2 = $res['access_key_2'];

        $res = $this->CI->lector_library->activar_lector($access_key_1, $access_key_2);
        $this->assertTrue($res !== NULL);

        $public_key = $res['public_key'];

        $res = $this->CI->lector_library->activar_lector($access_key_1, $access_key_2);
        $this->assertNull($res);

        $res = $this->CI->lector_library->check_lector_public_key($idUser, $public_key);
        $this->assertTrue($res !== NULL);

        $res = $this->CI->lector_library->get_user_public_key($public_key);
        $this->assertEquals($res, $idUser);

        $res = $this->CI->lector_library->check_lector_public_key($idUser, '123213');
        $this->assertNull($res);
    }

    public function testGetIdLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_library->create_lector($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_library->generar_lector_token($idUser, $idLector);
        $access_key_1 = $res['access_key_1'];
        $access_key_2 = $res['access_key_2'];

        $res = $this->CI->lector_library->activar_lector($access_key_1, $access_key_2);
        $public_key = $res['public_key'];

        $idLectorPublicToken = $this->CI->lector_library->get_id_lector($public_key);
        $this->assertEquals($idLectorPublicToken, $idLector);
    }

    public function testDesactivarLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_library->create_lector($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_library->generar_lector_token($idUser, $idLector);
        $access_key_1 = $res['access_key_1'];
        $access_key_2 = $res['access_key_2'];

        $this->CI->lector_library->activar_lector($access_key_1, $access_key_2);

        $res = $this->CI->lector_library->desactivar_lector($idLector, $idUser);
        $this->assertTrue($res !== NULL);

        $res = $this->CI->lector_library->get_id_lector($public_key);
        $this->assertNull($res);
    }
}