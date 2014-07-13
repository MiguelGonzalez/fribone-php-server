<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../database_inflater.php';

class UserTest extends PHPUnit_Framework_TestCase {
	private $CI;

	private static $dataBase_inflater;

    public static function setUpBeforeClass() {
    	try {
	        self::$dataBase_inflater = DataBase_inflater::get_instance();
    	} catch(Exception $ex) {
    		self::fail($ex->getMessage());
    	}
    }

    public static function tearDownAfterClass() {
    	try {
        	self::$dataBase_inflater = NULL;
    	} catch(Exception $ex) {
    		self::fail($ex->getMessage());
    	}
    }

    public function __construct() {
    	parent::__construct();

    	$this->CI = &get_instance();
    	$this->CI->load->model('user');
    }

	public function setUp() {
		try {
			self::$dataBase_inflater->create();
		} catch(Exception $ex) {
			self::fail($ex);
		}
    }

    public function tearDown() {
    	try {
    		self::$dataBase_inflater->destroy();
    	} catch(Exception $ex) {
			self::fail($ex);
		}
    }

	public function testGetUser() {
		$user = $this->CI->User->get_user('test@test.com');
		$this->assertNull($user);

        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');
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
        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

        $user = $this->CI->User->get_user('test@test.com');
        $last_access = $user->last_access;

        sleep(1);
        $res = $this->CI->User->update_login_info($user->id);

        $user = $this->CI->User->get_user('test@test.com');
        $this->assertFalse($last_access === $user->last_access);
    }

    public function testIncreseClearLoginAttempts() {
        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

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

        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

        $this->assertFalse($this->CI->User->is_email_available('test@test.com'));
        $this->assertFalse($this->CI->User->is_email_available('teSt@test.com'));
        $this->assertFalse($this->CI->User->is_email_available('teSt@test.com '));
        $this->assertFalse($this->CI->User->is_email_available(' teSt@test.com'));
    }

    public function testChangePassword() {
        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

        $user = $this->CI->User->get_user('test@test.com');

        $this->CI->User->change_password('test@test.com', '9876543');

        $user_password_changed = $this->CI->User->get_user('test@test.com');

        $this->assertFalse($user->password == $user_password_changed->password);
    }

    public function testTokenRemember() {
        $token = uniqid('', true);
        $hashed_token = $this->get_password_hash($token);

        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

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


    private function get_password_hash($password) {
        $password_hashed = NULL;

        if (strnatcmp(phpversion(),'5.5.0') >= 0) {
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        } else {
            // Original PHP code by Chirp Internet: www.chirp.com.au
            // Please acknowledge use of this code by including this header.
            $salt = "";
            $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
            for($i=0; $i < 22; $i++) {
                $salt .= $salt_chars[array_rand($salt_chars)];
            }
            $password_hashed = crypt($password, sprintf('$2a$%02d$', 7) . $salt);
        }

        return $password_hashed;
    }
}