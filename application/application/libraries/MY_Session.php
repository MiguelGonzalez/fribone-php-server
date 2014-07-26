<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session {

    function MY_Session($params=array()) {
        parent::__construct();($params);
    }

    public function set_sess_expiration($sess_expiration) {
        $this->sess_expiration = $sess_expiration;
    }

    public function set_sess_expire_on_close($sess_expire_on_close) {
        $this->sess_expire_on_close = $sess_expire_on_close;
    }
}