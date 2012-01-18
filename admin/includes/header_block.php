<?php

if ($category == 'me') {
	echo "<ul class='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li class='list-divider'>".$title_complete."</li></ul>";
}

if ($category == 'official') {
	echo "<ul class='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a data-prefetch href='official'>
	<img src='photos/".$category."_".$photo.".jpg'>
	<h3>".$name_with_party."</h3><p>
	".$position.",  ".$parent_full.", ".$parent_2_name."</p></a></li>
	<li class='list-divider'>".$title_complete."</li></ul>";
}

if ($category == 'city') {
	echo "<ul class='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a data-prefetch href='city'>
	<img src='photos/city_aliso_viejo.png'>
	<h3>".$city_name_full."</h3><p>
	".$county_name_full.",  ".$state_name."</p></a></li>
	<li class='list-divider'>".$title_complete."</li></ul>";
}

if ($category == 'county') {
	echo "<ul class='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a data-prefetch href='county'>
	<img src='photos/county_orange.png'>
	<h3>".$county_name_full."</h3><p>
	".$state_name_full."</p></a></li>
	<li class='list-divider'>".$title_complete."</li></ul>";
}


?>