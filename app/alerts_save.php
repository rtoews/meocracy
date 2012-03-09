<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$user_id = User::get_user_id();
$type = get_param('type');

$id = get_param('id');
if (!$id || !$type) {
    redirect('/index.html');
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
    redirect('/alert_manager.html?type=' . $type);
}
?>
