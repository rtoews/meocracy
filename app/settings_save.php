<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$p = get_param('p');
if ($p == 1) {
    $user = new User($user_id);
    $user->firstname(get_param('first'));
    $user->lastname(get_param('last'));
    $user->mobile_phone(get_param('phone'));
    $user->update();
    $data = array(
        'name' => format_name($user->firstname(), $user->lastname()),
        'phone' => $user->mobile_phone(),
        'password' => $user->password(),
        'carrier' => SMS_Gateway::get_carrier($user->sms_id()),
        'success' => 1
    );
}
elseif ($p == 2) {
    $user_region = new UserRegion();
    $user_region->add_city($user_id, get_param('city_id'));
    $data = array(
        'success' => 2
    );
}

return_jsonp_data($data);
?>
