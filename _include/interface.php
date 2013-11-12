<?php

/**
 * Interfaces
 * Updated: 11/02/13
 * Author: Chris Hsu
 */

require_once("_include/session.php");
 
class DetnetBase {
    
    const TABLE = "";
    var $ID;
    var $info;
    
    /* Get the instance from database
     * Returns instance or null
     */
    protected static function _view(array $cols, array $id) {
        global $db;
        if (empty($cols)) $arr_v = "*";
        else {
            //$arr_v = implode("','", $data);
            $arr_v = '';
            foreach ($cols as $d) {
                $arr_v .= mysql_prep_string($d).",";
            }
            $arr_v = rtrim($arr_v, ',');
        }
        $q = sprintf("SELECT %s FROM ".TABLE." WHERE %s", $arr_v,  splitArray($id, " AND "));
        $res = $db->query($q);
        return $res; 
    }
    
    /* Create an instance in the database from array
     * Return true on success or false otherwise
    */
    protected static function _create(array $data) {
        global $db;
        //$arr_k = implode(",", array_keys($data));
        //$arr_v = implode("','", $data);
        $arr_k = ''; $arr_v = '';
        foreach ($data as $k => $d) {
            $arr_k = mysql_prep_string($k).",";
            $arr_v = mysql_prep_string($d).",";
        }
        $arr_k = rtrim($arr_k, ','); $arr_v = rtrim($arr_v, ',');
        $q = "INSERT INTO ".TABLE." ($arr_k) VALUES ('$arr_v')";
        $res = $db->query($q);
        return $res;
    }
    
    /* Update an instance in the database from array
     * Return true on success or false otherwise
    */
    protected static function _update(array $data, array $id) {
        global $db;
        $q = sprintf("UPDATE ".TABLE." SET %s WHERE %s", splitArray($data, ","), splitArray($id, " AND "));
        $res = $db->query($q);
        return $res;
    }
    
    /* Delete an instance in the database
     * Return true on success or false otherwise
     */
    protected static function _delete(array $id) {
        global $db;
        $q = sprintf("DELETE FROM ".TABLE." WHERE %s", splitArray($id, " AND "));
        $res = $db->query($q);
        return $res;
    }
    
    protected function _initDB() {
        
    }
    
    public function getDate($d) {
        if (!isset($this->info[$d])) return "no date";
        if ($this->info[$d] == "0000-00-00 00:00:00") return "---";
        return date("F j, Y H:i",strtotime($this->info[$d]));
    }
}
