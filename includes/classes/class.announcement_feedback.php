<?php
require_once(DOC_ROOT . '/includes/classes/class.user.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement.php');

class Announcement_Feedback extends DBInterface
{
    public function __construct($feedback_id = 0)
    {
        parent::__construct('announcement_feedback', $feedback_id);
    }

    private function _first_response()
    {
        $sql = sprintf("SELECT feedback_id FROM announcement_feedback WHERE user_id=%d AND announcement_id=%d", $this->user_id(), $this->announcement_id());
        $data = db()->Get_Cell($sql);
        $result = empty($data) ? true : false;
        return $result;
    }

    public function record_response($announcement_id)
    {
        $this->legislation_id($announcement_id);
        if ($this->_first_response()) {
            $this->feedback_date(TODAY_DATETIME);
            $this->insert();
//            $issue_obj = new Announcement($this->announcement_id());
//            $user = new User($this->user_id());
//            $issue_obj->notify($user, $this);
        }
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
