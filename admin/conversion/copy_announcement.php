<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');

$query = "SELECT * FROM old_announcement";
$data = db()->Get_Table($query);
foreach ($data as $row) {
    $id = $row['id'];
    $title = addslashes($row['title']);
    $description = addslashes($row['description']);
    $text = addslashes($row['text']);
    $question = addslashes($row['question']);
    $fiscal_impact = $row['fiscal_impact'];
    $staffing_impact = $row['staffing_impact'];
    $sponsor = $row['sponsoring_dept_1'];
    $views = $row['views'];
    $feedback_support = $row['feedback_support'];
    $feedback_oppose = $row['feedback_oppose'];
    $announce_query = sprintf("INSERT INTO announcement (heading, description, text, question, fiscal_impact, staffing_impact)
                                                 VALUES ('%s',    '%s',        '%s', '%s',     %0.2d,         %0.2d)",
                               $title, $description, $text, $question, $fiscal_impact, $staffing_impact);
print "$announce_query<br/>";
    db()->query($announce_query);
    $announcement_id = db()->_meta->_last_insert_id;
    for ($i = 0; $i < $feedback_support; $i++) {
        $user_id = 999999 - $i;
        $feedback_query = sprintf("INSERT INTO feedback (user_id, announcement_id, response) VALUES (%d, %d, 1)", $user_id, $announcement_id);
        db()->query($feedback_query);
    }
    for ($i = 0; $i < $feedback_oppose; $i++) {
        $user_id = 499999 - $i;
        $feedback_query = sprintf("INSERT INTO feedback (user_id, announcement_id, response) VALUES (%d, %d, -1)", $user_id, $announcement_id);
        db()->query($feedback_query);
    }
}
?>
