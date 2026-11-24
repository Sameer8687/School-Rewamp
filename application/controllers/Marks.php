<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Marks extends CI_Controller
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
        $this->load->model('MasterForm_model');
        $this->load->model('Lesson_model');
        date_default_timezone_set('Asia/Kolkata');
        $this->load->model('Marksheet_model');
        $this->load->model('Stats_model');
        $this->load->model('Marks_model');
        // $this->load->model('StudentDetails_model');
    }

    public function Subject_master()
    {
        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
        $data['user'] = $userdetail;
        $classes = $this->Stats_model->getAllClasses();
        $data['classes'] = $classes;
        $data['subjects'] = $this->MasterForm_model->get_all_subjects();
        $data['subjs'] = $this->MasterForm_model->get_all_subjects();
        $data['academic_details'] = $this->Stats_model->getAcademicDetails();
        $data['filter_view'] = "Subject-form";
        $this->load->view('Markssheet/Subject-form', $data);
    }

    public function add_subject()
    {
        $this->Marksheet_model->add_subject();
    }

    function getSubjectDataByID()
    {
        $data = $this->MasterForm_model->getSubjectDataByID();
        echo json_encode($data);
    }

    public function updatemodal()
    {
        $this->load->model('Marksheet_model');

        $row = $this->Marksheet_model->update();
        if ($row) {
            redirect(base_url('index.php/Markssheet/Driver_master'));
        }
    }
    public function marks_report()
    {
        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
        $data['user'] = $userdetail;
        $data['stvexam_name'] = $this->Marksheet_model->fetch_exam_data();
        $data['stats'] = array();
        $data['exams'] = $this->MasterForm_model->get_all_exam();
        $data['setups'] = $this->Marksheet_model->fetch_data();
        $data['sub'] = $this->Marksheet_model->fetch_sub();
        $data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
        $data['academic_details'] = $this->Stats_model->getAcademicDetails();

        $classes = $this->Stats_model->getAllClasses();
        $data['classes'] = $classes;
        $data['filter_view'] = "marks_Report";
        $this->load->view('Markssheet/marks_Report', $data);
    }


    public function Exam_master()
    {
        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
        $data['user'] = $userdetail;
        $data['all_exams'] = $this->MasterForm_model->get_all_exam();
        $data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
        $id = $this->uri->segment(3);
        $data['filter_view'] = "exam-master";
        $this->load->view('Markssheet/exam-master', $data);
    }
    public function add_exam_data()
    {
        $this->Marksheet_model->add_exam_data();
    }

    function getExamDataByID()
    {
        $data = $this->MasterForm_model->getExamDataByID();
        echo json_encode($data);
    }

    function getClassStd()
    {
        $std = $this->input->get('std');
        $div = $this->input->get('div');
        $data = $this->Marksheet_model->getStuData($std, $div);
        echo json_encode($data);
    }




    public function Save()
    {
        $this->Marksheet_model->Save();
    }
    public function all_exams()
    {
        $this->Marksheet_model->all_exams();
    }
    function all_lessons()
    {
        echo json_encode($this->Lesson_model->all_lessons($this->input->get('class'), $this->input->get('sub')));
    }
    function getAllSubjetcsByStd()
    {
        echo json_encode($this->MasterForm_model->getAllSubjetcsByStd($this->input->get('std')));
    }
    public function Examsetup()
    {
        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
        $data['user'] = $userdetail;
        $data['stats'] = array();
        $data['exam_name'] = $this->Marksheet_model->fetch_exam_data();

        $data['exams'] = $this->MasterForm_model->get_all_exam();
        $data['setups'] = $this->Marksheet_model->fetch_data();
        $data['sub'] = $this->Marksheet_model->fetch_sub();
        $data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
        $data['academic_details'] = $this->Stats_model->getAcademicDetails();

        $current_academic_year = $this->Stats_model->getCurrentAcademicYear();
        $data['selected_academic_year'] = !empty($current_academic_year)
            ? $current_academic_year['stvacdm_id']
            : '';

        $classes = $this->Stats_model->getAllClasses();
        $data['classes'] = $classes;
        $data['filter_view'] = "Exam-setup";
        $this->load->view('Markssheet/Exam-setup', $data);
    }

    function get_all_academic_years()
    {
        echo json_encode($this->Marksheet_model->fetch_acadmic($this->input->get('year'), $this->input->get('std')));
    }
    function getAllExamByStd()
    {
        echo json_encode($this->Marksheet_model->getAllSubjetcsByStd($this->input->get('std'), ));
        // $data['exam'] = $this->getAllSubjetcsByStd(['stvlmst_std']);
    }

    public function Student_marks()
    {
        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
        $data['user'] = $userdetail;
        // $data['exam_name'] = $this->Marksheet_model->fetch_exam_data();

        // $data['sets'] = $this->Marksheet_model->student_marks();
        $data['stvsubj'] = $this->Marks_model->fetch_stvsub();
        $data['exams'] = $this->MasterForm_model->get_all_exam();
        $data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

        $current_academic_year = $this->Stats_model->getCurrentAcademicYear();
        $data['selected_academic_year'] = !empty($current_academic_year)
            ? $current_academic_year['stvacdm_id']
            : '';

        $classes = $this->Stats_model->getAllClasses();
        $data['classes'] = $classes;
        $data['stats'] = array();
        // $data['academic_year'] = 0;
        $data['class'] = 0;
        $data['division'] = 0;
        $data['filter_view'] = "Student_marks";
        $this->load->view('Markssheet/Student_marks', $data);
    }

    function marks()
    {
        echo json_encode($this->Marks_model->student_marks($this->input->get('year'), $this->input->get('std'), $this->input->get('divi'), $this->input->get('exam'), $this->input->get('subject')));
    }

    function exam_setup()
    {
        $exam_setup = $this->input->post('exam_setup');

        echo json_encode($this->Marks_model->exam_setup($exam_setup));
    }

    function insert_marks()
    {
        $this->Marks_model->insert_marks();
    }
}
