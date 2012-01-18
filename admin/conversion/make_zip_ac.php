<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');

$query = "SELECT * FROM ref_zip";
$data = db()->Get_Table($query);
foreach ($data as $row) {
    $zip_id = $row['zip_id'];
    $zip = $row['zip'];
    $sql = "SELECT city_id FROM ref_city WHERE zip='$zip'";
    $zipdata = db()->Get_Table($sql);
    foreach ($zipdata as $ziprow) {
        $city_id = $ziprow['city_id'];
        $sql2 = "INSERT INTO ref_city_zip (zip_id, city_id) VALUES($zip_id, $city_id)";
        db()->query($sql2);
    }
}

$query = "SELECT * FROM ref_area_code";
$data = db()->Get_Table($query);
foreach ($data as $row) {
    $area_code_id = $row['area_code_id'];
    $area_code = $row['area_code'];
    $sql = "SELECT city_id FROM ref_city WHERE area_code='$area_code'";
    $acdata = db()->Get_Table($sql);
    foreach ($acdata as $acrow) {
        $city_id = $acrow['city_id'];
        $sql2 = "INSERT INTO ref_city_area_code (area_code_id, city_id) VALUES($area_code_id, $city_id)";
        db()->query($sql2);
    }
}

?>
