<?php

/* helper functions */

function splitArray(array $arr, $join) {
    $id_str = '';
    if (!is_array($arr)) return $arr;
    foreach ($arr as $k => $d) {
        $id_str .= sprintf("%s = '%s'$join", mysql_prep_string($k), mysql_prep_string($d));
    }
    $id_str = substr($id_str, 0, -strlen($join)); //remove the last characters
    return $id_str;
}

function passwordHash($pass, $salt) {
    return md5($pass.$salt);
}

function link_isSelected($p, $page) {
    if ($page == $p) echo " selected";
}

?>