<?php

/**
 * Register
 * Updated: 10/24/13
 * Author: Chris Hsu
 */

// Create Account
$bad_captcha = false;
if (isset($_POST['page'])) {
    require_once("_include/recaptchalib.php");
    $r = recaptcha_check_answer(RECAPTCHA_PRIVATE_KEY,
        $_SERVER['REMOTE_ADDR'],
        $_POST["recaptcha_challenge_field"],
        $_POST["recaptcha_response_field"]);
    if (!$r->is_valid) { $bad_captcha = true; goto normal; } //ew
    //should go straight through, show username & link to login
    //otherwise display an error, try again..
    
    require_once("_include/db.php");
    require_once("_include/functions.php");
    
    $p = array();
    foreach($_POST as $key => $post) {
        $p[$key] = mysql_prep_string($post);
    }
    $error = array(false,"Something failed :(");
    
    $q = sprintf("SELECT COUNT(*) FROM ".TBL_USER." WHERE email = '%s'", $p['email']);
    $res = $db->query($q);
    if ($res) {
       $count = $db->fetch($res);
       if ($count[0] != 0) $error = array(true,"Email is already used!");
    }
    else $error[0] = true;
    if ($error[0]) goto display;
    
    $passwordHash = passwordHash($p['password'], PW_SALT);
    $q = sprintf("INSERT INTO ".TBL_USER.
                 " (email, nameFirst, nameLast, nameMiddle, passwordHash, phone, admin, defaultGroup, createdOn)".
                 " VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %d, %d, NOW())",
                 $p['email'], $p['firstname'], $p['lastname'], $p['midname'], $passwordHash, $p['phone'], TBL_USER__not_admin, 0);
    if (!$db->query($q)) $error[0] = true;
    $error[1] .= "<br>".$q."<br>".$db->error();
    
    // Display section
    display:
    ?>
<!DOCTYPE html>
<html>
<head>
    <title>DetNet Layout</title>
    <link href="css/styles.css" type="text/css" rel="stylesheet" media="screen">
    <link href="css/register-styles.css" type="text/css" rel="stylesheet" media="screen">
</head>
<body id="register">
<div id="wrapper">
    <h1>DetNet <span class="right">Create Account</span></h1>
    <div id="create-account">
        <?php
        if ($error[0]):
            echo $error[1]." Please try again.";
        else:
            echo sprintf("%s %s, you have registered!<br><br><a href=\"login.php\">Login Here</a>", $p['firstname'], $p['lastname']);
        endif;
        ?>
    </div>
</div>
</body>
</html>
    <?php
    return;
}

// Normal Page
normal:

function disp ($value) {
    echo (isset($_POST[$value])) ? " value='".htmlentities($_POST[$value])."'": "";
}

// PLUGIN FUNCTIONALITY

?>
<!DOCTYPE html>
<html>
<head>
    <title>DetNet Layout</title>
    <link href="css/styles.css" type="text/css" rel="stylesheet" media="screen">
    <link href="css/register-styles.css" type="text/css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="js/register.js"></script>
</head>
<body id="register">
<div id="wrapper">
    <h1>DetNet <span class="right">Create Account</span></h1>
    <div id="create-account">
        <form method="post" id="ca-form" action="register.php">
            <div class="create-box"><h2>Step 1: Basic Information</h2>
                <label for="firstname">First Name</label><input type="text" id="firstname" name="firstname" required<?php disp('firstname'); ?>/><br>
                <label for="lastname">Last Name</label><input type="text" id="lastname" name="lastname" required<?php disp('lastname'); ?>/><br>
                <label for="midname">Middle Initial</label><input type="text" maxlength="1" name="midname" id="midname" required<?php disp('midname'); ?>/><br>
                <label for="email">Email</label><input type="email" name="email" id="email" required<?php disp('email'); ?>/><br>
                <label for="phone">Cell Phone</label><input type="text" name="phone" id="phone" required<?php disp('phone'); ?>/><br>
            </div>
            <div class="create-box"><h2>Step 2: Set Password</h2>
                <!--<label for="username">Username</label><input type="text" id="username" disabled required<?php disp('username'); ?>/><input type="hidden" id="username_h" name="username"<?php disp('username'); ?>/><label class="error">Your username is auto-generated</label><br>-->
                <label for="password">Password</label><input type="password" id="password" name="password" required<?php disp('password'); ?>/><br>
                <label for="password2">Confirm Password</label><input type="password" id="password2" name="password2" required<?php disp('password2'); ?>/>
            </div>
            <div class="create-box"><h2>Step 3: Security Key</h2>
                <!--<label for="security-key">Key</label><input type="text" id="security-key" required/>-->
                <div id="recaptcha_box">
                <?php
                require_once('_include/recaptchalib.php');
                echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY);
                ?>
                <?php
                if ($bad_captcha) : ?>
                <label for="recaptcha_response_field" class="error">Incorrect input. Please try again.</label>
                <?php
                endif;
                ?>
                </div>
            </div>
            <div id="nav">
                <button type="button" id="prev" class="left hidden">Prev</button>
                <button type="button" id="next" class="right">Next</button>
                <button type="submit" id="submit" class="right hidden">Register</button>
                <input type="hidden" id="page" name="page" value="<?php echo ($bad_captcha) ? 3:1; ?>"/>
            </div>
        </form>     
    </div>
</div>
</body>
</html>