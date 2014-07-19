<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class My_PHPMailerTest extends PHPTest_Unit {

	public function __construct() {
        parent::__construct();

        $this->CI->load->library('login_auth_library');
        $this->CI->load->library('My_PHPMailer');
    }

    public function testSendEmailRemember() {
    	$res = $this->CI->login_auth_library->create_user('test@test.com', 'TestName' , '123456');
        $remember_token = $this->CI->login_auth_library->password_remember('test@test.com');

        $data['remember_token'] = $remember_token;

        $res = $this->CI->my_phpmailer->send_email('miguelgonzalezgomez@gmail.com','Remember password', $data, 'remember_password');

        $this->assertTrue($res);
    }
}