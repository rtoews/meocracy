<?php

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

?>