<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$zip = get_param('zip');

$data = City::get_city_list_by_zip($zip);

return_jsonp_data($data);
?>
