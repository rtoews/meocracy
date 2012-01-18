<?php
class Tag extends DBInterface
{
	public function __construct($tag_id = 0)
	{
		parent::__construct('tag', $tag_id);
        if ($tag_id) {
        }
 	}


    public static function get_tag_id($tag)
    {
        $sql = sprintf("SELECT tag_id FROM tag WHERE tag='%s'", $tag);
        $tag_id = db()->Get_Cell($sql);
        if (!$tag_id) {
            $new_tag = new Tag();
            $new_tag->tag($tag);
            $tag_id = $new_tag->insert();
        }
        
        return $tag_id;
    }

    public static function get_all($user_id)
    {
        $alerts = array();
        $sql = sprintf("SELECT user_alert_id FROM user_alert WHERE user_id=%d ORDER BY tag", $user_id);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $alert = new User_Alert($row['user_alert_id']);
                $alerts[] = $alert;
            }
        }
        return $alerts;
    }

    public function add()
    {
        $this->status(1);
        $this->create_date(date(TODAY_DATETIME));
        $this->insert();
    }

}
?>
