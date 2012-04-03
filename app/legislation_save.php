<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation_feedback.php');

$user = new User($user_id);
$id = get_param('id');
$feedback_id = get_param('fid');

$legislation = new Legislation($id);

$data = array(
    'status' => '-1'
);
if (!empty($legislation)) {
    $feedback = new Legislation_Feedback($feedback_id);
    $feedback->user_id($user_id);
    $response = get_param('response');
    if (abs(1*$response) == 1) {
        $feedback->response($response);
        $feedback_id = $feedback->record_response($id);
        $data = array(
            'legislation_id' => $id,
            'response_type' => "opinion: $response",
            'feedback_id' => $feedback_id,
            'comment_data' => $legislation->comment_data,
        );
    }
    else {
        $feedback->comments(get_param('comments'));
        $feedback->update();
        $data = array(
            'legislation_id' => $id,
            'response_type' => 'comments',
            'comment_data' => $legislation->get_comment_data(),
        );
    }
}

return_jsonp_data($data);
?>
