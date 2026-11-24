<?php

defined('BASEPATH') or exit('No direct script access allowed');



class RandomClass extends CI_Controller

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

        $this->load->model('RandomClass_model');

        $this->load->model('Stats_model');

        $this->load->model('Teacher_model');

        date_default_timezone_set('Asia/Kolkata');

    }





    function assign_class()

    {

        $data['academic_details'] = $this->Stats_model->getAcademicDetails();

        $userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

        //Get Books & Result Data with Joins

        $lastLoginData = $this->Stats_model->last_login();

        $data['lastLoginData'] = $lastLoginData;

        $data['user'] = $userdetail;

        $classes = $this->Stats_model->getAllClasses();

        $data['classes'] = $classes;

        $teachers = $this->Teacher_model->getAllTeachers();

        $data['teachers'] = $teachers;
        $data['filter_view'] = "assign_class1";
        $this->load->view('random/assign-class1', $data);

    }

    function student_class_wise()

    {

        echo json_encode($this->RandomClass_model->student_class_wise1($this->input->get('class')));

    }





    function add_data()

    {

        $status = $this->RandomClass_model->add_data();

        if ($status){

            echo "success";

        }else {

            echo "fail";

        }

        

    }

}

