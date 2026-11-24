<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Transportation extends CI_Controller

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
        // $this->load->model('Report_model');
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Transportation_model');
        $this->load->model('Stats_model');
        if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
    }









    public function Driver_master()

    {
        $id = $this->uri->segment(3);

        $data['rows'] = $this->Transportation_model->driver_fetch();
        $data['filter_view']= "driving_master";
        $this->load->view('Transportation/Driver-master', $data);
        // echo '<pre>';
        // print_r($data['rows']);
        // die();


    }

    public function add()
    {
        $this->Transportation_model->add();
    }
    public function updatemodal()
    {
        $this->load->model('Transportation_model');
        // $id = $this->uri->segment(3);
        // $formArray = $this->input->post();
        $row = $this->Transportation_model->update();
        if ($row) {
            redirect(base_url('index.php/Transportation/Driver_master'));
        }
    }
    // public function updateDataByID()
    // {
    //     $data = $this->Transportation_model->update($this->input->get('id'));
    //     echo json_encode($data);
    // }

    public function Vehicle_master()

    {
        $id = $this->uri->segment(3);
        $data['rows'] = $this->Transportation_model->vehicle_fetch();
        $data['filter_view'] = "Vehicle-master";
        $this->load->view('Transportation/Vehicle-master', $data);
        // echo '<pre>';
        // print_r($data['rows']);
        // die();
    }
    public function Vehicle_ad()
    {
        $this->Transportation_model->Vehicle_ad();
    }

    public function updateV()
    {
        $this->load->model('Transportation_model');
        // $id = $this->uri->segment(4);
        // $formArray = $this->input->post();
        $row = $this->Transportation_model->Vehicle_update();
        if ($row) {
            redirect(base_url('index.php/Transportation/Vehicle_master'));
        }
    }




    // public function student_details()

    // {
    //     $id = $this->uri->segment(3);
    //     $data['rows'] = $this->Transportation_model->details_fetch();
    //     $this->load->view('Transportation/student-details', $data);
    // }

	public function Save()
    {
        $this->Transportation_model->Save();
    }


    public function student_details()

    {
        $data['class_filter'] = $this->uri->segment(3);
        $data['division_filter'] = $this->uri->segment(4);
        $data['Driver_name'] = $this->Transportation_model->getdrivrDetails();
        $classes = $this->Stats_model->getAllClasses();
        $data['classes'] = $classes;
        if ($this->uri->total_segments() == 4)
            $data['rows'] = $this->Transportation_model->details_fetch($data['class_filter'], $data['division_filter']);
        else
            $data['rows'] = [];
        $data['filter_view'] = "student-details";
        $this->load->view('Transportation/student-details', $data);
    }


    function getdrivrDetails()
    {
        // echo json_encode($this->Transportation_model->getdrivrDetails($this->input->post('Driver_name')));
        $data['Driver_name'] = $this->Transportation_model->getdrivrDetails();
    }

    public function delete($id)
    {
        $D = $this->Transportation_model->delete($id);
        if ($D) {
            return redirect('Transportation/Driver_master');
        } else {
            echo "nahi huvca";
        }
    }
    public function vehicle_delete($id)
    {
        $d = $this->Transportation_model->vehicle_delete($id);
        if ($d) {
            return redirect('Transportation/Vehicle_master');
        } else {
            echo "nahi huvca";
        }
    }
}
