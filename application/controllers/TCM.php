<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TCM extends CI_Controller
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
        $this->load->model('TCM_model');
        $this->load->model('student_model');
        $this->load->model('Stats_model');
        date_default_timezone_set("Asia/Kolkata");
        if (!$this->session->userdata('oliveSchoolDashboardUser'))
            redirect(base_url('login'));
    }

    public function index()
    {
        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
        $lastLoginData = $this->Stats_model->last_login();
        $data['lastLoginData'] = $lastLoginData;
        $data['teachers'] = $this->TCM_model->customernumbersdetails();
        $data['user'] = $userdetail;
        $data['feesDetails'] = $this->TCM_model->getAllRecords();
        $this->load->view('Student/schoolm', $data);
    }

    function get_fees_data_of_student(){
        echo json_encode($this->TCM_model->get_fees_data_of_student($this->input->post('student_id')));
    }

    function add_data(){
        $status = $this->TCM_model->add_data();
        if ($status) {
            $this->session->set_flashdata('message', "Data Added Successfully !!!");
            $this->session->set_flashdata('alert_class', "alert-success");
            redirect(base_url() . 'tcm/index');
        } else {
            $this->session->set_flashdata('message', "Failed to Add Data !!!");
            $this->session->set_flashdata('alert_class', "alert-danger");
            redirect(base_url() .'tcm/index');
        }
    }
    function search_from_spofees()
	{
		$student_name = $this->input->post('name');
		$student_info = $this->TCM_model->search_from_spofees($student_name);
		echo json_encode($student_info);
	}
}
