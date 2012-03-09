<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement_feedback.php');

$type = get_param('t');
$id = get_param('id');

$announcement = new Announcement($id);

$data = array(
    'title' => $announcement->title(),
    'question' => $announcement->question(),
    'location' => $announcement->location(),
    'description' => $announcement->description(),
    'image' => $announcement->get_image_src(),
    'status' => $announcement->status->status(),
    'calendared' => $announcement->calendared(),
    'vote' => $announcement->vote(),
    'days_remaining' => $announcement->days_remaining(),
);

return_jsonp_data($data);
?>
