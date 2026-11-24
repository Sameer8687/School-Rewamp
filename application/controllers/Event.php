<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Event extends CI_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
    */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Event_model');
        date_default_timezone_set('Asia/Kolkata');
    }

    function add_event()
    {
        $event_id = $this->input->post('event_id');
        $if_exist = $this->Event_model->checkIfExistEvent($event_id);
        if ($if_exist > 0) {
            $data = array(
                'stvevnt_start_date' => $this->input->post('start_date'),
                'stvevnt_end_date' => $this->input->post('end_date'),
                'stvevnt_title' => $this->input->post('title'),
                'stvevnt_details' => $this->input->post('details')
            );
            //add teacher assigned data into database
            $status = $this->Event_model->update_event($data, $event_id);

            if ($status) {
                $this->session->set_flashdata('message', "Event Updated Successfully !!!");
                $this->session->set_flashdata('alert_class', "alert-success");
                redirect(base_url() . 'events');
            } else {
                $this->session->set_flashdata('message', "Failed to Update Event !!!");
                $this->session->set_flashdata('alert_class', "alert-danger");
                redirect(base_url() . 'events');
            }
        } else {
            $data = array(
                'stvevnt_start_date' => $this->input->post('start_date'),
                'stvevnt_end_date' => $this->input->post('end_date'),
                'stvevnt_title' => $this->input->post('title'),
                'stvevnt_details' => $this->input->post('details')
            );
            //add teacher assigned data into database
            $status = $this->Event_model->add_event($data);

            if ($status) {
                $this->session->set_flashdata('message', "Event Created Successfully !!!");
                $this->session->set_flashdata('alert_class', "alert-success");
                redirect(base_url() . 'events');
            } else {
                $this->session->set_flashdata('message', "Failed to Create Event !!!");
                $this->session->set_flashdata('alert_class', "alert-danger");
                redirect(base_url() . 'events');
            }
        }
    }

    function getEventByID()
    {
        $data = $this->Event_model->getEventByID();
        echo json_encode($data);
    }
}