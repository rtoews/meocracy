<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.category.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$category_id = get_param('cat');
$location_code = get_param('loc');
$filter = $location_code ? $location_code : $category_id;

$category = new Category($category_id);
$category_name = $category->lc_category();
$data = array();

$search = preg_replace('/[^a-z0-9]/i', '', get_param('search'));

$city = UserRegion::get_city($user_id);

if ($search) {
    $issues = $city->get_issues_search($search);
}
else {
    $issues = $city->get_issues($filter);
}
$issue_list = array();
if (!empty($issues)) {
    foreach ($issues as $row) {
        $issue = $row['issue'];
        if ($issue->feedback_submitted($user_id)) {
            $issue_list[] = array('type' => $row['type'], 'is_checked' => 1, 'key' => $issue->id(), 'image' => $issue->get_image_src(), 'value' => $issue->title());
        }
        else {
            $issue_list[] = array('type' => $row['type'], 'is_checked' => 0, 'key' => $issue->id(), 'image' => $issue->get_image_src(), 'value' => $issue->title());
        }
    }
}

$data = array(
    'filter' => $filter,
    'category_id' => $category_id,
    'category' => $category_name,
    'issues' => $issue_list,
);

return_jsonp_data($data);
?>
