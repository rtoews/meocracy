<?php
class Announcement_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id)
    {
        $result = $this->db->where('announcement_id', $id)->get('announcement')->result();
        $data = !empty($result) ? $result[0] : null;
        return $data;
    }
}
?>
