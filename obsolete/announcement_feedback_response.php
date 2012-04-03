<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$type = get_param('t');
$id = get_param('id');
if (!$id || !$type) {
    redirect('/index.php');
}
$announcement = new Announcement($id);
$support = $announcement->get_support();
$oppose = $announcement->get_oppose();
$total = $support + $oppose;
$chart_question = $announcement->question();
$feedback_support = $announcement->get_support();
$feedback_oppose = $announcement->get_oppose();
$feedback_total = $feedback_support + $feedback_oppose;
//$feedback_average = Announcement::get_average_announcement_feedback();

$html = new HTML();
$html->set_title('Feedback Response');
$html->generate_header_mobile();
?>
<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li>
        <a href="/issues.php?type=<?php echo $type; ?>">Back</a>
    </li>
    <li><a href="/issues.php">Results</a></li>
    <li>
        <img class="ui-li-thumb" src="<?php echo $announcement->get_image_src(); ?>"/>
        <?php echo $announcement->question(); ?>
    </li>
    <li>Results</li>
    <li>
        Public Opinion:<br/><br/>
        <div class="chart_outer">
            <div class="chart_inner">
            <?php //draw_votes_chart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average); ?>
        </div>
        Support <?php echo $feedback_support; ?><br/>
        Oppose <?php echo $oppose; ?><br/>
        Total <?php echo $total; ?><br/>
    </li>
    <li><a href="/view_comments.php?type=a&id=<?php echo $id; ?>">See comments on this issue</a></li>
    <li><a href="/tell_friends.php?type=a&id=<?php echo $id; ?>">Let friends know</a></li>
    <li><a href="/alerts.php?type=a&id=<?php echo $id; ?>">Stay on top of it</a></li>
</ul>
<?php
$html->generate_footer_mobile();
?>
