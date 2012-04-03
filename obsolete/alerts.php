<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$type = get_param('type');

$id = get_param('id');
if (!$id || !$type) {
    redirect('/index.php');
}
if ($type == 'a') {
    $issue = new Announcement($id);
}
elseif ($type == 'l') {
    $issue = new Legislation($id);
}
$user = new User($user_id);

$tags = $issue->tags();

if (!empty($_POST)) {
    $alerts = get_param('alerts');
    $new_alerts = get_param('new_alerts');
    if (!empty($new_alerts)) {
        foreach ($new_alerts as $alert_tag) {
            $alert = new User_Alert();
            $alert->user_id($user_id);
            $alert->tag_id($alert_tag);
            $alert->add();
        }
    }
    redirect('/alert_manager.php?type=' . $type);
}

$html = new HTML();
$html->set_title('Related Issues');
$html->generate_header_mobile();
?>
<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li>Set A New Alert</li>
    <li>Stay on top of<br/><br/>
        <form method="post">
        <input type="hidden" name="type" value="<?php echo $type; ?>"/>
<?php
if (!empty($tags)) {
    $alert = new User_Alert();
    foreach ($tags as $tag_id => $tag) {
        if ($user_alert_id = User_Alert::is_set($user_id, $tag)) {
?>
            <input type="checkbox" id="alerts" name="alerts[]" value="<?php echo $user_alert_id; ?>" checked/><?php echo $tag; ?><br/>
<?php
        }
        else {
?>
            <input type="checkbox" id="new_alerts" name="new_alerts[]" value="<?php echo $tag_id; ?>"/><?php echo $tag; ?><br/>
<?php
        }
    }
}
?>
        <input type="submit" value="Set alerts"/>
        </form>
    </li>
</ul>
<?php
$html->generate_footer_mobile();
?>
