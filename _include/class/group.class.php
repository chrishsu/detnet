<?php

//require_once("_include/interface.php");

class GroupNotFoundException extends Exception {}
class GroupEditException extends Exception {}

class DetnetGroup {
    
    const TABLE = TBL_GROUP;
    var $groupID;
    var $name;
    var $description;
    var $parent;
    var $info;
    
    function DetnetGroup($a, $recurse = false) {
        if (is_array($a)) $this->_initArray($a, $recurse);
        else $this->_initID($a, $recurse);
    }
    
    private function _initID($id, $recurse) {
        global $db;
        $q = sprintf("SELECT * FROM ".TBL_GROUP." WHERE groupID = %d",
                     mysql_prep_string($id));
        $res = $db->query($q);
        if (!$res) throw new DatabaseException();
        $res = $db->fetch($res);
        if ($res == NULL) throw new GroupNotFoundException();
        $this->groupID = $res['groupID'];
        $this->name = $res['name'];
        $this->description = $res['description'];
        $this->info = $res;
        if ($recurse) $this->_getParent($res['parentID']);
    }
    
    private function _initArray($info, $recurse) {
        $this->groupID = $info['groupID'];
        $this->name = $info['name'];
        $this->description = $info['description'];
        $this->info = $info;
        if ($recurse) $this->_getParent($info['parentID']);
    }
    
    private function _getParent($gid) {
        $this->parent = "None";
        if ($gid == 0) return;
        try {
            $group = new DetnetGroup($gid);
            $this->parent = $group->name;
        }
        catch (DatabaseException $e) {
        }
        catch (GroupNotFoundException $e) {
        }
    }
    
    public function getDate($d) {
        if (!isset($this->info[$d])) return "no date";
        if ($this->info[$d] == "0000-00-00 00:00:00") return "---";
        return date("F j, Y H:i",strtotime($this->info[$d]));
    }
    
    static function createGroup($params) {
        global $db;
        $arr_k = ''; $arr_v = '';
        foreach ($params as $k => $d) {
            $arr_k .= mysql_prep_string($k).",";
            $arr_v .= mysql_prep_string($d)."','";
        }
        $arr_k = rtrim($arr_k, ','); $arr_v = rtrim($arr_v, "','");
        $q = "INSERT INTO ".DetnetGroup::TABLE." ($arr_k) VALUES ('$arr_v')";
        $res = $db->query($q);
        return $res;
    }
    
    static function updateGroup($data, $id) {
        global $db;
        $q = sprintf("UPDATE ".DetnetGroup::TABLE." SET %s WHERE %s", splitArray($data, ","), splitArray($id, " AND "));
        $res = $db->query($q);
        return $res;
    }
    
    static function deleteGroup($id) {
        global $db;
        $q = sprintf("DELETE FROM ".DetnetGroup::TABLE." WHERE %s", splitArray($id, " AND "));
        $res = $db->query($q);
        return $res;
    }
    
    static function searchGroups($str, $type) {
        global $db;
        $q = sprintf("SELECT * FROM ".DetnetGroup::TABLE." WHERE name LIKE '%s%%' AND %s", mysql_prep_string($str), splitArray($type, " OR "));
        $res = $db->query($q);
        if (!$res) return false;
        return $res;
    }
}

?>