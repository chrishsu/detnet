<?php

/* User */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

require_once("_include/class/user.class.php");

if (!isset($_GET['id'])) user_notFound();
else {
    try {
        $user = new DetnetUser($_GET['id']);
        user_Display($user);
    }
    catch (DatabaseExcpetion $e) {
        //Do something different?
        user_NotFound();
    }
    catch (UserNotFoundException $e) {
        user_NotFound();
    }
}

function user_NotFound() {
    ?>
<h2>User was not found :(</h2>
    <?php
}

function user_Display($user) {
    $assignSet = false;
    try {
        $assign = $user->getAssignment();
        $assignSet = true;
    }
    catch (Exception $e) { }
    ?>
<h2><?php echo $user->fullname; ?></h2>
<p>
    Email: <?php echo $user->email; ?><br>
    Phone Number: <?php echo  $user->phone; ?><br>
    Joined: <?php echo $user->getDate('createdOn'); ?><br>
    Default Group: <a href="?p=group&id=<?php  echo $user->info['defaultGroup']; ?>"><?php echo $user->defaultGroup['name']; ?></a><br>
    Last Activity on: <?php echo $user->getDate('lastActive'); ?><br>
    Assignment: <?php if ($assignSet) : ?>
    <a href="?p=assignment&id=<?php echo $assign['assignmentID']; ?>"><?php echo $assign['name'] ?></a>
    <?php else: ?>
    None
    <?php endif; ?>
</p>
    <?php
}