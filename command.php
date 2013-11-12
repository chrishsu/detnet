<?php

/* Command */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

require_once("_include/class/group.class.php");

?>
<h2>Command</h2>
<?php

// TODO: Limit, don't show admin? order by?
$q = sprintf("SELECT * FROM ".TBL_GROUP.
             " WHERE defaultType = %d", TBL_GROUP__hiearchy);
$res = $db->query($q);
if (!$res) groups_QueryError();
else {
    if ($db->is_empty($res)) groups_NotFound();
    else {
        groups_Wrapper(true);
        while (($row = $db->fetch($res)) != NULL) {
            $group = new DetnetGroup($row, true);
            groups_Display($group);
        }
        groups_Wrapper(false);
    }
}

function groups_QueryError() {
    ?>
<p>Database error :(</p>
    <?php
}

function groups_NotFound() {
    ?>
<p>Groups were not found :(</p>
    <?php
}

function groups_Wrapper($head) {
    if ($head) : ?>
<table>
    <tr>
        <td>Name</td>
        <td>Parent</td>
        <td>Created On</td>
    </tr>
    <?php else : ?>
</table>
    <?php endif;
}


function groups_Display($group) {
    ?>
    <tr>
        <td><a href="?p=group&id=<?php echo $group->groupID; ?>">
            <?php echo $group->name; ?></a></td>
        <td><?php echo $group->parent; ?></td>
        <td><?php echo $group->getDate('createdOn'); ?></td>
    </tr>
    <?php
}