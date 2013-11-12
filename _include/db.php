<?php

/**
 * Constants/Database Class
 * Updated: 10/22/13
 * Author: Chris Hsu
 */ 

/* Constants */

/* Database Constants */ //to change
define("DB_SERVER", "localhost"); 
define("DB_USER", "root"); //
define("DB_PASS", "pass"); // 
define("DB_NAME", "detnet2"); // 

/* Database Table Constants */
define("TBL_CRON", "cron");
define("TBL_USER", "user");

define("TBL_USER__offline", 0);
define("TBL_USER__idle", 1);
define("TBL_USER__online", 2);
define("TBL_USER__admin", 1);
define("TBL_USER__not_admin", 0);

define("TBL_U_HASH", "user_hash");

define("TBL_U_HASH__cookie", 1);
define("TBL_U_HASH__register", 2);
define("TBL_U_HASH__forgot", 3);

define("TBL_GROUP", "`group`");

define("TBL_GROUP__default", 1);
define("TBL_GROUP__hiearchy", 2);
define("TBL_GROUP__none", 0);

define("TBL_U_GROUP", "user_group");
define("TBL_POST", "post");
define("TBL_U_POST", "user_post");
define("TBL_NOTIFY", "notification");
define("TBL_U_NOTIFY", "user_notification");
define("TBL_ASSIGN", "assignment");
define("TBL_U_ASSIGN", "user_assignment");

/* Level Constants */
define("ADMIN_ACCESS", TBL_USER__admin);
define("LOGGED_IN", TBL_USER__not_admin);
define("NOT_LOGGED_IN", -1);

define("PW_SALT", "detnet");

class DatabaseException extends Exception {}


/* Database Class */
class Database
{
    var $connection;

    /* Class constructor */
    function Database() {
        /* Make connection to database */
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME) or die(mysql_error());
        //mysql_select_db(DB_NAME, $this->connection) or die(mysql_error()." peachy");
    }
    
    
    /**
     * query - Performs the given query on the database and
     * returns the result, which may be false, true or a
     * resource identifier.
     */
    function query($query) {
        $res = mysqli_query($this->connection, $query);
        if (!$res) return false;
        else return $res;
    }
    
    function fetch($res) {
        return mysqli_fetch_assoc($res);
    }
    
    function is_empty($res) {
        if (mysqli_num_rows($res) > 0) return false;
        else return true;
    }
    
    function insert_id() {
        return mysqli_insert_id($this->connection);
    }
    
    function error() {
        return mysqli_error($this->connection);
    }
};

/* Create database connection */
$db = new Database;

/**
 * mysql_prep_string - saves some typing by combining strip_tags, htmlentities, and mysql_real_escape_string
 * $string = string to prep
 */   
function mysql_prep_string($string) {
    global $db;
    return mysqli_real_escape_string($db->connection, htmlentities(strip_tags($string)));
}

function mysql_timestamp($time = NULL) {
    if ($time == NULL) $time = time();
    return date("Y-m-d H:i:s", $time);
}
?>