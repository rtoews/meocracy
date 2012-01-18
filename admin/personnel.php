<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');

$html = new HTML('admin');

$html->set_title('Edit personnel directory listings');

$html->generate_header();

include('includes/functions.php');
?>
<div class='row_clickable' onclick="document.location.href='personnel_new'">
	<h3>Add a new person to the directory</h3>
</div>
<?php
$entity_id = 1;
$query = sprintf("select * from `personnel` where `entity_id`=%d order by name_last", $entity_id);
$data = db()->Get_Table($query);
if (!empty($data)) {
    foreach ($data as $row) {

        $id=$row['id'];
        $department=ucfirst($row['department']);
        $name_last = ucfirst($row['name_last']);
        $name_first = ucfirst($row['name_first']);
        $name_middle = ucfirst($row['name_middle']);
        $title = ucfirst($row['title']);
        $photo=$row['image'];
?>
    <div class='row_clickable row_thumb' onclick="document.location.href='personnel_edit?id=<?php echo $id; ?>'">
    <div class='row_icon'></div>		
    <img src='/images//photos/personnel/<?php echo $photo; ?>' class='thumbnail'>
    <h3><?php echo $name_last; ?>, <?php echo $name_first; ?> <?php echo $name_middle; ?></h3>
    <p><?php echo $title; ?>, <?php echo $entity_name; ?></p></div>
<?php
    }
}
$html->generate_footer();
?>

