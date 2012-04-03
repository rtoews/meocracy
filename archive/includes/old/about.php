<?php 


if ($entity=='official') {

$official_name=$_GET["official_name"];
$official_title=$_GET["official_title"];
$official_photo=$_GET["official_photo"];

 echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='official_home.php?official_name=".urlencode($official_name)."&official_title=".urlencode($official_title)."&official_photo=".$official_photo."'><img src='photos/".$official_photo.".jpg' />
		<h3>".$official_name."</h3>
		<p>".$official_title.", ".$county.", ".$state."</p>
	</a></li>
</ul>
	<p></p><br>
	".$official_about."
"; }



if ($entity=='city') { 

$city_name=$_GET["city_name"];
$city_county=$_GET["city_county"];
$city_photo=$_GET["seal_city"];

echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='city_home.php?official_name=".urlencode($city_name)."&city_county=".urlencode($city_county)."&city_photo=".$city_photo."'><img src='photos/".$seal_city.".png'>
		<h3>".$city."</h3>
		<p>".$county.", ".$state."</p>
	</a></li>
</ul>
	<p></p><br>
	".$city_about."
"; }


if ($entity=='county') { 

$county_name=$_GET["county_name"];
$county_state=$_GET["county_state"];
$county_photo=$_GET["seal_county"];

echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='county_home.php?official_name=".urlencode($county_name)."&county_state=".urlencode($county_state)."&official_photo=".$county_photo."'><img src='photos/".$seal_county.".png'>
		<h3>".$county."</h3>
		<p>".$state."</p>
	</a></li>
</ul>
	<p></p><br>
	".$county_about."
"; }


if ($entity=='state') { 

$county_name=$_GET["state_name"];
$state_country=$_GET["state_country"];
$state_photo=$_GET["seal_state"];

echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a href='state_home.php?official_name=".urlencode($state_name)."&state_country=".urlencode($state_country)."&state_photo=".$state_photo."'><img src='photos/".$seal_state.".png'>
		<h3>".$state."</h3>
		<p>".$country."</p>
	</a></li>
</ul>
	<p></p><br>
	".$state_about."
"; }


?>

