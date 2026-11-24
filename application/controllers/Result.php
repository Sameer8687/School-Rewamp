<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Result extends CI_Controller

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
        $this->load->model('Stats_model');
        $this->load->model('Marks_model');
        $this->load->model('MasterForm_model');
        if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
    }


    public function Marksheet($class = 'XI')
    {
        $id = $this->input->get('id');
        $exam = $this->input->get('exam');

        $data['marks_subjs'] = $this->Marks_model->get_marks($id, $exam);
        $subjects = $this->Marks_model->getSubjectByClassForMarksheet($class, $exam, $id);
        $data['subjects'] = [];
        $data['subjects_code'] = [];
        $data['total_marks'] = [];
        $data['passing_marks'] = [];
        $data['marks'] = [];
        foreach ($subjects as $subject) {
            array_push($data['subjects'], $subject['stvsubj_subject']);
            array_push($data['total_marks'], $subject['total_marks']);
            array_push($data['subjects_code'], $subject['stvsubj_id']);
            array_push($data['passing_marks'], $subject['passing_marks']);
            array_push($data['marks'], $subject['marks']);
        }
        $data['student_name'] = $this->Marks_model->get_student_name($id);

        $this->load->view('result/result', $data);
        // echo '<pre>';
        // print_r($data['rows']);
        // die();
    }

    function quiz_result(){
        $qid = $this->input->get('quiz-id');
        $stid = $this->input->get('student-id');
        $data = array();
       $data['record'] = $this->Marks_model->quizResult($qid,$stid);
       $data['que_data'] = $this->Marks_model->quizreview($qid, $stid);
        $this->load->view('quiz-result/quiz-result',$data);
    }
}
