<?php

require_once("_include/session.php");

$log = array();

if (!$session->admin) Location:("index.php");

$log['error'] = true;

switch ($_POST['page']) {
    case 'group':
        require_once("_include/class/group.class.php");
        switch ($_POST['action']) {
            case 'search':
                $res = DetnetGroup::searchGroups($_POST['query'], array('defaultType' => TBL_GROUP__default, 'defaultType' => TBL_GROUP__none));
                $log['message'] = "failed to search";
                if (!$res) break;
                $log['error'] = false;
                $results = array();
                while (($row = $db->fetch($res)) != NULL) {
                    $results[] = array('id' => $row['groupID'],
                                       'name' => $row['name'],
                                       'description' => $row['description']);
                }
                $log['results'] = $results;
                break;
            case 'save':
                $log['message'] = "failed to save";
                $a = array();
                $a['name'] = $_POST['name'];
                $a['description'] = $_POST['description'];
                $log['name'] = $a['name'];
                // Create new
                if ($_POST['id'] == "") {
                    if (DetnetGroup::createGroup($a)) $log['error'] = false;
                } else {
                    $log['edit'] = true;
                    if (DetnetGroup::updateGroup($a, array('groupID' => $_POST['id']))) $log['error'] = false;
                }
                break;
            case 'del':
                $log['message'] = "failed to delete";
                if (DetnetGroup::deleteGroup(array('groupID' => $_POST['id']))) $log['error'] = false;
                break;
            default:
                break;
        }
        break;
    case "command":
        require_once("_include/class/group.class.php");
        switch ($_POST['action']) {
            case 'search':
                $id = $_POST['id'];
                $res = DetnetGroup::searchGroups($_POST['query'], array('defaultType' => TBL_GROUP__hiearchy));
                $log['message'] = "failed to search";
                if (!$res) break;
                $log['error'] = false;
                $results = array();
                while (($row = $db->fetch($res)) != NULL) {
                    if ($id == $row['groupID']) continue;
                    $name = "";
                    try {
                        if ($row['parentID'] != 0) {
                            $parent = new DetnetGroup($row['parentID']);
                            $name = $parent->name;
                        }
                    }
                    catch (Exception $e) {
                        
                    }
                    $results[] = array('id' => $row['groupID'],
                                       'name' => $row['name'],
                                       'description' => $row['description'],
                                       'parent' => $name,
                                       'parentID' => $row['parentID']);
                }
                $log['results'] = $results;
                break;
            case 'save':
                $log['message'] = "failed to save";
                $a = array();
                $a['name'] = $_POST['name'];
                $a['description'] = $_POST['description'];
                $a['parentID'] = $_POST['parent'];
                $a['defaultType'] = TBL_GROUP__hiearchy;
                
                $log['name'] = $a['name'];
                // Create new
                if ($_POST['id'] == "") {
                    if (DetnetGroup::createGroup($a)) $log['error'] = false;
                } else {
                    $log['edit'] = true;
                    if (DetnetGroup::updateGroup($a, array('groupID' => $_POST['id']))) $log['error'] = false;
                }
                break;
            case 'del':
                $log['message'] = "failed to delete";
                if (DetnetGroup::deleteGroup(array('groupID' => $_POST['id']))) $log['error'] = false;
                break;
            default:
                break;
        }
        break;
    case "assignment":
        require_once("_include/class/assignment.class.php");
        switch ($_POST['action']) {
            case 'search':
                switch ($_POST['params']['type']) {
                    case 'default':
                        searchGroup($_POST['query'], array('defaultType' => TBL_GROUP__default)); 
                        break;
                    case 'hiearchy':
                        searchGroup($_POST['query'], array('defaultType' => TBL_GROUP__hiearchy)); 
                        break;
                    default:
                        searchAssignment($_POST['query']);
                        break;
                }
                break;
            case 'save':
                $log['message'] = "failed to save";
                $a = array();
                $a['name'] = $_POST['name'];
                $a['description'] = $_POST['description'];
                $a['parentID'] = $_POST['parent'];
                $a['defaultRestrictions'] = $_POST['default'];
                $a['hiearchyID'] = $_POST['command'];
                
                $log['name'] = $a['name'];
                // Create new
                if ($_POST['id'] == "") {
                    if (DetnetAssignment::createAssignment($a)) $log['error'] = false;
                } else {
                    $log['edit'] = true;
                    if (DetnetAssignment::updateAssignment($a, array('assignmentID' => $_POST['id']))) $log['error'] = false;
                }
                break;
            case 'del':
                $log['message'] = "failed to delete";
                if (DetnetAssignment::deleteAssignment(array('assignmentID' => $_POST['id']))) $log['error'] = false;
                break;
            default:
                break;
        }
        break;
    case "member":
        switch ($_POST['action']) {
            case 'search':
                switch ($_POST['params']['type']) {
                    case 'default':
                        searchGroup($_POST['query'], array('defaultType' => TBL_GROUP__default)); 
                        break;
                    case 'assignment':
                        searchAssignment($_POST['query']);
                        break;
                    default:
                        searchUser($_POST['query']);
                        break;
                }
                break;
            case "save":
                require_once("_include/class/user.class.php");
                $log['message'] = "failed to save";
                $a = array();
                $uaID = $_POST['assignmenthash'];
                $assignID = $_POST['assignment'];
                $a['defaultGroup'] = $_POST['default'];
                $log['name'] = $_POST['name'];
                // Create new
                $log['edit'] = true;
                if (!DetnetUser::updateUser($a, array('userID' => $_POST['id']))) {
                    break;
                }
                //$log['message'] = "assignment faield";
                if ($uaID == "") {
                    $ua = array();
                    $ua['assignmentID'] = $assignID;
                    $ua['userID'] = $_POST['id'];
                    $uaID = md5($assignID.$_POST['id'].time());
                    if (DetnetUser::createUserAssignment($uaID, $assignID, $_POST['id'])) $log['error'] = false;
                    $log['hash'] = $uaID;
                }
                else {
                    if (!DetnetUser::updateUserAssignment($uaID, $assignID, $_POST['id'])) break;
                $log['error'] = false;
                }
                break;
            default:
                break;
        }
    default:
        break;
}

header("Content-type: text/json");
echo json_encode($log);


function searchUser($query) {
    global $db, $log;
    require_once("_include/class/user.class.php");
    $res = DetnetUser::searchUsers($query);
    $log['message'] = "failed to search";
    if (!$res) return false;
    $log['error'] = false;
    $results = array();
    while (($row = $db->fetch($res)) != NULL) {
        $assignID = ''; $assign = ''; $uaID = '';
        $default = '';
        try {
            $user = new DetnetUser($row['userID']);
            $default = $user->defaultGroup['name'];
            $as = $user->getAssignment();
            $assign = $as['name'];
            $assignID = $as['assignmentID'];
            $uaID = $as['uaID'];
        } catch (Exception $e) { }
        $results[] = array('id' => $row['userID'],
                           'name' => DetnetUser::getFullname($row),
                           'default' => $default,
                           'defaultID' => $row['defaultGroup'],
                           'assignment' => $assign,
                           'assignmentID' => $assignID,
                           'assignmentHash' => $uaID);
    }
    $log['results'] = $results;
    return true;
}

function searchGroup($query, $types) {
    global $db, $log;
    require_once("_include/class/group.class.php");
    $res = DetnetGroup::searchGroups($query, $types);
    $log['message'] = "failed to search";
    if (!$res) return false;
    $log['error'] = false;
    $results = array();
    while (($row = $db->fetch($res)) != NULL) {
        $results[] = array('id' => $row['groupID'],
                           'name' => $row['name'],
                           'description' => $row['description']);
    }
    $log['results'] = $results;
    return true;
}

function searchAssignment($query) {
    global $db, $log;
    require_once("_include/class/assignment.class.php");
    $res = DetnetAssignment::searchAssignments($query);
    $log['message'] = "failed to search";
    if (!$res) return false;
    $log['error'] = false;
    $results = array();
    while (($row = $db->fetch($res)) != NULL) {
        $default = ''; $command = '';
        try {
            $d = new DetnetGroup($row['defaultRestrictions']);
            $default = $d->name;
        } catch (Exception $e) { }
        try {
            $c = new DetnetGroup($row['hiearchyID']);
            $command = $c->name;
        } catch (Exception $e) { }
        
        $results[] = array('id' => $row['assignmentID'],
                           'name' => $row['name'],
                           'description' => $row['description'],
                           'default' => $default,
                           'defaultID' => $row['defaultRestrictions'],
                           'command' => $command,
                           'commandID' => $row['hiearchyID']);
    }
    $log['results'] = $results;
}

?>