<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
log_time('at the beginning');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.city.php');

$user_id = get_param('user_id');
if (!$user_id) {
    redirect('/login.php');
}

$region_type = get_param('type');

$user_region = new UserRegion();
//$city = $user_region->get_region_for_user($user_id);
$city = $user_region->get_city($user_id);

$html = new HTML();
$html->set_title('City Home');
$html->generate_header_mobile();
?>
<ul data-role='listview' data-inset='false' data-theme='a'  data-divider-theme='a'>
    <li>
        <?php echo $city->masthead_logo[$region_type]; ?>
        <?php echo $city->masthead[$region_type]; ?><br/>
    </li>
    <li data-theme="e">General Information</li>
    <li data-theme="e">Announcements</li>

<?php
$announcements = $city->get_announcements($region_type);
if (!empty($announcements)) {
    foreach ($announcements as $ann) {
        if ($ann->feedback_submitted($user_id)) {
            $output  = '<li>' . HTML_EOL;
            $output .= '    <a href="/announcement_feedback_response.php?t=' . $region_type . '&id=' . $ann->id() . '">' . HTML_EOL;
            $output .= '        <img src="' . $ann->get_image_src() . '" class="ui-li-thumb"/>' . HTML_EOL;
            $output .= '        ' . $ann->title() . HTML_EOL;
            $output .= '    </a>' . HTML_EOL;
            $output .= '</li>' . HTML_EOL;
        }
        else {
            $output  = '<li>' . HTML_EOL;
            $output .= '    <a href="/announcement.php?t=' . $region_type . '&id=' . $ann->id() . '">' . HTML_EOL;
            $output .= '        <img src="' . $ann->get_image_src() . '" class="ui-li-thumb"/>' . HTML_EOL;
            $output .= '        ' . $ann->title() . HTML_EOL;
            $output .= '    </a>' . HTML_EOL;
            $output .= '</li>' . HTML_EOL;
        }
        echo $output;
    }
}
else {
    echo "<li>No Announcements.</li>" . HTML_EOL;
}
?>
    <li data-theme="e">Legislation</li>
<?php
$legislation = $city->get_legislation($region_type);
if (!empty($legislation)) {
    foreach ($legislation as $leg) {
        if ($leg->feedback_submitted($user_id)) {
            $output  = '<li>' . HTML_EOL;
            $output .= '    <a href="/legislation_feedback_response.php?t=' . $region_type . '&id=' . $leg->id() . '">' . HTML_EOL;
//            $output .= '        <img src="' . $leg->get_image_src() . '" class="ui-li-thumb"/>' . HTML_EOL;
            $output .= '        ' . $leg->title() . HTML_EOL;
            $output .= '    </a>' . HTML_EOL;
            $output .= '</li>' . HTML_EOL;
        }
        else {
            $output  = '<li>' . HTML_EOL;
            $output .= '    <a href="/legislation.php?t=' . $region_type . '&id=' . $leg->id() . '">' . HTML_EOL;
//            $output .= '        <img src="' . $leg->get_image_src() . '" class="ui-li-thumb"/>' . HTML_EOL;
            $output .= '        ' . $leg->title() . HTML_EOL;
            $output .= '    </a>' . HTML_EOL;
            $output .= '</li>' . HTML_EOL;
        }
        echo $output;
    }
}
else {
    echo "<li>No Legislation.</li>" . HTML_EOL;
}

?>
</ul>
<?php
$html->generate_footer_mobile();
/*
$page = ob_get_clean();
$template = file_get_contents('includes/mobile_template.php');
$css = 'city_home.css';
$replace = array(
    'from' => array('%CSS%', '%TITLE%', '%H1%', '%CONTENT%'),
    'to' => array($css, $UIText['city_home']['page_title'], $city->city_name_full(), $page)
);
$output = str_replace($replace['from'], $replace['to'], $template);

if (1) {
    $replace = array(
        'from' => array('%IMG%', '%MASTHEAD_LINE1%', '%MASTHEAD_LINE2%'),
        'to' => array($city->img_src(), $city->masthead_city_of(), $city->masthead_city_state())
    );
    $output = str_replace($replace['from'], $replace['to'], $output);
}

print $output;
*/
?>
