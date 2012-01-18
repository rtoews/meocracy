<?php

class Office extends DBInterface
{
	public function __construct( $office_id = 0 )
	{
		parent::__construct( 'ref_office', $office_id );
 	}

    public static function get_ids_by_region($region_id, $region_type = REGION_CITY)
    {
        $office_id = array();
        $sql = sprintf("SELECT office_id FROM ref_office WHERE region_id=%d AND region_type=%d", $region_id, $region_type);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $office_id[] = $row['office_id'];
            }
        }
        return $office_id;
    }

}
?>
