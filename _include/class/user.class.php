<?php

include_once("_include/class/group.class.php");
include_once("_include/class/assignment.class.php");

class UserNotFoundException extends Exception {}
class UserInfoNotFoundException extends Exception {}


class DetnetUser {
    
    var $userID;
    var $fullname;
    var $email;
    var $phone;
    var $defaultGroup;
    var $info;
    
    function DetnetUser($a, $opts = array()) {
        if (is_array($a)) $this->_initArray($a, $opts);
        else $this->_initID($a, $opts);
    }
    
    private function _initID($id) {
        global $db;
        $q = sprintf("SELECT * FROM ".TBL_USER." WHERE userID = %d",
                     mysql_prep_string($id));
        $res = $db->query($q);
        if (!$res) throw new DatabaseException();
        $res = $db->fetch($res);
        if ($res == NULL) throw new UserNotFoundException();
        $this->userID = $res['userID'];
        $this->fullname = $res['nameFirst']." ".$res['nameMiddle']." ".
                          $res['nameLast'];
        $this->email = $res['email'];
        $this->phone = $res['phone'];
        $this->info = $res;
        $this->_getGroup($res['defaultGroup']);
    }
    
    private function _initArray($info) {
        $this->userID = $info['userID'];
        $this->fullname = $info['nameFirst']." ".$info['nameMiddle']." ".
                          $info['nameLast'];
        $this->email = $info['email'];
        $this->phone = $info['phone'];
        $this->info = $info;
        $this->_getGroup($info['defaultGroup']);
    }
    
    private function _getGroup($gid) {
        $this->defaultGroup['id'] = '';
        $this->defaultGroup['name'] = 'None';
        try {
            $group = new DetnetGroup($gid);
            $this->defaultGroup['id'] = $group->groupID;
            $this->defaultGroup['name'] = $group->name;
        }
        catch (DatabaseException $e) {
            
        }
        catch (GroupNotFoundException $e) {
            
        }
    }
    
    function getAssignment() {
        global $db;
        $q = sprintf("SELECT * FROM ".TBL_U_ASSIGN." AS ua, ".TBL_ASSIGN." AS a WHERE ua.userID = %d AND ua.startDate <= CURDATE() AND ua.endDate >= CURDATE() AND ua.assignmentID = a.assignmentID", $this->userID);
        //echo $q;
        $res = $db->query($q);
        if (!$res) throw new DatabaseException();
        $res = $db->fetch($res);
        if ($res == NULL) throw new UserInfoNotFoundException();
        return $res;
    }
    
    function getDate($d) {
        if (!isset($this->info[$d])) return "no date";
        if ($this->info[$d] == "0000-00-00 00:00:00") return "---";
        return date("F j, Y H:i",strtotime($this->info[$d]));
    }
    
    static function searchUsers($str) {
        global $db;
        $q = sprintf("SELECT * FROM ".TBL_USER." WHERE nameLast LIKE '%s%%' OR nameFirst LIKE '%s%%'", mysql_prep_string($str), mysql_prep_string($str));
        $res = $db->query($q);
        if (!$res) return false;
        return $res;
    }
    
    static function createUserAssignment($uaID, $aid, $uid) {
        global $db;
        $q = sprintf("INSERT INTO ".TBL_U_ASSIGN." (uaID, assignmentID, userID) VALUES ('%s', %d, %d)", mysql_prep_string($uaID), mysql_prep_string($aid), mysql_prep_string($uid));
        if (!$db->query($q)) return false;
        return true;
    }
    
    static function updateUserAssignment($uaID, $aid, $uid) {
        global $db;
        $q = sprintf("UPDATE ".TBL_U_ASSIGN." SET assignmentID = %d WHERE userID = %d AND uaID = '%s'", mysql_prep_string($aid), mysql_prep_string($uid), mysql_prep_string($uaID));
        if (!$db->query($q)) return false;
        return true;
    }
    
    static function updateUser($data, $id) {
        global $db;
        $q = sprintf("UPDATE ".TBL_USER." SET %s WHERE %s", splitArray($data, ","), splitArray($id, " AND "));
        $res = $db->query($q);
        return $res;
    }
    
    static function getFullname($info) {
        return $info['nameFirst']." ".$info['nameMiddle']." ".$info['nameLast'];
    }
}

?>