<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.category.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$category_id = get_param('c');
$tag_id = get_param('t');
$location_code = get_param('l');
if ($category_id) {
    $filter = $category_id;
    $filter_type = 'c';
    $category = new Category($category_id);
    $category_name = $category->lc_category();
}
elseif ($tag_id) {
    $filter = $tag_id;
    $filter_type = 't';
}
elseif ($location_code) {
    $filter = $location_code;
    $filter_type = 'l';
}

$data = array();

$search = preg_replace('/[^a-z0-9]/i', '', get_param('search'));

$city = UserRegion::get_city($user_id);

if ($search) {
    $issues = $city->get_issues_search($search);
}
else {
    $issues = $city->get_issues($filter, $filter_type);
}
$issue_list = array();
if (!empty($issues)) {
    foreach ($issues as $row) {
        $issue = $row['issue'];
        $issue_type = $row['type'];
        $checked = $issue->feedback_submitted($user_id) ? 1 : 0;
        if ($issue_type == ANNOUNCEMENT_TYPE) {
            $value = 'Announcement: ' . $issue->title();
        }
        else {
            $value = 'Bill ' . $issue->bill . ': ' . $issue->title();
        }
        $issue_list[] = array('type' => $row['type'], 'is_checked' => $checked, 'key' => $issue->id(), 'image' => $issue->get_image_src(), 'value' => $value);
    }
}

$data = array(
    'filter' => $filter,
    'filter_type' => $filter_type,
    'category' => $category_name,
    'issues' => $issue_list,
);

return_jsonp_data($data);
?>
