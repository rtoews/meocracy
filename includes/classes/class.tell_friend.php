<?php
class Tell_Friend extends DBInterface
{
	public function __construct($tf_id = 0)
	{
		parent::__construct('tell_friend', $tf_id);
 	}

    public static function get_data($code)
    {
        $sql = "SELECT phone, user_id, city_id, issue_type, issue_id FROM tell_friend WHERE code='$code' AND retrieved_date IS NULL";
        $data = db()->Get_Row($sql);
        if (!empty($data)) {
            $sql = "UPDATE tell_friend SET retrieved='1' WHERE code='$code'";
            db()->query($sql);
        }
        return $data;
    }

}
?>
