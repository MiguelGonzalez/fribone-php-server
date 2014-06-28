<?php

require_once dirname(__FILE__) . '/../database_inflater.php';

class UserTest extends PHPUnit_Framework_TestCase {
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
}