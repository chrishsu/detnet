<?php

/* Members */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

require_once("_include/class/user.class.php");

?>
<h2>Members</h2>
<?php

// TODO: Limit, don't show admin? order by?
$q = sprintf("SELECT * FROM ".TBL_USER);
$res = $db->query($q);
if (!$res) members_QueryError();
else {
    if ($db->is_empty($res)) members_NotFound();
    members_Wrapper(true);
    while (($row = $db->fetch($res)) != NULL) {
        $user = new DetnetUser($row);
        members_Display($user);
    }
    members_Wrapper(false);
}

function members_QueryError() {
    ?>
<p>Database error :(</p>
    <?php
}

function members_NotFound() {
    ?>
<p>Members were not found :(</p>
    <?php
}

function members_Wrapper($head) {
    if ($head) : ?>
<table>
    <tr>
        <td>Last Name</td>
        <td>First Name</td>
        <td>Email</td>
        <td>Phone Number</td>
    </tr>
    <?php else : ?>
</table>
    <?php endif;
}


function members_Display($user) {
    ?>
    <tr>
        <td><a href="?p=user&id=<?php echo $user->userID; ?>">
            <?php echo $user->info['nameLast']; ?></a></td>
        <td><?php echo $user->info['nameFirst']; ?></td>
        <td><?php echo $user->email; ?></td>
        <td><?php echo $user->phone; ?></td>
    </tr>
    <?php
}