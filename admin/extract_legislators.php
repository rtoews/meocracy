<?php
$connect_ca = mysql_connect('localhost', 'meo', 'Badlsd22');

$connect_meo = mysql_connect('localhost', 'meo', 'Badlsd22');

mysql_select_db('capublic', $connect_ca);
$sql = "SELECT * FROM legislator_tbl
";
$result = mysql_query($sql, $connect_ca);
while ($row = mysql_fetch_object($result)) {
    $meo_sql = "SELECT ca_bill_id FROM ca_bills WHERE bill_id='".$bill_id."'";
    mysql_select_db('meocracy', $connect_meo);
    $meo_result = mysql_query($meo_sql, $connect_meo);
    if (1||mysql_num_rows($meo_result) == 0) {

        $meo_sql = "INSERT INTO legislator
                    (district, state, name_last, name_first, session_year, house_type, legislator_name, author_name, name_title, party, active_flag)
                    VALUES ('$row->district', 'CA', '$row->last_name', '$row->first_name', '$row->session_year', '$row->house_type', '$row->legislator_name', '$row->author_name', '$row->name_title', '$row->party', '$row->active_flag')
                    ";
        print $meo_sql . "\n";
        mysql_query($meo_sql, $connect_meo);
        $inserts++;

    }
    mysql_select_db('capublic', $connect_ca);
}
?>
