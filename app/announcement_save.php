<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement_feedback.php');

$id = get_param('id');
$feedback_id = get_param('fid');

$announcement = new Announcement($id);

$data = array(
    'status' => '-1'
);
if (!empty($announcement)) {
    $feedback = new Announcement_Feedback($feedback_id);
    $feedback->user_id($user_id);
    $response = get_param('response');
    if (abs(1*$response) == 1) {
        $feedback->response($response);
        $feedback_id = $feedback->record_response($id);
        $data = array(
            'announcement_id' => $id,
            'response_type' => "opinion: $response",
            'feedback_id' => $feedback_id,
            'comment_data' => $announcement->comment_data,
        );
    }
    else {
        $feedback->comments(get_param('comments'));
        $feedback->update();
        $data = array(
            'announcement_id' => $id,
            'response_type' => 'comments',
            'comment_data' => $announcement->get_comment_data(),
        );
    }
}

return_jsonp_data($data);
?>
