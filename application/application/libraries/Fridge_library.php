<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fridge_library {

	private $ci = NULL;

	public function __construct() {
		$this->ci =& get_instance();

		$this->ci->load->model('fridge');
	}

	public function get_fridges($id_user) {
		$fridges = $this->ci->fridge->get_frigorificos_user($id_user);

		foreach($fridges as &$fridge) {
			$fridge->productos = $this->ci->fridge->get_items_fridge($fridge->id);
		}

		return $fridges;
	}

}