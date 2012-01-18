<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$user = new User($user_id);

if (!empty($_POST)) {
    $alerts = get_param('alerts');
    User_Alert::keep_selected_alerts($user_id, $alerts);
    redirect('/alert_manager.php');
}

$alerts = User_Alert::get_all($user_id);

$html = new HTML();
$html->set_title('Alert Manager');
$html->generate_header_mobile();
?>
<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li>Current Alerts</li>
    <li>
        <form method="post">
<?php
if (!empty($alerts)) {
    foreach ($alerts as $alert) {
?>
        <input type="checkbox" id="alerts" name="alerts[]" value="<?php echo $alert->tag_id(); ?>" checked/><?php echo $alert->tag->tag(); ?><br/>
<?php
    }
}
?>
        <p>Uncheck an alert to remove it.</p>
        <input type="submit" value="Submit"/>
        </form>
    </li>
</ul>
<?php
$html->generate_footer_mobile();
?>
