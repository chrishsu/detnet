<?php

/* Admin */

require_once("_include/session.php");

if (!$session->is_admin) header("Location: login.php");

$adminLinks = array(
    '' => 'Main',
    'members' => 'Members',
    'command' => 'Command Structure',
    'assignments' => 'Assignments',
    'groups' => 'Groups',
    'registration' => 'Registration'
);

$adminPage = "";
if (isset($_GET['e'])) $adminPage = $_GET['e'];

?>
<h2>Admin Panel</h2>
<ul>
    <?php foreach ($adminLinks as $u => $link) : ?>
    <li>
        <a href="?p=admin&e=<?php echo $u; ?>" class="<?php link_isSelected($u, $adminPage); ?>">
        <?php echo $link; ?></a>
    </li>
    <?php endforeach; ?>
</ul>
<?php

switch ($adminPage) {
    case "":
        ?>
        <h5>This is Version 0.2</h5>
        <p class="code">- admin: create groups, assignments, command structure
- basic command structure and assignments display
        </p>
        <h5>Version 0.1</h5>
        <p class="code">- basic registration
- Login
- basic user display, members display
- basic account management
        </p>
        <h5>Next Versions 0.3+</h5>
        <p class="code">- assign users jobs
- registration keys for groups
- CSS: user, members display
- JS: account validation
- CSS: command, assignment, group display
- forgot password generator
        </p>
        <?php
        break;
    default:
        include("_include/admin/".$adminPage.".admin.php");
        break;
}

?>