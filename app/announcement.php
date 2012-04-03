<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement_feedback.php');

$id = get_param('id');

$announcement = new Announcement($id);

$data = array(
    'id' => $id,
    'type' => ANNOUNCEMENT_TYPE,
    'title' => $announcement->title(),
    'description' => $announcement->text(),
    'text' => $announcement->text(),
    'category' => array(
        'id' => $announcement->category['id'],
        'name' => $announcement->category['name'],
    ),
    'question' => $announcement->question(),
    'location' => $announcement->location(),
    'image' => $announcement->get_image_src(),
    'status' => $announcement->status->status(),
    'calendared' => $announcement->calendared(),
    'vote' => $announcement->vote(),
    'support' => $announcement->get_support(),
    'oppose' => $announcement->get_oppose(),
    'days_remaining' => $announcement->days_remaining(),
    'sponsors' => $announcement->sponsors,
    'comment_data' => $announcement->comment_data,
    'user_feedback' => $announcement->feedback_submitted($user_id),
);

return_jsonp_data($data);
?>
