<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$type = get_param('type');
$id = get_param('id');

$announcement = new Announcement($id);

$data = array(
    'title' => $announcement->title(),
    'calendared' => $announcement->calendared(),
    'vote' => $announcement->vote(),
    'image' => $announcement->get_image_src(),
    'question' => $announcement->question(),
    'support' => $announcement->get_support(),
    'oppose' => $announcement->get_oppose(),
    'sponsors' => $announcement->sponsor,
);

return_jsonp_data($data);
?>
