<?php
class User_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_by_id($id)
    {
        $result = $this->db->
            from('user u')->
            join('user_region ur', 'u.user_id = ur.user_id')->
            where('u.user_id', $id)->
            order_by('ur.updated_date', 'desc')->
            get()->result();
                        
        $data = !empty($result) ? $result[0] : null;
        return $data;
    }

}
?>
