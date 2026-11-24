<?php
class StudentProfile extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('StudentProfile_model');
        $this->load->model('MasterForm_model');
        $this->load->model('Student_model');
    }

    function index()
    {
        $data['records'] = $this->StudentProfile_model->getStudentList();
        $data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
        $data['max_acdm'] = $this->StudentProfile_model->getMaxAcdm();
        $this->load->view('Studentprofile/profile', $data);
    }

    function getDetails()
    {
        $id = $this->input->post('id');
        $acdm = $this->input->post('acdm');
        echo json_encode($this->StudentProfile_model->getStudentData($id, $acdm));
    }

    function getQuizDetails()
    {
        $id = $this->input->post('id');
        $acdm = $this->input->post('acdm');
        echo json_encode($this->StudentProfile_model->getQuizData($id, $acdm));
    }

    function getExamDetails()
    {
        $id = $this->input->post('id');
        $acdm = $this->input->post('acdm');
        echo json_encode($this->StudentProfile_model->getExamData($id, $acdm));
    }

    function getRemarkDetails()
    {
        $id = $this->input->post('id');
        $acdm = $this->input->post('acdm');
        echo json_encode($this->StudentProfile_model->getRemarkData($id, $acdm));
    }

    function getAttendanceDetails()
    {
        $id = $this->input->post('id');
        $acdm = $this->input->post('acdm');
        echo json_encode($this->StudentProfile_model->getAttendanceData($id, $acdm));
    }


    function all_fees_details_of_student()

    {
        $student_id = $this->input->post('student_id');
        $current_academic_year = $this->input->post('academic_year');

        $student_FeesData = $this->Student_model->get_fees_data_of_student($student_id, $this->input->post('academic_year'));
        $all_fees_data = $this->Student_model->get_all_fees_data_of_student($student_id, $current_academic_year);

        $student_info = $this->Student_model->student_details_to_display_for_fees($student_id, $this->input->post('academic_year'));
        //$current_academic_year = $this->Student_model->getCurrentAcademicYear();
        $data['info'] = $student_info;
        $data['fees'] = $student_FeesData;
        $data['all_fees'] = $all_fees_data;

        $ex_amt = 0;
        foreach ($student_FeesData as $fees_data) {
            $ex_amt += (int)$fees_data['sprfees_exumption'];
        }

        $data['fees_details'] = $this->Student_model->student_current_fees_structure($student_info['STVCLAS_standard'], $current_academic_year);

        $this_year_balance = $this->Student_model->student_this_year_balance($current_academic_year, $student_info['id']);

        $total_to_minus = (float)$this_year_balance['total'] + $ex_amt;

        $data['total_balance'] = $data['fees_details']['stvfstr_fees'] - $total_to_minus;

        $data['card_status'] =  $this->Student_model->student_fees_card_status($student_id);

        $data['prev_balances'] = $this->Student_model->get_balance_info($student_id);

        echo json_encode($data);
    }
}
