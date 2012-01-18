<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$new_user = new User();

$select_city_page = '/settings.php';

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
            redirect($select_city_page);
        }
    }
}

$html = new HTML();

$html->set_title('Sign Up');
$html->generate_header_mobile();
?>
<form method="post">
    <label for="mobile_phone"><?php echo $UIText['signup']['mobile_phone']; ?></label><input type="text" id="mobile_phone" name="mobile_phone" value="<?php echo isset($mobile_phone) ? $mobile_phone : ''; ?>"/><br/>
<?php if (isset($error['phone_empty'])) { ?>
    <p class="error">Missing mobile phone number</p>
<?php } elseif (isset($error['phone_invalid'])) { ?>
    <p class="error">Invalid phone number</p>
<?php } elseif (isset($error['phone_exists'])) { ?>
    <p class="error">Phone number already in use</p>
<?php } ?>
    <label for="password"><?php echo $UIText['signup']['password']; ?></label><input type="password" id="password" name="password"/><br/>
    <label for="conf_password"><?php echo $UIText['signup']['conf_password']; ?></label><input type="password" id="conf_password" name="conf_password"/><br/>
<?php if (isset($error['password_empty'])) { ?>
    <p class="error">Missing password</p>
<?php } elseif (isset($error['password_unmatched'])) { ?>
    <p class="error">Passwords do not match</p>
<?php } elseif (isset($error['password_length'])) { ?>
    <p class="error">Password too short; must be at least six characters.</p>
<?php } ?>
    <label for="firstname"><?php echo $UIText['signup']['firstname']; ?></label><input type="text" id="firstname" name="firstname" value="<?php echo isset($firstname) ? $firstname : ''; ?>"/><br/>
<?php if (isset($error['firstname'])) { ?>
    <p class="error">Please supply your first name</p>
<?php } ?>
    <label for="lastname"><?php echo $UIText['signup']['lastname']; ?></label><input type="text" id="lastname" name="lastname" value="<?php echo isset($lastname) ? $lastname : ''; ?>"/><br/>
<?php if (isset($error['lastname'])) { ?>
    <p class="error">Please supply your last name</p>
<?php } ?>
    <p id="submit-block">
        <input type="submit" value="<?php echo $UIText['signup']['submit']; ?>"/>
    </p>
</form>
<script type="text/javascript">
$(document).ready(function() {
    $('#mobile_phone').focus();
});
</script>
<?php
$html->generate_mobile_footer();
?>
