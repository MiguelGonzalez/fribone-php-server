<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    //Page info
    protected $title = "Fribone";
    protected $template = "default";

    protected $data = Array();

    function __construct() {
        parent::__construct();
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
        $toBody["content_body"] = $this->load->view(
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

        //render view
        $this->load->view(
                $this->template . '/template/skeleton',
                $toBody);
    }
}