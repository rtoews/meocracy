<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$user = new User($user_id);
$user->firstname(get_param('first'));
$user->lastname(get_param('last'));
$user->mobile_phone(get_param('phone'));
$user->update();
$data = array(
    'first' => $user->firstname(),
    'last' => $user->lastname(),
    'name' => format_name($user->firstname(), $user->lastname()),
    'phone' => $user->mobile_phone(),
    'password' => $user->password(),
    'success' => 1
);

return_jsonp_data($data);
?>
