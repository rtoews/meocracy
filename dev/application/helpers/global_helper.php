<?php
function get_param($param)
{
    $value = null;
    if (!empty($_GET[$param])) {
        $value = $_GET[$param];
    }
    elseif (!empty($_POST[$param])) {
        $value = $_POST[$param];
    }
    elseif (!empty($_SESSION[$param])) {
        $value = $_SESSION[$param];
    }
    if (is_string($value)) {
        $value = stripslashes($value);
    }
    return $value;
}

?>
