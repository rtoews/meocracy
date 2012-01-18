<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
log_time('beginning of feedback_response');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$user_id = get_param('user_id');
log_time("User ID: $user_id");
if (!$user_id) {
log_time("Getting redirected to login.php");
    redirect('/login.php');
}
$id = get_param('id');
if (!$id) {
    redirect('/index.php');
}
$announcement = new Announcement($id);
log_time('new Announcement');
$support = $announcement->get_support();
$oppose = $announcement->get_oppose();
$total = $support + $oppose;
$chart_question = $announcement->question();
log_time('gathering feedback');
$feedback_support = $announcement->get_support();
$feedback_oppose = $announcement->get_oppose();
$feedback_total = $feedback_support + $feedback_oppose;
//$feedback_average = Announcement::get_average_announcement_feedback();
log_time('gathered data');

$html = new HTML();
$html->set_title('Feedback Response');
log_time('about to generate header');
$html->generate_header_mobile();
log_time('header generated');
?>
<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li>
        <img class="ui-li-thumb" src="<?php echo $announcement->get_image_src(); ?>"/>
        <?php echo $announcement->question(); ?>
    </li>
    <li>Results</li>
    <li>
        Public Opinion:<br/><br/>
        <div class="chart_outer">
            <div class="chart_inner">
<?php log_time('about to draw chart'); ?>
            <?php //draw_votes_chart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average); ?>
<?php log_time('chart done'); ?>
        </div>
        Support <?php echo $feedback_support; ?><br/>
        Oppose <?php echo $oppose; ?><br/>
        Total <?php echo $total; ?><br/>
    </li>
    <li><a href="/view_comments.php?id=<?php echo $id; ?>">See comments on this issue</a></li>
    <li><a href="/tell_friends.php?id=<?php echo $id; ?>">Let friends know</a></li>
    <li><a href="/alerts.php?id=<?php echo $id; ?>">Stay on top of it</a></li>
</ul>
<?php
log_time('generating footer'); 
$html->generate_footer_mobile();
die();
log_time('footer done'); 
?>
