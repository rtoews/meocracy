<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.tell_friend.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

$code = get_param('code');
$fyi_data = Tell_Friend::get_data($code);
if (!empty($fyi_data) && $fyi_data['city_id']) {
    $city = new City($fyi_data['city_id']);
}
if ($fyi_data['issue_type'] == ANNOUNCEMENT_TYPE) {
    $issue = new Announcement($fyi_data['issue_id']);
}
elseif ($fyi_data['issue_type'] == LEGISLATION_TYPE) {
    $issue = new Legislation($fyi_data['issue_id']);
}

$user = new User($user_id);
$user->mobile_phone($fyi_data['phone']);
$user->update();

$user_region = new UserRegion();
$user_region->add_city($user_id, $fyi_data['city_id']);

$data = array(
    'status' => -1,
);
if (!empty($city)) {
    $data = array(
        'user_id' => $user_id,
        'phone' => $fyi_data['phone'],
        'issue' => array(
            'id' => $fyi_data['issue_id'],
            'type' => $fyi_data['issue_type'],
            'category' => array(
                'id' => $issue->category['id'],
                'name' => $issue->category['name'],
            ),
            'feedback_id' => '', // defined after support / oppose
            'filter_type' => '',
            'title' => $issue->title(),
            'sponsor' => $issue->sponsors,
        ),
        'region' => $city->region_data(),
    );
}

return_jsonp_data($data);
?>
