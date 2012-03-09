<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$m = get_param('m');
$p = get_param('p');

$user = new User();
$success = false;
if ($user_id = $user->login($m, $p)) {
    $success = $user_id;
}

$data = array(
    'user_id' => $success
);

return_jsonp_data($data);
?>
