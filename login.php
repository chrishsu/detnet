<?php

/**
 * Login
 * Updated: 10/23/13
 * Author: Chris Hsu
 */

require_once("_include/session.php");

if (isset($_POST['login'])) {
    if ($session->login($_POST['username'], $_POST['password'])) {
        if (isset($_POST['remember'])) $session->cookie();
        header("Location: index.php");
    }
    $error = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>DetNet Login</title>
    <link href="css/styles.css" type="text/css" rel="stylesheet">
    <link href="css/login-styles.css" type="text/css" rel="stylesheet" media="screen and (min-width: 600px)">
    <!--<link href="small-styles.css" type="text/css" rel="stylesheet" media="screen and (max-width: 799px)">-->
</head>
<body id="login">
<div id="wrapper">
    <div id="left">
        <h1>Welcome to DetNet!</h1>
        <p>
            This is Det 730's internal network.<br>
            Unauthorized access disallowed.
        </p>
    </div>
    <div id="right">
        <form method="post">
            <label for="username"> Username:</label>
            <input type="text" name="username" id="username"><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password"><br>
            <label for="remember" id="remember-label">Remember Me:</label>
            <input type="checkbox" name="remember" id="remember">
            <input type="submit" name="login" value="Login">
        </form>
        <?php if ($error): ?>
        <p class="small error">The username or password was not valid.</p>
        <?php endif; ?>
        <p class="small bottom"><a href="#">Forgot Password?</a></p>
    </div>
</div>
</body>
</html>