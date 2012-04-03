<?php
class Legislation_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id)
    {
        $result = $this->db->where('legislation_id', $id)->get('legislation')->result();
        $data = !empty($result) ? $result[0] : null;
        return $data;
    }
}
?>

