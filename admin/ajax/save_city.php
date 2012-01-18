<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');

$city_id = get_param('city_id');
$county_id = get_param('county_id');
$state_id = get_param('state_id');
$_SESSION['admin_city_id'] = $city_id;
$_SESSION['admin_county_id'] = $county_id;
$_SESSION['admin_state_id'] = $state_id;

redirect('/admin/city_home.php');
?>
