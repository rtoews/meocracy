<?php 

// Connection

$connect = mysql_connect("meocracy2.db.3410806.hostedresource.com","meocracy2" , "Badlsd22") or die(mysql_error());
mysql_select_db('meocracy2', $connect) or die(mysql_error());
mysql_close($connect); 


// CityInfo and OfficeInfo are called in every header 

$CityInfo = mysql_query("select * from `tcuCity` where `CityKey`='$CityKey'") or die(mysql_error());
while($row = mysql_fetch_array($CityInfo)) {
	$CityKey=$row['CityKey'];
	$ZipCode=$row['ZipCode'];
	$CityName=$row['CityName'];
	$CountyName=$row['CountyName'];
	$CountyKey=$row['CountyKey'];
	$StateID=$row['StateID'];
	$Population=$row['Population'];
	$ImageSeal=$row['ImageSeal'];
	}

$OfficeInfo = mysql_query("select * from `tcuOffice` where `OfficeKey`='$OfficeKey'") or die(mysql_error());
while($row = mysql_fetch_array($OfficeInfo)) {
	$OfficeName=$row['OfficeName'];
	$OfficeDescription=$row['OfficeDescription'];
	}


// Grammar Rules

// Define the title
	if ($content == 'home') { $page_title=$name_full." Home Page"; }
	if ($content == 'about') { $page_title="About ".$name_full; }
	if ($content == 'announcement') { $page_title=$name_full." Announcements"; }
	if ($content == 'contact') { $page_title=$name_full." Contact"; }
	if ($content == 'legislation') { $page_title=$name_full." Legislation"; }
	if ($content == 'personnel') { $page_title=$name_full." Personnel"; }
	if ($content == 'opinion') { $page_title="My Opinion of ".$name_full; }
	if ($content == 'taxes') { $page_title="My ".$name_full." Taxes"; }

// Grammar rules
	$name_city="City of ".$CityName; 
	$name_county=$CountyName." County"; 
	$name_office=$OfficeDescription; 
	$name_state="State of ".$StateName; 



















/*-------------------------- ResultsAnnouncement loops result rows for Announcement. It calls DrawVotesChart into the loop. -------------------------- */

function ResultsAnnouncement($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id'") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$photo=$row['image'];
$shorttitle = myTruncate($row['title'], 200);
$shortdescription = myTruncate($row['description'], 200);
$chart_question=urlencode($row['question']);
$feedback_support=$row['feedback_support'];
$feedback_oppose=$row['feedback_oppose'];
$feedback_total=$row['feedback_total'];
$feedback_average=$row['feedback_average'];

echo "<ul data-role='listview' data-inset='true' style='text-align:center'>
<li><a href='announcement_edit_result?id=".$id."'>
<img src='/photos/announcement/".$photo."'>
<h3>".$shorttitle."</h3>
<p>".$shortdescription."</p><br clear='all'></li><center>";
DrawVotesChart($table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);
echo "</center></a></ul>";
	}
}




/*-------------------------- ResultsLegislation loops result rows for Legislation. It calls DrawVotesChart into the loop. -------------------------- */

function ResultsLegislation($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id'") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$shorttitle = myTruncate($row['title'], 200);
$shortdescription = myTruncate($row['background'], 200);
$chart_question=urlencode($row['question']);
$feedback_support=$row['feedback_support'];
$feedback_oppose=$row['feedback_oppose'];
$feedback_total=$row['feedback_total'];
$feedback_average=$row['feedback_average'];

echo "<ul data-role='listview' data-inset='true' style='text-align:center'>
<li><a href='legislation_edit_result?id=".$id."'>
<h3>".$shorttitle."</h3>
<p>".$shortdescription."</p><br clear='all'></li><center>";
DrawVotesChart($table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);
echo "</center></a></ul>";
	}
}




/*-------------------------- TotalAnnouncement shows a total result for announcement. It calls DrawVotesChart into the loop. -------------------------- */

/*
function TotalAnnouncement($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id' limit 1") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$feedback_support="4271";
$feedback_oppose="3854";
$feedback_total="573";
$feedback_average="687";

$chart_announcement=DrawVotesChart($table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);

$total_announcement="<ul data-role='listview' data-inset='true' style='text-align:center'>
<li><a href='support_announcement'>
<h3>Our Announcements</h3></li><center>".$chart_announcement."</center></a></ul>";
	}
}

*/


/*-------------------------- TotalLegislation shows a total result for Legislation. It calls DrawVotesChart into the loop. -------------------------- */

function TotalLegislation($table,$entity_id) {

$result = mysql_query("select * from `$table` where `entity_id`='$entity_id' limit 1") or die(mysql_error());
while($row = mysql_fetch_array($result)) {

$id=$row['id'];
$feedback_support="3854";
$feedback_oppose="4271";
$feedback_total="713";
$feedback_average="687";

echo "<ul data-role='listview' data-inset='true' style='text-align:center'>
<li><a href='support_legislation'>
<h3>Our Legislation</h3></li><center>";
DrawVotesChart($table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);
echo "</center></a></ul>";
	}
}






/*-------------------------- DrawVotesChart draws the Google Charts with rules and is included in the loop above -------------------------- */

function DrawVotesChart($table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average ) {

// Calculate ending range for support/oppose chart.  This is to allow spacing at the end of the bar to display the value.

if ($feedback_support > $feedback_oppose ) {
$ending_range=($feedback_support*.3)+$feedback_support;
};
if ($feedback_oppose > $feedback_support ) {
$ending_range=($feedback_oppose*.3)+$feedback_oppose;
};

// Calculate ending range for totals/average chart.  

if ($feedback_total > $feedback_average ) {
$total_ending_range=($feedback_total *.35)+$feedback_total;
};
if ($feedback_average > $feedback_total ) {
$total_ending_range=($feedback_average *.45)+$feedback_average;
};


// Echo Votes Chart

echo "\n\n<!-- Support/Oppose chart -->\n<img class='chart' src='http://chart.apis.google.com/chart?
&chtt=".$chart_question."
&chts=333333,12
&chf=bg,s,EEEEEE
&chxs=0,EAEAEB,11,0,_,EAEAEB
&chbh=16,7,8
&chs=260x70
&chma=5,10,0,0
&chm=N+Yes+,428120,0,0,12|N+No+,9F3A12,1,1,12
&chds=0,".$ending_range."
&cht=bhg
&chco=80C65A,C54D00
&chd=t:".$feedback_support."|".$feedback_oppose."
&chdlp=r'>";

// Echo Totals Chart

echo "\n\n<!-- Totals chart -->\n<img class='chart' src='http://chart.apis.google.com/chart?
&chtt=Voting+popularity
&chts=333333,12
&chf=bg,s,EEEEEE
&chxs=0,CACBCB,11,0,_,CACBCB
&chbh=16,7,8
&chs=260x70
&chma=5,10,0,0
&chm=N+Total+,193C7C,0,0,12|N+Average+,525252,1,1,12
&chds=0,".$total_ending_range."
&cht=bhg
&chco=5A80C6,8C8C8C
&chd=t:".$feedback_total."|".$feedback_average."
&chdlp=r'>\n\n";

}














// Truncate descriptions, titles for ul/li blocks

function myTruncate($string, $limit, $break=".", $pad="...")
{
  // return with no change if string is shorter than $limit
  if(strlen($string) <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
    if($breakpoint < strlen($string) - 1) {
      $string = substr($string, 0, $breakpoint) . $pad;
    }
  }
  return $string;
}


?>