<?php
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['HOME'] . '/public_html/meocracy';
require_once($DOC_ROOT . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');

User_Alert::send_push_notifications();
?>
