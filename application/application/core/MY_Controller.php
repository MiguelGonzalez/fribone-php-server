<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    //Page info
    protected $title = "Fribone";
    protected $template = "default";

    protected $data = Array();

    function __construct() {
        parent::__construct();

        if (defined('ENVIRONMENT') && ENVIRONMENT == 'testing') {
            $this->load->add_package_path(APPPATH.'tests/mockups');
        } else {
            $this->load->library('login_auth_library');
        }
    }

    protected function _renderJson($json) {
        $this->output
            ->set_content_type('application/json')
            ->set_output((json_encode($json)));
    }

    protected function _renderXml($fileXml) {
        $this->output
            ->set_content_type('text/xml');
        $this->load->view($fileXml, $this->data);
    }

    protected function _renderToVar($view) {
        return $this->load->view($view, $this->data, true);
    }

    protected function _render($view) {
        //meta
        $toTpl["title"] = $this->title;

        //data
        $toBody["body"] = $this->load->view(
                $this->template . '/pages/' . $view,
                array_merge($this->data, $toTpl),
                true);

        $toBody["header"] = $this->load->view(
                $this->template . '/template/header',
                $this->data,
                true);

        $toBody["footer"] = $this->load->view(
                $this->template . '/template/footer',
                $this->data,
                true);

        $this->load->view(
                $this->template . '/template/skeleton',
                $toBody);
    }
}