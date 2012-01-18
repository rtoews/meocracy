<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.admin.php');

$admin = new Admin();
if (!empty($_POST)) {
    $email = get_param('email');
    $password = get_param('password');

    if ($admin->login($email, $password)) {
        redirect('/admin/index.php');
    }
    else {
        $error['login'] = true;
    }
}

$html = new HTML('admin');
$html->set_title('Meocracy Admin Login');
?>
<form method="post">
    <label for="email">Admin Email</label><input type="text" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>"/><br/>
    <label for="password">Password</label><input type="password" id="password" name="password"/><br/>
    <p id="submit-block">
        <input type="submit" value="Login"/><br/>
    </p>
</form>
<script type="text/javascript">
$(document).ready(function() {
    $('#email').focus();
});
</script>
<?php
?>
