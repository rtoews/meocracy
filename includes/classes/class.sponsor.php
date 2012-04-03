<?php
require_once(DOC_ROOT . '/includes/classes/class.office.php');
require_once(DOC_ROOT . '/includes/classes/class.region.php');

class Sponsor extends DBInterface
{
	public function __construct( $sponsor_id = 0 )
	{
		parent::__construct( 'sponsor', $sponsor_id );
        if ($sponsor_id) {
            $region = new Region($this->region_id(), $this->region_type());
            $office = new Office($this->office_id());
            $this->sponsor_id = $sponsor_id;
            $this->office = $this->title();
            $this->full_name = $this->name_first() . ' ' . $this->name_last();
            $this->img_name = $this->image();
            $this->public_image = $this->get_image_src();
            $this->image = $this->get_image_src();
            $this->region = $region->city_name();
        }
 	}


    public static function get_ids_by_region($region_id, $region_type = REGION_CITY)
    {
        $sponsor_id = array();
        $sql = sprintf("SELECT sponsor_id FROM sponsor WHERE region_id=%d AND region_type=%d", $region_id, $region_type);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $sponsor_id[] = $row['sponsor_id'];
            }
        }
        return $sponsor_id;
    }

    public function sponsor_name()
    {
        if ($this->sponsor_type() == 'I') {
            $name_last = $this->name_last();
            $name_first = $this->name_first();
            $name_middle = $this->name_middle();
            $title = $this->title();
            $sponsor_name = $name_first . ' ' . $name_last . ', ' . $title;
        }
        elseif ($this->sponsor_type() == 'O') {
            $office = $this->office();
            $sponsor_name = $office;
        }
        return $sponsor_name;
    }


    public function get_image_src()
    {
        $src = 'missing.png';
        if ($this->image()) {
            $src = SPONSOR_IMAGE_PATH . $this->image();
        }
        return $src;
    }

}
?>
