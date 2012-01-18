<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');

$id = get_param('id');
$query = sprintf("SELECT * FROM personnel WHERE id=%d", $id);
$row = db()->Get_Row($query);
if (!empty($row)) {
	$id=$row['id'];
	$name_last= $row['name_last'];
	$name_first= $row['name_first'];
	$name_middle= $row['name_middle'];
	$salutation = $row['salutation'];
	$title = $row['title'];
	$phone_area = $row['phone_area'];
	$phone_prefix = $row['phone_prefix'];
	$phone_suffix = $row['phone_suffix'];
	$phone_extension = $row['phone_extension'];
	$email = $row['email'];
	$bio = $row['bio'];
	$photo = $row['photo'];
}

$html = new HTML('admin');

$html->set_title("Edit this person's directory information");

$html->generate_header();

include('includes/functions.php');
?>
	<form action='#' method='get'>

	<div class='row'>
		<div class='label' for='name_last'>Last Name:</div>
		<div class='input_border'>
			<input type='text' name='name_last' id='name_last' value='<?php echo $name_last; ?>'/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='name_last'>First Name:</div>
		<div class='input_border'>
			<input type='text' name='name_first' id='name_first' value='<?php echo $name_first; ?>'/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='name_middle'>Middle Name/Initial:</div>
		<div class='input_border'>
			<input style='width:150px;' type='text' name='name_middle' id='name_middle' value='<?php echo $name_middle; ?>'/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='salutation'>Salutation:</div>
		<div class='input_border'>
			<input style='width:150px;' type='text' name='salutation' id='salutation' value='<?php echo $salutation; ?>'/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='title'>Title:</div>
		<div class='input_border'>
			<input type='text' name='title' id='title' value='<?php echo $title; ?>'/>
		</div>
	</div>

	<div class='row'>
		<legend>Department:</legend>
		<input checked type='checkbox' name='department' id='department' class='custom'  />
		<div class='label' for='department'>City Council</div>
	</div>

	<div class='row'>
		<div class='label' for='tel'>Tel:</div>
		<div class='input_border'>
			<input style='width:150px;' type='tel' name='tel' id='tel' value='<?php echo "$phone_area $phone_prefix $phone_suffix"; ?>'/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='email'>Email:</div>
		<div class='input_border'>
			<input type='email' name='email' id='email' value='<?php echo $email; ?>'/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='bio'>Bio:</div>
		<div class='input_border'>
			<textarea rows='8' cols='60' name='bio' id='bio' ><?php echo $bio; ?></textarea>
		</div>
	</div>

	<div class='row'>
		<input type='submit' value='Save changes'>
	</div>
	</form>
<?php
$html->generate_footer();
?>

