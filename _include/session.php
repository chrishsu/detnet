<?php

/**
 * Constants/Database Class
 * Updated: 10/25/13
 * Author: Chris Hsu
 *
 * Keeps track of a users session
 */

define("DN_DEBUG", true);
define("DN_COOKIE_LIFE", 24*60*60); //24 hours
 
include_once("log.php");
require_once("db.php");
include_once("functions.php");

/*Session Class*/
class Session {
    var $userID;
    var $username;
    var $fullname;
    var $logged_in;
    var $is_admin;
    
    function Session() {
        $this->startSession();
    }
    
    function startSession() {
        session_start();
        $this->logged_in = $this->checkLogin();
        if (!$this->logged_in) {
            $this->is_admin = NOT_LOGGED_IN;
            unset($_SESSION['userID']);
        }
    }
    
    function checkLogin() {
        global $db;
        if (isset($_SESSION['userID'])) {
            $userID = $_SESSION['userID'];
        }
        elseif (isset($_COOKIE['detnet-user']) &&
                isset($_COOKIE['detnet-login'])) {
            $q = sprintf("SELECT * FROM ".TBL_U_HASH.
                         " WHERE hash = '%s' AND userID = '%s' AND ".
                         "type = %d AND expires > NOW()",
                         $_COOKIE['detnet-login'], $_COOKIE['detnet-user'],
                         TBL_U_HASH__cookie);
            if (!$db->query($q)) return false;
            $userID = $_COOKIE['detnet-user'];
            $_SESSION['userID'] = $userID;
        }
        else return false;
        
        $q = sprintf("SELECT * FROM ".TBL_USER." WHERE userID = '%s'",
                     mysql_prep_string($userID));
        $res = $db->query($q);
        
        if (!$res) return false;
        $res = $db->fetch($res);
        if ($res == NULL) return false;
        $this->is_admin = $res['admin'];
        $this->userID = $userID;
        $this->username = $res['email'];
        $this->fullname = $res['nameFirst']." ".$res['nameLast'];
        
        if (isset($_COOKIE['detnet-user']) &&
            isset($_COOKIE['detnet-login'])) {
            $this->cookie($_COOKIE['detnet-login']);
        }
        
        // Update the lastActive.
        $q = sprintf("UPDATE ".TBL_USER." SET lastActive = NOW() WHERE userID = '%s'",
                     mysql_prep_string($userID));
        $db->query($q);
        
        return true;
    }
    
    function login($user, $pass) {
        global $db;
        $p_user = mysql_prep_string($user);
        $q = sprintf("SELECT passwordHash, userID FROM ".TBL_USER." WHERE email = '%s'", $p_user);
        $res = $db->query($q);
        if (!$res) return false;
        $res = $db->fetch($res);
        if ($res['passwordHash'] != md5($pass.PW_SALT)) return false;
        $this->is_admin = $res['admin'];
        $_SESSION['userID'] = $res['userID'];
        return true;
    }
    
    function logout() {
        unset($_SESSION['userID']);
        $this->logged_in = false;
        $this->is_admin = NOT_LOGGED_IN;
        $this->userID = '';
        if (isset($_COOKIE['detnet-login'])) {
            global $db;
            $q = sprintf("DELETE FROM ".TBL_U_HASH." WHERE hash = '%s'",
                         $_COOKIE['detnet-login']);
            $db->query($q);
        }
        setcookie("detnet-login", "", time() - DN_COOKIE_LIFE); //remove cookie
    }
    
    function cookie($h = NULL) {
        global $db;
        if (!isset($_SESSION['userID'])) return;
        if ($h == NULL) $h = md5(time().$_SESSION['userID']); //cookie hash
        $ttl = time() + DN_COOKIE_LIFE;
        $q = sprintf("INSERT INTO ".TBL_U_HASH.
                     " (userID, hash, type, expires)".
                     " VALUES (%d, '%s', %d, '%s')".
                     " ON DUPLICATE KEY UPDATE expires='%s'",
                     $_SESSION['userID'], $h, TBL_U_HASH__cookie,
                     mysql_timestamp($ttl), mysql_timestamp($ttl));
        $db->query($q);
        setcookie("detnet-user", $_SESSION['userID'], $ttl);
        setcookie("detnet-login", $h, $ttl);
    }
    
    function checkUser($uid) {
        if ($uid == $this->userID) return true;
        else $this->checkLevel(LOGGED_IN);
    }
    
    function checkLevel($level) {
        if ($this->is_admin > $level) return true;
        logger("Session->checkLevel: Invalid Permissions for ".$this->username);
        return false;
    }
};

$session = new Session;

?>