<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
$m = get_param('mobile_phone');
$p = get_param('password');

$user = new User();
$user_id = $user->login($m, $p);

if (!$user_id) {
    $next = 'login.html?invalid';
}
else {
    $user_region = new UserRegion();

    $city = $user_region->get_region_for_user($user_id);
    if (!$city) {
        $next = 'settings.html';
    }
    else {
        setcookie('user_id', $user_id, time()+3600);
        $next = 'index.html';
    }
}
redirect($next);
?>
