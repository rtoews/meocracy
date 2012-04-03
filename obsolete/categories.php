<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');
$sql = "SELECT raw_category, COUNT(*) AS c FROM legislation GROUP BY raw_category";
$data = db()->Get_Table($sql);
print '<table>';
foreach ($data as $row) {
    print "<tr><td>{$row['raw_category']}</td><td>{$row['c']}</td></tr>";
}
print '</table>';
?>
