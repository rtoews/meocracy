<?php

$page_title="Public opinion of my announcements";
$category='opinion';
$table_entity="info";

include('includes/config.php');
include('includes/functions.php');
include('includes/header.php');
include('includes/nav.php');

$table="announcement";
ResultsAnnouncement($table,$entity_id);


include('includes/footer.php');

?>

