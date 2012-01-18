<?php
class Region extends DBInterface
{
	public function __construct($region_id = 0, $region_type = REGION_CITY)
	{
        if ($region_type == REGION_CITY) {
		    parent::__construct('ref_city', $region_id);
            if ($region_id) {
                $this->name($this->city_name());
            }
        }
        elseif ($region_type == REGION_COUNTY) {
		    parent::__construct('ref_county', $region_id);
            if ($region_id) {
                $this->name($this->county_name());
            }
        }
        elseif ($region_type == REGION_STATE) {
		    parent::__construct('ref_state', $region_id);
            if ($region_id) {
                $this->name($this->state_name());
            }
        }
        elseif ($region_type == REGION_NATION) {
        }
 	}
}
?>
