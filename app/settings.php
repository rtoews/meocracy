<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.alert.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$user = new User($user_id);

$data = array(
    'name' => format_name($user->firstname(), $user->lastname()),
    'first' => $user->firstname(),
    'last' => $user->lastname(),
    'phone' => $user->mobile_phone(),
);

$city = UserRegion::get_city($user_id);
if ($city) {
    $data['zip'] = $city->zip();
}

$alerts = User_Alert::get_all($user_id);

$tags = array();
if (!empty($alerts)) {
    foreach ($alerts as $alert) {
        $tags[] = array('is_checked' => true, 'key' => $alert->tag_id(), 'value' => $alert->tag->tag());
    }
}

$data['tags'] = $tags;

return_jsonp_data($data);
?>
