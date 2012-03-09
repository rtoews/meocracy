<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement_feedback.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$type = get_param('t');
$id = get_param('id');

if ($type == ANNOUNCEMENT_TYPE) {
    $issue = new Announcement($id);
}
elseif ($type == LEGISLATION_TYPE) {
    $issue = new Legislation($id);
}

$data = array(
    'title' => $issue->title(),
    'summary' => $issue->summary(),
    'question' => $issue->question(),
    'current_chamber' => $issue->current_chamber(),
    'location' => $issue->location(),
    'description' => $issue->description(),
    'image' => $issue->get_image_src(),
    'status' => $issue->status(),
    'calendared' => $issue->calendared(),
    'vote' => $issue->vote(),
    'days_remaining' => $issue->days_remaining(),
);

return_jsonp_data($data);
?>
