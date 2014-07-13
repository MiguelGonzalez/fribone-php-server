<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/../database_inflater.php';

class FrigorificoTest extends PHPUnit_Framework_TestCase {
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
    	$this->CI->load->model('frigorifico');
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

	public function testFrigorifico() {
		$resUser = $this->CI->User->create_user(
            'test@test.com',
            'TestName',
            $this->get_password_hash('12345678'));
        $idUser = $resUser['user_id'];

        $frigorificos = $this->CI->frigorifico->get_frigorificos_user($idUser);
        $this->assertEquals(0, count($frigorificos));
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