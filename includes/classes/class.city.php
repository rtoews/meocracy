<?php
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation.php');

class City extends DBInterface
{
	public function __construct($city_id = 0)
	{
		parent::__construct( 'ref_city', $city_id );
        if ($city_id) {
            $this->county = new County($this->county_id());
            $this->state = new State($this->state_id());
            $this->masthead_city_of('City of ' . $this->city_name());
            $this->masthead_city_state($this->city_name() . ', ' . $this->state->state_name());
            $this->city_full_name($this->city_name() . ', ' . $this->state_abbr());
            $this->masthead_logo = array(
                REGION_CITY => $this->image_tag(),
                REGION_COUNTY => $this->county->image_tag(),
                REGION_STATE => $this->state->image_tag(),
            );
            $this->masthead = array(
                REGION_CITY => 'City of ' . $this->city_name() . ', ' . $this->state->state_name(),
                REGION_COUNTY => $this->county->county_name() . ' County',
                REGION_STATE => 'State of ' . $this->state->state_name(),
            );
            $this->issue_count = array(
                REGION_CITY => $this->count_issues(REGION_CITY),
                REGION_COUNTY => $this->count_issues(REGION_COUNTY),
                REGION_STATE => $this->count_issues(REGION_STATE),
            );
//            $announcements = $this->get_announcements();
//            $this->announcements($announcements);    
        }
 	}
		

    private function count_issues($region_type = REGION_CITY)
    {
        $announcement_count = 0;
        $legislation_count = 0;
        if ($region_type == REGION_STATE) {
            $region_id = $this->state_id();
        }
        elseif ($region_type == REGION_COUNTY) {
            $region_id = $this->county_id();
        }
        else {
            $region_id = $this->id();
        }
        if ($region_id) {
            $sql = sprintf(
                "SELECT COUNT(ar.announcement_id) AS c FROM announcement_region ar 
                  WHERE ar.region_id=%d
                    AND ar.region_type=%d", $region_id, $region_type);
            $announcement_count = db()->Get_Cell($sql);
            $sql = 
                "SELECT COUNT(lr.legislation_id) AS c FROM legislation_region lr 
                   JOIN legislation l ON l.legislation_id=lr.legislation_id 
                  WHERE lr.region_id='$region_id'
                    AND lr.region_type='$region_type'
                    AND (l.current_location LIKE '%CS%' OR l.current_location LIKE '%CX%')";
            $legislation_count = db()->Get_Cell($sql);
        }
        $counts = array(
            'a_count' => $announcement_count,
            'l_count' => $legislation_count
        );
        return $counts;
    }


    public static function get_city_list_by_zip($zip)
    {
        $records = array();
        $sql = "SELECT city_id FROM ref_city WHERE zip='$zip' ORDER BY city_name";
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $record = new City($row['city_id']);
                $record->retrieve_record($row['city_id']);
                $records[] = array('key' => $record->id(), 'value' => $record->city_name(), 'county_id' => $record->county_id(), 'state_id' => $record->state_id());
            }
        }
        return $records; 
    }


    public function get_issues($filter)
    {
        if (preg_match('/^C[SX]\d{2}$/', $filter, $matches)) {
            $sql = 
                "SELECT l.legislation_id, created_date FROM legislation l
                   JOIN legislation_region lr ON lr.legislation_id = l.legislation_id
                  WHERE l.current_location='".$filter."'
                    AND (lr.region_id=".$this->id()." AND lr.region_type=4 OR lr.region_id=".$this->county_id()." AND lr.region_type=3 OR lr.region_id=".$this->state_id()." AND lr.region_type=2)
               ORDER BY created_date DESC";
        }
        else {
            $sql = 
                "SELECT l.legislation_id, created_date FROM legislation l
                   JOIN category c ON l.current_location=c.lc_category
                   JOIN legislation_region lr ON lr.legislation_id = l.legislation_id
                  WHERE c.lc_parent_id=".$filter."
                    AND (lr.region_id=".$this->id()." AND lr.region_type=4 OR lr.region_id=".$this->county_id()." AND lr.region_type=3 OR lr.region_id=".$this->state_id()." AND lr.region_type=2)
               ORDER BY created_date DESC";
        }
        $issues = array();
        $key_table = db()->Get_Table($sql);
        if (!empty($key_table)) {
            foreach ($key_table as $key_row) {
                $issue = new Legislation($key_row['legislation_id']);
                $issues[] = array('issue' => $issue, 'type' => 2);
            }
        }

        return $issues;
    }


    public function get_announcements($region_type = REGION_CITY)
    {
        $announcements = null;
        if ($region_type == REGION_STATE) {
            $region_id = $this->state_id();
        }
        elseif ($region_type == REGION_COUNTY) {
            $region_id = $this->county_id();
        }
        else {
            $region_id = $this->id();
        }
        if ($region_id) {
            $announcements = array();
            $sql = sprintf(
                "SELECT a.announcement_id FROM announcement a
                   JOIN announcement_region ar ON ar.announcement_id = a.announcement_id
                  WHERE ar.region_id=%d
                    AND ar.region_type=%d
                 ORDER BY a.created_date DESC, a.announcement_id", $region_id, $region_type);
            $key_table = db()->Get_Table($sql);
            if (!empty($key_table)) {
                foreach ($key_table as $key_row) {
                    $ann = new Announcement($key_row['announcement_id']);
                    $announcements[] = $ann;
                }
            }
        }
        return $announcements;
    }


    public function get_announcements_search($search)
    {
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
                        $this->id(), REGION_CITY,
                        $this->county_id(), REGION_COUNTY,
                        $this->state_id(), REGION_STATE);
        $key_table = db()->Get_Table($sql);
        if (!empty($key_table)) {
            foreach ($key_table as $key_row) {
                $ann = new Announcement($key_row['announcement_id']);
                $announcements[] = $ann;
            }
        }
        return $announcements;
        
    }

    public function get_legislation_categories($region_type = REGION_CITY)
    {
        $lc = null;
        if ($region_type == REGION_STATE) {
            $region_id = $this->state_id();
        }
        elseif ($region_type == REGION_COUNTY) {
            $region_id = $this->county_id();
        }
        else {
            $region_id = $this->id();
        }
        if ($region_id) {
            $lc = array();
            $sql = "SELECT lc_id FROM category WHERE lc_parent_id IS NULL ORDER BY lc_category";
            $data = db()->Get_Table($sql);
            foreach ($data as $row) {
                $lc[] = new Legislation_Category($row['lc_id']);
            }
        }
        return $lc;
    }


    public function get_legislation($region_type = REGION_CITY, $category_id = 0)
    {
        $legislation = null;
        if ($region_type == REGION_STATE) {
            $region_id = $this->state_id();
        }
        elseif ($region_type == REGION_COUNTY) {
            $region_id = $this->county_id();
        }
        else {
            $region_id = $this->id();
        }
        if ($region_id) {
            $legislation = array();
            $sql = sprintf(
                "SELECT l.legislation_id FROM legislation l
                   JOIN legislation_region lr ON lr.legislation_id = l.legislation_id
                   JOIN category lc ON l.current_location=lc.lc_category
                  WHERE lr.region_id=%d
                    AND lr.region_type=%d
                    AND lc.lc_parent_id=%d
                 ORDER BY l.created_date DESC, l.legislation_id ", $region_id, $region_type, $category_id);
            $key_table = db()->Get_Table($sql);
            if (!empty($key_table)) {
                foreach ($key_table as $key_row) {
                    $leg = new Legislation($key_row['legislation_id']);
                    $legislation[] = $leg;
                }
            }
        }
        return $legislation;
    }


    public function image_src()
    {
        $src = null;
        if ($this->image()) {
            $src = LOGO_PATH . $this->image();
        }
        return $src;
    }


    public function image_tag()
    {
        $img_src = null;
        if ($this->image()) {
            $src = LOGO_PATH . $this->image();
        }
        if (file_exists(DOC_ROOT . $src)) {
            $img_tag = '<img src="' . $src . '"/>';
        }
        return $img_tag;
    }
}


class County extends DBInterface
{
	public function __construct($county_id = 0)
	{
		parent::__construct( 'ref_county', $county_id );
    }


    public function image_src()
    {
        $src = null;
        if ($this->image()) {
            $src = LOGO_PATH . $this->image();
        }
        return $src;
    }


    public function image_tag()
    {
        $img_tag = null;
        if ($this->image()) {
            $src = LOGO_PATH . $this->image();
        }
        if (file_exists(DOC_ROOT . $src)) {
            $img_tag = '<img src="' . $src . '"/>';
        }
        return $img_tag;
    }
}


class State extends DBInterface
{
	public function __construct($state_id = 0)
	{
		parent::__construct( 'ref_state', $state_id );
    }


    public function image_src()
    {
        $src = null;
        if ($this->image()) {
            $src = LOGO_PATH . $this->image();
        }
        return $src;
    }


    public function image_tag()
    {
        $img_src = null;
        if ($this->image()) {
            $src = LOGO_PATH . $this->image();
        }
        if (file_exists(DOC_ROOT . $src)) {
            $img_tag = '<img src="' . $src . '"/>';
        }
        return $img_tag;
    }
}
