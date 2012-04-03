<?php
class County_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id)
    {
        $data = null;
        $result = $this->db->where('county_id', $id)->get('ref_county')->result();
        if (!empty($result)) {
            $data = $result[0];
            $announcement_count = $this->db->from('announcement_region')->where('region_id', $data->county_id)->where('region_type', REGION_COUNTY)->count_all_results();
            $legislation_count = $this->db->from('legislation_region')->where('region_id', $data->county_id)->where('region_type', REGION_COUNTY)->count_all_results();
            $data->issue_count = array(
                'a_count' => $announcement_count,
                'l_count' => $legislation_count,
            );
        }
        return $data;
    }
}
?>
