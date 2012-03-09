<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$category_id = get_param('cat');
$type = get_param('type');
$issue_type = ANNOUNCEMENT_TYPE | LEGISLATION_TYPE;
if ($type == 'a') { $issue_type &= ANNOUNCEMENT_TYPE; }
if ($type == 'l') { $issue_type &= LEGISLATION_TYPE; }
$data = array();

$region_type = get_param('reg');
$search = preg_replace('/[^a-z0-9]/i', '', get_param('search'));

$city = UserRegion::get_city($user_id);

if ($issue_type & ANNOUNCEMENT_TYPE) {
    $ann_data = array();
    if ($search) {
        $announcements = $city->get_announcements_search($search);
    }
    else {
        $announcements = $city->get_announcements($region_type);
    }
    if (!empty($announcements)) {
        foreach ($announcements as $ann) {
            if ($ann->feedback_submitted($user_id)) {
                $ann_data[] = array('is_checked' => 1, 'key' => $ann->id(), 'image' => $ann->get_image_src(), 'value' => $ann->title());
            }
            else {
                $ann_data[] = array('is_checked' => 0, 'key' => $ann->id(), 'image' => $ann->get_image_src(), 'value' => $ann->title());
            }
        }
    }
    $data['announcements'] = $ann_data;
}
if ($issue_type & LEGISLATION_TYPE) {
    $leg_data = array();
    $legislation = $city->get_legislation($region_type, $category_id);
    if (!empty($legislation)) {
        foreach ($legislation as $leg) {
            if ($leg->feedback_submitted($user_id)) {
                $leg_data[] = array('is_checked' => 1, 'key' => $leg->id(), 'value' => $leg->title());
            }
            else {
                $leg_data[] = array('is_checked' => 0, 'key' => $leg->id(), 'value' => $leg->title());
            }
        }
    }
    $data['legislation'] = $leg_data;
}

return_jsonp_data($data);
?>
