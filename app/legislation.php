<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation_feedback.php');

$id = get_param('id');

$legislation = new Legislation($id);

$legislators = array();
if (!empty($legislation->legislator)) {
    foreach ($legislation->legislator as $legislator) {
        $legislators[] = array('id' => $legislator->id(), 'img_name' => $legislator->img_name, 'name' => $legislator->full_name, 'office' => $legislator->name_title(), 'party' => $legislator->party);
    }
}

$data = array(
    'bill_id' => $legislation->bill_id(),
    'bill_latest_id' => $legislation->bill_latest_id(),
    'title' => $legislation->title(),
    'current_location' => $legislation->current_location(),
    'location_description' => $legislation->location_description,
    'current_chamber' => $legislation->current_chamber(),
    'raw_category' => $legislation->raw_category(),
    'status' => $legislation->status(),
    'sponsors' => $legislators,
    'summary' => iconv('ISO-8859-1', 'UTF8//TRANSLIT', $legislation->summary()),
);
/*
$summary = utf8_encode(substr($legislation->summary(), 450, 50));
print '<pre>';
print 'Plain: ';
print_r($summary);
print '</pre>';
print '<pre>';
print 'JSON-encoded: ';
print_r(json_encode($summary));
print '</pre>';
print '<pre>';
print 'Whole data: ';
print_r(json_encode($data));
print '</pre>';
*/
return_jsonp_data($data);
?>
