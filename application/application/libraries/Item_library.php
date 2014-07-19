<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item_library {

	private $ci = NULL;

	public function __construct() {
		$this->ci =& get_instance();

        $this->ci->load->model('item_model');
	}

	public function get_item($id_item) {
		$item = $this->ci->item_model->get_item($id_item);

        return $item;
	}

}