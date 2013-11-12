<?php

//require_once("_include/interface.php");
require_once("_include/class/group.class.php");

class AssignmentNotFoundException extends Exception {}

class DetnetAssignment {
    
    const TABLE = TBL_ASSIGN;
    var $id;
    var $name;
    var $description;
    var $parent;
    var $defaultGroup;
    var $hiearchy;
    var $info;
    
    function DetnetAssignment($a, $recurse = false) {
        if (is_array($a)) $this->_initArray($a, $recurse);
        else $this->_initID($a, $recurse);
    }
    
    private function _initID($id, $recurse) {
        global $db;
        $q = sprintf("SELECT * FROM ".TBL_ASSIGN." WHERE assignmentID = %d",
                     mysql_prep_string($id));
        $res = $db->query($q);
        if (!$res) throw new DatabaseException();
        $res = $db->fetch($res);
        if ($res == NULL) throw new AssignmentNotFoundException();
        $this->id = $res['assignmentID'];
        $this->name = $res['name'];
        $this->description = $res['description'];
        $this->info = $res;
        if ($recurse) $this->_getParent($res['parentID']);
        $this->defaultGroup = $this->_getGroup($res['defaultRestrictions']);
        $this->hierachy = $this->_getGroup($res['hiearchyID']);
    }
    
    private function _initArray($info, $recurse) {
        $this->id = $info['assignmentID'];
        $this->name = $info['name'];
        $this->description = $info['description'];
        $this->info = $info;
        if ($recurse) $this->_getParent($info['parentID']);
        $this->defaultGroup = $this->_getGroup($info['defaultRestrictions']);
        $this->hierachy = $this->_getGroup($info['hiearchyID']);
    }
    
    private function _getParent($pid) {
        $this->parent = "None";
        if ($pid == 0) return;
        try {
            $parent = new DetnetAssignment($pid);
            $this->parent = $parent->name;
        }
        catch (DatabaseException $e) {
        }
        catch (AssignmentNotFoundException $e) {
        }
    }
    
    private function _getGroup($gid) {
        $arr = array();
        $arr['id'] = "";
        $arr['name'] = "None";
        try {
            $group = new DetnetGroup($gid);
            $arr['id'] = $group->groupID;
            $arr['name'] = $group->name;
        }
        catch (DatabaseException $e) {
        }
        catch (GroupNotFoundException $e) {
        }
        return $arr;
    }
    
    public function getDate($d) {
        if (!isset($this->info[$d])) return "no date";
        if ($this->info[$d] == "0000-00-00 00:00:00") return "---";
        return date("F j, Y H:i",strtotime($this->info[$d]));
    }
    
    static function createAssignment($params) {
        global $db;
        $arr_k = ''; $arr_v = '';
        foreach ($params as $k => $d) {
            $arr_k .= mysql_prep_string($k).",";
            $arr_v .= mysql_prep_string($d)."','";
        }
        $arr_k = rtrim($arr_k, ','); $arr_v = rtrim($arr_v, "','");
        $q = "INSERT INTO ".DetnetAssignment::TABLE." ($arr_k) VALUES ('$arr_v')";
        $res = $db->query($q);
        return $res;
    }
    
    static function updateAssignment($data, $i) {
        global $db;
        $q = sprintf("UPDATE ".DetnetAssignment::TABLE." SET %s WHERE %s", splitArray($data, ","), splitArray($id, " AND "));
        $res = $db->query($q);
        return $res;
    }
    
    static function deleteAssignment($id) {
        global $db;
        $q = sprintf("DELETE FROM ".DetnetAssignment::TABLE." WHERE %s", splitArray($id, " AND "));
        $res = $db->query($q);
        return $res;
    }
    
    static function searchAssignments($str) {
        global $db;
        $q = sprintf("SELECT * FROM ".DetnetAssignment::TABLE." WHERE name LIKE '%s%%'", mysql_prep_string($str));
        $res = $db->query($q);
        if (!$res) return false;
        return $res;
    }
}

?>