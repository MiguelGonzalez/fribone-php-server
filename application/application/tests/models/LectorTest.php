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
}