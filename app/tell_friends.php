<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$type = get_param('type');
$id = get_param('id');
$screen = get_param('screen');

if ($type == 'a') {
    $issue = new Announcement($id);
}
elseif ($type == 'l') {
    $issue = new Legislation($id);
}
$user = new User($user_id);
$friends = $user->get_friend_list();

$select_friends = true;
if (!empty($_GET)) {
    $friend_ndx_list = get_param('friend_ndx_list');
    $message = get_param('message');
    $user->tell_friends($friend_ndx_list, $message);
}

$row = array();
if (!empty($friends)) {
    foreach ($friends as $key => $friend) {
        $tmp = array($friend['last'], $friend['first']);
        $name = implode(', ', $tmp);
        $row[] = array('key' => $key, 'value' => $name, 'last' => $friend['last'], 'first' => $friend['first'], 'name' => $name, 'phone' => $friend['phone'], 'email' => $friend['email']);
    }
}

$data = array(
    'friends' => $row
);

return_jsonp_data($data);
?>
