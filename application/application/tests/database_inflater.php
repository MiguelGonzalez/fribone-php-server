<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataBase_inflater {

	private static $instance = NULL;

	protected $pdo = NULL;
	private $is_create = FALSE;

	private function __construct() {
		$CI = &get_instance();
		$CI->load->database();

		$this->pdo = new PDO('mysql:host=' .
				$CI->db->hostname .
				';dbname=' . $CI->db->database,
				$CI->db->username,
				$CI->db->password
		);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
	}

	public static function &get_instance() {
		if(is_null(DataBase_inflater::$instance)) {
			DataBase_inflater::$instance = new DataBase_inflater();
		}

		return DataBase_inflater::$instance;
	}

	public function create() {
		if(!$this->is_create) {
			try {
				$queries = file_get_contents('database_inflater.sql');

				$stmt = $this->pdo->prepare($queries);
		        $stmt->execute();

		        $this->is_create = TRUE;
	    	} catch(Exception $ex) {
	    		throw new Exception($ex->getMessage());
	    	}
	    }
	}

	public function destroy() {
		try {
			$queries = file_get_contents('database_inflater_truncate.sql');

			$stmt = $this->pdo->prepare($queries);
	        $stmt->execute();
    	} catch(Exception $ex) {
    		throw new Exception($ex->getMessage());
    	}
	}
}