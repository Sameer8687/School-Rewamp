<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Class_DB extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('AttendDB_model');
        $this->load->model('DB_model');
        $this->load->model('ClassDB_model');
    }
    // function teacher_login()
    // {
    //     $this->session->set_userdata('oliveMobileSystemUser', 'E20220004');
    // }

    public function index()
    {
        if ($this->session->userdata('oliveMobileSystemUser')) {
            $data['academic_years'] = $this->DB_model->get_all_academic_years();
            $data['Exams'] = $this->ClassDB_model->exam_name();
            // $data['maham'] = $this->attendDB_model->daily_data();
            $data['Subs'] = $this->ClassDB_model->subject_name($this->session->userdata('oliveMobileSystemUser'));
            $data['Attends'] = $this->ClassDB_model->class_attend();
            $data['Totals'] = $this->ClassDB_model->class_stud();
            $data['users'] = $this->ClassDB_model->student_name();

            //CXJNCZHSSBDCMATEENJHSCNDSJNCCJNCZMISBAHSHDBCSDBCHBCBCBCDSILJNSABNXCNJDCBCCJKSDNCBC
            // $data['Stud_Attend'] = $this->ClassDB_model->stud_top_attend();

            $this->load->view('classresult/classresult', $data);
        } else {
            redirect(base_url('Teacher_login'));
        }
    }
    function top_stud()
    {
        echo json_encode($this->ClassDB_model->top_stud());
    }
    function GetSems()
    {
        echo json_encode($this->ClassDB_model->GetSems());
    }
}