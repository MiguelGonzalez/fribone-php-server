<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/../database_inflater.php';

class My_PHPMailerTest extends PHPUnit_Framework_TestCase {
	private $CI;

    private static $dataBase_inflater;

    public function __construct() {
        parent::__construct();

        $this->CI = &get_instance();
        $this->CI->load->library('login_auth');
        $this->CI->load->library('My_PHPMailer');
    }

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

    public function testSendEmailRemember() {
    	$res = $this->CI->login_auth->create_user('test@test.com', 'TestName' , '123456');
        $remember_token = $this->CI->login_auth->password_remember('test@test.com');

        $data['remember_token'] = $remember_token;

        $res = $this->CI->my_phpmailer->send_email('miguelgonzalezgomez@gmail.com','Remember password', $data, 'remember_password');

        $this->assertTrue($res);
    }
}