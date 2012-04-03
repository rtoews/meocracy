<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$mode = get_param('mode');
$type = get_param('type');
$id = get_param('id');

$alerts = array();
if ($type == ANNOUNCEMENT_TYPE) {
    $issue = new Announcement($id);
}
elseif ($type == LEGISLATION_TYPE) {
    $issue = new Legislation($id);
}

if ($issue) {
    $tags = $issue->tags();
}
if ($mode == 1) {
        if (!empty($tags)) {
        //    $alert = new User_Alert();
            foreach ($tags as $tag_id => $tag) {
                if ($user_alert_id = User_Alert::is_set($user_id, $tag)) {
                    $alerts[] = array('is_checked' => 1, 'key' => $tag_id, 'value' => $tag);
                }
                else {
                    $alerts[] = array('is_checked' => 0, 'key' => $tag_id, 'value' => $tag);
                }
            }
        }

    $data = array (
        'alerts' => $alerts
    );
}
elseif ($mode == 2) {
    $alerts = get_param('item');
    $delete_tags = array_diff(array_keys($tags), $alerts);
    User_Alert::delete_unchecked_alerts($user_id, $delete_tags);

    if (!empty($alerts)) {
        foreach ($alerts as $alert_tag) {
            $tag_to_add[] = $alert_tag;
            $alert = new User_Alert();
            $alert->user_id($user_id);
            $alert->tag_id($alert_tag);
            $alert->add_no_dup();
        }
    }
    $data = array (
        'tags_to_add' => $tag_to_add,
        'success' => 1
    );
}

return_jsonp_data($data);
?>
