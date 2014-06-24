<?php

class UserTest extends PHPUnit_Framework_TestCase {
	private $CI;
	
	/**
	* @var PDO
	*/
    private $pdo;

	public function setUp() {
		echo "Setup";
		try {
	        $this->pdo = new PDO("mysql:dbname=fribone_test;host=127.0.0.1","test", NULL);
	        
	        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	        $this->pdo->query(
	        	"CREATE TABLE IF NOT EXISTS `my_user` (" .
				  "`idUser` int(11) NOT NULL AUTO_INCREMENT," .
				  "`permission` char(1) NOT NULL," .
				  "`state` char(1) NOT NULL," .
				  "PRIMARY KEY (`idUser`)" .
				") ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"

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
        $this->pdo->query("DROP TABLE my_user");
    }


	public function testPublicUser() {
		$this->CI->load->model('User');

		$this->assertEquals(0, $this->CI->User->getIdUser());
		$this->assertFalse($this->CI->User->isLogged());
		$this->assertFalse($this->CI->User->isAdmin());
	}
}