<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/database_inflater.php';

class PHPTest_Unit extends PHPUnit_Framework_TestCase {

	protected $CI;
	protected static $dataBase_inflater;

    public function __construct() {
        parent::__construct();

        $this->CI = &get_instance();
        $this->CI->load->add_package_path(APPPATH.'tests/mockups');
    }

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

    public function testTrue() {
        $this->assertTrue(true);
    }

    protected function get_password_hash($password) {
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

    protected function arrayToObject($array) {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            $object->$key = $value;
        }
        return $object;
    }

    protected function generate_token($length = 12) {
        $token = "";
        $token_chars = array_merge(range(0,9));
        for($i=0; $i < $length; $i++) {
            $token .= $token_chars[array_rand($token_chars)];
        }
        return $token;
    }
}