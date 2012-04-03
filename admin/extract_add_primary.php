<?php
$connect_ca = mysql_connect('localhost', 'meo', 'Badlsd22');

$connect_meo = mysql_connect('localhost', 'meo', 'Badlsd22');

function fix_sponsor($legislation_id, $sponsor_id, $sponsor_name, $primary_sponsor) {
    global $connect_meo;
    echo "Legislation ID: $legislation_id; Sponsor: $sponsor_name, $sponsor_id\n";
    if ($sponsor_id > 0) {
        $sql = "SELECT legislation_sponsor_id FROM legislation_sponsor WHERE sponsor_id='$sponsor_id' AND legislation_id='$legislation_id'";
        $result = mysql_query($sql, $connect_meo);
        if (mysql_num_rows($result) == 0) {
            $sql = "INSERT INTO legislation_sponsor (sponsor_id, legislation_id, sponsor_name, primary_sponsor) VALUES ('$sponsor_id', '$legislation_id', '$sponsor_name', '$primary_sponsor')";
        }
    }
    else {
        $sql = "INSERT INTO legislation_sponsor (sponsor_name, legislation_id) VALUES ('$sponsor_name', '$legislation_id')";
    }
    mysql_query($sql, $connect_meo);
}

mysql_select_db('meocracy', $connect_meo);
$sql = "SELECT l.legislation_id, l.bill_id, 
            (SELECT COUNT(*) FROM legislation_sponsor ls WHERE ls.legislation_id=l.legislation_id) AS c 
        FROM legislation l 
        HAVING c>1";

$result = mysql_query($sql, $connect_meo);
while ($row = mysql_fetch_object($result)) {

    mysql_select_db('capublic', $connect_ca);
    $ca_sql = "
        SELECT bva.name, bva.primary_author_flg 
        FROM bill_tbl b
        JOIN bill_version_authors_tbl bva ON b.latest_bill_version_id=bva.bill_version_id
        WHERE b.bill_id='$row->bill_id'
          AND bva.primary_author_flg='Y'";
    $ca_result = mysql_query($ca_sql, $connect_ca);
    $ca_row = mysql_fetch_object($ca_result);

    $sponsor_id = 0;
    $primary_sponsor = $ca_row->primary_author_flg;
    $sponsor_name = $ca_row->name;
    $legislation_id = $row->legislation_id;
    mysql_select_db('meocracy', $connect_meo);
    $ls_sql = "UPDATE legislation_sponsor SET primary_sponsor='Y' WHERE legislation_id='$legislation_id' AND sponsor_name='$sponsor_name'";
    mysql_query($ls_sql, $connect_meo);
    print "$ls_sql<br/>";

}
?>
