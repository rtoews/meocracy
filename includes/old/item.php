<!-- Begin item -->

<?php 

$fp = fopen('data/aliso_viejo.txt','r'); 
if (!$fp) {echo 'ERROR: Unable to open file.</table></body></html>'; exit;} 
$loop = 0; 
while (!feof($fp)) { 
$loop++; 
$line = fgets($fp, 1024); //use 2048 if very long lines 
$field[$loop] = explode ('|', $line); 
echo ' <table>
<tr> 
<td>'.$field[$loop][0].'</td> 
<td>'.$field[$loop][1].'</td> 
<td>'.$field[$loop][2].'</td> 
<td>'.$field[$loop][3].'</td> 
<td>'.$field[$loop][4].'</td> 
<td>'.$field[$loop][5].'</td> 
</tr></table>'; 
$fp++; 
} 
fclose($fp); 

// [down] [across]

$city=$field[2][2];
$county=$field[3][2];

echo "<br><br>".$county."<br><br>";


switch ($entity){ 

case "City": 
$entity_name=$city; 
$department="City Council";
$entity_parent="County"; 
$entity_parent_name=$county; 
break;

case "County": 
$entity_name=$county; 
$department="Board of Supervisors";
$entity_parent="State"; 
$entity_parent_name=$state; 
break;

}

$page_title=$entity." ".$content_type;

echo "<div data-role='page' data-title='<?php echo $title;?>' data-theme='".$theme."' id='".$entity."_".$content_type."_".$anchor_id."'>
	<div data-role='header'>";
	include('includes/navbar.php');
echo "	</div>
	<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."'  data-divider-theme='".$theme_divider."'>
		<li data-role='list-divider'>".$page_title."</li>
	</ul>
<div data-role='content'>
	<ul data-role='listview' data-inset='".$data_inset."' data-theme='".$theme."' data-divider-theme='".$theme_divider."'>
		<li><a href='#".$entity."_top'>
		<img src='photos/".$seal_entity_name.".png'>
		<h3>".$entity." of ".$entity_name."</h3>
		<p>".$entity_parent." of  ".$entity_parent_name."</p>
		</a></li>
		</ul>
	<br>";

$title=$entity."_".$content_type."_".$anchor_id."_title";
$subhead=$entity."_".$content_type."_".$anchor_id."_subhead";
$text=$entity."_".$content_type."_".$anchor_id."_text";

echo 	"<h3>".$title."</h3>
	<h4>".$subhead."</h4>
	".$text;

$chart_support=rand(15, 70);
$chart_oppose=rand(15, 70);

echo
"<p></p><br>
<div class='ui-grid-a ui-bar-c' style='padding:5px;text-align:center'>";
echo $entity_name." ".$chart_subtitle."<p></p><img style='height:70px;width:280px;' src='http://chart.apis.google.com/chart?chf=bg,lg,90,E9EAEB,0,F0F0F0,1&chxr=0,0,70&chxs=0,676767,13,0,l,676767&chxt=x&chbh=a&chs=280x70&cht=bhg&chco=80C65A,AA3E00&chds=0,70,0,70&chd=t:";
echo $chart_support."|".$chart_oppose."&chdl=Support|Oppose&chdlp=l&chma=70,0,5'>

	<p></p>
	<fieldset class='ui-grid-a'> 
		<div class='ui-block-a'><a href='#".$entity."_".$content_type."_".$anchor_id."_support' data-role='button' data-rel='dialog' class='back_green' data-icon='check'>Support</a></div> 
		<div class='ui-block-b'><a href='#".$entity."_".$content_type."_".$anchor_id."_oppose' data-role='button' data-rel='dialog'  class='back_red' data-icon='delete'>Oppose</a></div> 
	</fieldset>
</div>";

include('includes/footer.php'); 

// Support page 

echo "<div data-role='page' data-title='<?php echo $title;?>' data-theme='".$theme."' id=".$entity."_".$content_type."_".$anchor_id."_support'>
	<div data-role='header' data-theme='".$theme."'></div>
		<div data-role='content' data-theme='".$theme."'>
			<ul data-role='listview' data-theme='".$theme."'>
				<li><a href='#".$department."'><img src='photos/".$city_official_1_photo."'/>";

?>

		
		<h3><?php echo $city_official_1_name; ?></h3>
		<p><?php echo $city_official_1_title; ?>, <?php echo $city; ?></p></a></li>
	</ul>
	<p></p><br>
	Send my support of <?php echo $city_announcements_1_title; ?> to <?php echo $city_official_1_title; ?> <?php echo $city_official_1_name; ?>:</p>
	<p> </p>
	<fieldset class="ui-grid-a"> 
		<div class="ui-block-a"><a href='#' data-role='button' data-rel='back' class="back_green" data-icon="check">Support</a></div>
		<div class="ui-block-b"><a href='#City_' data-role='button' data-rel='back' class="back_red" data-icon="delete">Cancel</a></div>
	</fieldset>
</div>
<div data-role='footer' data-theme='<?php echo $theme;?>'></div>
</div>

<!-- City Oppose page -->
<div data-role='page' data-title='<?php echo $title;?>' data-theme='<?php echo $theme;?>'  id='City_announcements_1_oppose'>
<div data-role='header' data-theme='<?php echo $theme;?>'></div>
<div data-role='content' data-theme='<?php echo $theme;?>'>
	<ul data-role='listview' data-theme='<?php echo $theme;?>'>
		<li><a href='#City_council'><img src='photos/<?php echo $city_official_1_photo;?>'/>
		<h3><?php echo $city_official_1_name; ?> </h3>
		<p><?php echo $city_official_1_title; ?>, <?php echo $city; ?></p></a></li>
	</ul>
	<p></p><br>
	Send my opposition to <?php echo $city_announcements_1_title; ?> to <?php echo $city_official_1_title; ?> <?php echo $city_official_1_name; ?>:</p>
	<p> </p>
	<fieldset class="ui-grid-a"> 
		<div class="ui-block-a"><a href='#' data-role='button' data-rel='back' class="back_green" data-icon="check">Oppose</a></div>
		<div class="ui-block-b"><a href='#City_' data-role='button' data-rel='back' class="back_red" data-icon="delete">Cancel</a></div>
	</fieldset> 
</div>
<div data-role='footer' data-theme='<?php echo $theme;?>'></div>
</div>

<!-- End item -->