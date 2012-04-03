<?php
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

class Announcement_Feedback extends DBInterface
{
    public function __construct($feedback_id = 0)
    {
        parent::__construct('announcement_feedback', $feedback_id);
        if ($feedback_id) {
            $this->user = new User($this->user_id());
        }
    }

    private function _already_responded()
    {
        $sql = sprintf("SELECT feedback_id FROM announcement_feedback WHERE user_id=%d AND announcement_id=%d", $this->user_id(), $this->announcement_id());
        $feedback_id = db()->Get_Cell($sql);
        $result = !empty($feedback_id) ? $feedback_id : false;
        return $result;
    }

    public function record_response($announcement_id)
    {
        $this->announcement_id($announcement_id);
        $feedback_id = $this->_already_responded();;
        if (!$feedback_id) {
            $new_response = true;
            $this->feedback_date(TODAY_DATETIME);
            $feedback_id = $this->insert();
        }
        return $feedback_id;
    }

    public static function get_feedback_count()
    {
        $sql = "SELECT COUNT(*) total_feedback FROM announcement_feedback";
        $total_feedback = db()->Get_Cell($sql);
        return $total_feedback;
    }

    public static function get_feedback($issue_id)
    {
        $feedback = array();
        $sql = sprintf("SELECT feedback_id FROM announcement_feedback WHERE announcement_id=%d", $this->announcement_id());
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $feedback[] = new Announcement_Feedback($row['feedback_id']);
            }
        }
        return $feedback;
    }

    public static function get_feedback_with_comments($issue_id)
    {
        $feedback = array();
        $sql = sprintf("SELECT feedback_id FROM announcement_feedback WHERE announcement_id=%d AND comments > ''", $issue_id);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $feedback[] = new Announcement_Feedback($row['feedback_id']);
            }
        }
        return $feedback;
    }
}
?>
