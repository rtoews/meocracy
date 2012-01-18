<?php
class Status extends DBInterface
{
	public function __construct($status_id = 0)
	{
		parent::__construct('status', $status_id);
 	}


    public static function get_ids()
    {
        $status_id = array();
        $sql = sprintf("SELECT status_id FROM status");
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $status_id[] = $row['status_id'];
            }
        }
        return $status_id;
    }

}
?>
