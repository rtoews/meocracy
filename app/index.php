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
        'user_id' => $user_id,
        'issue' => array(
            'id' => '',
            'type' => '',
            'category' => array(
                'id' => '',
                'name' => '',
            ),
            'feedback_id' => '', // defined after support / oppose
            'filter_type' => '',
            'title' => '',
            'sponsor' => '',
        ),
        'region' => $city->region_data(),
    );
}

return_jsonp_data($data);
?>
