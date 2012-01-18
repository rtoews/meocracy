<?php

if ($category == 'me') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li data-role='list-divider'>".$page_title_complete."</li></ul>";
}

if ($category == 'official') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official.php'>
	<img src='photos/".$category."_".$photo.".jpg'>
	<h3>".$name_with_party."</h3><p>
	".$position.",  ".$parent_full.", ".$parent_2_name."</p></a></li>
	<li data-role='list-divider'>".$page_title_complete."</li></ul>";
}

if ($category == 'city') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='city.php'>
	<img src='photos/city_aliso_viejo.png'>
	<h3>".$city_name_full."</h3><p>
	".$county_name_full.",  ".$state_name."</p></a></li>
	<li data-role='list-divider'>".$page_title_complete."</li></ul>";
}

if ($category == 'county') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='county.php'>
	<img src='photos/county_orange.png'>
	<h3>".$county_name_full."</h3><p>
	".$state_name_full."</p></a></li>
	<li data-role='list-divider'>".$page_title_complete."</li></ul>";
}


?>