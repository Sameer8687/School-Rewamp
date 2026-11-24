<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Report_card extends CI_Controller
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
        // $this->load->model('Transportation_model');
        $this->load->model('Dashboard_model');
        $this->load->model('MasterForm_model');
        $this->load->model('Report_card_model');
        $this->load->model('Stats_model');

    }

    public function index()
    {
        // $classes = $this->Stats_model->getAllClasses();
        $data['classes'] = $this->Report_card_model->getAllClasses();
        $data['all_exams'] = $this->Report_card_model->get_all_exams();
        $data['academic_years'] = $this->Report_card_model->get_all_academic_years();

        $current_academic_year = $this->Stats_model->getCurrentAcademicYear();
        $data['selected_academic_year'] = !empty($current_academic_year)
            ? $current_academic_year['stvacdm_id']
            : '';

        $this->load->view('report_card', $data);
    }


    function get_subject_by_std()
    {
        $data['subjects'] = $this->Report_card_model->get_subject_by_std($this->input->post('std'));
        echo json_encode($data);
    }

    function get_report_card_data()
    {
        $std = $this->input->post('std');
        $div = $this->input->post('div');
        $exam = $this->input->post('exam');
        $acdm_year = $this->input->post('acdm_year');

        $data['report_card_data'] = $this->Report_card_model->get_report_card_data($std, $div, $exam, $acdm_year);
        echo json_encode($data);
    }

    function view_report_card($student_id, $acdm_year, $std, $div, $exam)
    {
        $data['marks'] = $this->Report_card_model->getMarks($student_id, $acdm_year, $std, $div, $exam);
        $data['student'] = $this->Report_card_model->getStudentData($student_id, $acdm_year);
        $data['acdm_year'] = $this->Report_card_model->getAcdmYear($acdm_year);

        if (in_array($std, ['I', 'II', 'III', 'IV'])) {
            $this->load->view('report_card_view_1to4', $data);

        } elseif (in_array($std, ['V', 'VI', 'VII', 'VIII'])) {
            $this->load->view('report_card_view_5to8', $data);

        } elseif (in_array($std, ['IX', 'X'])) {
            $this->load->view('report_card_view_9to10', $data);

        } elseif (in_array($std, ['XI', 'XII'])) {
            $this->load->view('report_card_view_9', $data);
        }
    }

    // ==================================================================
    public function getReportCardData()
    {
        $data['acdm'] = $this->uri->segment(4);
        $data['class'] = $this->uri->segment(5);
        $data['div'] = $this->uri->segment(6);
        // Get all academic years for dropdown
        $data['academic_years'] = $this->Report_card_model->get_all_academic_years();

        $current_academic_year = $this->Stats_model->getCurrentAcademicYear();
        $data['selected_academic_year'] = !empty($current_academic_year)
            ? $current_academic_year['stvacdm_id']
            : '';

        // If parameters exist, get the filtered data
        if ($data['acdm'] && $data['class'] && $data['div']) {
            $data['report_data'] = $this->Report_card_model->getReportCardData($data['acdm'], $data['class'], $data['div']);
        }

        $this->load->view('Report_card/result', $data);
    }

    public function report_list()
    {
        $acdm = $this->input->get('acdm');
        $class = $this->input->get('class');
        $div = $this->input->get('div');
        echo json_encode($this->Dashboard_model->getReportCardData($acdm, $class, $div));
    }

    public function Report_card($student_id, $acdm, $class, $div)
    {
        $data['marks'] = $this->Dashboard_model->getMarks($student_id, $acdm, $class, $div);
        $data['student'] = $this->Dashboard_model->getStudentData($student_id, $acdm);
        $data['acdm_year'] = $this->Dashboard_model->getAcdmYear($acdm);
        if ($class == 'IX')
            $this->load->view('Report_card/form-v2', $data);
        else
            $this->load->view('Report_card/form', $data);
    }

    public function getClasses()
    {
        $teacher_id = $this->session->userdata('oliveMobileSystemUser');
        $acdm = $this->input->get('acdm_year');
        echo json_encode($this->Dashboard_model->getClasses($teacher_id, $acdm));
    }

    public function getDivisions()
    {
        $teacher_id = $this->session->userdata('oliveMobileSystemUser');
        $acdm = $this->input->get('acdm_year');
        echo json_encode($this->Dashboard_model->getDivisions($teacher_id, $acdm));
    }
}