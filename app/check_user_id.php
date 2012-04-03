<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$check_id = User::ensure_user_id($user_id);
$user = new User($check_id);
$user_region = new UserRegion();
$city = $user_region->get_region_for_user($user_id);

$data = array(
    'user_id' => $check_id,
    'issue' => array(
        'id' => '',
        'type' => '',
        'category_id' => '',
        'category_name' => '',
        'feedback_id' => '', 
        'filter_type' => '',
        'title' => '',
        'sponsor' => '',
    ),
    'region' => $city->region_data(),
);

return_jsonp_data($data);
?>
