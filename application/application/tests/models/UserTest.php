<?php

class UserTest extends PHPUnit_Framework_TestCase {
	private $CI;
	
	/**
	* @var PDO
	*/
   protected static $pdo = NULL;

    public static function setUpBeforeClass() {
    	try {
	        self::$pdo = new PDO("mysql:dbname=fribone_test;host=localhost","root", "");
	        
	        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        self::$pdo->query(
	        	"CREATE TABLE IF NOT EXISTS `my_user` (" .
				  "`idUser` int(11) NOT NULL AUTO_INCREMENT," .
				  "`permission` char(1) NOT NULL," .
				  "`state` char(1) NOT NULL," .
				  "PRIMARY KEY (`idUser`)" .
				") ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"

	    	);

	    	self::$pdo->query(
	        	"INSERT INTO `my_user` (`permission`,`state`) VALUES (1,'A');" 
	    	);
	    	self::$pdo->query(
	        	"INSERT INTO `my_user` (`permission`,`state`) VALUES (2,'A');" 
	    	);
    	} catch(Exception $ex) {
    		self::fail($ex->getMessage());
    	}
    }

    public static function tearDownAfterClass() {
    	try {
        	self::$pdo->query("DROP TABLE my_user");
        	self::$pdo = NULL;
    	} catch(Exception $ex) {
    		self::fail($ex->getMessage());
    	}
    }

	public function setUp() {
		try {
        	$this->CI = &get_instance();
        	$this->CI->load->library('session');
        	$this->CI->session->sess_create();
        } catch(Exception $ex) {
        	$this->fail($ex);
        }
    }

    public function tearDown() {
    	$this->CI->session->sess_destroy();
    }

	public function testPublicUser() {
		$this->CI->load->model('User','userPublic');

		$this->assertEquals(0, $this->CI->userPublic->getIdUser());
		$this->assertFalse($this->CI->userPublic->isLogged());
		$this->assertFalse($this->CI->userPublic->isAdmin());
	}

	public function testLoginUser() {
		$datosUsuario = array(
            'idUser'  => 1,
            'permission' => 1
        );

        $this->CI->session->set_userdata($datosUsuario);

		$this->CI->load->model('User','userLogin');

		$this->assertEquals(1, $this->CI->userLogin->getIdUser());
		$this->assertTrue($this->CI->userLogin->isLogged());
		$this->assertFalse($this->CI->userLogin->isAdmin());
	}

	public function testAdminUser() {
		$this->CI->load->library('session');

		$datosUsuario = array(
            'idUser'  => 2,
            'permission' => 2
        );

        $this->CI->session->set_userdata($datosUsuario);

		$this->CI->load->model('User','userAdmin');

		$this->assertEquals(2, $this->CI->userAdmin->getIdUser());
		$this->assertTrue($this->CI->userAdmin->isLogged());
		$this->assertTrue($this->CI->userAdmin->isAdmin());
	}
}