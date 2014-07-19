<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablon extends MY_Controller_User {

	public function __construct() {
		parent::__construct();

        $this->load->library('fridge_library');

		$this->data['username'] = $this->login_auth->get_username();
		$this->template = 'tablon';
	}

	public function index(array $params = NULL) {
		$this->_render('tablon');
	}

    public function menus() {
        $frigorificos_usuario = $this->fridge_library->get_fridges(
                $this->login_auth->get_user_id()
        );

        $menus = array();

        foreach($frigorificos_usuario as $frigorifico_usuario) {
            array_push($menus, array(
                'id' => $frigorifico_usuario->id,
                'title' => $frigorifico_usuario->titulo,
                'to' => '/fridge/' . $frigorifico_usuario->id
            ));
        }

        array_push($menus, array(
            'id' => NULL,
            'title' => 'Estadísticas',
            'to' => '/estadisticas'
        ));

        $this->_renderJson(array('menu' => $menus));
    }

	public function logout() {
		$this->login_auth->logout();

		redirect(
			site_url(),
            'refresh'
        );
	}
}