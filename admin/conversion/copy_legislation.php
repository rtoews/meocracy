<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');

$query = "SELECT * FROM legislation";
$data = db()->Get_Table($query);
foreach ($data as $row) {
    $legislation_id = $row['legislation_id'];
    $feedback_support = $row['feedback_support'];
    $feedback_oppose = $row['feedback_oppose'];
//    $legislation_id = db()->_meta->_last_insert_id;
    for ($i = 0; $i < $feedback_support; $i++) {
        $user_id = 999999 - $i;
        $feedback_query = sprintf("INSERT INTO legislation_feedback (user_id, legislation_id, response) VALUES (%d, %d, 1)", $user_id, $legislation_id);
        db()->query($feedback_query);
    }
    for ($i = 0; $i < $feedback_oppose; $i++) {
        $user_id = 499999 - $i;
        $feedback_query = sprintf("INSERT INTO legislation_feedback (user_id, legislation_id, response) VALUES (%d, %d, -1)", $user_id, $legislation_id);
        db()->query($feedback_query);
    }
}
?>
