<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fridge extends MY_Controller_User {

	public function __construct() {
		parent::__construct();
	}

	public function productos() {
		$this->_renderJson(array('productos' => array(
			array('titulo' => 'Producto uno'),
			array('titulo' => 'Producto dos'),
		)));
	}
}