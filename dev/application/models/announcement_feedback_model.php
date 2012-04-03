<?php
class Announcement_feedback_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id)
    {
        $result = $this->db->where('announcement_feedback_id', $id)->get('announcement_feedback')->result();
        $data = !empty($result) ? $result[0] : null;
        return $data;
    }
}
?>
