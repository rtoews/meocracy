<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement_feedback.php');

$type = get_param('type');
$id = get_param('id');

$announcement = new Announcement($id);

if (!empty($_GET)) {
    $feedback = new Announcement_Feedback();
    $feedback->response(get_param('response'));
    $feedback->comments(get_param('response_comments'));
    $feedback->user_id($user_id);
    $feedback->announcement_id($id);
    $feedback->record_response();
}

$data = array(
    'type' => $type,
    'id' => $id
);

return_jsonp_data($data);
?>
