<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.sponsor.php');

$region_id = 65517;
$region_type = REGION_CITY;

$sponsor_ids = Sponsor::get_ids_by_region($region_id, $region_type);

$html = new HTML('admin');

$html->set_title('Edit personnel directory listings');
$html->generate_header();
?>
<div class='row_clickable' onclick="document.location.href='sponsor_edit'">
    <h3>Add a new sponsor to the directory</h3>
</div>
<?php
if (!empty($sponsor_ids)) {
    foreach ($sponsor_ids as $id) {
        $sponsor = new Sponsor($id);

        $sponsor_type = $sponsor->sponsor_type();
        if ($sponsor_type == 'I') {
            $name_last = $sponsor->name_last();
            $name_first = $sponsor->name_first();
            $name_middle = $sponsor->name_middle();
            $title = $sponsor->title();
            $sponsor_name = $name_first . ' ' . $name_last . ', ' . $title;
        }
        elseif ($sponsor_type == 'O') {
            $office = $sponsor->office();
            $sponsor_name = $office;
        }
        $src = '/images/photos/personnel/' . $sponsor->image();
        $region_name = $sponsor->region;
?>
    <div class='row_clickable row_thumb' onclick="document.location.href='sponsor_edit?id=<?php echo $id; ?>'">
    <div class='row_icon'></div>		
    <img src='<?php echo $src; ?>' class='thumbnail'>
    <h3><?php echo $sponsor_name; ?></h3>
    </div>
<?php
    }
}
$html->generate_footer();
?>

