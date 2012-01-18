<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement_feedback.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$id = get_param('id');
log_time("Announcement $id");
if (!$id) {
log_time('Returning to home.');
    redirect('/index.php');
}
$announcement = new Announcement($id);

if (!empty($_POST)) {
log_time('Preparing to process feedback.');
    $feedback = new Announcement_Feedback();
log_time('new Feedback');
    $feedback->response(get_param('response'));
log_time('response');
    $feedback->comments(get_param('response_comments'));
log_time('comments');
    $feedback->user_id($user_id);
log_time('user_id');
    $feedback->announcement_id($id);
log_time('announcement_id');
    $feedback->record_response();
log_time('record_response');
    redirect('/announcement_feedback_response.php?id=' . $id);
}

$html = new HTML();
$html->set_title('Announcement');
$html->use_style('announcement.css');
$html->generate_header_mobile();
?>
<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li>
        <img src="<?php echo $announcement->get_image_src(); ?>"/>
        <?php echo $announcement->description(); ?>
    </li>
    <li>
        <span class="label">Status</span> <?php echo $announcement->status->status(); ?><br/>
        <span class="label">Calendared</span> <?php echo $announcement->calendared(); ?><br/>
        <span class="label">Sponsored by</span> 
<?php
if (!empty($announcement->sponsor)) {
    foreach ($announcement->sponsor as $sponsor) {
        echo $sponsor->sponsor_name() . '<br/>';
    }
}
?><br/>
        <span class="label">Vote</span> <?php echo $announcement->vote(); ?><br/>
        <div class="clear"></div>
        <br/><br/>
        <p>I have <?php echo $announcement->days_remaining(); ?> left to:</p>
        <div id="btn_support" data-val="1" class="button">Support</div>
        <div id="btn_oppose" data-val="-1" class="button">Oppose</div>
        <div class="clear"></div>
        <form id="response_form" method="post">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
        <input type="hidden" name="response" id="response" value=""/>
        <ul>
            <li><img src="<?php echo $announcement->sponsor[0]->get_image_src(); ?>"/><?php echo $announcement->sponsor[0]->sponsor_name(); ?><br/><?php echo $announcement->sponsor[0]->office->name(); ?></li>
            <li>Comments (optional):<br/>
                <textarea name="response_comments" id="response_comments"></textarea>
            </li>
            <div id="btn_response_send" class="button"></div>
        </ul>
        </form>
    </li>
</ul>
<script type="text/javascript">
var A = {};

A.getResponse = function(responseVal) {
    var response_desc = responseVal == '1' ? 'support' : 'opposition';
    $('#response_form').show();
    $('#btn_response_send').html('Send my ' + response_desc);
    $('#response').val(responseVal);
};

A.sendResponse = function() {
    $('form').submit();
};

A.processButton = function(btnObj) {
    var btn = btnObj.attr('id');
    var responseVal = btnObj.attr('data-val');
    if (btn == 'btn_response_send') {
        A.sendResponse();
    }
    else {
        A.getResponse(responseVal);
    } 
};

$(document).ready(function() {
    $('.button').click(function() {
        A.processButton($(this));
    });
});
</script>
<?php
$html->generate_footer_mobile();
?>
