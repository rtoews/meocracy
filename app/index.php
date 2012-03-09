<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$user = new User($user_id);
$user_region = new UserRegion();
$city = $user_region->get_region_for_user($user_id);

$data = array(
    'status' => '-1'
);
if (!empty($city)) {
    $data = array(
        'user' => array(
            'meostate' => $user->meostate(),
        ),
        'city' => array(
            'image' => LOGO_PATH . $city->image(),
            'name' => $city->city_name(),
            'announcement_count' => $city->issue_count[REGION_CITY]['a_count'],
            'legislation_count' => $city->issue_count[REGION_CITY]['l_count'],
        ),
        'county' => array(
            'image' => LOGO_PATH . $city->county->image(),
            'name' => $city->county->county_name(),
            'announcement_count' => $city->issue_count[REGION_COUNTY]['a_count'],
            'legislation_count' => $city->issue_count[REGION_COUNTY]['l_count'],
        ),
        'state' => array(
            'image' => LOGO_PATH . $city->state->image(),
            'name' => $city->state->state_name(),
            'announcement_count' => $city->issue_count[REGION_STATE]['a_count'],
            'legislation_count' => $city->issue_count[REGION_STATE]['l_count'],
        ),
    );
}

return_jsonp_data($data);
?>
