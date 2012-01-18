<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.sponsor.php');
require_once(DOC_ROOT . '/includes/classes/class.office.php');

$region_id = 65517;
$region_type = REGION_CITY;

$office_ids = Office::get_ids_by_region($region_id, $region_type);
$id = get_param('id');
$sponsor = new Sponsor($id);

if (!empty($_POST)) {
    $sponsor->region_id($region_id);
    $sponsor->region_type($region_type);
    $sponsor->department_id(get_param('department_id'));
    $sponsor->sponsor_type(get_param('sponsor_type'));
    $sponsor->title(get_param('title'));
    $sponsor->name_first(get_param('name_first'));
    $sponsor->name_last(get_param('name_last'));
    $sponsor->name_middle(get_param('name_middle'));
    $sponsor->office(get_param('office'));
    $sponsor->phone(get_param('phone'));
    $sponsor->email(get_param('email'));
    $sponsor->bio(get_param('bio'));
    $sponsor->image(get_param('image'));
    if ($id) {
        $sponsor->update();
    }
    else {
        $id = $sponsor->insert();
    }
    redirect('sponsor');

}

$html = new HTML('admin');

$html->set_title("Edit this person's directory information");
$html->generate_header();

?>
    <style type="text/css">
    .sponsor_type {
        display:none;
    }
    </style>

    <form method="post">

	<div class="row">
        <div class="input_border">Is this Sponsor an Individual or an Office?</div>
        <div class="input_border">
            <?php $checked = $sponsor->sponsor_type() == 'I' ? 'checked' : ''; ?>
            <input type='radio' class="sponsor_button" name='sponsor_type' id='sponsor_type' <?php echo $checked; ?> value='I'/> Individual
            <?php $checked = $sponsor->sponsor_type() == 'O' ? 'checked' : ''; ?>
            <input type='radio' class="sponsor_button" name='sponsor_type' id='sponsor_type' <?php echo $checked; ?> value='O'/> Office
        </div>
    </div>

    <div id="sponsor_individual" class="sponsor_type">
       	<div class='row'>
    		<div class='label' for='title'>Title:</div>
    		<div class='input_border'>
    			<input type='text' name='title' id='title' value='<?php echo $sponsor->title(); ?>'/>
    		</div>
    	</div>

        <div class="row">
            <div class="label" for="name_last">Last Name:</div>
            <div class="input_border">
                <input type="text" name="name_last" id="name_last" value="<?php echo $sponsor->name_last(); ?>"/>
            </div>
        </div>

    	<div class='row'>
    		<div class='label' for='name_last'>First Name:</div>
    		<div class='input_border'>
    			<input type='text' name='name_first' id='name_first' value='<?php echo $sponsor->name_first(); ?>'/>
    		</div>
    	</div>

    	<div class='row'>
    		<div class='label' for='name_middle'>Middle Name/Initial:</div>
    		<div class='input_border'>
    			<input style='width:150px;' type='text' name='name_middle' id='name_middle' value='<?php echo $sponsor->name_middle(); ?>'/>
    		</div>
    	</div>

    	<div class='row'>
    		<div class='label' for='salutation'>Salutation:</div>
    		<div class='input_border'>
    			<input style='width:150px;' type='text' name='salutation' id='salutation' value='<?php echo $sponsor->salutation(); ?>'/>
    		</div>
    	</div>
    </div>

    <div id="sponsor_office" class="sponsor_type">
       	<div class='row'>
    		<div class='label' for='office'>Office:</div>
    		<div class='input_border'>
    			<input type='text' name='office' id='office' value='<?php echo $sponsor->office(); ?>'/>
    		</div>
    	</div>
    </div>

    <div class="row">
        <div class="label" class="select">Department:</div>
        <div class="input_border">
            <select name="department_id" id="department_id">
            <option selected>Select One:</option>
<?php
$office_id = $sponsor->department_id();
if (!empty($office_ids)) {
    foreach ($office_ids as $id) {
        $office = new Office($id);
        $selected = $id == $office_id ? 'selected' : '';
        echo '<option ' . $selected . ' value="'.$id.'">'.$office->name().'</option>"';
    }
}
?>
            </select>
        </div>
    </div>

	<div class='row'>
		<div class='label' for='phone'>Phone:</div>
		<div class='input_border'>
			<input style='width:150px;' type='text' name='phone' id='phone' value="<?php echo $sponsor->phone(); ?>"/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='email'>Email:</div>
		<div class='input_border'>
			<input type='text' name='email' id='email' value='<?php echo $sponsor->email(); ?>'/>
		</div>
	</div>

	<div class='row'>
		<div class='label' for='bio'>Bio:</div>
		<div class='input_border'>
			<textarea rows='8' cols='60' name='bio' id='bio' ><?php echo $sponsor->bio(); ?></textarea>
		</div>
	</div>
    <input type="hidden" name="image" id="image" value="<?php echo $sponsor->image(); ?>"/>

	<div class='row'>
		<input type='submit' value='Save changes'>
	</div>
	</form>
    <script type="text/javascript">
    var Sponsor = {
        type : '<?php echo $sponsor->sponsor_type(); ?>',

        init : function() {
            Sponsor.selectType(Sponsor.type);
        },

        selectType : function(type) {
            $('.sponsor_type').hide();
            if (type == 'I') {
                $('#sponsor_individual').show();
            }
            else if (type == 'O') {
                $('#sponsor_office').show();
            }
        }
    };


    $(document).ready(function() {
        Sponsor.init();
        $('.sponsor_button').click(function() {
            Sponsor.selectType($(this).val());
        });
    });
    </script>
<?php
$html->generate_footer();
?>

