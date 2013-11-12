<?php

/* Assignments */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

require_once("_include/class/assignment.class.php");

?>
<h2>Assignments</h2>
<?php

$q = sprintf("SELECT * FROM ".TBL_ASSIGN);
$res = $db->query($q);
if (!$res) assignment_QueryError();
else {
    if ($db->is_empty($res)) assignment_NotFound();
    else {
        assignment_Wrapper(true);
        while (($row = $db->fetch($res)) != NULL) {
            $assign = new DetnetAssignment($row, true);
            assignment_Display($assign);
        }
        assignment_Wrapper(false);
    }
}

function assignment_QueryError() {
    ?>
<p>Database error :(</p>
    <?php
}

function assignment_NotFound() {
    ?>
<p>Assignments were not found :(</p>
    <?php
}

function assignment_Wrapper($head) {
    if ($head) : ?>
<table>
    <tr>
        <td>Name</td>
        <td>Parent</td>
        <td>Restrictions</td>
        <td>Command</td>
    </tr>
    <?php else : ?>
</table>
    <?php endif;
}


function assignment_Display($assign) {
    ?>
    <tr>
        <td><a href="?p=assignment&id=<?php echo $assign->id; ?>">
            <?php echo $assign->name; ?></a></td>
        <td><?php echo $assign->parent; ?></td>
        <td><a href="?p=group&id=<?php echo $assign->defaultGroup['id']; ?>">
        <?php echo $assign->defaultGroup['name']; ?></a></td>
        <td><a href="?p=group&id=<?php echo $assign->hierachy['id']; ?>">
        <?php echo $assign->hierachy['name']; ?></a></td>
    </tr>
    <?php
}