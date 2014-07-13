<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../database_inflater.php';

class Login_authTest extends PHPUnit_Framework_TestCase {
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
        $this->CI->load->library('login_auth');
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

    public function testLogin() {
    	$res = $this->CI->login_auth->login('test@test.com', '123456');
    	$this->assertFalse($res);

    	self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');
        sleep(1);
    	$res = $this->CI->login_auth->login('test@test.com', '123456');
    	$this->assertTrue($res);

    	$this->assertTrue($this->CI->login_auth->is_logged_in());
    	$this->assertEquals('test@test.com', $this->CI->login_auth->get_email());
    	$this->assertEquals('TestName', $this->CI->login_auth->get_username());

    	$this->CI->login_auth->logout();
    	$this->assertFalse($this->CI->login_auth->is_logged_in());
    	$this->assertFalse($this->CI->login_auth->get_email());
    	$this->assertFalse($this->CI->login_auth->get_username());
    }

    public function testCreateUser() {
    	$res = $this->CI->login_auth->create_user('', 'TestName', '123456');
    	$this->assertNull($res);

    	$res = $this->CI->login_auth->create_user('test@test.com', 'TestName' , '123456');
    	$this->assertTrue($res !== NULL);

    	$res = $this->CI->login_auth->create_user('test@test.com', 'TestName', '123456');
    	$this->assertNull($res);
    }

    public function testRememberPassword() {
        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

        $remember_token = $this->CI->login_auth->password_remember('test@test.com');
        $this->assertTrue(!is_null($remember_token));

        $res = $this->CI->login_auth->password_remember_change('test@test.com', $remember_token, '9876543');
        $this->assertTrue($res);

        $res = $this->CI->login_auth->login('test@test.com', '9876543');
        $this->assertTrue($res);
    }

    public function testRememberPasswordInvalid() {
        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

        $remember_token = $this->CI->login_auth->password_remember('test@test.com');

        $remember_token = $this->CI->login_auth->password_remember('test@test.com');
        $res = $this->CI->login_auth->password_remember_change('test@test.com', 'invalid', '9876543');
        $this->assertFalse($res);
        $res = $this->CI->login_auth->password_remember_change('test@test.com', 'invalid', '9876543');
        $this->assertFalse($res);
        $res = $this->CI->login_auth->password_remember_change('test@test.com', 'invalid', '9876543');
        $this->assertFalse($res);

        $res = $this->CI->login_auth->password_remember_change('test@test.com', $remember_token, '9876543');
        $this->assertFalse($res);
    }

    public function testRememberPasswordExcededTime() {
        self::$dataBase_inflater->create_user('test@test.com', 'TestName', '123456');

        $remember_token = $this->CI->login_auth->password_remember('test@test.com');
        $this->assertTrue(!is_null($remember_token));

        $exceded_date_by_one_second = strtotime(date('Y-m-d H:i:s')) + 60 * 60 * 2 + 1;

        $stillActive = $this->CI->login_auth->is_still_active('test@test.com', $exceded_date_by_one_second);
        $this->assertFalse($stillActive);

        $res = $this->CI->login_auth->password_remember_change('test@test.com', $remember_token, '9876543');
        $this->assertFalse($res);
    }
}