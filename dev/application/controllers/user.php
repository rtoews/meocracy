<?php
class User extends CI_Controller {
    public function index($id)
    {
        $this->load->model('user_model'); 
        $user_data = $this->user_model->get_by_id($id);

        $this->load->model('city_model');
        $user_data->city = $this->city_model->get_by_id($user_data->region_id);

        $county_id = $user_data->city->county_id;
        $state_id = $user_data->city->state_id;

        $this->load->model('county_model');
        $user_data->county = $this->county_model->get_by_id($county_id);

        $this->load->model('state_model');
        $user_data->state = $this->state_model->get_by_id($state_id);

            $user_data->masthead_logo = array(
                REGION_CITY => $this->image_tag($user_data->city->image),
                REGION_COUNTY => $this->image_tag($user_data->county->image),
                REGION_STATE => $this->image_tag($user_data->state->image),
            );
            $user_data->masthead = array(
                REGION_CITY => 'City of ' . $user_data->city->city_name . ', ' . $user_data->state->state_name,
                REGION_COUNTY => $user_data->county->county_name . ' County',
                REGION_STATE => 'State of ' . $user_data->state->state_name,
            );

print '<pre>';
print_r($user_data);
print '</pre>';
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($user_data));
    }


    private function image_tag($image)
    {
        $img_src = null;
        $img_tag = '';
        if ($image) {
            $src = LOGO_PATH . $image;
        }
        if (file_exists(DOC_ROOT . $src)) {
            $img_tag = '<img src="' . $src . '"/>';
        }
        return $img_tag;
    }

}
?>
