<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class Lector_libraryTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

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
}