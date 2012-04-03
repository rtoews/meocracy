<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$phone = get_param('phone');
$issue_type = get_param('t');
$issue_id = get_param('id');

if ($issue_type == ANNOUNCEMENT_TYPE) {
    $issue = new Announcement($issue_id);
}
elseif ($issue_type == LEGISLATION_TYPE) {
    $issue = new Legislation($issue_id);
}

$user = new User($user_id);

if (!empty($_GET)) {
    $data = array(
        'user_id' => $user_id,
        'name' => $user->firstname() . ' ' . $user->lastname(),
        'issue_type' => $issue_type,
        'issue_id' => $issue_id,
        'city_id' => get_param('city_id'),
        'title' => $issue->title(),
        'message' => get_param('message'),
    );
    $user->tell_friend($phone, $data);
}

$data = array(
    'type' => $issue_type,
    'success' => 1,
);
/*
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
*/

return_jsonp_data($data);
?>
