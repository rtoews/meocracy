<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.sms_gateway.php');

$user = new User($user_id);
$sms = new SMS_Gateway($user->sms_id());

$data = array(
    'name' => format_name($user->firstname(), $user->lastname()),
    'first' => $user->firstname(),
    'last' => $user->lastname(),
    'phone' => $user->mobile_phone(),
    'sms_id' => $user->sms_id(),
    'carrier' => $sms->carrier()
);

$city = UserRegion::get_city($user_id);
if ($city) {
    $data['city_name'] = $city->city_name();
    $data['county_name'] = $city->county->county_name();
    $data['state_name'] = $city->state->state_name();
    $data['zip'] = $city->zip();
}

$mode = get_param('mode');
if ($mode == 1) {
    $carriers = SMS_Gateway::get_all();
    foreach ($carriers as $c) {
        $data['carriers'][] = array($c->id() => $c->carrier());
    }
}

return_jsonp_data($data);
?>
