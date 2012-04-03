<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$new_user = new User();

$new_signup = '/signup_pending.php';

if (!empty($_POST)) {
    $error = array();

    $mobile_phone = get_param('mobile_phone');
    $password = get_param('password');
    $conf_password = get_param('conf_password');
    $firstname = get_param('firstname');
    $lastname = get_param('lastname');
    $signup_data = array(
        'firstname' => $firstname,
        'lastname' => $lastname
    );

    if (empty($mobile_phone)) {
        $error['phone_empty'] = true;
    }
    elseif (false == User::validate_phone($mobile_phone)) {
        $error['phone_invalid'] = true;
    }
    elseif (User::checkUserExists($mobile_phone)) {
        $error['phone_exists'] = true;
    }
    if (empty($password)) {
        $error['password_empty'] = true;
    }
    elseif ($password != $conf_password) {
        $error['password_unmatched'] = true;
    }
    elseif (strlen($password) < 6 || strlen($password) > 15) {
        $error['password_length'] = true;
    }
    if (empty($firstname)) {
        $error['firstname'] = true;
    }
    if (empty($lastname)) {
        $error['lastname'] = true;
    }

    if (empty($error)) {
        if ($new_user->signup($mobile_phone, $password, $signup_data)) {
            redirect($new_signup);
        }
    }
    else {
    }
}

$html = new HTML();

$html->set_title('Sign Up');
$html->generate_header_mobile();
?>
<div id="signup" data-role="page" data-title='Meocracy' data-theme='a'>
<!--
    <div data-role='header' data-theme='a'>
        <div data-role='navbar'>

            <ul>
            <li><a href='index.php' data-icon='home' data-theme='a'>Home</a></li>
            <li><a href='search.php' data-icon='search' data-theme='a'>Search</a></li>

            <li><a href='alert_manager.php' data-icon='alert' data-theme='a'>Alerts</a></li>
            <li><a href='settings.php' data-icon='gear' data-theme='a'>Settings</a></li>
            </ul>

        </div>
    </div>
-->



    <div id="content" data-role='content'>
        <form method="post">
            <div data-role="fieldcontain">
            <label for="mobile_phone">Mobile phone</label><input type="tel" id="mobile_phone" name="mobile_phone" value="<?php echo isset($mobile_phone) ? $mobile_phone : ''; ?>"/><br/>
<?php if (isset($error['phone_empty'])) { ?>
            <p class="error">Missing mobile phone number</p>
<?php } elseif (isset($error['phone_invalid'])) { ?>
            <p class="error">Invalid phone number</p>
<?php } elseif (isset($error['phone_exists'])) { ?>
            <p class="error">Phone number already in use</p>
<?php } ?>
            <label for="password">Password</label><input type="password" id="password" name="password"/><br/>
            <label for="conf_password">Confirm password</label><input type="password" id="conf_password" name="conf_password"/><br/>
<?php if (isset($error['password_empty'])) { ?>
            <p class="error">Missing password</p>
<?php } elseif (isset($error['password_unmatched'])) { ?>
            <p class="error">Passwords do not match</p>
<?php } elseif (isset($error['password_length'])) { ?>
            <p class="error">Password too short; must be at least six characters.</p>
<?php } ?>
            <label for="firstname">First name</label><input type="text" id="firstname" name="firstname" value="<?php echo isset($firstname) ? $firstname : ''; ?>"/><br/>
<?php if (isset($error['firstname'])) { ?>
            <p class="error">Please supply your first name</p>
<?php } ?>
            <label for="lastname">Last name</label><input type="text" id="lastname" name="lastname" value="<?php echo isset($lastname) ? $lastname : ''; ?>"/><br/>
<?php if (isset($error['lastname'])) { ?>
            <p class="error">Please supply your last name</p>
<?php } ?>
            </div>
            <p id="submit-block">
                <input type="submit" value="Sign Up"/>
            </p>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#mobile_phone').focus();
});
</script>
<?php
$html->generate_footer_mobile();
?>
