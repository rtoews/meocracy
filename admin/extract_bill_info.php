<?php
define('DB_DATABASE', 'capublic');
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');

$sql = "SELECT bill_version_id, bill_xml FROM bill_version_tbl LIMIT 1301, 1350";
$data = db()->Get_Table($sql);
foreach ($data as $row) {
    $id = $row['bill_version_id'];
    $xml = $row['bill_xml'];
    if (preg_match('/<caml:digesttext>(.*)<\/caml:digesttext>/is', $xml, $match)) {
        $digesttext = $match[1];
    }
    if (preg_match('/<caml:bill( .*?)?>(.*)<\/caml:bill>/is', $xml, $match)) {
        $bill = $match[2];
    }
    print "Bill version ID: $id\n<br/>";
    $sql = sprintf("UPDATE bill_version_tbl SET summary='%s' WHERE bill_version_id='%s'", $digesttext, $bill, $id);
    $result = db()->query($sql);
}
?>
