<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fridge extends MY_Controller_User {

	public function __construct() {
		parent::__construct();

        $this->load->library('fridge_library');
	}

	public function get_fridge($id_fridge) {
		$fridge = $this->fridge_library->get_fridge(
            $id_fridge
        );

        $this->_renderJson($fridge);
	}

    public function get_items_fridge($id_fridge) {
        $items = $this->fridge_library->get_items_fridge(
            $id_fridge
        );

        $this->_renderJson(array('item' => $items));
    }
}