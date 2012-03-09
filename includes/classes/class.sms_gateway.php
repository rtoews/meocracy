<?php
class SMS_Gateway extends DBInterface
{
	public function __construct($sms_id = 0)
	{
		parent::__construct('sms_gateway', $sms_id);
 	}


    public static function get_all()
    {
        $carriers = array();
        $sql = sprintf("SELECT sms_id FROM sms_gateway ORDER BY carrier");
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $carrier = new SMS_Gateway($row['sms_id']);
                $carriers[] = $carrier;
            }
        }
        return $carriers;
    }

    public static function get_carrier($id)
    {
        $sms = new SMS_Gateway($id);
        return $sms->carrier();
    }
}
?>
