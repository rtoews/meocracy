<?php

$page_title='Send and manage messages with the public';
$category='message';
$table_entity="info";

include('includes/config.php');
include('includes/functions.php');
include('includes/header.php');
include('includes/nav.php');


$result = mysql_query("select * from `office` where `entity_id`='$entity_id' order by `office_name`") or die(mysql_error());
	while($row = mysql_fetch_array($result)) {

	$id=$row['id'];
	$office_name = $row['office_name'];
	$count=$row['count'];

	echo "<div class='row_clickable' onClick=\"document.location.href='message_result?id=".$id."'\">
		".$office_name."
		<div class='ui-li-count'>".$count."</div>
	</div>";
	}


include('includes/footer.php');

?>

