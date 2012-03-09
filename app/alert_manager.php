<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$mode = get_param('mode');

if ($mode == 1) {
    $alerts = User_Alert::get_all($user_id);

    $tags = array();
    if (!empty($alerts)) {
        foreach ($alerts as $alert) {
            $tags[] = array('is_checked' => true, 'key' => $alert->tag_id(), 'value' => $alert->tag->tag());
        }
    }

    $data = array(
        'tags' => $tags
    );
}
elseif ($mode == 2) {
    $alerts = get_param('item');
    $tag_ids = implode(', ', $alerts);
    User_Alert::keep_selected_alerts($user_id, $tag_ids);

    $alerts = User_Alert::get_all($user_id);

    $tags = array();
    if (!empty($alerts)) {
        foreach ($alerts as $alert) {
            $tags[] = $alert->tag_id();
        }
    }

    $data = array(
        'tags' => $tags,
        'success' => 1
    );
}

return_jsonp_data($data);
?>
