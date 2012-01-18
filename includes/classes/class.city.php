<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.announcement.php" );
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.legislation.php" );

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
//            $announcements = $this->get_announcements();
//            $this->announcements($announcements);    
        }
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
/*
    public function get_announcements($city_id = 0)
    {
        $announcements = null;
        if (!$city_id) {
            $city_id = $this->id();
        }
        if ($city_id) {
            $announcements = array();
            $sql = sprintf(
                "SELECT a.announcement_id FROM announcement a
                   JOIN announcement_region ar ON ar.announcement_id = a.announcement_id
                  WHERE ar.region_id=%d
                    AND ar.region_type=%d
                 ORDER BY a.created_date DESC", $city_id, REGION_CITY);
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
*/
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
                 ORDER BY a.created_date DESC", $region_id, $region_type);
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


    public function get_legislation($region_type = REGION_CITY)
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
                  WHERE lr.region_id=%d
                    AND lr.region_type=%d
                 ORDER BY l.created_date DESC", $region_id, $region_type);
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
