<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Attend_DB extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('AttendDB_model');
        //Do your magic here
    }

    public function index()
    {
        $data['Teachers'] = $this->AttendDB_model->teacher_fetch();
        $this->load->view('Attend/Attend_db', $data);
    }
    function report_db()
    {
        $data['all_details'] = $this->AttendDB_model->report_db();
        $this->load->view('Attend/Report_db', $data);
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
        echo json_encode($this->AttendDB_model->DayData());
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