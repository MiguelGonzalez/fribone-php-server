<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class LectorTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('lector_model');

        $this->CI->load->library('login_auth_library');
    }

    public function testLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_model->get_lectores_user($idUser);
        $this->assertEquals(count($res), 0);
    }

    public function testAddLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_model->get_lectores_user($idUser);

        $res = $this->CI->lector_model->add_lector_user($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_model->get_lectores_user($idUser);
        $this->assertEquals(count($res), 1);
        $this->assertEquals($res[0]->titulo, 'Mi lector');

        $res = $this->CI->lector_model->get_lector_user($idUser, $idLector);
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res->titulo, 'Mi lector');
    }

    public function testActivarLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_model->add_lector_user($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_model->check_lector_public_key($idUser, 'miclavepublica');
        $this->assertNull($res);

        $res = $this->CI->lector_model->activar_lector($idUser, $idLector, 'miclavepublica');
        $this->assertTrue($res !== NULL);

        $res = $this->CI->lector_model->check_lector_public_key($idUser, 'miclavepublica');
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res->id, $idLector);
        $this->assertEquals($res->id_user, $idUser);

        $res = $this->CI->lector_model->desactivar_lector($idUser, $idLector);
        $this->assertTrue($res !== NULL);

        $res = $this->CI->lector_model->check_lector_public_key($idUser, 'miclavepublica');
        $this->assertNull($res);
    }

    public function testCheckTokenLector() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_model->add_lector_user($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        $res = $this->CI->lector_model->check_lector_token('123123','1231231');
        $this->assertNull($res);

        $fecha_alta_token = date('Y-m-d H:i:s');
        $access_key_1 = (string)(strtotime($fecha_alta_token) + $idUser);
        $access_key_1 =  str_pad($access_key_1, 12, '0',  STR_PAD_LEFT);
        $access_key_2 = $this->generate_token();

        $res = $this->CI->lector_model->anadir_lector_token(
                $idUser,
                $idLector,
                $access_key_1,
                $access_key_2,
                $fecha_alta_token
        );
        $this->assertTrue($res !== NULL);

        $res = $this->CI->lector_model->check_lector_token($access_key_1, $access_key_2);
        $this->assertTrue($res !== NULL);
        $this->assertEquals($res['id_user'], $idUser);
        $this->assertEquals($res['id_lector'], $idLector);

        $res = $this->CI->lector_model->check_lector_token($access_key_1, $access_key_2);
        $this->assertNull($res);
    }

    public function testTokenCaducado() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $res = $this->CI->lector_model->add_lector_user($idUser, 'Mi lector');
        $idLector = $res['lector_id'];

        // Token caducado
        $fecha_alta_token = date('Y-m-d H:i:s');

        $date = new DateTime($fecha_alta_token);
        $date->sub(new DateInterval('PT1H1S'));
        $fecha_alta_token = $date->format('Y-m-d H:i:s');

        $access_key_1 = (string)(strtotime($fecha_alta_token) + $idUser);
        $access_key_1 =  str_pad($access_key_1, 12, '0',  STR_PAD_LEFT);
        $access_key_2 = $this->generate_token();

        $res = $this->CI->lector_model->anadir_lector_token(
                $idUser,
                $idLector,
                $access_key_1,
                $access_key_2,
                $fecha_alta_token
        );
        $this->assertTrue($res !== NULL);

        $res = $this->CI->lector_model->check_lector_token($access_key_1, $access_key_2);
        $this->assertNull($res);
    }
}