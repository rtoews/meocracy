<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.office.php');

$region_id = 65517;
$region_type = REGION_CITY;

$id = get_param('id');
$announcement = new Announcement($id);

if (!empty($_POST)) {
    $announcement->region_id($region_id);
    $announcement->region_type($region_type);
    $announcement->heading(get_param('title'));
    $announcement->description(get_param('description'));
    $announcement->text(get_param('text'));
    $announcement->question(get_param('question'));
    $date_beginning = set_date_parts(get_param('begin_month'), get_param('begin_day'), get_param('begin_year'));
    $date_ending = set_date_parts(get_param('end_month'), get_param('end_day'), get_param('end_year'));
    $calendared = set_date_parts(get_param('calendared_month'), get_param('calendared_day'), get_param('calendared_year'));
    $vote = set_date_parts(get_param('vote_month'), get_param('vote_day'), get_param('vote_year'));
    $announcement->date_beginning($date_beginning);
    $announcement->date_ending($date_ending);
    $announcement->calendared($calendared);
    $announcement->vote($vote);
    if ($id) {
        $announcement->update();
    }
    else {
        $id = $announcement->insert();
    }
    // sponsor IDs next
    $sponsor_ids = get_param('sponsor_ids');
    if (!empty($sponsor_ids)) {
        $announcement->associate_sponsors($sponsor_ids);
    }
    // finally, tags
    $tag_list = get_param('tag_list');
    $tags = explode('|', $tag_list);
    if (!empty($tags)) {
        $announcement->associate_tags($tags);
    }
    else {
        $announcement->disassociate_tags();
    }
    redirect('announcement');

}

if ($id) {
    $sponsor_id = $announcement->sponsor_id();
    $office_name = $announcement->office->office_name();
}

$html = new HTML('admin');

$html->set_title('Edit this announcement');
$html->use_style('admin/tag.css');
$html->use_style('dp/themes/base/ui.all.css');
$html->use_style('dp/themes/base/ui.core.css');
$html->use_style('dp/themes/base/ui.datepicker.css');
$html->use_style('dp/themes/base/ui.theme.css');

$html->use_script('admin/stopwords.js');
$html->use_script('admin/tag_extraction.js');
$html->use_script('datepicker/ui/ui.core.js');
$html->use_script('datepicker/ui/ui.datepicker.js');
$html->use_script('datepicker/header.js');
$html->generate_header();
?>
<form method="post" data-ajax='false'>

	<div class='row'>
		<div class='label' for='title'>Title:</div>
		<div class='input_border'><input class="tag_source" name='title' id='title' value='<?php echo $announcement->heading(); ?>'></div>
	</div>

    <div class="row">
        <div class="label" class="select">Sponsor:</div>
        <div class="input_border">
            <select name="sponsor_ids[]" id="sponsor_ids" multiple>
            <option>Select One:</option>
<?php
$sponsor_ids = $announcement->get_sponsor_ids();
$region_sponsor_ids = Sponsor::get_ids_by_region($region_id);
if (!empty($region_sponsor_ids)) {
    foreach ($region_sponsor_ids as $id) {
        $sponsor = new Sponsor($id);
        $sponsor_name = $sponsor->sponsor_name();
        $selected = in_array($id, $sponsor_ids) ? 'selected' : '';
        echo '<option ' . $selected . ' value="'.$id.'">'.$sponsor_name.'</option>"';
    }
}
?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="label" class="select">Status:</div>
        <div class="input_border">
            <select name="status_id" id="status_id">
            <option>Select One:</option>
<?php
$status_id = $announcement->status_id();
$status_ids = Status::get_ids();
if (!empty($status_ids)) {
    foreach ($status_ids as $id) {
        $status = new Status($id);
        $selected = $id == $status_id ? 'selected' : '';
        echo '<option ' . $selected . ' value="'.$id.'">'.$status->status().'</option>"';
    }
}
?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class='label' for='date_beginning'>Beginning Date:</div>
        <div class="input_border">
            <input type="text" name="begin_month" id="begin-month" class="datepicker-field" value="<?php echo $announcement->date_beginning_parts['month']; ?>" />
            /
            <input type="text" name="begin_day" id="begin-day" class="datepicker-field" value="<?php echo $announcement->date_beginning_parts['day']; ?>" />
            /
            <input type="text" name="begin_year" id="begin-year" class="datepicker datepicker-field" value="<?php echo $announcement->date_beginning_parts['year']; ?>" />
        </div>
    </div>

	<div class='row'>
		<div class='label' for='time_beginning'>Beginning Time (optional):</div>
		<div class='input_border'><input name='time_beginning' id='time_beginning' type='date' class='datebox'  data-options='{"mode": "timebox", "timeFormat": 12,"pickPageButtonTheme":"c" }' /></div>
	</div>

    <div class="row">
        <div class='label' for='date_ending'>Ending Date:</div>
        <div class="input_border">
            <input type="text" name="end_month" id="end-month" class="datepicker-field" value="<?php echo $announcement->date_ending_parts['month']; ?>" />
            /
            <input type="text" name="end_day" id="end-day" class="datepicker-field" value="<?php echo $announcement->date_ending_parts['day']; ?>" />
            /
            <input type="text" name="end_year" id="end-year" class="datepicker datepicker-field" value="<?php echo $announcement->date_ending_parts['year']; ?>" />
        </div>
    </div>

	<div class='row'>
		<div class='label' for='time_ending'>Ending Time (optional):</div>
		<div class='input_border'><input name='time_ending' id='time_ending' type='date' class='datebox'  data-options='{"mode": "timebox", "timeFormat": 12,"pickPageButtonTheme":"c" }' /></div>
	</div>

    <div class="row">
        <div class='label' for='calendared'>Calendared:</div>
        <div class="input_border">
            <input type="text" name="calendared_month" id="calendared-month" class="datepicker-field" value="<?php echo $announcement->calendared_parts['month']; ?>" />
            /
            <input type="text" name="calendared_day" id="calendared-day" class="datepicker-field" value="<?php echo $announcement->calendared_parts['day']; ?>" />
            /
            <input type="text" name="calendared_year" id="calendared-year" class="datepicker datepicker-field" value="<?php echo $announcement->calendared_parts['year']; ?>" />
        </div>
    </div>

    <div class="row">
        <div class='label' for='vote'>Vote:</div>
        <div class="input_border">
            <input type="text" name="vote_month" id="vote-month" class="datepicker-field" value="<?php echo $announcement->vote_parts['month']; ?>" />
            /
            <input type="text" name="vote_day" id="vote-day" class="datepicker-field" value="<?php echo $announcement->vote_parts['day']; ?>" />
            /
            <input type="text" name="vote_year" id="vote-year" class="datepicker datepicker-field" value="<?php echo $announcement->vote_parts['year']; ?>" />
        </div>
    </div>

	<div class='row'>
		<div class='label' for='description'>Description:</div>
		<div class='input_border'><textarea class="tag_source" rows='8' cols='60' name='description' id='description' ><?php echo $announcement->description(); ?></textarea></div>
	</div>

	<div class='row'>
		<div class='label' for='text'>Text:</div>
		<div class='input_border'><textarea class="tag_source" rows='8' cols='60' name='text' id='text' ><?php echo $announcement->text(); ?></textarea></div>
	</div>

	<div class='row'>
		<div class='label' for='question'>Question:</div>
		<div class='input_border'><textarea class="tag_source" rows='2' cols='60' name='question' id='question' ><?php echo $announcement->question(); ?></textarea></div>
	</div>

    <div class="row">
        <div class="label">Custom Tags</div>
        <div class="input_border"><input type="text" name="custom_tag" id="custom_tag" value=""/></div>
    </div>

    <div class="row">
        <div class="label">Chosen Tags</div>
        <div class="input_border"><div id="tag_choices"></div></div>
        <input type="hidden" name="tag_list" id="tag_list" value=""/>
    </div>

    <p style="clear:both"></p>
    <div class="row">
        <div class="label">Suggested Tags</div>
        <div class="input_border"><div id="tag_suggestions"></div></div>
    </div>

    <p style="clear:both"></p>
	<div class='row'>
		<input id="submit" type='submit' value='Save changes'>
	</div>
</form>

<script type="text/javascript">
var Tag = window.Tag || {};

Tag.initialize = function() {
    var init_tags = [];
    Tag.extractTags();
<?php
$init_tags = $announcement->tags();
if (!empty($init_tags)) {
    foreach ($init_tags as $tag_id => $tag) {
        echo "init_tags[$tag_id] = '$tag';\n";
    }
}
?>
    for (k in init_tags) {
        if (typeof init_tags[k] != 'string') continue;
        var el = $('#tag_suggestions div[data-word="'+init_tags[k]+'"]').trigger('click');
        if (!el[0]) {
            Tag.addCustomTag(init_tags[k]);
        }
    }
};


$(document).ready(function() {
    Tag.initialize();
    $('#title').keyup(function() {
        Tag.extractTags();
    });
    $('#description').keyup(function() {
        Tag.extractTags();
    });
    $('#text').keyup(function() {
        Tag.extractTags();
    });
    $('#question').keyup(function() {
        Tag.extractTags();
    });
    $('#custom_tag').blur(function() {
        Tag.addCustomTag($(this).val());
    });
    $('#submit').click(function() {
        Tag.copyTagsToField();
    });
})
</script>
<?php
$html->generate_footer();
?>
