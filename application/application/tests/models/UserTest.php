<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../PHPTest_Unit.php';

class UserTest extends PHPTest_Unit {

    public function __construct() {
        parent::__construct();

        $this->CI->load->model('user');
        $this->CI->load->library('login_auth_library');
    }

	public function testGetUser() {
		$user = $this->CI->User->get_user('test@test.com');
		$this->assertNull($user);

        $resUser = $this->CI->User->create_user(
            'test@test.com',
            'TestName',
            $this->get_password_hash('123456'));
        $user = $this->CI->User->get_user('test@test.com');
        $this->assertTrue($user !== NULL);
        $this->assertEquals('test@test.com', $user->email);
        $this->assertEquals('TestName', $user->username);
        $this->assertEquals(1, $user->permission);
        $this->assertEquals('A', $user->state);
	}

    public function testCreateUser() {
        $resUser = $this->CI->User->create_user(
            'test@test.com',
            'TestName',
            $this->get_password_hash('12345678'));
        $this->assertTrue($resUser !== NULL);

        try {
            //No se puede crear otro usuario igual
            $resUser = $this->CI->User->create_user(
                'test@test.com',
                'TestName',
                $this->get_password_hash('12345678')
            );
        } catch(Exception $ex) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function testUpdateLoginInfo() {
        $resUser = $this->CI->User->create_user(
            'test@test.com',
            'TestName',
            $this->get_password_hash('123456'));

        $user = $this->CI->User->get_user('test@test.com');
        $last_access = $user->last_access;

        sleep(1);
        $res = $this->CI->User->update_login_info($user->id);

        $user = $this->CI->User->get_user('test@test.com');
        $this->assertFalse($last_access === $user->last_access);
    }

    public function testIncreseClearLoginAttempts() {
        $resUser = $this->CI->User->create_user(
            'test@test.com',
            'TestName',
            $this->get_password_hash('123456'));

        $this->CI->User->increase_login_attempt('test@test.com');
        $numAttempts = $this->CI->User->get_login_attempts('test@test.com');

        $this->assertTrue(1 == $numAttempts);

        $this->CI->User->increase_login_attempt('test@test.com');
        $numAttempts = $this->CI->User->get_login_attempts('test@test.com');

        $this->assertTrue(2 == $numAttempts);

        $this->CI->User->clear_login_attempts('test@test.com');
        $user = $this->CI->User->get_user('test@test.com');
        $numAttempts = $this->CI->User->get_login_attempts($user->id);

        $this->assertTrue(0 == $numAttempts);
    }

    public function testIsEmailAvailable() {
        $this->assertTrue($this->CI->User->is_email_available('test@test.com'));

        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');

        $this->assertFalse($this->CI->User->is_email_available('test@test.com'));
        $this->assertFalse($this->CI->User->is_email_available('teSt@test.com'));
        $this->assertFalse($this->CI->User->is_email_available('teSt@test.com '));
        $this->assertFalse($this->CI->User->is_email_available(' teSt@test.com'));
    }

    public function testChangePassword() {
        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');

        $user = $this->CI->User->get_user('test@test.com');

        $this->CI->User->change_password('test@test.com', '9876543');

        $user_password_changed = $this->CI->User->get_user('test@test.com');

        $this->assertFalse($user->password == $user_password_changed->password);
    }

    public function testTokenRemember() {
        $token = uniqid('', true);
        $hashed_token = $this->get_password_hash($token);

        $resUser = $this->CI->login_auth_library->create_user('test@test.com', 'TestName', '123456');

        $token = $this->CI->User->get_token_remember('test@test.com');
        $this->assertNull($token);

        $res = $this->CI->User->add_token_remember('test@test.com', $hashed_token);
        $this->assertTrue($res);

        $token = $this->CI->User->get_token_remember('test@test.com');
        $this->assertEquals($token, $hashed_token);

        $this->CI->User->increase_remember_attemps('test@test.com');
        $token = $this->CI->User->get_token_remember('test@test.com');
        $this->assertEquals($token, $hashed_token);

        $this->CI->User->increase_remember_attemps('test@test.com');
        $token = $this->CI->User->get_token_remember('test@test.com');
        $this->assertEquals($token, $hashed_token);

        $this->CI->User->increase_remember_attemps('test@test.com');
        $token = $this->CI->User->get_token_remember('test@test.com');
        $this->assertNull($token);

        $res = $this->CI->User->add_token_remember('test@test.com', $hashed_token);
        $res = $this->CI->User->invalidate_token_remember('test@test.com');
        $this->assertTrue($res);

        $token = $this->CI->User->get_token_remember('test@test.com');
        $this->assertNull($token);
    }
}