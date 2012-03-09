<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$type = get_param('t');
$id = get_param('id');

$legislation = new Legislation($id);

$data = array(
    'title' => $legislation->title(),
    'question' => $legislation->question(),
    'support' => $legislation->get_support(),
    'oppose' => $legislation->get_oppose(),
    'sponsors' => $legislation->legislator,
);

return_jsonp_data($data);
?>
