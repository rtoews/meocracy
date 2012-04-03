<?php
class User_alert_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public static function is_set($user_id, $tag)
    {
        $result = $this->db->select('user_alert_id')->
            from('user_alert ua')->
            join('tag t', 'ua.tag_id = t.tag_id')->
            where('ua.user_id', $user_id)->
            where('t.tag_id', $tag)->
            get()->result();
        $user_alert_id = !empty($result) ? $result[0] : null;

        return $user_alert_id;
    }

    public static function get_all($user_id)
    {
        $alerts = array();
        $result = $this->db->select('tag_id, tag')->
            from('user_alert ua')->
            join('tag t', 'ua.tag_id = t.tag_id')->
            where('user_id', $user_id)->
            get()->result();
        if (!empty($result)) {
            $alerts = $result;
        }
        return $alerts
    }

    public static function keep_selected_alerts($user_id, $alerts)
    {
        if (strlen($alerts) > 0) {
            $keep = $alerts;
            $this->db->where('user_id', $user_id)->
                where_not_in('tag_id', $keep)->
                delete('user_alert');
        }

    }


}
?>
