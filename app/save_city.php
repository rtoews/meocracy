<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$user_id = User::get_user_id();
$city_id = get_param('city_id');
$user_region = new UserRegion();
$city = $user_region->add_city($user_id, $city_id);
$data = array(
    'user_id' => $user_id,
    'issue' => array(
        'id' => '',
        'type' => '',
        'category' => array(
            'id' => '',
            'name' => '',
        ),
        'feedback_id' => '', 
        'filter_type' => '',
        'title' => '',
        'sponsor' => '',
    ),
    'region' => $city->region_data(),
);

return_jsonp_data($data);
?>
