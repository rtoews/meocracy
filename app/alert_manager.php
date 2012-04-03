<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

    $del_tags = get_param('del_item');
    User_Alert::delete_unchecked_alerts($user_id, $del_tags);

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

return_jsonp_data($data);
?>
