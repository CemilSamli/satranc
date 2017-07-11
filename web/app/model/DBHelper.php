<?php
include_once($_SERVER['DOCUMENT_ROOT']."/db/config.mysql.php");

class DBHelper {

	private $mysqli = null;
	private static $instance = null;

	private function __construct(){
		$this->mysqli = new mysqli(SERVER, USER, PASSWORD, DATABASE);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			die();
			exit();
		}
	}

	public static function getInstance(){
		if(self::$instance == null){
			self::$instance = new DBHelper();
		}
		return self::$instance;
	}

	public static function getConnection(){
		if(self::$instance == null){
			self::$instance = new DBHelper();
		}
		return self::$instance->mysqli;
	}
}
?>
