<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$region_id = 65517;
$region_type = REGION_CITY;

$announcement_ids = Announcement::get_ids_by_region($region_id, $region_type);
$legislation_ids = Legislation::get_ids_by_region($region_id, $region_type);

/*
$admin_id = get_param('admin_id');
if (!$admin_id) {
    redirect('/admin/login.php');
}
*/
$html = new HTML('admin');

$html->set_title('Dashboard');
$html->generate_header();
?>
<?php
if (!empty($announcement_ids)) {
    $feedback_average = Announcement::get_average_announcement_feedback();
    foreach ($announcement_ids as $id) {
        $announcement = new Announcement($id);
	    $announcement_id = $id;
        $src = $announcement->get_image_src();
        $shorttitle = my_truncate($announcement->heading(), 10, "..." );
        $shortdescription = my_truncate($announcement->description(), 20, "...");
        $chart_question = $announcement->question();
        $feedback_support = $announcement->get_support();
        $feedback_oppose = $announcement->get_oppose();
        $feedback_total = $feedback_support + $feedback_oppose;
?>
<div class="row_clickable row_thumb" onclick="document.location.href='announcement_edit?id=<?php echo $id; ?>'">
    <img src="<?php echo $src; ?>" class="thumbnail">
    <div class='row_icon'></div>
    <h3><?php echo $shorttitle; ?></h3>
    <p><?php echo $shortdescription; ?></p>
    <br clear="all">
    <div class="chart_outer">
        <div class="chart_inner">
        <?php draw_votes_chart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average); ?>
    </div>
</div>

</div>
<?php
    }
}
?>
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
?>
<?php
$html->generate_footer();
?>
