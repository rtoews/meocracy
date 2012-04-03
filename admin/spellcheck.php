<?php
require('spellutils.php');
require('class.spellcheck.php');

$words = get_param('words');
$sp = new SpellCheck();
$recognized = $sp->check($words);

$data = array(
    'recognized' => $recognized,
);

return_jsonp_data($data);
?>
