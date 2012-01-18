<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/class.html.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/classes/class.user.php');

$user = new User();

if (!empty($_POST)) {
    $email_reminder = get_param('email_reminder');
    $mobile_phone = get_param('mobile_phone');
    $password = get_param('password');

    if ($email_reminder) {
        $error['reminder_sent'] = true;
    }
    elseif ($user->login($mobile_phone, $password)) {
        redirect('/index.php');
    }
    else {
        $error['login'] = true;
    }
}

$html = new HTML();
$html->set_title('Meocracy Login');
$html->generate_header_mobile();
?>
<form method="post">
<?php if (isset($error['reminder_sent'])) { ?>
    <p class="error">Reminder has been sent.  Check your email.</p>
<?php } ?>
<?php if (isset($error['login'])) { ?>
    <p class="error">
        Unrecognized login.  <a href="/signup.php">Need an account</a>?
    </p>
<?php } ?>
    <label for="mobile_phone"><?php echo $UIText['login']['mobile_phone']; ?></label><input type="text" id="mobile_phone" name="mobile_phone" value="<?php echo isset($mobile_phone) ? $mobile_phone : ''; ?>"/><br/>
<?php if (isset($error['login'])) { ?>
    <input type="hidden" id="email_reminder" name="email_reminder" value="1"/>
    <p class="error">Forgot password?  <span id="remind">Send reminder to above address.</span></p>
<?php } ?>
<?php if (isset($error['phone_empty'])) { ?>
    <span class="error">Missing mobile phone number</span>
<?php } ?>
    <label for="password"><?php echo $UIText['login']['password']; ?></label><input type="password" id="password" name="password"/><br/>
    <p id="submit-block">
        <input type="submit" value="<?php echo $UIText['login']['submit']; ?>"/><br/>
    </p>
    <p id="signup-link">
        <a href="/signup.php">New user</a>?
    </p>
</form>
<script type="text/javascript">
$(document).ready(function() {
    $('#mobile_phone').focus();
    $('#remind').click(function() {
        $('form').submit();
    });
});
</script>
<?php
$html->generate_footer_mobile();

/*
$page = ob_get_clean();
$css = 'login.css';
$replace = array(
    'from' => array('%CSS%', '%TITLE%', '%H1%', '%CONTENT%'),
    'to' => array($css, $UIText['login']['page_title'], $UIText['login']['h1'], $page)
);

$template = file_get_contents('includes/mobile_template.php');
$output = str_replace($replace['from'], $replace['to'], $template);
print $output;
*/
?>
