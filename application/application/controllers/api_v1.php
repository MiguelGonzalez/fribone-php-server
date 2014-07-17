<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_v1 extends MY_Controller_User {

	public function __construct() {
		parent::__construct();

		$this->load->library('Fridge_library');
	}

	public function fridges_get() {
		$fridges = $this->fridge_library->get_fridges($this->login_auth->get_user_id());

		$this->_renderJson(array('fridges' => $fridges));	
	}

	public function productos_get() {

		$ids_productos = $this->get('ids');
		
		$productos = array();
		foreach($ids_productos as $id_producto) {
			if($id_producto == 1) {
				array_push(
					$productos,
					array (
						'id' => 1, 'title' => 'Producto uno'
					)
				);
			}
			if($id_producto == 2) {
				array_push(
					$productos,
					array (
						'id' => 2, 'title' => 'Producto dos'
					)
				);	
			}
			if($id_producto == 3) {
				array_push(
					$productos,
					array (
						'id' => 3, 'title' => 'Producto tres'
					)
				);
			}
			if($id_producto == 4) {
				array_push(
					$productos,
					array (
						'id' => 4, 'title' => 'Producto cuatro'
					)
				);
			}
			if($id_producto == 5) {
				array_push(
					$productos,
					array (
						'id' => 5, 'title' => 'Producto cinco'
					)
				);
			}
			if($id_producto == 6) {
				array_push(
					$productos,
					array (
						'id' => 6, 'title' => 'Producto seis'
					)
				);
			}

		}

		$this->_renderJson(array('productos' => $productos));
	}
}