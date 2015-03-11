<?php
class Common {
	private static $uid = "i7214451";
	private static $pwd = "Password";
	private static $host = "127.0.0.1";
	private static $db = "i7214451";
	
	static function connect_db(){
		$conn = mysqli_connect(Common::$host, Common::$uid, Common::$pwd, Common::$db);
        return $conn;
	}
	
	function sanitise($conn,$input){
		mysqli_real_escape_string($conn, $input);
	}
}
?>