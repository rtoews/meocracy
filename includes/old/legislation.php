<?php 

if ($entity=='city') { echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='city_home.php'>
		<img src='photos/".$seal_city.".png'>
		<h3>".$city."</h3>
		<p>".$county.", ".$state."</p>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li data-role='list-divider'>".$city_announcements_1_date."</li>
	<li><a href='city_announcements_1.php'>
		<h3>".$city_announcements_1_title."</h3>
		<p>".$city_announcements_1_subhead."</p>
	</a></li>
	<li data-role='list-divider'>".$city_announcements_2_date."</li>
	<li><a href='city_announcements_2.php'>
		<h3>".$city_announcements_2_title."</h3>
		<p>".$city_announcements_2_subhead."</p>
	</a></li>
</ul>"; }


if ($entity=='county') { echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='county_home.php'>
		<img src='photos/".$seal_county.".png'>
		<h3>".$county."</h3>
		<p>".$state."</p>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li data-role='list-divider'>".$county_announcements_1_date."</li>
	<li><a href='county_announcements_1.php'>
		<h3>".$county_announcements_1_title."</h3>
		<p>".$county_announcements_1_subhead."</p>
	</a></li>
	<li data-role='list-divider'>".$county_announcements_2_date."</li>
	<li><a href='county_announcements_2.php'>
		<h3>".$county_announcements_2_title."</h3>
		<p>".$county_announcements_2_subhead."</p>
	</a></li>
</ul>"; }


if ($entity=='state') { echo "

<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
	<li><a  href='state_home.php'>
		<img src='photos/".$seal_state.".png'>
		<h3>".$state."</h3>
		<p>".$country."</p>
		<div class='ui-li-count'>2</div>
	</a></li>
	<li data-role='list-divider'>".$state_announcements_1_date."</li>
	<li><a href='state_announcements_1.php'>
		<h3>".$state_announcements_1_title."</h3>
		<p>".$state_announcements_1_subhead."</p>
	</a></li>
	<li data-role='list-divider'>".$state_announcements_2_date."</li>
	<li><a href='state_announcements_2.php'>
		<h3>".$state_announcements_2_title."</h3>
		<p>".$state_announcements_2_subhead."</p>
	</a></li>
</ul>"; }


?>

