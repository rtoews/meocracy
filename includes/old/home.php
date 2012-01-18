<?php 

if ($entity=='official') { 

$official_name=$_GET["official_name"];
$official_title=$_GET["official_title"];
$official_photo=$_GET["official_photo"];

echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='official_about.php?official_name=".urlencode($official_name)."&official_title=".urlencode($official_title)."&official_photo=".$official_photo."'><img src='photos/".$official_photo.".jpg' />
		<h3>About ".$official_name."</h3>
		<p>".$official_title.", ".$official_city.", ".$official_state."</p>
	</a></li>
	<li><a  href='announcements.php'>
		<h3>Announcements</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='legislation.php'>
		<h3>Hearings and Legislation</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='official_offices.php'>
		<h3>My Offices</h3>
		<div class='ui-li-count'>5</div>
	</a></li>
</ul>"; }


if ($entity=='city') { 

$city_name=$_GET["city_name"];
$city_county=$_GET["city_county"];
$city_photo=$_GET["seal_city"];

echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='city_about.php?city_name=".urlencode($city_name)."&city_county=".urlencode($city_county)."&city_photo=".$city_photo."'><img src='photos/".$seal_city.".png'>
		<h3>About the ".$city."</h3>
		<p>".$county.", ".$state."</p>
		<div class='ui-li-count'>1</div>
	</a></li>
	<li><a  href='announcements.php'>
		<h3>Announcements</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='legislation.php'>
		<h3>Hearings and Legislation</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='government.php'>
		<h3>My City Council</h3>
		<div class='ui-li-count'>5</div>
	</a></li>
</ul>"; }


if ($entity=='county') { 

$county_name=$_GET["county_name"];
$county_state=$_GET["county_state"];
$county_photo=$_GET["seal_county"];

echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='county_about.php?county_name=".urlencode($county_name)."&county_state=".urlencode($county_state)."&county_photo=".$county_photo."'><img src='photos/".$seal_county.".png'>
		<h3>About ".$county."</h3>
		<p>".$state."</p>
		<div class='ui-li-count'>1</div>
	</a></li>
	<li><a  href='county_announcements.php'>
		<h3>Announcements</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='county_legislation.php'>
		<h3>Hearings and Legislation</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='county_government.php'>
		<h3>My County Supervisors</h3>
		<div class='ui-li-count'>5</div>
	</a></li>
</ul>"; }


if ($entity=='state') { 

$county_name=$_GET["state_name"];
$state_country=$_GET["state_country"];
$state_photo=$_GET["seal_state"];

echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='state_about.php?state_name=".urlencode($state_name)."&state_state=".urlencode($state_state)."&state_photo=".$state_photo."'><img src='photos/".$seal_state.".png'>
		<h3>About ".$state."</h3>
		<p>".$country."</p>
		<div class='ui-li-count'>1</div>
	</a></li>
	<li><a  href='state_announcements.php'>
		<h3>Announcements</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='state_legislation.php'>
		<h3>Hearings and Legislation</h3>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li><a  href='state_government.php'>
		<h3>My State Government</h3>
		<div class='ui-li-count'>5</div>
	</a></li>
</ul>"; }


?>

