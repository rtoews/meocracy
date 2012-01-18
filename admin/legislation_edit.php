<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

$region_id = 65517;
$region_type = REGION_CITY;

$id = get_param('id');
$legislation = new Legislation($id);

if (!empty($_POST)) {
    $legislation->region_id($region_id);
    $legislation->region_type($region_type);
    $legislation->title(get_param('title'));
    $legislation->status(get_param('status'));
    $legislation->recommended_action(get_param('recommended_action'));
    $legislation->background(get_param('background'));
    $legislation->discussion(get_param('discussion'));
    $legislation->question(get_param('question'));
    $date_introduced = set_date_parts(get_param('intro_month'), get_param('intro_day'), get_param('intro_year'));
    $date_heard = set_date_parts(get_param('heard_month'), get_param('heard_day'), get_param('heard_year'));
    $legislation->date_introduced($date_introduced);
    $legislation->date_heard($date_heard);
    if ($id) {
        $legislation->update();
    }
    else {
        $id = $legislation->insert();
    }
    // sponsor IDs next
    $sponsor_ids = get_param('sponsor_ids');
    if (!empty($sponsor_ids)) {
        $legislation->associate_sponsors($sponsor_ids);
    }
    // tags next
    $tag_list = get_param('tag_list');
    $tags = explode('|', $tag_list);
    if (!empty($tags)) {
        $legislation->associate_tags($tags);
    }
    else {
        $legislation->disassociate_tags();
    }
    redirect('legislation');
}


$html = new HTML('admin');

$html->set_title('Edit this legislative legislation');
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
<form method="post">

<div class='row'>
    <div class='label' for='title'>Title:</div>
    <div class='input_border'>
        <input class="tag_source" name='title' id='title' value='<?php echo $legislation->title(); ?>'>
    </div>
</div>

<div class="row">
    <div class="label" class="select">Sponsor:</div>
    <div class="input_border">
        <select name="sponsor_ids[]" id="sponsor_ids" multiple>
        <option>Select One:</option>
<?php
$sponsor_ids = $legislation->get_sponsor_ids();
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
    <div class='label' for='date_introduced'>Date Introduced:</div>
    <div class="input_border">
        <input type="text" name="intro_month" id="intro-month" class="datepicker-field" value="<?php echo $legislation->date_introduced_parts['month']; ?>" />
        /
        <input type="text" name="intro_day" id="intro-day" class="datepicker-field" value="<?php echo $legislation->date_introduced_parts['day']; ?>" />
        /
        <input type="text" name="intro_year" id="intro-year" class="datepicker datepicker-field" value="<?php echo $legislation->date_introduced_parts['year']; ?>" />
    </div>
</div>

<div class="row">
    <div class='label' for='date_heard'>Date Heard:</div>
    <div class="input_border">
        <input type="text" name="heard_month" id="heard-month" class="datepicker-field" value="<?php echo $legislation->date_heard_parts['month']; ?>" />
        /
        <input type="text" name="heard_day" id="heard-day" class="datepicker-field" value="<?php echo $legislation->date_heard_parts['day']; ?>" />
        /
        <input type="text" name="heard_year" id="heard-year" class="datepicker datepicker-field" value="<?php echo $legislation->date_heard_parts['year']; ?>" />
    </div>
</div>

<div class='row'>
    <div class='label' for='background'>Background:</div>
    <div class='input_border'>
        <textarea class="tag_source" rows='8' cols='60' name='background' id='background'><?php echo $legislation->background(); ?></textarea>
    </div>
</div>

<div class="row">
    <div class="label" for="discussion">Discussion:</div>
    <div class="input_border">
        <textarea class="tag_source" rows="8" cols="60" name="discussion" id="discussion"><?php echo $legislation->discussion(); ?></textarea>
    </div>
</div>

<div class="row">
    <div class="label" for="discussion">Recommended Action:</div>
    <div class="input_border">
        <textarea class="tag_source" rows="8" cols="60" name="recommended_action" id="recommended_action"><?php echo $legislation->recommended_action(); ?></textarea>
    </div>
</div>

<div class="row">
    <div class="label" for="question">Question:</div>
    <div class="input_border">
        <textarea class="tag_source" rows="8" cols="60" name="question" id="question"><?php echo $legislation->question(); ?></textarea>
    </div>
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
<div class="row">
    <input id="submit" type="submit" value="Save changes">
</div>

</form>

<script type="text/javascript">
var Tag = window.Tag || {};

Tag.initialize = function() {
    var init_tags = [];
    Tag.extractTags();
<?php
$init_tags = $legislation->tags();
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
    $('#background').keyup(function() {
        Tag.extractTags();
    });
    $('#discussion').keyup(function() {
        Tag.extractTags();
    });
    $('#recommended_action').keyup(function() {
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
