<?php
class Main extends CI_Controller {

    public function index($id)
    {
        $this->load->model('user_model'); 
        $user = $this->user_model->get_by_id($id);

        $this->load->model('city_model');
        $city = $this->city_model->get_by_id($user->region_id);

        $county_id = $city->county_id;
        $state_id = $city->state_id;

        $this->load->model('county_model');
        $county = $this->county_model->get_by_id($county_id);

        $this->load->model('state_model');
        $state = $this->state_model->get_by_id($state_id);

        $main_data = array(
            'city' => array(
                'image' => LOGO_PATH . $city->image,
                'name' => $city->city_name,
                'announcement_count' => $city->issue_count['a_count'],
                'legislation_count' => $city->issue_count['l_count'],
            ),
            'county' => array(
                'image' => LOGO_PATH . $county->image,
                'name' => $county->county_name,
                'announcement_count' => $county->issue_count['a_count'],
                'legislation_count' => $county->issue_count['l_count'],
            ),
            'state' => array(
                'image' => LOGO_PATH . $state->image,
                'name' => $state->state_name,
                'announcement_count' => $state->issue_count['a_count'],
                'legislation_count' => $state->issue_count['l_count'],
            ),
        );

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($main_data));
    }

}
?>
