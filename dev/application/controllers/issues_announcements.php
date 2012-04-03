<?php
class Issues_announcements extends CI_Controller {

    public function index($user_id)
    {
        $issue_type = get_param('type');
        $region_type = get_param('reg');

        $search = preg_replace('/[^a-z0-9]/i', '', get_param('search'));

        $this->load->model('user_model'); 
        $user = $this->user_model->get_by_id($user_id);

        $this->load->model('city_model');
        $city = $this->city_model->get_by_id($user->region_id);

        if ($issue_type & ANNOUNCEMENT_TYPE) {
            $ann_data = array();
            if ($search) {
                $announcements = $city->get_announcements_search($search);
            }
            else {
                $announcements = $city->get_announcements($region_type);
            }
            if (!empty($announcements)) {
                foreach ($announcements as $ann) {
                    if ($ann->feedback_submitted($user_id)) {
                        $ann_data[] = array('is_checked' => 1, 'key' => $ann->id(), 'image' => $ann->get_image_src(), 'value' => $ann->title());
                    }
                    else {
                        $ann_data[] = array('is_checked' => 0, 'key' => $ann->id(), 'image' => $ann->get_image_src(), 'value' => $ann->title());
                    }
                }
            }
            $data['announcements'] = $ann_data;
        }
        if ($issue_type & LEGISLATION_TYPE) {
            $leg_data = array();
            $legislation = $city->get_legislation($region_type);
            if (!empty($legislation)) {
                foreach ($legislation as $leg) {
                    if ($leg->feedback_submitted($user_id)) {
                        $leg_data[] = array('is_checked' => 1, 'key' => $leg->id(), 'value' => $leg->title());
                    }
                    else {
                        $leg_data[] = array('is_checked' => 0, 'key' => $leg->id(), 'value' => $leg->title());
                    }
                }
            }
            $data['legislation'] = $leg_data;
        }


    }
}
?>
