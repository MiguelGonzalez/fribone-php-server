<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablon extends MY_Controller_User {

	public function __construct() {
		parent::__construct();

        $this->load->library('fridge_library');
        $this->load->library('lector_library');

		$this->data['username'] = $this->login_auth_library->get_username();
		$this->template = 'tablon';
	}

	public function index(array $params = NULL) {
		$this->_render('tablon');
	}

    public function menus() {
        $frigorificos_usuario = $this->fridge_library->get_fridges(
                $this->id_user
        );

        $menusfrigorificos = array();
        foreach($frigorificos_usuario as $frigorifico_usuario) {
            array_push($menusfrigorificos, array(
                'id' => $frigorifico_usuario->id,
                'title' => $frigorifico_usuario->titulo,
                'to' => '/fridge/' . $frigorifico_usuario->id . '/productos'
            ));
        }

        $lectores_usuario = $this->lector_library->get_lectores(
            $this->id_user
        );
        $menusLectores = array();
        foreach($lectores_usuario as $lector_usuario) {
            array_push($menusLectores, array(
                'id' => $lector_usuario->id,
                'title' => $lector_usuario->titulo,
                'to' => '/lector/' . $lector_usuario->id
            ));
        }

        $this->_renderJson(array('menu_frigorifico' => $menusfrigorificos, 'menu_lector' => $menusLectores));
    }

	public function logout() {
		$this->login_auth_library->logout();

		redirect(
			site_url(),
            'refresh'
        );
	}
}