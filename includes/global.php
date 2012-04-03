<?php
$DOC_ROOT = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] : $_SERVER['HOME'] . '/public_html/meocracy';
require_once($DOC_ROOT . '/includes/config.php');
require_once(DOC_ROOT . '/includes/classes/class.database.php');
require_once(DOC_ROOT . '/includes/classes/class.db_interface.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

/*
    EVERY CLASS NEEDS THE USER ID, WHICH IS RETRIEVED USING THE DEVICE ID
*/
if (PHONEGAP) {
    $device_id = get_param('uuid');

    if (!$device_id || $device_id == 'test') {
        $user_id = 7;
    }
    else {
        $user_id = User::get_user_id_by_device($device_id);
    }
}
else {
    $user_id = User::get_user_id();
}


/*
define(LOG_FILE, DOC_ROOT . '/log/log_file.txt');
function log_time($point = '')
{
    $log_data = date('U') . $point . "\n";
    file_put_contents(LOG_FILE, $log_data, FILE_APPEND);
}
*/

function remove_accents($str)
{
    setlocale(LC_ALL, 'en_US.utf8');
    $output = iconv('utf-8', 'ascii//TRANSLIT', $str);
    return $output;
}

function fix_name($str)
{
    $str = str_replace(' ', '', $str);
    $str = str_replace("'", '', $str);
    $str = str_replace('.', '_', $str);
    return $str;
}

function return_jsonp_data($data)
{
    $callback = get_param('callback');
    echo $callback . '(' . json_encode($data) . ')';
}


function gen_confirmid() {
  $idlength = 6;
  $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
  $counter = 0;
  $unique = 0;
  $id = '';
  while (strlen($id) < $idlength) {
    $n = intval(.5 + rand(0, strlen($chars) - 1));
    $c = substr($chars, $n, 1);
    $id .= $c;
  }
  return $id;
}


function get_param($param)
{
    $value = null;
    if (!empty($_GET[$param])) {
        $value = $_GET[$param];
    }
    elseif (!empty($_POST[$param])) {
        $value = $_POST[$param];
    }
    elseif (!empty($_SESSION[$param])) {
        $value = $_SESSION[$param];
    }
    if (is_string($value)) {
        $value = stripslashes($value);
    }
    return $value;
}


function get_date_parts($date)
{
    $tmp = explode('-', $date);
    $parts['month'] = $tmp[1];
    $parts['day'] = $tmp[2];
    $parts['year'] = $tmp[0];
    return $parts;
}

function set_date_parts($m, $d, $y)
{
    $date_str = "$y-$m-$d";
    return $date_str;
}

function format_name($first, $last, $suffix = '', $salutation = '')
{
    if ($salutation > '') {
        $first = $salutation . ' ' . $first;
    }
    $formatted = $last . ', ' . $first;
    if ($suffix > '') {
        $formatted .= ' ' . $suffix;
    }

    return $formatted;
}

function format_phone($str)
{
    $area = substr($str, 0, 3);
    $pre = substr($str, 3, 3);
    $sln = substr($str, 6, 4);
    $formatted = sprintf('(%s) %s-%s', $area, $pre, $sln);
    return $formatted;
}

/*-------------------------- DrawVotesChart draws the Google Charts with rules and is included in the loop above -------------------------- */

function draw_votes_chart($id,$table,$entity_id,$chart_question,$feedback_support,$feedback_oppose,$feedback_total,$feedback_average ) {
    $chart_question = str_replace("'", "\'", $chart_question);

    // Echo Votes Chart
    $votes_chart = '<!-- Support/Oppose chart -->' . HTML_EOL;
    $votes_chart .= '<script type="text/javascript">' . HTML_EOL;
    $votes_chart .= 'google.load("visualization", "1", {packages:["corechart"]});' . HTML_EOL;
    $votes_chart .= 'google.setOnLoadCallback(drawChart);' . HTML_EOL;
    $votes_chart .= 'function drawChart() {' . HTML_EOL;
    $votes_chart .= '   var data = new google.visualization.DataTable();' . HTML_EOL;
    $votes_chart .= '   data.addColumn("string", "Votes");' . HTML_EOL;
    $votes_chart .= '   data.addColumn("number", "Support");' . HTML_EOL;
    $votes_chart .= '   data.addColumn("number", "Oppose");' . HTML_EOL;
    $votes_chart .= '   data.addRows([' . HTML_EOL;
    $votes_chart .= '       ["", '.$feedback_support.', '.$feedback_oppose.'],' . HTML_EOL;
    $votes_chart .= '   ]);' . HTML_EOL;
    $votes_chart .= '   var options = {' . HTML_EOL;
    $votes_chart .= '   animation:{duration: 2000},' . HTML_EOL;
    $votes_chart .= '   legend: {position:"none"},' . HTML_EOL;
    $votes_chart .= '   axisTitlesPosition:"none",' . HTML_EOL;
    $votes_chart .= '   width:260, height:70,' . HTML_EOL;
    $votes_chart .= '   title: "'.$chart_question.'",' . HTML_EOL;
    $votes_chart .= '   colors:["#5cbc6a","#ee2700"],' . HTML_EOL;
    $votes_chart .= '   backgroundColor:"transparent",' . HTML_EOL;
    $votes_chart .= '   vAxis:{ textPosition:"none" },' . HTML_EOL;
    $votes_chart .= '   isStacked:"false"' . HTML_EOL;
    $votes_chart .= '};' . HTML_EOL;
    $votes_chart .= 'var chart = new google.visualization.BarChart(document.getElementById("chart_feedback_'.$id.'"));' . HTML_EOL;
    $votes_chart .= '   chart.draw(data, options);' . HTML_EOL;
    $votes_chart .= '}' . HTML_EOL;
    $votes_chart .= '</script>' . HTML_EOL;
    $votes_chart .= '<div id="chart_feedback_'.$id.'" class="chart"></div>' . HTML_EOL;

    echo $votes_chart;

// Echo Voting popularity Chart

    $voting_popularity_chart = '<!-- Support/Oppose chart -->' . HTML_EOL;
    $voting_popularity_chart .= '<script type="text/javascript">' . HTML_EOL;
    $voting_popularity_chart .= 'google.load("visualization", "1", {packages:["corechart"]});' . HTML_EOL;
    $voting_popularity_chart .= 'google.setOnLoadCallback(drawChart);' . HTML_EOL;
    $voting_popularity_chart .= 'function drawChart() {' . HTML_EOL;
    $voting_popularity_chart .= '   var data = new google.visualization.DataTable();' . HTML_EOL;
    $voting_popularity_chart .= '   data.addColumn("string", "Totals");' . HTML_EOL;
    $voting_popularity_chart .= '   data.addColumn("number", "Votes");' . HTML_EOL;
    $voting_popularity_chart .= '   data.addColumn("number", "Views");' . HTML_EOL;
    $voting_popularity_chart .= '   data.addRows([' . HTML_EOL;
    $voting_popularity_chart .= '       ["", '.$feedback_total.', '.$feedback_average.'],' . HTML_EOL;
    $voting_popularity_chart .= '   ]);' . HTML_EOL;
    $voting_popularity_chart .= '   var options = {' . HTML_EOL;
    $voting_popularity_chart .= '   animation:{duration: 2000},' . HTML_EOL;
    $voting_popularity_chart .= '   legend: {position:"none"},' . HTML_EOL;
    $voting_popularity_chart .= '   axisTitlesPosition:"none",' . HTML_EOL;
    $voting_popularity_chart .= '   width:260, height:70,' . HTML_EOL;
    $voting_popularity_chart .= '   title: "Voting popularity",' . HTML_EOL;
    $voting_popularity_chart .= '   colors:["#5A80C6","#8C8C8C"],' . HTML_EOL;
    $voting_popularity_chart .= '   backgroundColor:"transparent",' . HTML_EOL;
    $voting_popularity_chart .= '   vAxis:{ textPosition:"none" },' . HTML_EOL;
    $voting_popularity_chart .= '   isStacked:"false"' . HTML_EOL;
    $voting_popularity_chart .= '};' . HTML_EOL;
    $voting_popularity_chart .= 'var chart = new google.visualization.BarChart(document.getElementById("chart_total_'.$id.'"));' . HTML_EOL;
    $voting_popularity_chart .= 'chart.draw(data, options);' . HTML_EOL;
    $voting_popularity_chart .= '}' . HTML_EOL;
    $voting_popularity_chart .= '</script>' . HTML_EOL;
    $voting_popularity_chart .= '<div id="chart_total_'.$id.'" class="chart"></div>' . HTML_EOL;

    echo $voting_popularity_chart;
}


// Truncate descriptions, titles for ul/li blocks

function my_truncate($input, $numwords, $padding = '')
{
    $output = strtok($input, " \n");
    while(--$numwords > 0) $output .= " " . strtok(" \n");
    if($output != $input) $output .= $padding;
    return $output;
}

function redirect($url)
{
    header('Location: ' . $url);
    exit();
}
?>
