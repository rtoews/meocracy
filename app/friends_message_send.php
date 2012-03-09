<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$user_id = User::get_user_id();
$type = get_param('type');
$id = get_param('id');

if ($type == 'a') {
    $issue = new Announcement($id);
}
elseif ($type == 'l') {
    $issue = new Legislation($id);
}
$user = new User($user_id);
$friends = $user->get_friend_list();

if (!empty($_GET)) {
    $contact_list = get_param('contact_list');
    $message = get_param('message');
    $user->tell_friends($contact_list, $message);
}

$data = array(
    'sent' => 1
);

return_jsonp_data($data);
?>
