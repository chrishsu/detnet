<?php

header('Content-Type: text/plain');

//echo "already installed!";
//exit();

require_once('db.php');
include_once('functions.php');


echo "installed!";
exit();

// set up defaults
function defaultGroups() {
    global $db;
    echo "Creating default groups..\n";
    $default_groups = array('all' => '', 'POC' => 'all', 'GMC' => 'all',
                            'cadre' => 'all',
                            '700/800' => 'POC', '400' => 'POC', '300' => 'POC',
                            '250' => 'GMC', '200' => 'GMC', '100' => 'GMC');
    $default_group_ids = array();
    foreach ($default_groups as $dg => $p) {
        if (isset($default_group_ids[$p])) $pid = $default_group_ids[$p];
        else $pid = 0;
        $q = sprintf("INSERT INTO ".TBL_GROUP." (name, parentID, defaultType) ".
                     "VALUES ('%s', %d, %d)", $dg, $pid, TBL_GROUP__default);
        if (!$db->query($q)) {
            echo "[ERROR] unable to insert into table: ".$db->error();
            echo "\nquery: ".$q;
            exit();
        }
        $default_group_ids[$dg] = $db->insert_id();
    }
}

function defaultUser() {
    global $db;
    echo "Creating default user..\n";
    $pass = passwordHash("detnet-password", PW_SALT);
    $q = sprintf("INSERT INTO ".TBL_USER." (userID, email, passwordHash, createdOn, admin, defaultGroup) ".
                 "VALUES (%d, '%s', '%s', NOW(), %d, %d)",
                 0, "admin", $pass, TBL_USER__admin, 0);
    if (!$db->query($q)) {
        echo "[ERROR] unable to insert into table: ".$db->error();
        echo "\nquery: ".$q;
        exit();
    }
}

?>