<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Teacher_login extends CI_Controller
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

        $this->load->model('Login_model');

        date_default_timezone_set("Asia/Kolkata");

        if ($this->session->userdata('oliveMobileSystemUser'))

            redirect(base_url('dashboard'));

    }



    public function index()
    {

        $this->load->view('teacher_login');

    }

    function validate()
    {
        $type = $this->input->post('class_type');   // e.g. "XI-A"

        $user = $this->Login_model->validateTeacher();

        if ($user == null) {
            $this->session->set_flashdata('Message', "Invalid Credentials..! <br> Please Check ID & Password");
            redirect(base_url() . 'teacher_login');
        } else {
            $login_data = date('d M, h:i A');
            $this->session->set_userdata('login_data', $login_data);
            $this->session->set_userdata('session_type', $type);  // "XI-A" or "Assigned"

            // store std and div separately if needed
            if (strpos($type, '-') !== false) {
                list($std, $div) = explode('-', $type);
                $this->session->set_userdata('session_std', $std);
                $this->session->set_userdata('session_div', $div);
            }

            $userID = $user['SPRIDEN_student_id'];
            $this->session->set_userdata('oliveMobileSystemUser', $userID);

            $this->Login_model->manageLoginHistory($userID);

            redirect(base_url() . 'dashboard');
        }
    }

    // function validate()
    // {

    //     $type = $this->input->post('class_type');

    //     $user = $this->Login_model->validateTeacher();

    //     if ($user == null) {

    //         $this->session->set_flashdata('Message', "Invalid Credentials..! <br> Please Check ID & Password");

    //         redirect(base_url() . 'teacher_login');

    //     } else {

    //         $login_data = date('d M, h:i A');

    //         $this->session->set_userdata('login_data', $login_data);

    //         $this->session->set_userdata('session_type', $type);

    //         $userID = $user['SPRIDEN_student_id'];

    //         $this->session->set_userdata('oliveMobileSystemUser', $userID);
    //         /*
    //             Developer: Kamran Ansari
    //             Date: 01-Sep-2023
    //             ------------------ STARTED --------------------------------
    //         */
    //         // insert login details
    //         $this->Login_model->manageLoginHistory($userID);

    //         redirect(base_url() . 'dashboard');

    //     }

    // }

    //----------26-oct-2026----------
    // AJAX: return list of classes assigned to a teacher
    public function getAssignedClasses()
    {
        $teacher_id = trim($this->input->post('teacher_id'));

        if ($teacher_id === '' || $teacher_id === null) {
            echo json_encode(['status' => false, 'data' => []]);
            return;
        }

        $classes = $this->Login_model->getAssignedClasses($teacher_id);

        echo json_encode([
            'status' => true,
            'data' => $classes
        ]);
    }



}

