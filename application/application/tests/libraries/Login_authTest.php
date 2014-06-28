<?php

require_once dirname(__FILE__) . '/../database_inflater.php';

class HomeTest extends PHPUnit_Framework_TestCase {
	private $CI;
    
    private static $dataBase_inflater;

    public static function setUpBeforeClass() {
        try {
            self::$dataBase_inflater = new DataBase_inflater();
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
    	$res = $this->CI->login_auth->login('test@test.com', '123456');
    	$this->assertTrue($res);
    }
}