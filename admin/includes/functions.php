<?php 

$connect = mysql_connect("meocracy.db.3410806.hostedresource.com","meocracy" , "Badlsd22") or die(mysql_error());
mysql_select_db('meocracy', $connect) or die(mysql_error());


// EntityInfo is called in every header 

function EntityInfo($table_entity,$entity_id) {

$result_entity = mysql_query("select * from `$table_entity` where `id`='$id'") or die(mysql_error());
while($row = mysql_fetch_array($result_entity)) {

$entity_county_id=$row['county_id'];
$entity_state_id=$row['state_id'];
$entity_county_id=$row['county_id'];
$entity_country_id=$row['country_id'];
$entity_name=$row['name'];
$entity_about_title=$row['title'];
$entity_about_text=$row['text'];
$entity_image=$row['image'];
$entity_fed_senate_district_1=$row['$fed_senate_district_1'];
$entity_fed_senate_district_2=$row['$fed_senate_district_2'];
$entity_fed_congress_district_1=$row['$fed_congress_district_1'];
$entity_fed_congress_district_2=$row['$fed_congress_district_2'];
$entity_state_senate_district_1=$row['$state_senate_district_1'];
$entity_state_senate_district_2=$row['$state_senate_district_2'];
$entity_state_congress_district_1=$row['$state_congress_district_1'];
$entity_state_congress_district_2=$row['$state_congress_district_2'];
$entity_school_district_1=$row['$school_district_1'];
$entity_school_district_2=$row['$school_district_2'];
$entity_school_district_3=$row['$school_district_3'];
$entity_special_district_1=$row['$special_district_1'];
$entity_special_district_2=$row['$special_district_2'];
$entity_special_district_3=$row['$special_district_3'];
	}
}






/*-------------------------- ResultsAnnouncement loops result rows for Announcement. It calls DrawVotesChart into the loop. -------------------------- */

function ResultsAnnouncement($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id'") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$shorttitle = myTruncate($row['title'], 10, "..." );
$shortdescription = myTruncate($row['description'], 20, "..." );
$chart_question=$row['question'];
$feedback_support=$row['feedback_support'];
$feedback_oppose=$row['feedback_oppose'];
$feedback_total=$row['feedback_total'];
$feedback_average=$row['feedback_average'];

echo "<div class='row_clickable' onClick=\"document.location.href='announcement_edit?id=".$id."'\">
	<div class='row_icon'></div>
	<h3>".$shorttitle."</h3>
	<div class='chart_wrapper'>";
	DrawVotesChart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);
echo "</div></div>";
	}
}




/*-------------------------- ResultsLegislation loops result rows for Legislation. It calls DrawVotesChart into the loop. -------------------------- */

function ResultsLegislation($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id'") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$shorttitle = myTruncate($row['title'], 10, "..." );
$shortdescription = myTruncate($row['background'], 20, "..." );
$chart_question=$row['question'];
$feedback_support=$row['feedback_support'];
$feedback_oppose=$row['feedback_oppose'];
$feedback_total=$row['feedback_total'];
$feedback_average=$row['feedback_average'];

echo "<div class='row_clickable' onClick=\"document.location.href='legislation_edit?id=".$id."'\">
	<div class='row_icon'></div>
	<h3>".$shorttitle."</h3>
	<p>".$shortdescription."</p><div class='chart_wrapper'>";
	DrawVotesChart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);
echo "</div></div>";
	}
}




/*-------------------------- TotalAnnouncement shows a total result for announcement. It calls DrawVotesChart into the loop. -------------------------- */


function TotalAnnouncement($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id' limit 1") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$feedback_support="4271";
$feedback_oppose="3854";
$feedback_total="573";
$feedback_average="687";

$chart_announcement=DrawVotesChart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);

$total_announcement="<div class='row_clickable' onClick=\"document.location.href='support_announcement'\">
	<div class='row_icon'></div>
	<h3>Announcements</h3>
	<p>Description</p><br clear='all'><center>".$chart_announcement."</center></div>";
	}
}





/*-------------------------- TotalLegislation shows a total result for Legislation. It calls DrawVotesChart into the loop. -------------------------- */

function TotalLegislation($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id' limit 1") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$feedback_support="3854";
$feedback_oppose="4271";
$feedback_total="713";
$feedback_average="687";

echo "<div class='row_clickable' onClick=\"document.location.href='support_legislation'\">
	<div class='row_icon'></div>
	<h3>Our Legislation</h3></li><center>";

DrawVotesChart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);
echo "</center></div>";
	}
}






?>
