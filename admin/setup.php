<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');

$html = new HTML('admin');

$html->set_title('Manage the information you show to the public');
$html->generate_header();

?>
<div class='row_clickable' onclick="document.location.href='office'">
	<div class='row_icon'></div>
	<h3>Offices and Departments</h3>
	<p>Add my offices, departments and agencies to the directory.</p>
</div>

<div class='row_clickable' onclick="document.location.href='sponsor'">
	<div class='row_icon'></div>
	<h3>Sponsor Directory</h3>
	<p>Add or edit sponsor contact information.</p>
</div>

<div class='row_clickable' onclick="document.location.href='user'">
	<div class='row_icon'></div>
	<h3>Users</h3>
	<p>Last updated 45 days ago.</p>
</div>

<div class='row_clickable' onclick="document.location.href='account'">
	<div class='row_icon'></div>
	<h3>My Account</h3>
	<p>Last updated 45 days ago.</p>
</div>
<?php
$html->generate_footer();
?>

