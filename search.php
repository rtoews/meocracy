<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/global.php');
require_once(DOC_ROOT . '/includes/classes/class.html.php');
require_once(DOC_ROOT . '/includes/classes/class.user.php');

$user_id = get_param('user_id');
$user = new User($user_id);

if (!empty($_POST)) {
    $search_announcements = get_param('announcements');
    $search_legislation = get_param('legislation');
    $search_fulltext = get_param('fulltext');
    $search = preg_replace('/[^a-z0-9]/i', '', get_param('search'));
    if ($search_announcements) {
        $sql = sprintf("SELECT at.announcement_id FROM announcement_tag at 
                        JOIN announcement_region ar ON ar.announcement_id=at.announcement_id
                        JOIN tag t ON at.tag_id=t.tag_id 
                        WHERE t.tag='%s'
                        AND 
                        (
                         ar.region_id=%d AND ar.region_type=%d
                         OR ar.region_id=%d AND ar.region_type=%d
                         OR ar.region_id=%d AND ar.region_type=%d
                        )", 
                        $search, 
                        $user->city_id, REGION_CITY,
                        $user->county_id, REGION_COUNTY,
                        $user->state_id, REGION_STATE);
        $data = db()->Get_Table($sql);
    }
    if ($search_legislation) {
        $sql = sprintf("SELECT lt.legislation_id FROM legislation_tag lt 
                        JOIN legislation_region lr ON lr.legislation_id=lt.legislation_id
                        JOIN tag t ON lt.tag_id=t.tag_id 
                        WHERE t.tag='%s'
                        AND
                        (
                         lr.region_id=%d AND lr.region_type=%d
                         OR lr.region_id=%d AND lr.region_type=%d
                         OR lr.region_id=%d AND lr.region_type=%d
                        )", 
                        $search,
                        $user->city_id, REGION_CITY,
                        $user->county_id, REGION_COUNTY,
                        $user->state_id, REGION_STATE);
        $data = db()->Get_Table($sql);
    }
    if ($search_fulltext) {
        if ($search_announcements) {
            $sql = "SELECT a.announcement_id FROM announcement a
                    WHERE a.heading LIKE '%" . $search . "%'
                    OR a.description LIKE '%" . $search . "%'
                    OR a.text LIKE '%" . $search . "%'
                    OR a.question LIKE '%" . $search . "%'
                    ";
            $data = db()->Get_Table($sql);
        }
        if ($search_legislation) {
            $sql = "SELECT l.legislation_id FROM legislation l
                    WHERE l.title LIKE '%" . $search . "%'
                    OR l.recommended_action LIKE '%" . $search . "%'
                    OR l.background LIKE '%" . $search . "%'
                    OR l.discussion LIKE '%" . $search . "%'
                    OR l.question LIKE '%" . $search . "%'
                    ";
            $data = db()->Get_Table($sql);
        }
    }
}

$html = new HTML();
$html->set_title('Settings');
//$html->generate_header_mobile();
?>
<p>Enter search term</p>
<form method="post">
    <input type="text" id="search" name="search" value=""/><br/>
    <input type="checkbox" id="announcements" name="announcements" value=1" checked /> Announcements<br/>
    <input type="checkbox" id="legislation" name="legislation" value=1" checked /> Legislation<br/>
    <input type="checkbox" id="fulltext" name="fulltext" value=1" checked /> Search Full Text<br/>
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
    <input type="submit" value="Search"/>
</form>
<script type="text/javascript">
</script>
<?php
//$html->generate_footer_mobile();
?>
