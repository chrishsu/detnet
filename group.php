<?php

/* Group */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

require_once("_include/class/group.class.php");

if (!isset($_GET['id'])) group_NotFound();
else {
    try {
        $group = new DetnetGroup($_GET['id']);
        group_Display($group);
    }
    catch (DatabaseExcpetion $e) {
        //Do something different?
        user_NotFound();
    }
    catch (GroupNotFoundException $e) {
        group_NotFound();
    }
}

function group_NotFound() {
    ?>
<h2>Group was not found :(</h2>
    <?php
}

function group_Display($group) {
    ?>
<h2><?php echo $group->name; ?></h2>
<p>
    Description: <?php echo $group->description; ?><br>
    Created On: <?php echo $group->getDate('createdOn'); ?><br>
</p>
    <?php
}