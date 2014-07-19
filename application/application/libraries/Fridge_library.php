<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fridge_library {

	private $ci = NULL;

	public function __construct() {
		$this->ci =& get_instance();

		$this->ci->load->model('fridge_model');
	}

	public function get_fridges($id_user) {
		$fridges = $this->ci->fridge_model->get_frigorificos_user($id_user);

		return $fridges;
	}

    public function get_fridge($id_fridge) {
        $fridge = $this->ci->fridge_model->get_fridge_user($id_fridge);

        return $fridge;
    }

    public function get_items_fridge($id_fridge) {
        $items = $this->ci->fridge_model->get_items_fridge($id_fridge);

        return $items;
    }
}