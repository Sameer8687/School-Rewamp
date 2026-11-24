<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absent_student extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Absent_student_model');
        date_default_timezone_set('Asia/Kolkata');
		if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
    }

    public function index()
    {
        $data['academic_year'] = $this->Absent_student_model->get_all_academic_years();
        $this->load->view('Absent/absent_student', $data);
    }

    public function get_absent_details()
    {
        $year = $this->input->post('year');
        $std = $this->input->post('std');
        $div = $this->input->post('div');

        if (empty($year) || empty($std) || empty($div)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([]));
            return;
        }
        $result = $this->Absent_student_model->get_absent_students($std, $div, $year);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
    
    public function absent_student()
    {
        $id = $this->input->post('id');
        $year = $this->input->post('year');

        $absent_dates = $this->Absent_student_model->getDailyAbsentDates($id, $year);

        // Debug output - temporarily check what's being returned
        // header('Content-Type: application/json');
        // echo json_encode($absent_dates);
        // return;

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($absent_dates));
    }

}