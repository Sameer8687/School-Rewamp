<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Lc_certificate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Kolkata');
        if (!$this->session->userdata('oliveSchoolDashboardUser'))
            redirect(base_url() . 'login');
        $this->load->model('Stats_model');
        $this->load->model('Report_model');
    }

    // public function index()
    // {
    //     $data['title'] = 'Report';
    //     $data['records'] = $this->Report_model->get_lc_report();
    //     $this->load->view('report/student-list', $data);
    // }

    public function index()
    {
        $year = $this->input->get('year');
        $std = $this->input->get('std');

        $data['title'] = 'Report';
        $data['years'] = $this->Stats_model->get_all_academic_Years();
        $data['classes'] = $this->Stats_model->getAllClasses();

        $data['records'] = $this->Report_model->get_lc_report($year, $std);

        $this->load->view('report/student-list', $data);
    }

    public function view_lc()
    {
        $data['user'] = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

        $data['title'] = 'View LC';
        $student_id = $this->input->get('student-id');
        $acdm = $this->input->get('year');
        $token = $this->input->get('token');

        $data['action'] = $this->input->get('type');

        $data['record'] = $this->Report_model->student_get_lc($student_id, $acdm);

        $data['student_std'] = $data['record']['current_std'];

        if ($data['student_std'] == 'XI' || $data['student_std'] == 'XII') {
            $data['record'] = $this->Report_model->student_get_lc_jc($student_id, $acdm, $token);
        } else {
            $data['record'] = $this->Report_model->student_get_lc($student_id, $acdm, $token);
        }

        $data['lc_no'] = $this->Report_model->generate_lc_no($data['student_std']);
        $this->load->view('report/view_lc', $data);
    }
}
