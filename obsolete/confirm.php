<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.sms_gateway.php');

$id = get_param('id');
$code = get_param('code');

$user = new User($id);
if ($code == '11235') {
    $user->status(1);
    $result = $user->update();
    echo 'Updated.';
    echo 'Result:<pre>';
    print_r($result);
    echo '</pre>';
}
?>
