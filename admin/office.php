<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.office.php');

$region_id = 65517;
$region_type = REGION_CITY;
$region_logo = '/images/photos/logo/city_aliso_viejo.png';

$office_ids = Office::get_ids_by_region($region_id, $region_type);

$html = new HTML('admin');

$html->set_title('Edit office directory listings');
$html->generate_header();

include('includes/functions.php');
?>
<div class='row_clickable' onclick="document.location.href='office_edit'">
	<h3>Add a new office to the directory</h3>
</div>
<?php
if (!empty($office_ids)) {
    foreach ($office_ids as $id) {
        $office = new Office($id);
        $title = $office->title();
        $description = $office->description();
?>
    <div class='row_clickable row_thumb' onclick="document.location.href='office_edit?id=<?php echo $id; ?>'">
    <div class='row_icon'></div>
    <img src='<?php echo $region_logo; ?>' class='thumbnail'>
    <h3><?php echo $title; ?></h3>
    <p><?php echo $description; ?></p></div>
<?php
    }
}
$html->generate_footer();
?>
