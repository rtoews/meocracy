<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation_feedback.php');

$id = get_param('id');

$legislation = new Legislation($id);

$data = array(
    'id' => $id,
    'type' => LEGISLATION_TYPE,
    'bill_id' => $legislation->bill,
    'bill_latest_id' => $legislation->bill_latest_id(),
    'title' => $legislation->title(),
    'image' => $legislation->image,
    'location' => $legislation->bill_location,
    'category' => array(
        'id' => $legislation->category['id'],
        'name' => $legislation->category['name'],
    ),
    'question' => $legislation->question(),
    'support' => $legislation->get_support(),
    'oppose' => $legislation->get_oppose(),
    'current_location' => $legislation->current_location(),
    'location_description' => $legislation->location_description,
    'current_chamber' => $legislation->current_chamber(),
    'raw_category' => $legislation->raw_category(),
    'status' => $legislation->status(),
    'sponsors' => $legislation->sponsors,
    'summary' => iconv('ISO-8859-1', 'UTF8//TRANSLIT', $legislation->summary()),
    'text' => iconv('ISO-8859-1', 'UTF8//TRANSLIT', $legislation->summary()),
    'comment_data' => $legislation->comment_data,
    'user_feedback' => $legislation->feedback_submitted($user_id),
);
return_jsonp_data($data);
?>
