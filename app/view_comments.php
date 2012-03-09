<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$type = get_param('type');
$id = get_param('id');
if (!$id || !$type) {
    redirect('/index.php');
}
if ($type == 'a') {
    $issue = new Announcement($id);
}
elseif (type == 'l') {
    $issue = new Legislation($id);
}
$feedback_rows = $issue->get_feedback_with_comments($id);
$comments = array();
if (!empty($feedback_rows)) {
    foreach ($feedback_rows as $feedback) {
        $comments[] = array('comment' => $feedback->comments());
    }
}

$data = array(
    'title' => $issue->title(),
    'comments' => $comments
);

return_jsonp_data($data);
?>
