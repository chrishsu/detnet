<?php

/* Assignment */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

require_once("_include/class/assignment.class.php");

if (!isset($_GET['id'])) assignment_NotFound();
else {
    try {
        $assign = new DetnetAssignment($_GET['id'], true);
        assignment_Display($assign);
    }
    catch (DatabaseException $e) {
        //Do something different?
        assignment_NotFound();
    }
    catch (AssignmentNotFoundException $e) {
        assignment_NotFound();
    }
}

function assignment_NotFound() {
    ?>
<h2>Assignment was not found :(</h2>
    <?php
}

function assignment_Display($assign) {
    ?>
<h2><?php echo $assign->name; ?></h2>
<p>
    Description: <?php echo $assign->description; ?><br>
    Parent: <?php echo $assign->parent; ?><br>
    Restricted to: <a href="?p=group&id=<?php echo $assign->defaultGroup['id']; ?>">
        <?php echo $assign->defaultGroup['name']; ?></a><br>
    Command Unit: <a href="?p=group&id=<?php echo $assign->hierachy['id']; ?>">
        <?php echo $assign->hierachy['name']; ?></a><br>
</p>
    <?php
}