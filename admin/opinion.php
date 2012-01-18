<?php

$page_title='Public opinion';
$category='opinion';
$table_entity="info";

include('includes/config.php');
include('includes/functions.php');
include('includes/header.php');
include('includes/nav.php');



echo "

<!-- Announcements -->

<div class='row_clickable' onClick=\"document.location.href='opinion_announcement'\">
	<div class='row_icon'></div>
	<h3>Announcements</h3>
	<p>View public opinion and opposition of each of my announcements</p>

<div class='chart_wrapper'>";

/* These fake variables should actually be a query, totalled from all this entity's votes: */


$id='1';
$entity_id='2';
$chart_question='Total votes';
$feedback_support='1,456';
$feedback_oppose='931';
$feedback_total='2377';
$feedback_average='644';

DrawVotesChart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average);

echo "
</div>
</div>


<!-- Legislation -->

<div class='row_clickable' onClick=\"document.location.href='opinion_legislation'\">
	<div class='row_icon'></div>
	<h3>Legislation</h3>
	<p>View public opinion and opposition of my legislation, by item</p>

<div class='chart_wrapper'>

<!-- opinion/Oppose chart -->
<img class='chart' src='http://chart.apis.google.com/chart?
&chtt=opinion+vs.+opposition
&chts=333333,12
&chf=bg,s,EEEEEE
&chxs=0,EAEAEB,11,0,_,EAEAEB
&chbh=16,7,8
&chs=260x70
&chma=5,10,0,0
&chm=N+Yes+,428120,0,0,12|N+No+,9F3A12,1,1,12
&chds=0,100
&cht=bhg
&chco=80C65A,C54D00
&chd=t:63|27
&chdlp=r'>

<!-- Totals chart -->
<img class='chart' src='http://chart.apis.google.com/chart?
&chtt=Voting+popularity
&chts=333333,12
&chf=bg,s,EEEEEE
&chxs=0,CACBCB,11,0,_,CACBCB
&chbh=16,7,8
&chs=260x70
&chma=5,10,0,0
&chm=N+Total+,193C7C,0,0,12|N+Average+,525252,1,1,12
&chds=0,700
&cht=bhg
&chco=5A80C6,8C8C8C
&chd=t:550|430
&chdlp=r'>

</div>
</div>




<!-- Popularity -->


<div class='row_clickable' onClick=\"document.location.href='opinion_popularity'\">
	<div class='row_icon'></div>
	<h3>Popularity of cityofalisoviejo.meocracy.com</h3>
	<p>Increase my visibility within my constituency</p>

<div class='chart_wrapper'>

<!-- opinion/Oppose chart -->
<img class='chart' src='http://chart.apis.google.com/chart?
&chtt=opinion+vs.+opposition
&chts=333333,12
&chf=bg,s,EEEEEE
&chxs=0,EAEAEB,11,0,_,EAEAEB
&chbh=16,7,8
&chs=260x70
&chma=5,10,0,0
&chm=N+Yes+,428120,0,0,12|N+No+,9F3A12,1,1,12
&chds=0,100
&cht=bhg
&chco=80C65A,C54D00
&chd=t:63|27
&chdlp=r'>

<!-- Totals chart -->
<img class='chart' src='http://chart.apis.google.com/chart?
&chtt=Voting+popularity
&chts=333333,12
&chf=bg,s,EEEEEE
&chxs=0,CACBCB,11,0,_,CACBCB
&chbh=16,7,8
&chs=260x70
&chma=5,10,0,0
&chm=N+Total+,193C7C,0,0,12|N+Average+,525252,1,1,12
&chds=0,700
&cht=bhg
&chco=5A80C6,8C8C8C
&chd=t:550|430
&chdlp=r'>

</div>
</div>


";


include('includes/footer.php');

?>


