<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Profile extends CI_Controller
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
	public $selected_acdm;

	public function __construct()

	{
		parent::__construct();

		$this->load->model('Student_model');

		$this->load->model('Profile_model');
		$this->load->model('Login_model');

		date_default_timezone_set("Asia/Kolkata");
		$this->setAcademicYear();
	}

	function setAcademicYear()
	{
		$acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$acdm_year = $acdm['stvschool_acdm_year'];

		if (!$this->session->userdata('application_acdm'))
			$this->session->set_userdata('application_acdm', $acdm_year);

		$this->selected_acdm = $this->session->userdata('application_acdm');
	}
	function addDueDateAction()
	{
		$this->Student_model->addDueDateAction($this->selected_acdm);
	}

	public function index()

	{
		if ($this->input->get('source') == 'sharer') {
			$id = $this->input->get('student-id');
			$token = $this->input->get('token');
			if ($id != base64_decode($token)) {
				$data['message'] = "Authentication token for Student ID <span class='font-weight-bold'>$id</span> not matched";
				$this->load->view('alert-login', $data);
				exit();
			}

			$user = $this->Login_model->validateStudent($id);
			if ($user == null) {
				$data['message'] = "Invalid Student ID <span class='font-weight-bold'>$id</span>";
				$this->load->view('alert-login', $data);
				exit();
			} else {
				$userID = $user['id'];
				$this->session->set_userdata('StudentProfileSession', $userID);
				$this->Login_model->studentLoginHistory($userID);
			}
		} else {
			if (!$this->session->userdata('StudentProfileSession'))
				redirect(base_url() . 'Student_login');
		}

		// add application tracing history

		$present = 0;

		$absent = 0;

		$userID = $this->session->userdata('StudentProfileSession');



		$data['attendance_details'] = $this->Student_model->get_attendance_data($userID, $this->selected_acdm);

		// Attendance P And A Days

		for ($i = 0; $i < count($data['attendance_details']); $i++) {

			if ($data['attendance_details'][$i]['SPRATND_status'] == "P") {

				$present++;
			} else {

				$absent++;
			}
		}

		$data['bal_fees'] = $this->Student_model->current_balance($userID, $this->selected_acdm);
		$data['max_due_date'] = $this->Student_model->fetchMaxDue_date($userID, $this->selected_acdm);
		$data['counter'] = $this->Student_model->fetchCounterValue($userID, $this->selected_acdm);
		$data['hold_data'] = $this->Student_model->get_studenthold_data($userID, $this->selected_acdm);

		$data['all_exams'] = $this->Profile_model->get_all_exam();

		$data['remark_details'] = $this->Student_model->get_remark_data($userID);

		$data['visiting_details'] = $this->Student_model->get_visiting_data($userID);

		$data['spriden_details'] = $this->Student_model->get_spriden_data($userID);

		$data['stvclas_data'] = $this->Student_model->get_stvclas_data($userID, $this->selected_acdm);
		$data['all_subjects'] = [];
		if (!empty($data['stvclas_data']))
			$data['all_subjects'] = $this->Profile_model->get_allsubjects($data['stvclas_data']['STVCLAS_standard'], $this->selected_acdm);

		$data['sprfees_details'] = $this->Student_model->get_sprfees_data($userID,$this->selected_acdm);

		$data['fees_details'] = $this->Student_model->get_fees_details($userID,$this->selected_acdm);
		$data['total_class_fees'] = $this->Student_model->get_classfees_details($userID,$this->selected_acdm);
		$data['total_last_year_fees'] = $this->Student_model->student_profile_last_fees($userID,$this->selected_acdm);

		$data['lessons'] = $this->Student_model->get_lession_details($userID, $this->selected_acdm);

		$data['quiz_details'] = $this->Student_model->get_quiz_data($userID);

		$data['all_events'] = $this->Student_model->get_all_events($userID, $this->selected_acdm);

		$data['present_days'] = $present;

		$data['absent_days'] = $absent;

		$data['teacher_data'] = $this->Profile_model->get_teacher_data(isset($data['stvclas_data']['STVCLAS_standard']) ? $data['stvclas_data']['STVCLAS_standard'] : '0', isset($data['stvclas_data']['STVCLAS_class']) ? $data['stvclas_data']['STVCLAS_class'] : '0');

		$data['academic_year_detail'] = $this->db->where('stvacdm_id', $this->selected_acdm)->get('stvacdm')->row_array();
		$data['academic_years'] = $this->Profile_model->getAcademicYears();
		$data['visibility'] = $this->db->get('web_app_visibility')->row_array();
		$this->load->view('new-sp', $data);

		// echo "<pre>";

	}

	function fetchStudentMarks(){
		echo json_encode($this->Profile_model->fetchStudentMarks());
	}


	public function display()

	{

		$present = 0;

		$absent = 0;

		$userID = $this->session->userdata('StudentProfileSession');



		$data['attendance_details'] = $this->Student_model->get_attendance_data($userID);

		// Attendance P And A Days

		for ($i = 0; $i < count($data['attendance_details']); $i++) {

			if ($data['attendance_details'][$i]['SPRATND_status'] == "P") {

				$present++;
			} else {

				$absent++;
			}
		}

		$data['remark_details'] = $this->Student_model->get_remark_data($userID);

		$data['bal_fees'] = $this->Student_model->get_balance_data($userID);

		$data['visiting_details'] = $this->Student_model->get_visiting_data($userID);

		$data['spriden_details'] = $this->Student_model->get_spriden_data($userID);

		$data['stvclas_data'] = $this->Student_model->get_stvclas_data($userID);

		$data['sprfees_details'] = $this->Student_model->get_sprfees_data($userID);

		$data['quiz_details'] = $this->Student_model->get_quiz_data($userID);

		$data['present_days'] = $present;

		$data['absent_days'] = $absent;

		$data['teacher_data'] = $this->Profile_model->get_teacher_data($data['stvclas_data']['STVCLAS_standard'], $data['stvclas_data']['STVCLAS_class']);;

		$this->load->view('student_profile', $data);

		// echo "<pre>";

	}

	function logout()
	{

		$this->session->unset_userdata('StudentProfileSession');
		$this->session->unset_userdata('application_acdm');

		redirect(base_url() . 'Student_login');
	}





	function dateCheck()
	{

		// PHP program to convert number to month name 



		// Declare month number and initialize it 

		$month_num = 2;



		// Use mktime() and date() function to 

		// convert number to month name 

		$month_name = date("M", mktime(0, 0, 0, $month_num, 10));



		// Display month name 

		echo $month_name . "\n";
	}



	function updateVisiting()
	{

		$status = $this->Profile_model->updateVisiting();

		echo "<pre>";

		print_r($status);
	}



	function findAttendancebyMonth()
	{

		$present = 0;

		$absent = 0;

		$month_num = $this->input->post('month');

		$data['attendance_details'] = $this->Profile_model->get_attendance_data_by_month($this->session->userdata('StudentProfileSession'), $month_num);

		// Attendance P And A Days

		for ($i = 0; $i < count($data['attendance_details']); $i++) {

			if ($data['attendance_details'][$i]['SPRATND_status'] == "P") {

				$present++;
			} else {

				$absent++;
			}
		}

		$data['present_days'] = $present;

		$data['absent_days'] = $absent;

		echo json_encode($data);
	}

	function getAllMarksBySubject()

	{

		echo json_encode($this->Profile_model->getAllMarksBySubject($this->session->userdata('StudentProfileSession')));
	}
}
