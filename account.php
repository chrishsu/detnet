<?php

/* Account */

require_once("_include/session.php");

if (!$session->logged_in) header("Location: login.php");

require_once("_include/class/user.class.php");

$error = false;
$updated = false;
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case "basic":
            $q = sprintf("UPDATE ".TBL_USER." SET ".
                         "nameFirst = '%s', nameMiddle = '%s', ".
                         "nameLast = '%s', email = '%s', phone = '%s' ".
                         "WHERE userID = %d",
                         mysql_prep_string($_POST['firstname']),
                         mysql_prep_string($_POST['midname']),
                         mysql_prep_string($_POST['lastname']),
                         mysql_prep_string($_POST['email']),
                         mysql_prep_string($_POST['phone']), $session->userID);
            if (!$db->query($q)) $error = true;
            $updated = true;
            break;
        case "password":
            $q = sprintf("UPDATE ".TBL_USER." SET ".
                         "passwordHash = '%s' WHERE userID = %d",
                         passwordHash($_POST['password'], PW_SALT),
                         $session->userID);
            if (!$db->query($q)) $error = true;
            $updated = true;
            break;
        default:
            break;
    }
}

?>
<h2>Account</h2>
<?php if ($updated) : ?><span class="">Updated</span><?php endif; ?>
<?php if ($error) : ?><span class="">Error Updating</span><?php endif; ?>
<?php

try {
    $user = new DetnetUser($session->userID);
    account_Basic($user);
    account_Password($user);
}
catch (DatabaseExcpetion $e) {
    //Do something different?
    account_Error();
}
catch (UserNotFoundException $e) {
    account_Error();
}

function account_Error() {
    ?>
<p>Failed to load your account info :(</p>
    <?php
}

function account_Basic($user) {
    ?>
<div class="account-box">
    <div class="account-box-header">
        <h3>Basic Information</h3>
        <span><a href="#" class="save-account">Save</a><a href="#" class="cancel-account">Cancel</a><a href="#" class="edit-account">Edit</a></span>
    </div>
    <form method="post" id="account-basic" action="index.php?p=account">
        <input type="hidden" name="action" value="basic"/>
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" disabled required value="<?php echo $user->info['nameFirst']; ?>"/><br>
        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" disabled required value="<?php echo $user->info['nameLast']; ?>"/><br>
        <label for="midname">Middle Initial</label>
        <input type="text" maxlength="1" name="midname" id="midname" disabled required value="<?php echo $user->info['nameMiddle']; ?>"/><br>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" disabled required value="<?php echo $user->email; ?>"/><br>
        <label for="phone">Cell Phone</label>
        <input type="text" name="phone" id="phone" disabled required value="<?php echo $user->phone;?>"/><br>
    </form>
</div>
    <?php
}

function account_Password($user) {
    ?>
<div class="account-box">
    <div class="account-box-header">
        <h3>Set Password</h3>
        <span><a href="#" class="save-account">Save</a><a href="#" class="cancel-account">Cancel</a><a href="#" class="edit-account">Edit</a></span>
    </div>
    <form method="post" id="account-password" action="index.php?p=account">
        <input type="hidden" name="action" value="password"/>
        <label for="password">Password</label><input type="password" id="password" name="password" disabled required<?php  ?>/><br>
        <label for="password2">Confirm</label><input type="password" id="password2" name="password2" disabled required<?php  ?>/>
    </form>
</div>
    <?php
}
?>