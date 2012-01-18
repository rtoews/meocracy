<?php
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.sponsor.php" );
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.image.php" );
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.office.php" );
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.tag.php" );
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.legislation_feedback.php" );
require_once( $_SERVER["DOCUMENT_ROOT"] . "/includes/classes/class.email.php" );

class Legislation extends DBInterface
{
	public function __construct( $legislation_id = 0 )
	{
		parent::__construct( 'legislation', $legislation_id );
        if ($legislation_id) {
            $this->sponsor_ids = $this->get_sponsor_ids();
            if (!empty($this->sponsor_ids)) {
                foreach ($this->sponsor_ids as $id) {
                    $this->sponsor[] = new Sponsor($id);
                }
            }
            $this->status = new Status($this->status_id());
            $this->date_introduced_parts = get_date_parts($this->date_introduced());
            $this->date_heard_parts = get_date_parts($this->date_heard());
            $this->_get_public_opinion();
        }
 	}
		

/*
    private function _get_sponsor_id()
    {
        $sponsor_id = null;
        if ($id = $this->id()) {
            $sql = sprintf("SELECT sponsor_id FROM legislation_sponsor WHERE legislation_id=%d", $id);
            $sponsor_id = db()->Get_Cell($sql);
        }
        return $sponsor_id;
    }
*/
    private function _get_public_opinion()
    {
        $opinion = array();
        $sql = sprintf("SELECT COUNT(*) AS total, SUM(response) AS response FROM legislation_feedback WHERE legislation_id=%d", $this->id());
        $data = db()->Get_Row($sql);
        $opinion[-1] = $data['response'] < 0 ? -1*$data['response'] : 0;
        $opinion[1] = $data['response'] > 0 ? $data['response'] : 0;
        $opinion[0] = ($data['total'] - abs($data['response'])) / 2; // total - abs(response) always even, since response is either 1 or -1.
        $this->opinion = $opinion;
    }


    public static function get_legislation_count()
    {
        $sql = "SELECT COUNT(*) total_legislation FROM legislation WHERE legislation_id > 8";
        $total_legislation = db()->Get_Cell($sql);
        return $total_legislation;
    }

    public static function get_ids_by_region($region_id, $region_type = REGION_CITY)
    {
        $legislation_id = array();
        $sql = sprintf("SELECT legislation_id FROM legislation a JOIN legislation_region ar USING (legislation_id) WHERE ar.region_id=%d AND ar.region_type=%d", $region_id, $region_type);
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $legislation_id[] = $row['legislation_id'];
            }
        }
        return $legislation_id;
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
            $sql = sprintf("DELETE FROM legislation_tag WHERE legislation_id=%d AND tag_id NOT IN (%s)", $this->id(), $keep_tags);
            db()->query($sql);
            User::clear_alerts_except('legislation', $this->id(), $keep_tags);
        }

        foreach ($tags_to_associate as $tag_id) {
            $sql = sprintf("SELECT legislation_tag_id FROM legislation_tag WHERE legislation_id=%d AND tag_id=%d", $this->id(), $tag_id);
            $id = db()->Get_Cell($sql);
            if (!$id) {
                $sql = sprintf("INSERT INTO legislation_tag (legislation_id, tag_id) VALUES (%d, %d)", $this->id(), $tag_id);
                db()->query($sql);
            }
            User::queue_alerts('legislation', $legislation_id, $tag_id);
        }
    }

    public function disassociate_tags()
    {
        $sql = sprintf("DELETE FROM legislation_tag WHERE legislation_id=%d", $this->id());
        db()->Get_Cell($sql);
    }

    public function associate_sponsors($sponsor_ids)
    {
        if (empty($sponsor_ids)) return;

        if (!empty($sponsor_ids)) {
            $keep_sponsors = implode(', ', $sponsor_ids);
            $sql = sprintf("DELETE FROM legislation_sponsor WHERE legislation_id=%d AND sponsor_id NOT IN (%s)", $this->id(), $keep_sponsors);
            db()->query($sql);
        }

        foreach ($sponsor_ids as $sponsor_id) {
            $sql = sprintf("SELECT legislation_sponsor_id FROM legislation_sponsor WHERE legislation_id=%d AND sponsor_id=%d", $this->id(), $sponsor_id);
            $id = db()->Get_Cell($sql);
            if (!$id) {
                $sql = sprintf("INSERT INTO legislation_sponsor (legislation_id, sponsor_id) VALUES (%d, %d)", $this->id(), $sponsor_id);
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


    public static function get_average_legislation_feedback()
    {
        $total_feedback = Legislation_Feedback::get_feedback_count();
        $total_legislation = Legislation::get_legislation_count();
        $average = $total_feedback / $total_legislation;
        return $average;
    }


    public function get_image_src()
    {
        return null;
    }

    public function feedback_submitted($user_id)
    {
        $submitted = false;
        $sql = sprintf("SELECT feedback_id FROM legislation_feedback WHERE user_id=%d AND legislation_id=%d", $user_id, $this->id());
        $data = db()->Get_Cell($sql);
        if (!empty($data)) {
            $submitted = true;
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
        $id = $this->id();
        $sql = "SELECT t.tag_id, t.tag FROM legislation_tag lt JOIN tag t ON lt.tag_id=t.tag_id
                WHERE lt.legislation_id=" . $id;
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
        $sql = "SELECT sponsor_id FROM legislation_sponsor WHERE legislation_id=" . $this->id();
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
