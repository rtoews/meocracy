<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');

require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');

$announcement_id = get_param('id');
if ($announcement_id) {
    $announcement = new Announcement($announcement_id);
}
else {
    $announcement = new Announcement();
}

if (!empty($_POST)) {
    // announcement data first
    $announcement->heading(get_param('heading'));
    $announcement->description(get_param('description'));
    $calendared = get_param('calendared');
    $announcement->calendared($calendared);
    $vote = get_param('vote');
    $announcement->vote($vote);
    $fiscal = get_param('fiscal');
    $announcement->fiscal_impact($vote);
    $staffing = get_param('staffing');
    $announcement->staffing_impact($vote);
    if ($announcement_id) {
        $announcement->update();
    }
    else {
        $announcement_id = $announcement->insert();
    }
    // tags second
    $tag_list = get_param('tag_list');
    $tags = explode('|', $tag_list);
print '<pre>';
print_r($tags);
print '</pre>';
    if (!empty($tags)) {
        $announcement->associate_tags($tags);
    }
    else {
        $announcement->disassociate_tags();
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Announcements</title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="/includes/css-mobile/global.css"/>
    <script type="text/javascript" src="/jquery/jquery-1.6.4.js"></script>
    <style type="text/css">
        #tag_choices {
            background:#eee;
            width:300px;
            height:50px;
        }
        .tag_wrap {
            float:left;
            border:1px solid gray;
            border-radius:2px 2px 2px 2px;
            padding:3px;
            background:gray;
            margin:3px;
            cursor:pointer;
        }
        .tag_wrap span {
            font:10px verdana;
            color:white;
            text-align:center;
        }
    </style>
</head>
<body>
<form method="post">
<label for="heading">Title</label><input type="text" name="heading" id="heading" value="<?php echo $announcement->heading(); ?>" class="tag_source"/><br/>
<label for="calendared">Calendared Date</label><input type="text" name="calendared" id="calendared" value="<?php echo $announcement->calendared(); ?>"/><br/>
<label for="vote">Vote Date</label><input type="text" name="vote" id="vote" value="<?php echo $announcement->vote(); ?>"/><br/>
<label for="fiscal">Fiscal Impact</label><input type="text" name="fiscal" id="fiscal" value="<?php echo $announcement->fiscal(); ?>"/><br/>
<label for="staffing">Staffing Impact</label><input type="text" name="staffing" id="staffing" value="<?php echo $announcement->staffing(); ?>"/><br/>
<label for="description">Description</label><textarea name="description" id="description" class="tag_source"><?php echo $announcement->description(); ?></textarea><br/>
<label for="tags">Tags</label><input type="text" name="custom_tag" id="custom_tag" value=""/><br/>
<div id="tag_choices"></div>
<input type="hidden" name="tag_list" id="tag_list" value=""/>

<p style="clear:both"></p>
<div id="tag_suggestions"></div>

<p style="clear:both"></p>
<input type="submit" id="submit" value="Submit" />
</form>
<script type="text/javascript" src="/admin/includes/stopwords.js"></script>
<script type="text/javascript">
var Tag = {};

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

</script>
</body>
</html>
