<?php
require_once(DOC_ROOT . '/includes/classes/class.sponsor.php');
require_once(DOC_ROOT . '/includes/classes/class.status.php');
require_once(DOC_ROOT . '/includes/classes/class.office.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');
require_once(DOC_ROOT . '/includes/classes/class.announcement_feedback.php');
require_once(DOC_ROOT . '/includes/classes/class.email.php');

class Announcement extends DBInterface
{
	public function __construct($announcement_id = 0, $get_list = false)
	{
		parent::__construct( 'announcement', $announcement_id );
        if ($get_list === false && $announcement_id) {
            $this->sponsor_ids = $this->get_sponsor_ids();
            $sponsors = array();
            if (!empty($this->sponsor_ids)) {
                foreach ($this->sponsor_ids as $id) {
                    $sponsors[] = new Sponsor($id);
                }
            }
            $this->sponsors = Announcement::get_sponsor_data($sponsors);
            list($category_id, $category_name) = Category::announcement_get_category($this->id());
            $this->category = array('id' => $category_id, 'name' => $category_name);
            $this->status = new Status($this->status_id());
            $this->office = new Office($this->sponsor_id());
            $this->date_beginning_parts = get_date_parts($this->date_beginning());
            $this->date_ending_parts = get_date_parts($this->date_ending());
            $this->calendared_parts = get_date_parts($this->calendared());
            $this->vote_parts = get_date_parts($this->vote());
            $this->_get_public_opinion();
            $this->comment_data = $this->get_comment_data();
        }
 	}


    public static function get_sponsor_data($data)
    {
        $sponsors = array();
        if (!empty($data)) {
            foreach ($data as $sponsor) {
                $sponsors[] = array(
                    'id' => $sponsor->id(), 
                    'image' => $sponsor->image, 
                    'full_title' => $sponsor->full_title, 
                    'level' => $sponsor->level, 
                    'title' => $sponsor->name_first(), 
                    'position' => $sponsor->title(), 
                    'name' => $sponsor->full_name, 
                    'party' => $sponsor->party, 
                    'state' => $sponsor->state(), 
                    'office' => $sponsor->name_title(), 
                    'district' => $sponsor->district, 
                    'firstname' => $sponsor->name_first(), 
                    'lastname' => $sponsor->name_last(),
                );
            }
        }
        return $sponsors;
    }

    public function get_feedback_with_comments($id)
    {
        return Announcement_Feedback::get_feedback_with_comments($id);
    }


    function get_comment_data()
    {
        $comment_count = $this->_get_comment_count();
        $comments = $this->_get_comments();
        $data = array('count' => $comment_count, 'comments' => $comments);
        return $data;
    }

    private function _get_comment_count()
    {
        $sql = sprintf("SELECT COUNT(*) comment_count FROM announcement_feedback WHERE announcement_id=%d AND comments > ''", $this->id());
        $total_feedback = db()->Get_Cell($sql);
        return $total_feedback;
    }

    private function _get_comments()
    {
        $comments = array();
        $sql = sprintf("SELECT feedback_id FROM announcement_feedback WHERE announcement_id=%d AND comments > '' ORDER BY feedback_date DESC", $this->id());
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $c = new Announcement_Feedback($row['feedback_id']);
                $comments[] = array('feedback_id' => $c->id(), 'announcement_id' => $c->announcement_id(), 'user_id' => $c->user->id(), 'user_handle' => $c->user->get_handle(), 'comments' => $c->comments(), 'response' => $c->response(), 'date' => $c->feedback_date());
            }
        }
        return $comments;
    }

    private function _get_public_opinion()
    {
        $opinion = array();
        $sql = sprintf("SELECT COUNT(*) AS total, SUM(response) AS response FROM announcement_feedback WHERE announcement_id=%d", $this->id());
        $data = db()->Get_Row($sql);
        $opinion[-1] = $data['response'] < 0 ? -1*$data['response'] : 0;
        $opinion[1] = $data['response'] > 0 ? $data['response'] : 0;
        $opinion[0] = ($data['total'] - abs($data['response'])) / 2; // total - abs(response) always even, since response is either 1 or -1.
        $this->opinion = $opinion;
    }


    public static function get_announcement_count()
    {
        $sql = "SELECT COUNT(*) total_announcement FROM announcement WHERE announcement_id > 8";
        $total_announcement = db()->Get_Cell($sql);
        return $total_announcement;
    }

    public static function get_ids_by_region($region_id, $region_type = REGION_CITY)
    {
        $announcement_id = array();
        $sql = sprintf("SELECT announcement_id FROM announcement a JOIN announcement_region ar USING (announcement_id) WHERE ar.region_id=%d AND ar.region_type=%d", $region_id, $region_type);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $announcement_id[] = $row['announcement_id'];
            }
        }
        return $announcement_id;
    }

    public function associate_tags($tags)
    {
        if (empty($tags)) return;

        $tags_to_associate = array();
        for ($i = 0, $j = sizeof($tags); $i < $j; $i++) {
            if (trim($tags[$i]) == '') continue;
            $tag_id = Tag::get_tag_id($tags[$i]);
            $tags_to_associate[] = $tag_id;
        }
        if (!empty($tags_to_associate)) {
            $keep_tags = implode(', ', $tags_to_associate);
            $sql = sprintf("DELETE FROM announcement_tag WHERE announcement_id=%d AND tag_id NOT IN (%s)", $this->id(), $keep_tags);
            db()->query($sql);
            User::clear_alerts_except('announcement', $this->id(), $keep_tags);
        }

        foreach ($tags_to_associate as $tag_id) {
            $sql = sprintf("SELECT announcement_tag_id FROM announcement_tag WHERE announcement_id=%d AND tag_id=%d", $this->id(), $tag_id);
            $id = db()->Get_Cell($sql);
            if (!$id) {
                $sql = sprintf("INSERT INTO announcement_tag (announcement_id, tag_id) VALUES (%d, %d)", $this->id(), $tag_id);
                db()->query($sql);
            }
            User::queue_alerts('announcement', $this->id(), $tag_id);
        }
    }

    public function disassociate_tags()
    {
        $sql = sprintf("DELETE FROM announcement_tag WHERE announcement_id=%d", $this->id());
        db()->Get_Cell($sql);
    }


    public function associate_sponsors($sponsor_ids)
    {
        if (empty($sponsor_ids)) return;

        if (!empty($sponsor_ids)) {
            $keep_sponsors = implode(', ', $sponsor_ids);
            $sql = sprintf("DELETE FROM announcement_sponsor WHERE announcement_id=%d AND sponsor_id NOT IN (%s)", $this->id(), $keep_sponsors);
            db()->query($sql);
        }

        foreach ($sponsor_ids as $sponsor_id) {
            $sql = sprintf("SELECT announcement_sponsor_id FROM announcement_sponsor WHERE announcement_id=%d AND sponsor_id=%d", $this->id(), $sponsor_id);
            $id = db()->Get_Cell($sql);
            if (!$id) {
                $sql = sprintf("INSERT INTO announcement_sponsor (announcement_id, sponsor_id) VALUES (%d, %d)", $this->id(), $sponsor_id);
                db()->query($sql);
            }
        }
    }


    public function get_support()
    {
        $support = $this->opinion[0] + $this->opinion[1];
        return $support;
    }

    public function get_oppose()
    {
        $oppose = $this->opinion[0] + $this->opinion[-1];
        return $oppose;
    }


    public static function get_average_announcement_feedback()
    {
        $total_feedback = Announcement_Feedback::get_feedback_count();
        $total_announcement = Announcement::get_announcement_count();
        $average = $total_feedback / $total_announcement;
        return $average;
    }


    public function get_image_src()
    {
        $src = 'missing.png';
        if ($this->image()) {
            $src = ANNOUNCEMENT_IMAGE_PATH . $this->image();
        }
        return $src;
    }

    public function feedback_submitted($user_id)
    {
        $submitted = false;
        $sql = sprintf("SELECT feedback_id FROM announcement_feedback WHERE user_id=%d AND announcement_id=%d", $user_id, $this->id());
        $feedback_id = db()->Get_Cell($sql);
        if (!empty($feedback_id)) {
            $feedback = new Announcement_Feedback($feedback_id);
            $submitted = array(
                'feedback_id' => $feedback_id,
                'user_id' => $feedback->user_id(),
                'announcement_id' => $feedback->announcement_id(),
                'response' => $feedback->response(),
                'comments' => $feedback->comments(),
                'date' => $feedback->feedback_date(),
            );
        }
        return $submitted;
    }

    public function deadline_format()
    {
        $formatted = null;
        if ($calendared = $this->calendared()) {
            $formatted = date(DATE_FORMAT, strtotime($calendared));
        }
        return $formatted;
    }

    public function days_remaining()
    {
        $today = date_create(date('Y-m-d'));
        $calendared = date_create($this->vote());
        $diff = date_diff($today, $calendared);
        $days = $diff->days;
        if ($days == 1) {
            $word = 'day';
        }
        elseif ($days > 1) {
            $word = 'days';
        }

        return "$days $word";
    }


    private function _get_tags()
    {
        $sql = "SELECT t.tag_id, t.tag FROM announcement_tag at JOIN tag t ON at.tag_id=t.tag_id
                WHERE at.announcement_id=" . $this->id();
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $kv) {
                $tags[$kv['tag_id']] = $kv['tag'];
            }
        }
        return $tags;
    }


    public function tags()
    {
        $tags = $this->_get_tags();
        return $tags;
    }


    public function get_sponsor_ids()
    {
        // Limit to 1
        $sql = "SELECT sponsor_id FROM announcement_sponsor WHERE announcement_id='" . $this->id() . "' ORDER BY is_primary DESC LIMIT 1";
        $data = db()->Get_Table($sql);
        $sponsor_ids = array();
        if (!empty($data)) {
            foreach ($data as $row) {
                $sponsor_ids[] = $row['sponsor_id'];
            }
        }
        return $sponsor_ids;
    }


    public function begin_month()
    {
    }

    public function notify($user, $feedback)
    {
return;
        $message = Email::format_message($feedback->response(), $feedback->comments());
        $contact_method = 'email';
        if ($contact_method == 'email') {
            $to = Email::format_email($this->sponsor->email_address(), $this->sponsor->firstname(), $this->sponsor->lastname());
            $from = Email::format_email($user->email_address(), $user->firstname(), $user->lastname());
            $email = new Email($to, $from, $subject, $message);
            // $email->send();
        }
        elseif ($contact_method == 'sms') {

        }
    }
}
