<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');

$search = get_param('search');

$data = Tag::filter_tags($search);

return_jsonp_data($data);
?>
