<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.office.php" );
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.region.php" );

class Sponsor extends DBInterface
{
	public function __construct( $sponsor_id = 0 )
	{
		parent::__construct( 'sponsor', $sponsor_id );
        if ($sponsor_id) {
            $this->full_name = $this->name_first() . ' ' . $this->name_last();
            $this->region = new Region($this->region_id(), $this->region_type());
            $this->office = new Office($this->office_id());
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
        $src = SPONSOR_IMAGE_PATH . $this->image();
        if (!file_exists(DOC_ROOT . $src)) {
            $src = null;
        }
        return $src;
    }

}
?>
