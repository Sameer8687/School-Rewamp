<?php
defined('BASEPATH') or exit('No direct script access allowed');
class FeesAlert extends CI_Controller
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
        $this->load->model('Student_model');
        $this->load->model('Stats_model');
        $this->load->model('FeesAlert_model');
        $this->load->helper('fees_helper');
        date_default_timezone_set('Asia/Kolkata');
        if (!$this->session->userdata('oliveSchoolDashboardUser'))
            redirect(base_url() . 'login');
    }

    function fees_report()
    {
        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
        $lastLoginData = $this->Stats_model->last_login();
        $data['lastLoginData'] = $lastLoginData;
        $data['user'] = $userdetail;
        $data['academic_details'] = $this->Stats_model->getAcademicDetails();
        $data['message_templates'] = $this->FeesAlert_model->messageTemplates();
        $feesDetails = $this->FeesAlert_model->get_fees_report_for_alert($data['academic_details']['stvacdm_id']);
        $data['feesDetails'] = $feesDetails;
        $data['filter_view'] = "send_message";
        $this->load->view('Student/fees_report_send_message', $data);
    }

    function message_template()
    {
        echo json_encode($this->FeesAlert_model->message_template($this->input->get('id')));
    }

    public function admission_confirmation()
    {
        $userdetail = $this->Stats_model->getUserDetails(
            $this->session->userdata('oliveSchoolDashboardUser')
        );

        $data['academic_year'] = $this->FeesAlert_model->get_all_academic_years();
        $data['classes'] = $this->FeesAlert_model->getAllClasses();

        $data['lastLoginData'] = $this->Stats_model->last_login();
        $data['user'] = $userdetail;

        $data['students'] = [];

        $data['year_filter'] = '';
        $data['std_filter'] = '';
        $data['div_filter'] = '';

        if ($this->uri->segment(3)) {
            $year = $this->uri->segment(3);
            $std = $this->uri->segment(4);
            $div = $this->uri->segment(5);

            $data['students'] = $this->FeesAlert_model->admission_confirmation($year, $std, $div);

            $data['year_filter'] = $year;
            $data['std_filter'] = $std;
            $data['div_filter'] = $div;
        }

        $this->load->view('Fees/admission_confirmation_message', $data);
    }
}