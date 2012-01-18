<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.office.php');

$region_id = 65517;
$region_type = REGION_CITY;

$id = get_param('id');
$office = new Office($id);

if (!empty($_POST)) {
    $office->region_id($region_id);
    $office->region_type($region_type);
    $office->title(get_param('title'));
    $office->description(get_param('description'));
    $office->phone(get_param('phone'));
    $office->email(get_param('email'));
    if ($id) {
        $office->update();
    }
    else {
        $id = $office->insert();
    }
    redirect('office');

}

$html = new HTML('admin');

$html->set_title("Edit this office's directory listing");
$html->generate_header();
?>
    <form method="post">

    <div class='row'>
        <div class='label' for='title'>Title:</div>
        <div class='input_border'>
            <input name='title' id='title' value='<?php echo $office->title(); ?>'>
        </div>
    </div>
    <div class='row'>
        <div class='label' for='description'>Description:</div>
        <div class='input_border'>
            <input type="text" name="description" id="description" value="<?php echo $office->description(); ?>"/>
        </div>
    </div>
    <div class='row'>
        <div class='label' for='tel'>Tel:</div>
        <div class='input_border'>
            <input style='width:150px;' type='tel' name='phone' id='phone' value='<?php echo $office->phone(); ?>'/>
        </div>
    </div>
    <div class='row'>
        <div class='label' for='email'>Email:</div>
        <div class='input_border'>
            <input type='email' name='email' id='email' value='<?php echo $office->email(); ?>' />
        </div>
    </div>
    <button type='submit'>Submit</button>

    </form>
<?php
$html->generate_footer();
?>
