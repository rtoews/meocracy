<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$region_id = 65517;
$region_type = REGION_CITY;

$legislation_ids = Legislation::get_ids_by_region($region_id, $region_type);

$html = new HTML('admin');

$html->set_title('Send and manage legislative announcements');
$html->generate_header();
?>
<div class='row_clickable' onClick="document.location.href='legislation_edit'">
	<div class='row_icon'></div>
	<h3>Send a new legislative announcement</h3>
	<p>I last posted a legislative announcement 3 days ago.</p>
</div>
<?php
if (!empty($legislation_ids)) {
    $feedback_average = Legislation::get_average_legislation_feedback();
    foreach ($legislation_ids as $id) {
        $legislation = new Legislation($id);
        $legislation_id = $id;
        $shorttitle = my_truncate($legislation->title(), 10, "..." );
        $shortdescription = my_truncate($legislation->recommended_action(), 20, "...");
        $chart_question = $legislation->question();
        $feedback_support = $legislation->get_support();
        $feedback_oppose = $legislation->get_oppose();
        $feedback_total = $feedback_support + $feedback_oppose;
?>
    <div class='row_clickable' onclick="document.location.href='legislation_edit?id=<?php echo $id; ?>'">
    <div class='row_icon'></div>
    <h3><?php echo $shorttitle; ?></h3>
    <p><?php echo $shortdescription; ?></p><br clear='all'>
    <div class='chart_outer'><div class='chart_inner'>
    <?php draw_votes_chart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average); ?>
    </div>
</div>
</div>
<?php
    }
}
$html->generate_footer();
?>
