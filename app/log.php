<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.log.php');

$msg = get_param('msg');

$log = new Log();
$log->add($msg);
$data = array(
    'success' => 1
);
return_jsonp_data($data);
?>
