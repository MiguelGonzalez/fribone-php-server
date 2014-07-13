<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataBase_inflater {

	protected $pdo = NULL;

	private static $instance = NULL;

	private $is_create = FALSE;

	private function __construct() {
		$this->pdo = new PDO("mysql:dbname=fribone_test;host=localhost","root", "");
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

	public function create_user($email, $username, $password) {
		$hashed_password = $this->get_password_hash($password);

		return $this->pdo->exec(
			"INSERT INTO `my_user` ( " .
			" `email` , `username` , `password` , `permission` , `state` , `last_ip` , `last_access` , `login_attempts` " .
			" ) " .
			" VALUES ( " .
			" '" . trim($email) . "', '" . $username . "', '" . $hashed_password . "', 1, 'A', '127.0.0.1', '". date('Y-m-d H:i:s') ."' , 0 " .
			" ) "
		);
	}

	private function get_password_hash($password) {
        $password_hashed = NULL;

        if (strnatcmp(phpversion(),'5.5.0') >= 0) {
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        } else {
            // Original PHP code by Chirp Internet: www.chirp.com.au
            // Please acknowledge use of this code by including this header.
            $salt = "";
            $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
            for($i=0; $i < 22; $i++) {
                $salt .= $salt_chars[array_rand($salt_chars)];
            }
            $password_hashed = crypt($password, sprintf('$2a$%02d$', 7) . $salt);
        }

        return $password_hashed;
    }
}