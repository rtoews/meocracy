<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$region_type = get_param('type');

$user_region = new UserRegion();
$city = $user_region->get_city($user_id);

$data = array(
    'masthead_logo' => $city->masthead_logo[$region_type],
    'masthead' => $city->masthead[$region_type],
);

return_jsonp_data($data);
?>
