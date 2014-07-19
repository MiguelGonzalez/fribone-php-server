<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class Login_auth_Test extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->library('login_auth_library');
    }

    public function testLogin() {
    	$res = $this->CI->login_auth_library->login('test@test.com', '123456');
    	$this->assertFalse($res);

        $res = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
        sleep(1);
    	$res = $this->CI->login_auth_library->login('test@test.com', '123456');
    	$this->assertTrue($res);

    	$this->assertTrue($this->CI->login_auth_library->is_logged_in());
    	$this->assertEquals('test@test.com', $this->CI->login_auth_library->get_email());
    	$this->assertEquals('TestName', $this->CI->login_auth_library->get_username());

    	$this->CI->login_auth_library->logout();
    	$this->assertFalse($this->CI->login_auth_library->is_logged_in());
    	$this->assertFalse($this->CI->login_auth_library->get_email());
    	$this->assertFalse($this->CI->login_auth_library->get_username());
    }

    public function testCreateUser() {
    	$res = $this->CI->login_auth_library->create_user('', 'TestName', '123456');
    	$this->assertNull($res);

    	$res = $this->CI->login_auth_library->create_user('test@test.com', 'TestName' , '123456');
    	$this->assertTrue($res !== NULL);

    	$res = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');
    	$this->assertNull($res);
    }

    public function testRememberPassword() {
        $res = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');

        $remember_token = $this->CI->login_auth_library->password_remember('test@test.com');
        $this->assertTrue(!is_null($remember_token));

        $res = $this->CI->login_auth_library->password_remember_change('test@test.com', $remember_token, '9876543');
        $this->assertTrue($res);

        $res = $this->CI->login_auth_library->login('test@test.com', '9876543');
        $this->assertTrue($res);
    }

    public function testRememberPasswordInvalid() {
        $res = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');

        $remember_token = $this->CI->login_auth_library->password_remember('test@test.com');

        $remember_token = $this->CI->login_auth_library->password_remember('test@test.com');
        $res = $this->CI->login_auth_library->password_remember_change('test@test.com', 'invalid', '9876543');
        $this->assertFalse($res);
        $res = $this->CI->login_auth_library->password_remember_change('test@test.com', 'invalid', '9876543');
        $this->assertFalse($res);
        $res = $this->CI->login_auth_library->password_remember_change('test@test.com', 'invalid', '9876543');
        $this->assertFalse($res);

        $res = $this->CI->login_auth_library->password_remember_change('test@test.com', $remember_token, '9876543');
        $this->assertFalse($res);
    }

    public function testRememberPasswordExcededTime() {
        $res = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');

        $remember_token = $this->CI->login_auth_library->password_remember('test@test.com');
        $this->assertTrue(!is_null($remember_token));

        $exceded_date_by_one_second = strtotime(date('Y-m-d H:i:s')) + 60 * 60 * 2 + 1;

        $stillActive = $this->CI->login_auth_library->is_still_active('test@test.com', $exceded_date_by_one_second);
        $this->assertFalse($stillActive);

        $res = $this->CI->login_auth_library->password_remember_change('test@test.com', $remember_token, '9876543');
        $this->assertFalse($res);
    }
}