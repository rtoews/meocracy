<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');

$html = new HTML('admin');

$html->set_title("");

$html->generate_header();

include('includes/functions.php');
?>
<?php
$query = "select * from `old_user` order by `name_last`";
$data = db()->Get_Table($query);
foreach ($data as $row) {
    $id=$row['id'];
    $mobile="(".$row['mobile_area'].") ".$row['mobile_prefix']."-".$row['mobile_suffix'];
    $name_last = ucfirst($row['name_last']);
    $name_first = ucfirst($row['name_first']);
?>
<div class='row_clickable' onclick="document.location.href='user_result?id=<?php echo $id; ?>'">
<?php echo "$mobile $name_last, $name_first"; ?>
</div>
<?php
}
$html->generate_footer();
?>

