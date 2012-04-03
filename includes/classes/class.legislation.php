<?php
require_once(DOC_ROOT . '/includes/classes/class.city.php');
require_once(DOC_ROOT . '/includes/classes/class.legislator.php');
require_once(DOC_ROOT . '/includes/classes/class.image.php');
require_once(DOC_ROOT . '/includes/classes/class.office.php');
require_once(DOC_ROOT . '/includes/classes/class.tag.php');
require_once(DOC_ROOT . '/includes/classes/class.legislation_feedback.php');
require_once(DOC_ROOT . '/includes/classes/class.category.php');
require_once(DOC_ROOT . '/includes/classes/class.email.php');

class Legislation extends DBInterface
{
	public function __construct($legislation_id = 0)
	{
		parent::__construct('legislation', $legislation_id);
        if ($legislation_id) {
            $state = new State($this->region_id());
            $logo = LOGO_PATH . strtolower('state_' . $state->state_abbr() . '_' . $this->current_chamber()) . '.png';
            $location = $state->state_name() . ' State ' . $this->current_chamber();
            list($category_id, $category_name) = Category::legislation_get_category($this->current_location());
            $committee = Category::get_location($this->current_location());
            $this->legislator_ids = $this->get_legislator_ids();
            $legislators = array();
            if (!empty($this->legislator_ids)) {
                foreach ($this->legislator_ids as $id) {
                    $legislators[] = new Legislator($id);
                }
            }
            $this->sponsors = Legislation::get_legislator_data($legislators, $committee);
            $this->bill = $this->_extract_bill_id();
            $this->image = $logo;
            $this->bill_location = $location;
            $this->location_description = $committee;
            $this->category = array('id' => $category_id, 'name' => $category_name);
            $this->status = new Status($this->status_id());
            $this->date_introduced_parts = get_date_parts($this->date_introduced());
            $this->date_heard_parts = get_date_parts($this->date_heard());
            $this->_get_public_opinion();
            $this->comment_data = $this->get_comment_data();
        }
 	}
		

/*
    private function _get_legislator_id()
    {
        $legislator_id = null;
        if ($id = $this->id()) {
            $sql = sprintf("SELECT legislator_id FROM legislation_legislator WHERE legislation_id=%d", $id);
            $legislator_id = db()->Get_Cell($sql);
        }
        return $legislator_id;
    }
*/
    public static function get_legislator_data($data, $committee)
    {
        $committee = $committee ? $committee . ' Committee' : '';
        $first_position = $committee && sizeof($data) > 1 ? 'Chair, ' . $committee : $committee;
        $legislators = array();
        if (!empty($data)) {
            foreach ($data as $legislator) {
                $legislators[] = array(
                    'id' => $legislator->id(), 
                    'img_name' => $legislator->img_name, 
                    'image' => $legislator->image, 
                    'full_title' => $legislator->full_title, 
                    'level' => $legislator->level, 
                    'position' => $committee, 
                    'title' => $legislator->name_title(),
                    'name' => $legislator->full_name, 
                    'party' => $legislator->party, 
                    'state' => $legislator->state(), 
                    'office' => $legislator->name_title(), 
                    'district' => $legislator->district, 
                    'lastname' => $legislator->name_last(),
                    'firstname' => $legislator->name_first(),
                );
            }
            $legislators[0]['position'] = $first_position;
        }
        return $legislators;
    }

    public function get_feedback_with_comments($id)
    {
        return Legislation_Feedback::get_feedback_with_comments($id);
    }

    private function _extract_bill_id()
    {
        $bill_id = $this->bill_id() . 'cap';
        $bill_id = trim($bill_id, '0123456789');
        $bill_id = substr($bill_id, 0, strlen($bill_id)-3);
        return $bill_id;
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
        $sql = sprintf("SELECT COUNT(*) comment_count FROM legislation_feedback WHERE legislation_id=%d AND comments IS NOT NULL", $this->id());
        $total_feedback = db()->Get_Cell($sql);
        return $total_feedback;
    }

    private function _get_comments()
    {
        $comments = array();
        $sql = sprintf("SELECT feedback_id FROM legislation_feedback WHERE legislation_id=%d AND comments IS NOT NULL ORDER BY feedback_date DESC", $this->id());
        $data = db()->Get_Table($sql);
        if (!empty($data)) {
            foreach ($data as $row) {
                $c = new Legislation_Feedback($row['feedback_id']);
                $comments[] = array('feedback_id' => $c->id(), 'legislation_id' => $c->legislation_id(), 'user_id' => $c->user->id(), 'user_handle' => $c->user->get_handle(), 'comments' => $c->comments(), 'response' => $c->response(), 'date' => $c->feedback_date());
            }
        }
        return $comments;
    }

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
        $tags_to_associate = array();
        for ($i = 0, $j = sizeof($tags); $i < $j; $i++) {
            if (trim($tags[$i]) == '') continue;
            $tag_id = Tag::get_tag_id($tags[$i]);
            $tags_to_associate[] = $tag_id;
        }
        $keep_tags = !empty($tags_to_associate) ? implode(', ', $tags_to_associate) : -1;
        $sql = sprintf("DELETE FROM legislation_tag WHERE legislation_id=%d AND tag_id NOT IN (%s)", $this->id(), $keep_tags);
        db()->query($sql);
        User::clear_alerts_except('legislation', $this->id(), $keep_tags);

        foreach ($tags_to_associate as $tag_id) {
            $sql = sprintf("SELECT legislation_tag_id FROM legislation_tag WHERE legislation_id=%d AND tag_id=%d", $this->id(), $tag_id);
            $id = db()->Get_Cell($sql);
            if (!$id) {
                $sql = sprintf("INSERT INTO legislation_tag (legislation_id, tag_id) VALUES (%d, %d)", $this->id(), $tag_id);
                db()->query($sql);
            }
            User::queue_alerts('legislation', $this->id(), $tag_id);
        }
    }

    public function disassociate_tags()
    {
        $sql = sprintf("DELETE FROM legislation_tag WHERE legislation_id=%d", $this->id());
        db()->Get_Cell($sql);
    }

    public function associate_legislators($legislator_ids)
    {
        if (empty($legislator_ids)) return;

        if (!empty($legislator_ids)) {
            $keep_legislators = implode(', ', $legislator_ids);
            $sql = sprintf("DELETE FROM legislation_sponsor WHERE legislation_id=%d AND sponsor_id NOT IN (%s)", $this->id(), $keep_sponsors);
            db()->query($sql);
        }

        foreach ($legislator_ids as $legislator_id) {
            $sql = sprintf("SELECT legislation_sponsor_id FROM legislation_sponsor WHERE legislation_id=%d AND sponsor_id=%d", $this->id(), $legislator_id);
            $id = db()->Get_Cell($sql);
            if (!$id) {
                $sql = sprintf("INSERT INTO legislation_sponsor (legislation_id, sponsor_id) VALUES (%d, %d)", $this->id(), $legislator_id);
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
        $feedback_id = db()->Get_Cell($sql);
        if (!empty($feedback_id)) {
            $feedback = new Legislation_Feedback($feedback_id);
            $submitted = array(
                'feedback_id' => $feedback_id,
                'user_id' => $feedback->user_id(),
                'legislation_id' => $feedback->legislation_id(),
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


    public function get_legislator_ids()
    {
        $sql = "SELECT sponsor_id, primary_sponsor FROM legislation_sponsor WHERE legislation_id=" . $this->id() . " ORDER BY primary_sponsor DESC";
        $data = db()->Get_Table($sql);
        $legislator_ids = array();
        if (!empty($data)) {
            foreach ($data as $row) {
                $legislator_ids[] = $row['sponsor_id'];
            }
        }
        return $legislator_ids;
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
