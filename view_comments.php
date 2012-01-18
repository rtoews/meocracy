<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$id = get_param('id');
if (!$id) {
    redirect('/index.php');
}
$announcement = new Announcement($id);
$feedback_rows = $announcement->feedback_with_comments;

$html = new HTML();
$html->generate_header_mobile();
?>
<h1><?php echo $announcement->heading(); ?></h1>
<h2>Comments</h2>
<ul>
<?php
if (!empty($feedback_rows)) {
    foreach ($feedback_rows as $feedback) {
?>
    <li><?php echo $feedback->comments(); ?></li>
<?php
    }
}
else {
}
?>
</ul>
<?php
$html->generate_footer_mobile();
?>
