<?php

class UserTest extends PHPUnit_Framework_TestCase {
	private $CI;
	
	/**
	* @var PDO
	*/
    private $pdo = NULL;

	public function setUp() {
		try {
	        $this->pdo = new PDO("mysql:dbname=fribone_test;host=localhost","root", "");
	        
	        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $this->pdo->query(
	        	"CREATE TABLE IF NOT EXISTS `my_user` (" .
				  "`idUser` int(11) NOT NULL AUTO_INCREMENT," .
				  "`permission` char(1) NOT NULL," .
				  "`state` char(1) NOT NULL," .
				  "PRIMARY KEY (`idUser`)" .
				") ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"

	    	);

	    	$this->pdo->query(
	        	"INSERT INTO `my_user` (`permission`,`state`) VALUES (1,'A');" 
	    	);
    	} catch(Exception $ex) {
    		$this->fail($ex->getMessage());
    	}

		try {
        	$this->CI = &get_instance();
        } catch(Exception $ex) {
        	$this->fail($ex);
        }
    }

    public function tearDown() {
    	try {
        	$this->pdo->query("DROP TABLE my_user");
    	} catch(Exception $ex) {
    		$this->fail($ex->getMessage());
    	}
    }


	public function testPublicUser() {
		$this->CI->load->model('User','user1');

		$this->assertEquals(0, $this->CI->user1->getIdUser());
		$this->assertFalse($this->CI->user1->isLogged());
		$this->assertFalse($this->CI->user1->isAdmin());
	}

	public function testLoginUser() {
		$this->CI->load->library('session');

		$datosUsuario = array(
            'idUser'  => 1,
            'permission' => 1
        );

        $this->CI->session->set_userdata($datosUsuario);

		$this->CI->load->model('User','user2');

		$this->assertEquals(1, $this->CI->user2->getIdUser());
		$this->assertTrue($this->CI->user2->isLogged());
		$this->assertFalse($this->CI->user2->isAdmin());
	}
}