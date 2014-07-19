<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item extends MY_Controller_User {

    public function __construct() {
        parent::__construct();

        $this->load->library('item_library');
    }

    public function get_item($id_item) {
        $item = $this->item_library->get_item(
            $id_item
        );

        $this->_renderJson($item);
    }
}