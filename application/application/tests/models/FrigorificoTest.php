<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class FrigorificoTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('user');
        $this->CI->load->model('frigorifico');

        $this->CI->load->library('login_auth');
    }

	public function testFrigorifico() {
		$resUser = $this->CI->login_auth->create_user('test@test.com', 'TestName', '123456');
        $idUser = $resUser['user_id'];

        $frigorificos = $this->CI->frigorifico->get_frigorificos_user($idUser);
        $this->assertEquals(0, count($frigorificos));
	}
}