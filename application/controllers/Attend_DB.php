<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Attend_DB extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AttendDB_model');
    }

    public function index()
    {
        $data['Teachers'] = $this->AttendDB_model->teacher_fetch();
        $this->load->view('Attend/Attend_db', $data);
    }

    function report_db()
    {
        $teacher_id = $this->input->get('teacher_id');
        $month = $this->input->get('month') ?: date('m');
        $year = $this->input->get('year') ?: date('Y');
        $data['all_details'] = $this->AttendDB_model->report_db($month, $year, $teacher_id);
        $data['teacher_id'] = $teacher_id; // Pass teacher_id to the view
        $this->load->view('Attend/Report_db', $data);
    }

    public function getSingleStudentDetails()
    {
        $teacher_id = $this->input->get('teacher_id');
        $student_id = $this->input->get('student_id');
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        $type = $this->input->get('type'); // 'P' for Present, 'A' for Absent
        $data = $this->AttendDB_model->getSingleStudentDetails($student_id, $month, $year, $type, $teacher_id);
        // Return the result as JSON
        echo json_encode($data);
    }

    function total_student()
    {
        echo json_encode($this->AttendDB_model->total_student());
    }

    function present_student()
    {
        echo json_encode($this->AttendDB_model->present_student());
    }

    function Absent_student()
    {
        echo json_encode($this->AttendDB_model->Absent_student());
    }

    function DayData()
    {
        $teacher_id = $this->input->get('teacher_id');
        $month = $this->input->get('month');
        $year = $this->input->get('year');
        $selectedDate = $this->input->get('date');
        echo json_encode($this->AttendDB_model->DayData($month, $year, $teacher_id, $selectedDate));
    }

    public function getDailyStudentDetails()
    {
        $teacher_id = $this->input->get('teacher_id');
        $selectedDate = $this->input->get('date');
        $type = $this->input->get('type'); // 'All', 'P', 'A'
        $data = $this->AttendDB_model->getDailyStudentDetails($teacher_id, $selectedDate, $type);
        // Return the result as JSON
        echo json_encode($data);
    }

    function  DateWise()
    {
        echo json_encode($this->AttendDB_model->DateData());
    }

    function daily_data()
    {
        echo json_encode($this->AttendDB_model->daily_data());
    }
    function monthwise()
    {
        echo json_encode($this->AttendDB_model->monthwise());
    }
}