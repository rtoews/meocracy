<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$city_id = get_param('city_id');
$user_id = get_param('user_id');

$user_region = new UserRegion();
$result = $user_region->add_city($user_id, $city_id);
$_SESSION['city_id'] = $city_id;

redirect('/index.php');
?>
