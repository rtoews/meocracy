<?php
class User_Alert extends DBInterface
{
	public function __construct($user_alert_id = 0)
	{
		parent::__construct('user_alert', $user_alert_id);
        if ($user_alert_id) {
            $this->tag = new Tag($this->tag_id());
        }
 	}


    public static function is_set($user_id, $tag)
    {
        $sql = sprintf("SELECT user_alert_id FROM user_alert ua JOIN tag t on ua.tag_id=t.tag_id WHERE ua.user_id=%d AND t.tag='%s'", $user_id, $tag);
        $user_alert_id = db()->Get_Cell($sql);
        
        return $user_alert_id;
    }

    public static function get_all($user_id)
    {
        $alerts = array();
        $sql = sprintf("SELECT user_alert_id FROM user_alert ua JOIN tag t ON ua.tag_id = t.tag_id WHERE user_id=%d ORDER BY t.tag", $user_id);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $alert = new User_Alert($row['user_alert_id']);
                $alerts[] = $alert;
            }
        }
        return $alerts;
    }

    public static function keep_selected_alerts($user_id, $alerts)
    {
        if (!empty($alerts)) {
            $keep = implode(', ', $alerts);
            $sql = sprintf("DELETE FROM user_alert WHERE user_id=%d AND tag_id NOT IN (%s)", $user_id, $keep);
            db()->query($sql);
        }
            
    }

    public function add()
    {
        $this->status(1);
        $this->create_date(date(TODAY_DATETIME));
        $this->insert();
    }

}
?>
