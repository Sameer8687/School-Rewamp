<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Result_db extends CI_Controller

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
        $this->load->model('DB_model');
        $this->load->model('StudresultDB_model');
        // $this->load->model('Marks_model');
        // $this->load->model('MasterForm_model');
    }


    public function index()
    {
        $data['academic_years'] = $this->DB_model->get_all_academic_years();
        $data['users'] = $this->StudresultDB_model->student_name('data');
        $data['Exams'] = $this->StudresultDB_model->exam_name();


        $this->load->view('StudResult/studresult_dashboard', $data);
    }

    function getData()
    {
        echo json_encode($this->StudresultDB_model->getData());
    }

    function GetSems()
    {
        echo json_encode($this->StudresultDB_model->GetSems());
    }
}
