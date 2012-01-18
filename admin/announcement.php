<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$region_id = 65517;
$region_type = REGION_CITY;
$region_id = 216;
$region_type = REGION_COUNTY;

$announcement_ids = Announcement::get_ids_by_region($region_id, $region_type);

$html = new HTML('admin');

$html->set_title('Send and manage announcements');
$html->generate_header();
?>
<div class="row_clickable" onclick="document.location.href='announcement_edit'">
    <div class="row_icon"></div>
    <h3>Send a new announcement</h3>
    <p>My last announcement was 3 days ago.</p>
</div>
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
$html->generate_footer();
?>
