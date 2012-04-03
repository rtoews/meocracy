<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}
$user_region = new UserRegion();
$city = $user_region->get_region_for_user($user_id);

$html = new HTML();
$html->set_title('City Home');
$html->generate_header_mobile();
?>

<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li>
        <a href="/issues.php?type=<?php echo REGION_CITY; ?>"><?php echo $city->image_tag(); ?><?php echo $city->city_name(); ?></a> (<?php echo $city->issue_count[REGION_CITY]; ?>)
    </li>
    <li>
        <a href="/issues.php?type=<?php echo REGION_COUNTY; ?>"><?php echo $city->county->image_tag(); ?><?php echo $city->county->county_name(); ?></a> (<?php echo $city->issue_count[REGION_COUNTY]; ?>)
    </li>
    <li>
        <a href="/issues.php?type=<?php echo REGION_STATE; ?>"><?php echo $city->state->image_tag(); ?><?php echo $city->state->state_name(); ?></a> (<?php echo $city->issue_count[REGION_STATE]; ?>)
    </li>
</ul>
<?php
$html->generate_footer_mobile();
?>
