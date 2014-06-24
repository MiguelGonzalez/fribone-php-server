<?php

class UserTest extends PHPUnit_Framework_TestCase {
	private $CI;
	
	public function setUp() {
		try {
        	$this->CI = &get_instance();
        } catch(Exception $ex) {
        	$this->fail($ex);
        }
    }

	public function testPublicUser() {
		$this->CI->load->model('User');

		$this->assertEquals(0, $this->CI->User->getIdUser());
		$this->assertFalse($this->CI->User->isLogged());
		$this->assertFalse($this->CI->User->isAdmin());
	}
}