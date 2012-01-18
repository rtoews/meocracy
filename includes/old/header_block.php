<?php

if ($entity == 'personnel_city') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_first." ".$name_last."</h3><p>
	".$title.",  ".$name_office.", ".$name_city."</p></a></li></ul><br /><br />";
}

if ($entity == 'personnel_county') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_first." ".$name_last."</h3><p>
	".$title.",  ".$name_office.", ".$name_county."</p></a></li></ul><br /><br />";
}

if ($entity == 'personnel_state') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_first." ".$name_last."</h3><p>
	".$title.",  ".$name_office.", ".$name_state."</p></a></li></ul><br /><br />";
}

if ($entity == 'personnel_federal') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_first." ".$name_last."</h3><p>
	".$title.",  ".$name_office.", ".$name_country."</p></a></li></ul><br /><br />";
}

if ($entity == 'office_city') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_office."</h3><p>
	".$name_city.", ".$name_county."</p></a></li></ul><br /><br />";
}

if ($entity == 'office_county') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_office."</h3><p>
	".$name_county.", ".$name_state."</p></a></li></ul><br /><br />";
}

if ($entity == 'office_state') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_office."</h3><p>
	".$name_state.", ".$name_country."</p></a></li></ul><br /><br />";
}

if ($entity == 'office_federal') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='official'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_office."</h3><p>
	".$name_state.", ".$name_country."</p></a></li></ul><br /><br />";
}

if ($entity == 'city') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='city'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_city."</h3><p>
	".$name_county.",  ".$name_state."</p></a></li></ul><br /><br />";
}

if ($entity == 'county') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='county'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_county."</h3><p>
	".$name_state."</p></a></li></ul><br /><br />";
}

if ($entity == 'state') {
	echo "<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='state'>
	<img src='photos/logo/".$ImageSeal.".png'>
	<h3>".$name_state."</h3><p>
	".$name_country."</p></a></li></ul><br /><br />";
}

?>