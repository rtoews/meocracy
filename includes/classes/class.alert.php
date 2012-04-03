<?php
if (isset($DOC_ROOT)) {
    require_once($DOC_ROOT . '/includes/classes/class.smsified.php');
}

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

    public static function delete_unchecked_alerts($user_id, $alerts)
    {
        if (!empty($alerts)) {
            $delete = implode(', ', $alerts);
            $sql = sprintf("DELETE FROM user_alert WHERE user_id=%d AND tag_id IN (%s)", $user_id, $delete);
            db()->query($sql);
        }
            
    }

    private static function get_notification_data()
    {
        $sql = "SELECT aq.alert_queue_id, aq.alert_item_id, 'announcement' AS page, a.title, a.question, u.user_id, u.mobile_phone, u.firstname, u.lastname, t.tag
                FROM alert_queue aq
                JOIN user u ON u.user_id=aq.user_id
                JOIN tag t ON t.tag_id=aq.tag_id
                JOIN announcement a ON aq.alert_item_id=a.announcement_id
                WHERE aq.send_attempted IS NULL
                  AND aq.alert_item_table='announcement'
                UNION
                SELECT aq.alert_queue_id, aq.alert_item_id, 'legislation' AS page, l.title, l.question, u.user_id, u.mobile_phone, u.firstname, u.lastname, t.tag
                FROM alert_queue aq
                JOIN user u ON u.user_id=aq.user_id
                JOIN tag t ON t.tag_id=aq.tag_id
                JOIN legislation l ON aq.alert_item_id=l.legislation_id
                WHERE aq.send_attempted IS NULL
                  AND aq.alert_item_table='legislation'";
        $data = db()->Get_Table($sql);
        return $data;
    }

    private static function _mark_push_status($id, $date, $status)
    {
        $sql = sprintf("UPDATE alert_queue SET send_attempted='%s', success=%d", $date, $status);
        db()->query($sql);
    }

    public static function send_push_notifications()
    {
        $push_recipients = User_Alert::get_notification_data();
        if (!empty($push_recipients)) {
            foreach ($push_recipients as $recip) {
                $queue_id = $recip['alert_queue_id'];
                $sms_recipient = '1' . $recip['mobile_phone'];
                $item_id = $recip['alert_item_id'];
                $html_page = $recip['page'];
                $info = sprintf("Meocracy: %s", $recip['question']);
                $link = 'http://www.meocracy.com/' . $html_page . '.html?id=' . $item_id;
                $tag = $recip['tag'];
                $success = User_Alert::_notify($sms_recipient, $tag, $info, $link);
                $date = date('Y-m-d H:i:s');
                if ($success) {
                    User_Alert::_mark_push_status($queue_id, $date, 1);
                    echo "Wow, it worked!\n\n"; 
                }
                else {
                    User_Alert::_mark_push_status($queue_id, $date, 0);
                    echo "Problem\n";
                }
            }        
        }        
    }

    private static function _notify($address, $tag, $info, $link)
    {
        $sms = new SMSified(SMS_USER, SMS_PWD);
        if ($address) {
            $subject = $tag . ' notification';
            $message = $info . ", " . $link;
            $request = sprintf("address=%s&message=%s", $address, $message);
print "Request: $request\n";
            $url = PUSHURL;
            $response = $sms->sendMessage(SMS_SENDER, $address, $message);
            $responseJson = json_decode($response);
print 'Response:'."\n";
print_r($responseJson);
print "\n";
            $success = $responseJson;
        }
        elseif (0) {
            $contents = array(
                'badge' => '1',
                'alert' => 'New item for ' . $tag,
            ); 
            $push = array('aps' => $contents); 

            $json = json_encode($push); 

            $session = curl_init(PUSHURL); 
            curl_setopt($session, CURLOPT_USERPWD, APPKEY . ':' . PUSHSECRET); 
            curl_setopt($session, CURLOPT_POST, true); 
            curl_setopt($session, CURLOPT_POSTFIELDS, $json); 
            curl_setopt($session, CURLOPT_HEADER, false); 
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); 
            $content = curl_exec($session); 
            echo $content; // just for testing what was sent

            // Check if any error occured 
            $response = curl_getinfo($session); 
            curl_close($session);
            $success = $response['http_code'] == 200;
        }
        return $success;
    }

    public function add()
    {
        $this->status(1);
        $this->create_date(date(TODAY_DATETIME));
        $this->insert();
    }

    public function add_no_dup()
    {
        $sql = sprintf("SELECT user_alert_id FROM user_alert WHERE user_id=%d AND tag_id=%d", $this->user_id(), $this->tag_id());
        $user_alert_id = db()->Get_Cell($sql);
        if (!$user_alert_id) {
            $this->add();
        }
    }
}
?>
