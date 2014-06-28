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
    	} catch(Exception $ex) {
    		throw new Exception($ex->getMessage());
    	}
	}

	public function destroy() {
		try {
        	self::$pdo->query("DROP TABLE my_user");
    	} catch(Exception $ex) {
    		throw new Exception($ex->getMessage());
    	}
	}

	public function create_user($email, $username, $password) {
		$password = password_hash($password, PASSWORD_BCRYPT);

		return self::$pdo->exec(
			"INSERT INTO `my_user` ( " .
			" `email` , `username` , `password` , `permission` , `state` , `last_ip` , `last_access` , `login_attempts` " .
			" ) " .
			" VALUES ( " .
			" '" . $email . "', '" . $username . "', '" . $password . "', '1', 'A', '127.0.0.1', NOW( ) , '0' " .
			" ) "
		);
	}
}