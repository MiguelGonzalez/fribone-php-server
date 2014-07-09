<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataBase_inflater {

	protected static $pdo = NULL;

	public function __construct() {
		self::$pdo = new PDO("mysql:dbname=fribone_test;host=localhost","root", "");
		self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function create() {
		try {
	        self::$pdo->query(
				"CREATE TABLE IF NOT EXISTS `my_user` (" .
				"  `id` int(11) NOT NULL AUTO_INCREMENT," .
				"  `email` varchar(128) NOT NULL," .
				"  `username` varchar(128) NOT NULL," .
				"  `password` char(60) NOT NULL," .
				"  `permission` tinyint(4) NOT NULL," .
				"  `state` char(1) NOT NULL," .
				"  `last_ip` char(15) NOT NULL," .
				"  `last_access` datetime NOT NULL," .
				"  `login_attempts` int(11) NOT NULL," .
				"  PRIMARY KEY (`id`)," .
				"  UNIQUE KEY `email` (`email`)" .
				") ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
	    	);

	    	self::$pdo->query(
				" CREATE TABLE IF NOT EXISTS `my_user_remember_token` ( " .
				  " `email` varchar(128) NOT NULL, " .
				  " `token` varchar(60) NOT NULL, " .
				  " `creation_date` datetime NOT NULL, " .
				  " `attempts` tinyint(3) unsigned NOT NULL DEFAULT '0', " .
				  " `state` char(1) NOT NULL DEFAULT 'A', " .
				  " PRIMARY KEY (`email`,`creation_date`) " .
				" ) ENGINE=InnoDB DEFAULT CHARSET=utf8 "
	    	);
    	} catch(Exception $ex) {
    		throw new Exception($ex->getMessage());
    	}
	}

	public function destroy() {
		try {
        	self::$pdo->query("DROP TABLE my_user");
        	self::$pdo->query("DROP TABLE my_user_remember_token");
    	} catch(Exception $ex) {
    		throw new Exception($ex->getMessage());
    	}
	}

	public function create_user($email, $username, $password) {
		$hashed_password = $this->get_password_hash($password);

		return self::$pdo->exec(
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