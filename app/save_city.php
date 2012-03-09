<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$user_id = User::get_user_id();
$city_id = get_param('city_id');

$user_region = new UserRegion();
$result = $user_region->add_city($user_id, $city_id);
$data = array(
    'user_id' => $user_id,
    'city_id' => $city_id,
    'result' => $result
);
return_jsonp_data($data);
?>
