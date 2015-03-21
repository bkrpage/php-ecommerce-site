<?php

/**
 * @author Bradley Page
 */
class Common
{
    private static $uid = "i7214451";
    private static $pwd = "Password";
    private static $host = "127.0.0.1";
    private static $db = "i7214451";

    /**
     * @return mysqli Returns the mysqli connection
     */
    static function connect_db()
    {
        $conn = mysqli_connect(Common::$host, Common::$uid, Common::$pwd, Common::$db);
        return $conn;
    }

    /**
     * @param $info The string to be cleaned
     * @param $conn The database connection
     * @return string Returns the cleaned $info string.
     */
    function clean($info, $conn)
    {
        $info = trim($info);
        $info = strip_Tags($info);
        $info = mysqli_real_escape_string($conn, $info);
        return $info;
    }
}

?>