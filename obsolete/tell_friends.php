<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$type = get_param('type');

$id = get_param('id');
if (!$id || !$type) {
    redirect('/index.php');
}
if ($type == 'a') {
    $issue = new Announcement($id);
}
elseif ($type == 'l') {
    $issue = new Legislation($id);
}
$user = new User($user_id);
$friends = $user->get_friend_list();

$select_friends = true;
if (!empty($_POST)) {
    $select_friends = false;
    $screen = get_param('screen');
    if ($screen == 1) {
        $friend_ndx = get_param('friend_ndx');
        $friend_ndx_list = implode('|', $friend_ndx);
    }
    elseif ($screen == 2) {
        $friend_ndx_list = get_param('friend_ndx_list');
        $message = get_param('message');

        $user->tell_friends($friend_ndx_list, $message);
    }
}

$html = new HTML();
$html->set_title('Tell Friends');
$html->generate_header_mobile();
?>
<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li><?php echo $issue->title(); ?></li>
    <li>Let my friends know</li>
    <li>
        <form method="post">
<?php if ($select_friends) { ?>
        <input type="hidden" id="screen" name="screen" value="1"/>
<?php
    if (!empty($friends)) {
        foreach ($friends as $key => $friend) {
?>
        <input type="checkbox" id="friend_ndx" name="friend_ndx[]" value="<?php echo $key; ?>"/><?php echo $friend['last'] . ', ' .$friend['first']; ?><br/>
<?php
        }
    }
    else {
    }
?>
        <input type="submit" value="Tell Friends"/><br/>

<?php } else { ?>
        <input type="hidden" id="screen" name="screen" value="2"/>
        <input type="hidden" id="friend_ndx_list" name="friend_ndx_list" value="<?php echo $friend_ndx_list; ?>"/>
        <textarea id="message" name="message" style="width:200px; height:75px;"></textarea>
        <input type="submit" value="Send to my friends"/>

<?php } ?>
    </form>
    </li>
</ul>
<?php
$html->generate_footer_mobile();
?>
