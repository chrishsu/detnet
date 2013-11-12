<?php

/**
 * Login
 * Updated: 10/23/13
 * Author: Chris Hsu
 */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

if (isset($_GET['logout'])) {
    $session->logout();
    header("Location: login.php");
}

$page = "";
if (isset($_GET['p'])) $page = $_GET['p'];

//NEXT: account
//NEXT: user
//NEXT: members (display only)
//NEXT: admin

$mainLinks = array(
    '' => 'Home',
    'members' => 'Members',
    'assignments' => 'Assignments',
    'command' => 'Command Structure'
);

?>
<!DOCTYPE html>
<html>
<head>
    <title>DetNet</title>
    <link href="css/styles.css" type="text/css" rel="stylesheet" media="screen">
    <link href="css/nav-styles.css" type="text/css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
    <!--<link href="small-styles.css" type="text/css" rel="stylesheet" media="screen and (max-width: 799px)">-->
    <?php
    /** Include CSS styles **/
    switch($page) {
        case "account": 
            ?>
    <link href="css/account-styles.css" type="text/css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/account.js"></script>
            <?php
            break;
        case "user":
            ?>
            <?php
            break;
        case "members":
            ?>
            <?php
            break;
        case "admin":
            ?>
    <link href="css/admin-styles.css" type="text/css" rel="stylesheet" media="screen">
            <?php
            break;
        default:
    }
    ?>
</head>
<body>
<div id="header-bar">
    <div id="header-nav">
        <div class="left" id="left-box">
            <span id="title">DetNet</span>
            <a href="#" id="notification-button">Notifications</a>
        </div>
        <div id="search-box"><form method="post">
            <input type="text" name="search" id="search-bar" autocomplete="off"><label for="search-bar">Search</label>
        </form></div>
        <div class="right">
        <a href="?p=account" id="account-button">Account</a>
        <a href="?logout" id="logout">Logout</a>
        </div>
    </div>
</div>
<div id="wrapper">
    <div id="left-col">
        <div id="person-nav" class="nav-section">
            <a href="?p=user&id=<?php echo $session->userID; ?>"><img src="http://placehold.it/50x50"></a>
            <span><a href="?p=user&id=<?php echo $session->userID; ?>">
            <?php echo $session->fullname; ?>
            </a></span>
        </div>
        <div id="main-nav" class="nav-section">
            <ul>
                <?php foreach ($mainLinks as $u => $link) : ?>
                <li>
                    <?php if ($session->is_admin) : ?>  
                    <!--<a class="admin-button" id="admin-home-button" href="#"></a>-->
                    <?php endif; ?>
                    <a class="nav-click<?php link_isSelected($u, $page); ?>" href="?p=<?php echo $u; ?>"><?php echo $link;?></a>
                </li>
                <?php endforeach; ?>
                <?php if ($session->is_admin) : ?>  
                <li><a class="nav-click<?php link_isSelected("admin", $page); ?>" href="?p=admin">Admin</a></li>
                <?php endif; ?>
                <!--<li><a class="nav-click" href="#">Events<span class="unread">3</span></a></li>
                <li><a class="nav-click" href="#">Plugins</a></li>-->
            </ul>
        </div>
        <div id="group-nav" class="nav-section">
            <h6><a href="?p=groups">Groups</a></h6>
            <ul>
                <!--<li><a class="nav-click" href="#">Group A</a></li>
                <li><a class="nav-click" href="#">Group B</a></li>
                <li><a class="nav-click" href="#">Group C</a></li>-->
            </ul>
        </div>
    </div>
    <div id="content-col" class="clearfix">
        <?php
        switch($page) {
            case "":
                ?>
        <h2>Welcome! <?php echo $session->username; ?></h2>
                <?php
                break;
            case "account":
                include("account.php");
                break;
            case "user":
                include("user.php");
                break;
            case "members":
                include("members.php");
                break;
            case "command":
                include("command.php");
                break;
            case "admin":
                include("admin.php");
                break;
            case "groups":
                include("groups.php");
                break;
            case "group":
                include("group.php");
                break;
            case "assignments":
                include("assignments.php");
                break;
            case "assignment":
                include("assignment.php");
                break;
            default:
        }
        ?>
    </div>
    <div id="footer" class="clearfix">
        <span>&copy; 2013 Det 730</span>
    </div>
</div>
</body>
</html>
