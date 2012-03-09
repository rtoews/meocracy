<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation_category.php');

$region = get_param('reg');

$city = UserRegion::get_city($user_id);
$lc = $city->get_legislation_categories($region);
$categories = array();
foreach ($lc as $category) {
    $categories[] = array(
        'key' => $category->id(),
        'value' => $category->lc_category(),
        'count' => $category->cat_count(),
    );
}
$data = $categories;

return_jsonp_data($data);
?>
