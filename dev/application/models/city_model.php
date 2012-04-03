<?php
class City_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id)
    {
        $data = null;
        $result = $this->db->
            join('ref_county cty', 'c.county_id=cty.county_id')->
            join('ref_state s', 'c.state_id=s.state_id')->
            where('city_id', $id)->
            get('ref_city c')->result();

        if (!empty($result)) {
            $data = $result[0];
            $announcement_count = $this->db->from('announcement_region')->where('region_id', $data->city_id)->where('region_type', REGION_CITY)->count_all_results();
            $legislation_count = $this->db->from('legislation_region')->where('region_id', $data->city_id)->where('region_type', REGION_CITY)->count_all_results();
            $data->issue_count = array(
                'a_count' => $announcement_count,
                'l_count' => $legislation_count,
            );
        }
        return $data;
    }
}
?>
