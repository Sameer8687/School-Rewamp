<?php defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends MY_Controller
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
		if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
		$this->load->model('Student_model');
		$this->load->model('MasterForm_model');
		$this->load->model('Teacher_model');
		$this->load->model('Stats_model');
		$this->load->model('Login_model');
		$this->load->model('Timetable_model');
		$this->load->model('dashboard_model');

		$this->load->model('Modules_Model');
		$this->load->model('Forms_Model');
		$this->load->model('User_Model');
		date_default_timezone_set('Asia/Kolkata');
	}




	public function index()
	{

		//Get User Details
		$totalCount = $this->Stats_model->get_total_count();
		$lastLoginData = $this->Stats_model->last_login();
		$currentLoginData = $this->Stats_model->current_login();
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['user'] = $userdetail;
		$data['count'] = $totalCount;
		$data['lastLoginData'] = $lastLoginData;
		$data['currentLoginData'] = $currentLoginData;
		$data['active'] = $this->Stats_model->student_total_active();
		$data['rte'] = $this->Stats_model->rte_student();
		$data['inActive'] = $this->Stats_model->inActive_student();
		$data['counts'] = $this->Stats_model->get_all_std_count();
		$this->load->model('StudentDetails_model');
		$data['all_students'] = $this->StudentDetails_model->studentListAll();
		$data['chart_data'] = $this->Stats_model->chart_data();

		// $data['academic_details'] = $this->Stats_model->getAcademicDetails();
		// $data['all_acdm_years'] = $this->Stats_model->getAcademicYearsForSet();
		// $data['all_due_records'] = $this->Stats_model->fetchDueDateRecords();
		$data['academic_details'] = $this->Stats_model->getAcademicDetails();
		$data['all_acdm_years'] = $this->Stats_model->getAcademicYearsForSet();

		if (!$this->session->userdata('academic_year')) {

			$current_academic_year = $this->Stats_model->getCurrentAcademicYear();

			if (!empty($current_academic_year)) {
				$this->session->set_userdata(
					'academic_year',
					$current_academic_year['stvacdm_id']
				);
			}
		}

		$data['all_due_records'] = $this->Stats_model->fetchDueDateRecords();
		//Till here change

		// ─────────────────────────────────────────────
		// Daily Fees Report stats for the dashboard card
		// ─────────────────────────────────────────────
		$dash_from = $this->input->get('dash_from') ?: date('Y-m-d');
		$dash_to = $this->input->get('dash_to') ?: date('Y-m-d');
		$dash_year = $this->input->get('dash_year') ?: 'current';
		$dash_new_admission = $this->input->get('dash_new_admission') ?: 'off';

		$data['dash_fees_stats'] = $this->Stats_model->daily_fees_report(
			$dash_from,
			$dash_to,
			'stats',
			$dash_year,
			$dash_new_admission
		);

		$data['dash_from'] = $dash_from;
		$data['dash_to'] = $dash_to;
		$data['dash_year'] = $dash_year;
		$data['dash_new_admission'] = $dash_new_admission;
		//till here

		// ─────────────────────────────────────────────
		// Teacher Daily Activity stats for dashboard card
		// ─────────────────────────────────────────────
		$this->load->model('Report_model');

		$tch_from = $this->input->get('tch_from') ?: date('Y-m-d');
		$tch_to = $this->input->get('tch_to') ?: date('Y-m-d');

		$data['tch_from'] = $tch_from;
		$data['tch_to'] = $tch_to;
		$data['tch_activities'] = $this->Report_model->fetchTeacherActivities($tch_from, $tch_to, null);
		//till here

		$data['filter_view'] = "dashboard";
		if ($data['academic_details']['stvacdm_end'] > date('Y-m-d')) {
			if ($userdetail['spruser_username'] == 'jafar') {
				$this->load->view('supervisor/dashboard', $data);
			} elseif ($userdetail['spruser_username'] == 'admin') {
				$this->load->view('dashboard1', $data); // load dashboard1 for admin
			} else {
				$this->load->view('dashboard', $data);
			}
		} else {
			$this->load->view('set-academic-year', $data);
		}
	}

	//for dashboard-new.php
	public function dashboard_new()
	{
		// Get User Details
		$totalCount = $this->Stats_model->get_total_count();
		$lastLoginData = $this->Stats_model->last_login();
		$currentLoginData = $this->Stats_model->current_login();
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		$data['user'] = $userdetail;
		$data['count'] = $totalCount;
		$data['lastLoginData'] = $lastLoginData;
		$data['currentLoginData'] = $currentLoginData;
		$data['active'] = $this->Stats_model->student_total_active();
		$data['rte'] = $this->Stats_model->rte_student();
		$data['inActive'] = $this->Stats_model->inActive_student();
		$data['counts'] = $this->Stats_model->get_all_std_count();

		$this->load->model('StudentDetails_model');
		$data['all_students'] = $this->StudentDetails_model->studentListAll();
		$data['chart_data'] = $this->Stats_model->chart_data();

		$data['academic_details'] = $this->Stats_model->getAcademicDetails();
		$data['all_acdm_years'] = $this->Stats_model->getAcademicYearsForSet();

		if (!$this->session->userdata('academic_year')) {
			$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
			if (!empty($current_academic_year)) {
				$this->session->set_userdata('academic_year', $current_academic_year['stvacdm_id']);
			}
		}

		$data['all_due_records'] = $this->Stats_model->fetchDueDateRecords();

		// ─── Daily Fees stats ───
		$dash_from = $this->input->get('dash_from') ?: date('Y-m-d');
		$dash_to = $this->input->get('dash_to') ?: date('Y-m-d');
		$dash_year = $this->input->get('dash_year') ?: 'current';
		$dash_new_admission = $this->input->get('dash_new_admission') ?: 'off';

		$data['dash_fees_stats'] = $this->Stats_model->daily_fees_report(
			$dash_from,
			$dash_to,
			'stats',
			$dash_year,
			$dash_new_admission
		);

		$data['dash_from'] = $dash_from;
		$data['dash_to'] = $dash_to;
		$data['dash_year'] = $dash_year;
		$data['dash_new_admission'] = $dash_new_admission;

		// ─── Teacher Daily Activity stats ───
		$this->load->model('Report_model');

		$tch_from = $this->input->get('tch_from') ?: date('Y-m-d');
		$tch_to = $this->input->get('tch_to') ?: date('Y-m-d');

		$data['tch_from'] = $tch_from;
		$data['tch_to'] = $tch_to;
		$data['tch_activities'] = $this->Report_model->fetchTeacherActivities($tch_from, $tch_to, null);

		$data['filter_view'] = "dashboard";
		$this->load->view('dashboard-new', $data);
	}

	//For Not Reloading Dashboard
	public function ajax_daily_fees_stats()
	{
		$from = $this->input->get('dash_from') ?: date('Y-m-d');
		$to = $this->input->get('dash_to') ?: date('Y-m-d');
		$year = $this->input->get('dash_year') ?: 'current';
		$new_admission = $this->input->get('dash_new_admission') ?: 'off';

		$stats = $this->Stats_model->daily_fees_report($from, $to, 'stats', $year, $new_admission);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'success' => true,
				'stats' => $stats,
			]));
	}

	public function ajax_teacher_activity_stats()
	{
		$this->load->model('Report_model');

		$from = $this->input->get('tch_from') ?: date('Y-m-d');
		$to = $this->input->get('tch_to') ?: date('Y-m-d');

		$activities = $this->Report_model->fetchTeacherActivities($from, $to, null);

		$attendance = [];
		$remarks = [];
		$absent_sms = [];
		$quiz = [];
		$marks = [];

		foreach ($activities as $act) {
			$name = trim($act['teacher_name'] ?? '');
			if ($name === '')
				continue;

			if ((int) ($act['total_attendance'] ?? 0) >= 1)
				$attendance[] = $name;
			if ((int) ($act['total_remark'] ?? 0) >= 1)
				$remarks[] = $name;
			if ((int) ($act['total_absent_sms'] ?? 0) >= 1)
				$absent_sms[] = $name;
			if ((int) ($act['total_quiz'] ?? 0) >= 1)
				$quiz[] = $name;
			if ((int) ($act['total_marks'] ?? 0) >= 1)
				$marks[] = $name;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'success' => true,
				'attendance' => $attendance,
				'remarks' => $remarks,
				'absent_sms' => $absent_sms,
				'quiz' => $quiz,
				'marks' => $marks,
			]));
	}
	//till here

	public function setAcademicYear()
	{
		$year = $this->input->post('academic_year', true);

		if (!empty($year)) {

			//$this->session->set_userdata('academic_year', $year);

			echo json_encode([
				'status' => true,
				'message' => 'Academic year updated'
			]);
		} else {

			echo json_encode([
				'status' => false,
				'message' => 'Academic year missing'
			]);
		}
	}

	// ------------------------------ Student View Section ----------------------------------------------
	//student admission form
	function admission_form()
	{
		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['academic_details'] = $this->Stats_model->getAcademicDetails();
		$this->load->view('Student/new-form-admission', $data);
	}
	//Students Profile Section
	function student_profile()
	{
		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Students from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$students = $this->Student_model->get_all_student_details();
		$data['user'] = $userdetail;
		$data['students'] = $students;
		$this->load->view('Student/student_profile', $data);
	}
	//student books amd result form
	function booksandresult()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get Books & Result Data with Joins
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$studentsbooksDetails = $this->Student_model->get_books_and_result_data_all();
		$data['user'] = $userdetail;
		$data['students_books'] = $studentsbooksDetails;
		$data['filter_view'] = "books_result";
		$this->load->view('Student/books', $data);
	}
	//student fees form
	public function fees()
	{
		$type = $this->input->get('type');
		$student_id = $this->input->get('student_id');
		$academic_year = $this->input->get('academic_year');

		// Current Academic Year
		$data['academic_details'] = $this->Stats_model->getAcademicDetails();

		// All Academic Years
		$all_years = $this->MasterForm_model->get_all_academic_years();

		if ($type == 'pre') {

			$current_academic_year = $data['academic_details']['stvacdm_id'];
			$academic_ids = !empty($academic_year)
				? explode('-', $academic_year)
				: [];

			if (!empty($academic_ids)) {
				$data['academic_years'] = array_filter($all_years, function ($year) use ($academic_ids) {
					return in_array((string) $year['stvacdm_id'], $academic_ids);
				});
			} else {
				$data['academic_years'] = array_filter($all_years, function ($year) use ($current_academic_year) {
					return $year['stvacdm_id'] != $current_academic_year;
				});
			}

			$data['selected_academic_year'] = $academic_year;
			$data['header_color'] = '#ad0048';
			$data['header_year'] = 'Previous Academic Years';
		} elseif ($type == 'current') {

			// Current + Previous = All Academic Years
			$data['academic_years'] = $all_years;

			$data['header_color'] = '#53769c';
			$data['header_year'] = 'All Academic Years';
		} else {

			// Current Academic Year Only
			$data['academic_years'] = array_filter($all_years, function ($year) use ($data) {
				return $year['stvacdm_id'] == $data['academic_details']['stvacdm_id'];
			});

			$data['header_color'] = '#53769c';
			$data['header_year'] = 'Current Academic Year ' . $data['academic_details']['stvacdm_year'];
		}

		$data['lastLoginData'] = $this->Stats_model->last_login();

		$data['user'] = $this->Stats_model->getUserDetails(
			$this->session->userdata('oliveSchoolDashboardUser')
		);

		$data['students_books'] = $this->Student_model->get_books_and_result_data_all();

		$data['filter_view'] = 'fees-v1';
		$data['type'] = $type;
		$data['student_id'] = $student_id;

		$this->load->view('Student/fees-v1', $data);
	}

	//student books amd result form
	function fees_report($academic_year = null)
	{
		// Get User Details
		$data['academic_details'] = $this->Stats_model->getAcademicDetails();

		// Determine which academic year to use
		if ($this->input->post('fees_academic_year')) {
			$selected_year = $this->input->post('fees_academic_year');
		} elseif ($academic_year) {
			$selected_year = $academic_year;
		} else {
			$selected_year = $data['academic_details']['stvacdm_id'];
		}

		$feesDetails = $this->Stats_model->get_fees_report_by_academic_year($selected_year);
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['feesDetails'] = $feesDetails;
		$data['all_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['filter_view'] = "fees_report";
		$data['selected_year'] = $selected_year; // Pass the selected year to view

		$this->load->view('Student/fees_report', $data);
	}


	// ------------------------------ Teacher View Section ----------------------------------------------
	//Generate Teacher ID 
	function generateID()
	{
		static $nos = 0001;
		$maxId = $this->Teacher_model->get_MaxID();
		$main_id = '';
		$acdm_year = '';
		$academicYear = $this->Stats_model->getAcademicYear();

		if ($academicYear != 0) {
			$acdm_year = $academicYear['stvacdm_year'];
			$acdm_substr = substr($acdm_year, 0, 4);
			$lastID_substr = substr($maxId, 1, 4);
			if ($lastID_substr == $acdm_substr) {
				$newstring = substr($maxId, -4);
				$newstring += 1;
				$newstring = sprintf('%04d', $newstring);
				$main_id = "E" . $acdm_substr . $newstring;
				return $main_id;
			} else {
				$maxId = $this->Teacher_model->get_ExistedMaxID($acdm_substr);

				$newstring = $maxId != '' ? substr($maxId, -4) : 0000;
				$newstring += 1;
				$newstring = sprintf('%04d', $newstring);
				$nos = sprintf('%04d', $nos);
				$main_id = "E" . $acdm_substr . $newstring;
				return $main_id;
			}
		} else {
			return 0;
		}
	}
	//Teacher registration form
	function add_account()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['new_id'] = $this->generateID();
		$data['filter_view'] = "add_account";
		$this->load->view('Teacher/add_account', $data);
	}


	// ------------------------------ Class View Section ----------------------------------------------

	function assign_teacher()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$classes = $this->Stats_model->getAllClasses();
		$teachers = $this->Teacher_model->getAllTeachers();
		$lastLoginData = $this->Stats_model->last_login();

		// Read selected academic year from GET, default to current
		$filter_acdm = $this->input->get('acdm');
		if (!empty($filter_acdm)) {
			$data['selected_academic_year'] = $filter_acdm;
		} else {
			$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
			$data['selected_academic_year'] = !empty($current_academic_year) ? $current_academic_year['stvacdm_id'] : '';
		}

		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		// Pass selected year to filter the list
		$assigned_teachers = $this->MasterForm_model->get_all_assigned_teachers($data['selected_academic_year']);

		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['teachers'] = $teachers;
		$data['assigned_teachers'] = $assigned_teachers;
		$data['academic_year'] = $this->Stats_model->getAcademicDetails();
		$data['filter_view'] = "assign_teacher";
		$this->load->view('ClassForms/assign_teacher', $data);
	}

	//Create Class
	function create_class()
	{
		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$acdm = $this->MasterForm_model->get_all_academic_years();
		$all_non_class_students = $this->Stats_model->get_all_non_class_students();
		$teachers = $this->Teacher_model->getAllTeachers();
		$data['user'] = $userdetail;
		$data['all_non_class_students'] = $all_non_class_students;
		$data['all_years'] = $acdm;
		$data['classes'] = $classes;
		$data['teachers'] = $teachers;
		$data['filter_view'] = "create_class";
		$this->load->view('ClassForms/create_class', $data);
	}
	//Assign Subject
	function subject_teacher()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$teachers = $this->Teacher_model->getAllTeachers();
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['teachers'] = $teachers;
		$data['subject_teachers'] = $this->Teacher_model->get_all_subject_teacher();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['filter_view'] = "subject_teacher";
		$this->load->view('ClassForms/subject_teacher', $data);
	}
	//view Class
	function class()
	{
		$userdetail = $this->Stats_model->getUserDetails(
			$this->session->userdata('oliveSchoolDashboardUser')
		);

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;
		$data['all_acdm_years'] = $this->Stats_model->getAcademicYearsForSet();
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$classes = $this->Stats_model->getAllClasses();

		$data['user'] = $userdetail;
		$data['classes'] = $classes;

		$data['action'] = "no";

		$data['newStd'] = '';
		$data['newDiv'] = '';
		$data['newAcademicYear'] = '';

		$data['filter_view'] = 'student_class';

		if ($this->input->post('class')) {

			$data['dataforClass'] = $this->Student_model->getDataForClass();

			$data['action'] = "yes";

			$data['newStd'] = $this->input->post('class');

			$data['newDiv'] = $this->input->post('divi');

			$data['newAcademicYear'] = $this->input->post('acdm_year');
		}

		$this->load->view('ClassForms/student_class', $data);
	}
	// ================================= MASTER FORM SECTION ================================================
	//Subject Master Forms-----------------------------------------------------------------------------
	function stvsubj()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$subjects = $this->MasterForm_model->get_all_subjects();
		$classes = $this->Stats_model->getAllClasses();
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();

		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$data['classes'] = $classes;
		$data['user'] = $userdetail;
		$data['subjects'] = $subjects;
		$data['filter_view'] = "subject_master";
		$this->load->view('MasterForms/subject_master', $data);
	}
	//Exam Master Forms-----------------------------------------------------------------------------
	function stvexam()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$all_exam = $this->MasterForm_model->get_all_exam();
		$data['all_exam'] = $all_exam;
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['filter_view'] = "stvexam";
		$this->load->view('MasterForms/exam_master', $data);
	}
	//Hold Master Forms-----------------------------------------------------------------------------
	function stvhold()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$get_all_holds = $this->MasterForm_model->get_all_holds();
		$data['all_holds'] = $get_all_holds;
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['filter_view'] = "hold_master";
		$this->load->view('MasterForms/hold_master', $data);
	}
	//Fees Master
	function stvfees()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$all_classes = $this->MasterForm_model->getAllClassesForFees();
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['all_classes'] = $all_classes;
		$data['user'] = $userdetail;
		$data['filter_view'] = "feesMaster";
		$this->load->view('MasterForms/feesMaster', $data);
	}
	//School Master
	function stvpsch()
	{

		//Get User Details
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$all_school_data = $this->MasterForm_model->get_all_school_data();
		//Get All Classes from DB
		$data['user'] = $userdetail;
		$data['schools'] = $all_school_data;
		$data['filter_view'] = "schoolMaster";
		$this->load->view('MasterForms/schoolMaster', $data);
	}
	//College Master
	function stvcolg()
	{

		//Get User Details
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$all_college_data = $this->MasterForm_model->get_all_college_data();
		//Get All Classes from DB
		$data['user'] = $userdetail;
		$data['colleges'] = $all_college_data;
		$data['filter_view'] = "collegeMaster";
		$this->load->view('MasterForms/collegeMaster', $data);
	}
	//College Master
	function stvuniv()
	{

		//Get User Details
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$all_college_data = $this->MasterForm_model->get_all_university_data();
		//Get All Classes from DB
		$data['user'] = $userdetail;
		$data['universities'] = $all_college_data;
		$data['filter_view'] = "universityMaster";
		$this->load->view('MasterForms/universityMaster', $data);
	}
	//Major Master Forms-----------------------------------------------------------------------------
	function stvmajr()
	{

		//Get User Details
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$major = $this->MasterForm_model->get_all_majors();
		$data['user'] = $userdetail;
		$data['majors'] = $major;
		$data['filter_view'] = "major_master";
		$this->load->view('MasterForms/major_master', $data);
	}
	//Subject Master Forms-----------------------------------------------------------------------------
	function stvshif()
	{

		//Get User Details
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$shifts = $this->MasterForm_model->get_all_shifts();
		$data['user'] = $userdetail;
		$data['shifts'] = $shifts;
		$data['filter_view'] = "shift_master";
		$this->load->view('MasterForms/shift_master', $data);
	}
	//Subject Master Forms-----------------------------------------------------------------------------
	function stvposn()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$positions = $this->MasterForm_model->get_all_positions();
		$data['user'] = $userdetail;
		$data['positions'] = $positions;
		$data['filter_view'] = "position_master";
		$this->load->view('MasterForms/position_master', $data);
	}
	//Grades Master Forms-----------------------------------------------------------------------------
	function stvgrds()
	{

		//Get User Details
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$grades = $this->MasterForm_model->get_all_grades();
		$data['user'] = $userdetail;
		$data['grades'] = $grades;
		$data['filter_view'] = "grades_master";
		$this->load->view('MasterForms/grades_master', $data);
	}
	//Grades Master Forms-----------------------------------------------------------------------------
	function stvacdm()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$academic_years = $this->MasterForm_model->get_all_academic_years();
		$data['user'] = $userdetail;
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['academic_years'] = $academic_years;
		$data['filter_view'] = "academic_master";
		$this->load->view('MasterForms/academic_master', $data);
	}
	//--------------- History -----------------------------------------
	function stvlogh() //Login History
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$login = $this->MasterForm_model->getAllLoginData();
		$users = $this->Stats_model->getAllUsers();
		$data['login_data'] = $login;
		$data['user'] = $userdetail;
		$data['allusers'] = $users;
		$data['filter_view'] = "loginHistory";
		$this->load->view('MasterForms/loginHistory', $data);
	}
	function stvfrmh() //Form Access History
	{
		// $form_access_data = array (
		// 	'stvfrmh_userid' => $this->session->userdata('oliveSchoolDashboardUser'),
		// 	'stvfrmh_date' => date('Y-m-d'),
		// 	'stvfrmh_time' => date('h:i:s'),
		// 	'stvfrmh_form_code' => "stvfrmh"
		// );
		// $this->Stats_model->add_form_data($form_access_data);
		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$form_data = $this->Stats_model->getAllFormAccessData();
		$users = $this->Stats_model->getAllUsers();
		$data['form_data'] = $form_data;
		$data['user'] = $userdetail;
		$data['allusers'] = $users;
		$data['filter_view'] = "formHistory";
		$this->load->view('MasterForms/formHistory', $data);
	}
	//States Master Forms-----------------------------------------------------------------------------
	function stvstts()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$states = $this->MasterForm_model->get_all_states();
		$data['user'] = $userdetail;
		$data['states'] = $states;
		$data['filter_view'] = "state_master";
		$this->load->view('MasterForms/state_master', $data);
	}

	//City Master Forms-----------------------------------------------------------------------------
	function stvcity()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$cities = $this->MasterForm_model->get_all_cities();
		$states = $this->MasterForm_model->get_all_states();
		$data['states'] = $states;
		$data['user'] = $userdetail;
		$data['cities'] = $cities;
		$data['filter_view'] = "city_master";
		$this->load->view('MasterForms/city_master', $data);
	}

	//Form Access Master Forms-----------------------------------------------------------------------------
	function stvfacs()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$users = $this->Stats_model->getAllUsers();
		$forms = $this->Stats_model->getallForm_name();
		$all_forms = $this->MasterForm_model->getAllFormAccessData();
		//Get All details from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['users'] = $users;
		$data['forms'] = $forms;
		$data['all_forms'] = $all_forms;
		$data['filter_view'] = "form_access_master";
		$this->load->view('MasterForms/form_access_master', $data);
	}

	//Form Access Master Forms-----------------------------------------------------------------------------
	function stvusrs()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$users = $this->Stats_model->getAllUsers();
		//Get All details from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['users'] = $users;
		$data['filter_view'] = "user_master";
		$this->load->view('MasterForms/user_master', $data);
	}

	//Form Access Master Forms-----------------------------------------------------------------------------
	function stvpcnf()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$users = $this->Stats_model->getAllUsers();
		//Get All details from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['users'] = $users;
		$data['filter_view'] = "communication_master";
		$this->load->view('MasterForms/communication_master', $data);
	}

	//========================= Attendance Masters ============================================
	//Student Attendance --------------------------------------------------------
	function student_attendance()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$users = $this->Stats_model->getAllUsers();
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['user'] = $userdetail;
		$data['filter_view'] = "student_attendance";
		$this->load->view('Attendance/student_attendance', $data);
	}
	function not_found()
	{
		$this->load->view('notfound');
	}


	//===================== Time Table Management ==============================
	//Time table hours --------------------------------------------------------
	function create_time()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['all_times'] = $this->Timetable_model->get_all_time();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['filter_view'] = "create-time";
		$this->load->view('MasterForms/create-time', $data);
	}

	//Time table Generator --------------------------------------------------------
	function create_timetable()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['all_times'] = $this->Timetable_model->get_all_time();
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['filter_view'] = "create-timetable";
		$this->load->view('MasterForms/create-timetable', $data);
	}

	//Time table Generator --------------------------------------------------------
	function view_timetable()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['all_times'] = $this->Timetable_model->get_all_time();
		$classes = $this->Stats_model->getAllClasses_div();
		$data['classes'] = $classes;
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['filter_view'] = "new-view-timetable";
		$this->load->view('MasterForms/new-view-timetable', $data);
	}

	// ------------------------------ Event management View Section ----------------------------------------------
	//event
	function events()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$lastThreeEvents = $this->Stats_model->getAllEventsForDispay();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['all_events'] = $lastThreeEvents;
		$data['filter_view'] = "event";
		$this->load->view('event', $data);
	}
	// ------------------------------ Remark View Section ----------------------------------------------
	//remark
	function remark()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$all_remark_data = $this->Teacher_model->get_all_remark();
		$data['lastLoginData'] = $lastLoginData;
		$data['all_remark_data'] = $all_remark_data;
		$data['user'] = $userdetail;
		$data['filter_view'] = 'student_remark';
		$this->load->view('Teacher/remark', $data);
		//print_r($data['all_remark_data']);
	}

	function visiting()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$allColumns = $this->Stats_model->getcolumnName();
		$classes = $this->Stats_model->getAllClasses();
		$visitinf = $this->Student_model->getAllVisitingData();
		$data['allColumns'] = $allColumns;
		$data['classes'] = $classes;
		$data['lastLoginData'] = $lastLoginData;
		$login = $this->MasterForm_model->getAllLoginData();
		$users = $this->Stats_model->getAllUsers();
		$data['user'] = $userdetail;

		if (isset($_POST['filterVisiButton'])) {
			$col_name = $this->input->post('filter_col');
			$input_data = $this->input->post('filter_data');
			$data['all_visiting'] = $this->Student_model->filterVisitingData($col_name, $input_data);
		} else {
			$data['all_visiting'] = $visitinf;
		}
		$data['filter_view'] = "visiting";
		$this->load->view('Student/visiting', $data);
	}

	function sprhold()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();

		$all_hold_types = $this->Stats_model->get_all_hold_types();
		$all_hold_data = $this->Student_model->get_all_hold_data();
		$data['all_hold_types'] = $all_hold_types;
		$data['all_hold_data'] = $all_hold_data;

		$data['lastLoginData'] = $lastLoginData;
		$login = $this->MasterForm_model->getAllLoginData();
		$users = $this->Stats_model->getAllUsers();

		$data['user'] = $userdetail;
		$data['filter_view'] = "hold_student";

		$this->load->view('Student/hold_student', $data);
	}

	// --------------------------- Log out from Dashboard -----------------------------------------
	function logout()
	{

		$data = array(
			'stvlgin_date' => date('Y-m-d'),
			'stvlgin_time' => date('H:i:s'),
			'stvlgin_userid' => $this->session->userdata('oliveSchoolDashboardUser'),
			'stvlgin_status' => "Logged Out"
		);
		$this->Login_model->addLogoutHistory($data);
		//------------------------------------------------------------------------------------------
		$status = $this->session->unset_userdata('oliveSchoolDashboardUser');
		$urlPath = base_url() . 'login/';
		echo $urlPath;
	}

	function testprint()
	{
		$this->load->view('email-template');
	}


	// Started From Here --- 28 SEp 2021

	function stvlmst()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['lessons'] = $this->MasterForm_model->get_all_lessons();
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['filter_view'] = "add_lesson";
		$this->load->view('MasterForms/add_lesson', $data);
	}

	//To Here --- 28 SEp 2021

	function change_password()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['filter_view'] = "change-password";
		$this->load->view('change-password', $data);
	}

	function rte_students()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$this->load->view('Student/rte', $data);
	}
	function stvlvm()
	{
		$name = $this->MasterForm_model->getDriverDetails();
		$data['name'] = $name;
		$vehicle = $this->MasterForm_model->showList();
		$data['vehicles'] = $vehicle;
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['filter_view'] = "vehicle_master";
		$this->load->view('MasterForms/vehicle_master', $data);
	}
	function stvldrm()
	{
		$driver = $this->MasterForm_model->allData();
		$data['drivers'] = $driver;
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['filter_view'] = "driver_master";
		$this->load->view('MasterForms/driver_master', $data);
	}
	function structure()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['user'] = $userdetail;
		$data['classes'] = $this->MasterForm_model->getClass();
		$data['acadmic'] = $this->MasterForm_model->getAcdmYear();
		$data['records'] = $this->MasterForm_model->getData();
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['filter_view'] = "fees_structure";
		$this->load->view('MasterForms/fees_structure', $data);
	}


	// --------------------------- for promote ---------------------------------------
	public function promote()
	{
		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['filter_view'] = "promote_cls";
		$this->load->view('promoteclass/promote_cls', $data);
	}

	function viewClass()
	{
		$this->load->model('Dashboard_model');
		echo json_encode($this->Dashboard_model->getStudents($this->input->post('std')));
	}
	function updateclass()
	{
		$this->load->model('Dashboard_model');
		echo json_encode($this->Dashboard_model->updateclass());
	}


	// ===============================================================================================================
	function Form_clg()
	{
		$this->load->view('Form_college');
	}
	//  ================================================================================================================
	function add_college_form()
	{

		$this->load->model('College_Form');

		$this->College_Form->add();

		redirect(base_url('welcome/Form_clg'));
	}
	function submit()
	{
		$this->load->view('Form_submit');
	}
	// ====================================================================================================================
	public function fetching()
	{
		$data['student_details'] = $this->College_Form->get_student_details();


		$this->load->view('Fetch_clg', $data);
	}
	public function edit($id)
	{
		$this->load->model('College_Form'); // Model ko load karna na bhoolein
		$data['student'] = $this->College_Form->get_student_by_id($id);
		$this->load->view('edit_formclg', $data);
	}

	public function view($id = null)
	{
		if (!isset($id)) {
			echo "Tu ID PRovide kar re bhaya";
			exit();
		}
		$this->load->model('College_Form'); // Model ko load karna na bhoolein
		$data['student'] = $this->College_Form->get_student_by_id($id);
		$this->load->view('view_formclg', $data);
	}

	public function update()
	{
		$this->load->model('College_Form'); // Model ko load karna na bhoolein
		$id = $this->input->post('id');
		$student_data = array(
			'db_branch' => $this->input->post('db_branch'),
			'db_stream' => $this->input->post('db_stream'),
			'db_admissiontype' => $this->input->post('db_admissiontype'),
			'db_class' => $this->input->post('db_class'),
			'db_medium' => $this->input->post('db_medium'),
			'firstname' => $this->input->post('firstname'),
			'Middlename' => $this->input->post('Middlename'),
			'lastname' => $this->input->post('lastname'),
			'date' => $this->input->post('date'),
			'db_gender' => $this->input->post('db_gender'),
			'db_place' => $this->input->post('db_place'),
			'db_religion' => $this->input->post('db_religion'),
			'db_caste' => $this->input->post('db_caste'),
			'db_adharnumber' => $this->input->post('db_adharnumber'),
			'db_nationality' => $this->input->post('db_nationality'),
			'choice_subj_hin_mar' => $this->input->post('choice_subj_hin_mar'),
			'choice_math_geog_bio' => $this->input->post('choice_math_geog_bio'),
			'commerce_optional_sub' => $this->input->post('commerce_optional_sub'),
		);

		$this->College_Form->update_student($id, $student_data);
		redirect('welcome/fetching');
	}

	// Updated 2024 Aug 02 - kamran Ansari
	function updateStudentStatus()
	{
		$stud_id = $this->input->get('student-id');
		$status = $this->input->get('status');

		$this->Teacher_model->updateStudentStatus($stud_id, $status);
	}

	// =====================================================================================\
	// ----------------------start----------------------------
	// this functhions making by Aman 
	//  newly added 21 august

	// public function mode()
	// {
	// 	// Check if the form was submitted for insert or update
	// 	if ($this->input->post()) {
	// 		$module_id = $this->input->post('module_id');
	// 		$module_name = $this->input->post('modename');
	// 		$icon = $this->input->post('iconname');
	// 		$module_description = $this->input->post('modedesc');

	// 		// Prepare data for insertion/updation
	// 		$data = array(
	// 			'module_name' => $module_name,
	// 			'icon' => $icon,
	// 			'module_description' => $module_description
	// 		);

	// 		if ($module_id) {
	// 			// Update mode
	// 			$this->Modules_Model->update_module($module_id, $data);
	// 			$this->session->set_flashdata('success', 'Module updated successfully!');
	// 		} else {
	// 			// Insert mode
	// 			$this->Modules_Model->insert_module($data);
	// 			$this->session->set_flashdata('success', 'Module added successfully!');
	// 		}

	// 		// Redirect to avoid resubmission
	// 		redirect('Welcome/mode');
	// 	} else {
	// 		// Load the view
	// 		$this->load->view('modules');
	// 	}
	// }

	// public function fetchModule()
	// {
	// 	$data = $this->Modules_Model->fetch_module();
	// 	// You may want to send this as JSON if using AJAX for other purposes
	// 	$this->load->view('modules', array('modules' => $data));
	// }



	// public function deleteModule($id)
	// {
	// 	$this->Modules_Model->delete_module($id);
	// 	$this->session->set_flashdata('success', 'Module deleted successfully!');
	// 	redirect('Welcome/mode');
	// }

	// public function fetchSingleModule($id)
	// {
	// 	$data = $this->Modules_Model->fetch_single_module($id);
	// 	echo json_encode($data);
	// }

	// // =========

	// public function forms()
	// {
	// 	// Check if the form was submitted for insert or update
	// 	if ($this->input->post()) {
	// 		$form_id = $this->input->post('form_id');
	// 		$form_name = $this->input->post('formname');
	// 		$form_link = $this->input->post('formlink');
	// 		$module_id = $this->input->post('moduleid');

	// 		// Prepare data for insertion/updation
	// 		$data = array(
	// 			'form_name' => $form_name,
	// 			'form_link' => $form_link,
	// 			'module_id' => $module_id
	// 		);

	// 		if ($form_id) {
	// 			// Update mode
	// 			$this->Forms_Model->update_form($form_id, $data);
	// 			$this->session->set_flashdata('success', 'Module updated successfully!');
	// 		} else {
	// 			// Insert mode
	// 			$this->Forms_Model->insert_form($data);
	// 			$this->session->set_flashdata('success', 'Module added successfully!');
	// 		}

	// 		// Redirect to avoid resubmission
	// 		redirect('Welcome/forms');
	// 	} else {
	// 		// Fetch data for the view
	// 		$data['modules'] = $this->Forms_Model->fetch_modules();
	// 		$data['forms'] = $this->Forms_Model->fetch_form();

	// 		// Load the view
	// 		$this->load->view('forms', $data);
	// 	}
	// }

	// public function fetchForm()
	// {
	// 	$data = $this->Forms_Model->fetch_form();
	// 	echo json_encode($data);
	// }

	// public function deleteForm($id)
	// {
	// 	$this->Forms_Model->delete_form($id);
	// 	$this->session->set_flashdata('success', 'Module deleted successfully!');
	// 	redirect('Welcome/forms');
	// }

	// public function fetchSingleForm($id)
	// {
	// 	$data = $this->Forms_Model->fetch_single_form($id);
	// 	echo json_encode($data);
	// }

	// public function fetchModules()
	// {
	// 	$data = $this->Forms_Model->fetch_modules();
	// 	echo json_encode($data);
	// }

	// // ==========

	// public function user()
	// {
	// 	$userId = $this->input->get('user_id');
	// 	$data['userId'] = $userId;
	// 	$data['allModules'] = $this->User_Model->fetch_modules_name();
	// 	$this->load->view('user_access', $data);
	// }


	// // Fetch the user data for the dropdown
	// public function fetchUsers()
	// {
	// 	$data = $this->User_Model->fetch_users();
	// 	echo json_encode($data);
	// }

	// // Fetch forms based on selected module ID
	// public function fetchFormsByModuleId()
	// {
	// 	$moduleId = $this->input->post('module_id');
	// 	$forms = $this->User_Model->getFormsByModuleId($moduleId);
	// 	echo json_encode($forms);
	// }

	// // Fetch user information based on user ID
	// public function fetchUserInfo()
	// {
	// 	$userId = $this->input->post('user_id');
	// 	$userInfo = $this->User_Model->fetch_user_info($userId);
	// 	echo json_encode($userInfo);
	// }

	// public function insertData()
	// {
	// 	$userId = $this->input->post('userid');
	// 	$modules = $this->input->post('module_id');
	// 	$forms = $this->input->post('form_id');
	// 	$access = $this->input->post('access');
	// 	$fromDates = $this->input->post('from_date');
	// 	$toDates = $this->input->post('to_date');
	// 	$hold = $this->input->post('hold');

	// 	foreach ($modules as $key => $moduleId) {
	// 		$formId = $forms[$key];

	// 		// Check if record with the same user_id, module_id, and form_id already exists
	// 		$this->db->select('id');  // Select only the ID for existence check
	// 		$this->db->where('user_id', $userId);
	// 		$this->db->where('module_id', $moduleId);
	// 		$this->db->where('form_id', $formId);
	// 		$query = $this->db->get('user_access');

	// 		$data = array(
	// 			'access' => $access[$key],
	// 			'from_date' => $fromDates[$key],
	// 			'to_date' => $toDates[$key],
	// 			'hold' => $hold[$key]
	// 		);

	// 		// If record exists, update it
	// 		if ($query->num_rows() > 0) {
	// 			$this->db->where('user_id', $userId);
	// 			$this->db->where('module_id', $moduleId);
	// 			$this->db->where('form_id', $formId);
	// 			$this->db->update('user_access', $data);
	// 		} else {
	// 			// If no record exists, insert a new one
	// 			$data['user_id'] = $userId;
	// 			$data['module_id'] = $moduleId;
	// 			$data['form_id'] = $formId;
	// 			$this->db->insert('user_access', $data);
	// 		}
	// 	}

	// 	// Redirect to the user access page after save
	// 	redirect('Welcome/user');
	// }

	// // =========================

	// public function updateAccess($userId)
	// {

	// 	// Load the model
	// 	$this->load->model('User_Model');

	// 	// Fetch user info
	// 	$userInfo = $this->User_Model->fetch_user_info($userId);

	// 	// Fetch user access data
	// 	$userAccess = $this->User_Model->fetch_access($userId);

	// 	// Fetch all modules for the dropdown
	// 	$allModules = $this->User_Model->fetch_modules_name();

	// 	// Prepare data for the view
	// 	$data = [
	// 		'userId' => $userId,
	// 		'userInfo' => $userInfo,
	// 		'userAccess' => $userAccess,
	// 		'allModules' => $allModules,
	// 	];

	// 	// Load the view with fetched data
	// 	$this->load->view('update_access', $data);
	// }

	// public function updateData()
	// {
	// 	// Load the model
	// 	$this->load->model('User_Model');

	// 	// Get posted data
	// 	$userId = $this->input->post('userid');
	// 	$accessIds = $this->input->post('access_id'); // The hidden IDs of each row
	// 	$moduleIds = $this->input->post('module_id');
	// 	$formIds = $this->input->post('form_id');
	// 	$access = $this->input->post('access');
	// 	$fromDates = $this->input->post('from_date');
	// 	$toDates = $this->input->post('to_date');
	// 	$holds = $this->input->post('hold');
	// 	$deletedRows = json_decode($this->input->post('deleted_rows'), true); // Get the deleted rows

	// 	// Update the access data
	// 	foreach ($moduleIds as $key => $moduleId) {
	// 		$data = [
	// 			'module_id' => $moduleId,
	// 			'form_id' => $formIds[$key],
	// 			'access' => $access[$key],
	// 			'from_date' => $fromDates[$key],
	// 			'to_date' => $toDates[$key],
	// 			'hold' => $holds[$key],
	// 		];

	// 		// Update user access based on row ID
	// 		$this->User_Model->update_data($accessIds[$key], $data);
	// 	}

	// 	// Delete rows from the database if they exist
	// 	if (!empty($deletedRows)) {
	// 		foreach ($deletedRows as $deleteId) {
	// 			if (!empty($deleteId)) { // Ensure the ID is not empty
	// 				$this->User_Model->delete_access($deleteId);
	// 			}
	// 		}
	// 	}

	// 	// Redirect to the appropriate page
	// 	redirect('Welcome/user_report');
	// }



	// public function getForms()
	// {
	// 	// Load the model
	// 	$this->load->model('User_Model');

	// 	// Get the module ID from the POST request
	// 	$moduleId = $this->input->post('moduleId');

	// 	// Fetch forms related to the module
	// 	$forms = $this->User_Model->getFormsByModuleId($moduleId);

	// 	// Generate options for the form select dropdown
	// 	$formOptions = '<option value="">Select Form</option>';
	// 	foreach ($forms as $form) {
	// 		$formOptions .= '<option value="' . $form['id'] . '">' . $form['form_name'] . '</option>';
	// 	}

	// 	// Return the options as the response
	// 	echo $formOptions;
	// }


	// // ==============

	// public function user_report()
	// {
	// 	$data['user_reports'] = $this->User_Model->getUserReports();
	// 	$this->load->view('user_report', $data);
	// }

	// public function delete_user($user_id)
	// {
	// 	$this->User_Model->deleteUserAccess($user_id);
	// 	redirect('Welcome/user_report');
	// }

	// // ==============

	// public function access_List()
	// {
	// 	$user_id = $this->input->get('user_id'); // Get the user_id from the URL query string
	// 	$this->load->model('User_Model');

	// 	// Fetch the access list for the specific user
	// 	$data['access_list'] = $this->User_Model->fetchAccessList($user_id);


	// 	$this->load->view('access_report_list', $data);
	// }



	public function mode()
	{
		// Check if the form was submitted for insert or update
		if ($this->input->post()) {
			$module_id = $this->input->post('module_id');
			$module_name = $this->input->post('modename');
			$icon = $this->input->post('iconname');
			$module_description = $this->input->post('modedesc');

			// Prepare data for insertion/updation
			$data = array(
				'module_name' => $module_name,
				'icon' => $icon,
				'module_description' => $module_description
			);

			if ($module_id) {
				// Update mode
				$this->Modules_Model->update_module($module_id, $data);
				$this->session->set_flashdata('success', 'Module updated successfully!');
			} else {
				// Insert mode
				$this->Modules_Model->insert_module($data);
				$this->session->set_flashdata('success', 'Module added successfully!');
			}

			// Redirect to avoid resubmission
			redirect('Welcome/mode');
		} else {
			// Load the view
			$this->load->view('modules');
		}
	}

	public function fetchModule()
	{
		$data = $this->Modules_Model->fetch_module();
		// You may want to send this as JSON if using AJAX for other purposes
		$this->load->view('modules', array('modules' => $data));
	}



	public function deleteModule($id)
	{
		$this->Modules_Model->delete_module($id);
		$this->session->set_flashdata('success', 'Module deleted successfully!');
		redirect('Welcome/mode');
	}

	public function fetchSingleModule($id)
	{
		$data = $this->Modules_Model->fetch_single_module($id);
		echo json_encode($data);
	}

	// =========

	public function forms()
	{
		// Check if the form was submitted for insert or update
		if ($this->input->post()) {
			$form_id = $this->input->post('form_id');
			$form_name = $this->input->post('formname');
			$form_code = $this->input->post('formcode');
			$form_type = $this->input->post('formtype');
			$form_link = $this->input->post('formlink');
			$module_id = $this->input->post('moduleid');

			// Prepare data for insertion/updation
			$data = array(
				'form_name' => $form_name,
				'form_code' => $form_code,
				'form_type' => $form_type,
				'form_link' => $form_link,
				'module_id' => $module_id
			);

			if ($form_id) {
				// Update mode
				$this->Forms_Model->update_form($form_id, $data);
				$this->session->set_flashdata('success', 'Module updated successfully!');
			} else {
				// Insert mode
				$this->Forms_Model->insert_form($data);
				$this->session->set_flashdata('success', 'Module added successfully!');
			}

			// Redirect to avoid resubmission
			redirect('Welcome/forms');
		} else {
			// Fetch data for the view
			$data['modules'] = $this->Forms_Model->fetch_modules();
			$data['forms'] = $this->Forms_Model->fetch_form();

			// Load the view
			$this->load->view('forms', $data);
		}
	}

	public function fetchForm()
	{
		$data = $this->Forms_Model->fetch_form();
		echo json_encode($data);
	}

	public function deleteForm($id)
	{
		$this->Forms_Model->delete_form($id);
		$this->session->set_flashdata('success', 'Module deleted successfully!');
		redirect('Welcome/forms');
	}

	public function fetchSingleForm($id)
	{
		$data = $this->Forms_Model->fetch_single_form($id);
		echo json_encode($data);
	}

	public function fetchModules()
	{
		$data = $this->Forms_Model->fetch_modules();
		echo json_encode($data);
	}

	// ==========

	public function user()
	{
		$userId = $this->input->get('user_id');
		$data['userId'] = $userId;
		$data['allModules'] = $this->User_Model->fetch_modules_name();
		$this->load->view('user_access', $data);
	}

	public function fetchUsers()
	{
		$data = $this->User_Model->fetch_users();
		echo json_encode($data);
	}

	public function fetchFormsByModuleId()
	{
		$moduleId = $this->input->post('module_id');

		if (!empty($moduleId)) {

			$forms = $this->User_Model->getFormsByModuleId($moduleId);

			if (!empty($forms)) {
				echo json_encode($forms);
			} else {
				echo json_encode([]);
			}
		} else {
			echo json_encode([]);
		}
	}

	public function fetchUserInfo()
	{
		$userId = $this->input->post('user_id');
		$userInfo = $this->User_Model->fetch_user_info($userId);
		echo json_encode($userInfo);
	}

	public function insertData()
	{
		$userId = $this->input->post('userid');
		$modules = $this->input->post('module_id');
		$forms = $this->input->post('form_id');
		$access = $this->input->post('access');
		$fromDates = $this->input->post('from_date');
		$toDates = $this->input->post('to_date');
		$hold = $this->input->post('hold');

		foreach ($modules as $key => $moduleId) {
			$formId = $forms[$key];
			$accessLevel = $access[$key];
			$fromDate = $fromDates[$key];
			$toDate = $toDates[$key];
			$holdStatus = $hold[$key];

			// Check if a record already exists for the same user_id, module_id, and form_id
			$this->db->select('id');
			$this->db->from('user_access');
			$this->db->where('user_id', $userId);
			$this->db->where('module_id', $moduleId);
			$this->db->where('form_id', $formId);
			$existingRecord = $this->db->get()->row();

			if ($existingRecord) {
				// Update the existing record
				$this->db->where('id', $existingRecord->id);
				$this->db->update('user_access', [
					'access' => $accessLevel,
					'from_date' => $fromDate,
					'to_date' => $toDate,
					'hold' => $holdStatus
				]);
			} else {
				// Insert a new record if no matching record exists
				$this->db->insert('user_access', [
					'user_id' => $userId,
					'module_id' => $moduleId,
					'form_id' => $formId,
					'access' => $accessLevel,
					'from_date' => $fromDate,
					'to_date' => $toDate,
					'hold' => $holdStatus
				]);
			}
		}

		// Redirect back to the user page
		redirect('Welcome/user');
	}





	public function deleteUserAccessRows()
	{

		$deletedRows = json_decode(file_get_contents('php://input'), true);
		if (!empty($deletedRows)) {

			$this->load->model('User_Model');

			$deleteSuccess = $this->User_Model->delete_user_access_rows($deletedRows);

			if ($deleteSuccess) {
				echo json_encode(['success' => true, 'message' => 'Rows deleted successfully']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to delete rows']);
			}
		} else {

			echo json_encode(['success' => false, 'message' => 'No rows to delete']);
		}
	}

	public function getSameAsUsers()
	{
		$this->load->model('User_Model');

		$data['users'] = $this->User_Model->getUsersWithAccess();

		echo json_encode($data['users']);
	}

	public function fetchUserAccessData()
	{

		$input = json_decode(file_get_contents('php://input'), true);
		$userId = $input['user_id'];

		$this->load->model('User_Model');
		$accessData = $this->User_Model->fetch_access($userId);

		echo json_encode($accessData);
	}


	// =========================

	public function updateAccess($userId)
	{

		// Load the model
		$this->load->model('User_Model');

		// Fetch user info
		$userInfo = $this->User_Model->fetch_user_info($userId);

		// Fetch user access data
		$userAccess = $this->User_Model->fetch_access($userId);

		// Fetch all modules for the dropdown
		$allModules = $this->User_Model->fetch_modules_name();

		// Prepare data for the view
		$data = [
			'userId' => $userId,
			'userInfo' => $userInfo,
			'userAccess' => $userAccess,
			'allModules' => $allModules,
		];

		// Load the view with fetched data
		$this->load->view('update_access', $data);
	}

	public function updateData()
	{
		// Load the model
		$this->load->model('User_Model');

		// Get posted data
		$userId = $this->input->post('userid');
		$accessIds = $this->input->post('access_id'); // The hidden IDs of each row
		$moduleIds = $this->input->post('module_id');
		$formIds = $this->input->post('form_id');
		$access = $this->input->post('access');
		$fromDates = $this->input->post('from_date');
		$toDates = $this->input->post('to_date');
		$holds = $this->input->post('hold');
		$deletedRows = json_decode($this->input->post('deleted_rows'), true); // Get the deleted rows

		// Update or insert access data
		foreach ($moduleIds as $key => $moduleId) {
			$data = [
				'module_id' => $moduleId,
				'form_id' => $formIds[$key],
				'access' => $access[$key],
				'from_date' => $fromDates[$key],
				'to_date' => $toDates[$key],
				'hold' => $holds[$key],
				'user_id' => $userId, // Ensure the user ID is set
			];

			if (!empty($accessIds[$key])) {
				// If access_id exists, update the row
				$this->User_Model->update_data($accessIds[$key], $data);
			} else {
				// If access_id is empty, insert a new row
				$this->User_Model->insert_data($data);
			}
		}

		// Delete rows from the database if they exist
		if (!empty($deletedRows)) {
			foreach ($deletedRows as $deleteId) {
				if (!empty($deleteId)) { // Ensure the ID is not empty
					$this->User_Model->delete_access($deleteId);
				}
			}
		}

		// Redirect to the appropriate page
		redirect('Welcome/user_report');
	}

	public function getForms()
	{
		// Load the model
		$this->load->model('User_Model');

		// Get the module ID from the POST request
		$moduleId = $this->input->post('moduleId');

		// Fetch forms related to the module
		$forms = $this->User_Model->getFormsByModuleId($moduleId);

		// Generate options for the form select dropdown
		$formOptions = '<option value="">Select Form</option>';
		foreach ($forms as $form) {
			$formOptions .= '<option value="' . $form['id'] . '">' . $form['form_name'] . '</option>';
		}

		// Return the options as the response
		echo $formOptions;
	}


	// ==============

	public function user_report()
	{
		$data['user_reports'] = $this->User_Model->getUserReports();
		$this->load->view('user_report', $data);
	}

	public function delete_user($user_id)
	{
		$this->User_Model->deleteUserAccess($user_id);
		redirect('Welcome/user_report');
	}

	// ==============

	public function access_List($spruser_id)
	{
		// Load the model
		$this->load->model('User_Model');

		// Fetch the access list for the specific user
		$data['access_list'] = $this->User_Model->fetchAccessList($spruser_id);

		// Fetch the user's name based on the spruser_id
		$data['spruser_name'] = $this->User_Model->fetchSingleUserName($spruser_id);

		// Load the view and pass the data
		$this->load->view('access_report_list', $data);
	}



	public function fetchSingleUserName($spruser_id)
	{
		$this->load->model('User_Model');
		$user_data = $this->User_Model->fetchSingleUserName($spruser_id);
		return $user_data['spruser_name'];
	}


	// ======= sidebar search =======


	public function searchForm()
	{
		$user_id = $this->session->userdata('oliveSchoolDashboardUser');

		$form_name = $this->input->post('form_name');
		$this->load->model('Forms_Model');
		$forms = $this->Forms_Model->getFormsByName($form_name); // Make sure this method is optimized

		if (!empty($forms)) {
			echo '<ul class="form-list" style="list-style-type: none; padding-left: 0;margin-top: 5px;">';
			foreach ($forms as $form) {
				echo '<li class="custom-bullet form-list-item" style=" background-color: #1e2633; color: white; border-radius: 5px; padding: 10px; transition: background-color 0.3s ease; border: 1px solid black;">
						<a href="javascript:void(0)" class="form-item" style="color: white; text-decoration: none;padding-left: 30px;" data-form-id="' . $form['id'] . '">'
					. $form['form_name'] .
					'</a>
					</li>';
			}
			echo '</ul>';
		} else {
			echo '<div class="form-not-found text-white text-center text-warning mt-3">Form not found!</div>';
		}
	}


	public function checkAccess()
	{
		$form_id = $this->input->post('form_id');
		$user_id = $this->session->userdata('oliveSchoolDashboardUser');  // session 'spruser_id'


		$this->load->model('Forms_Model');
		$access = $this->Forms_Model->checkUserAccess($user_id, $form_id);

		if ($access) {
			// If access granted, redirect to form link
			$form_link = $this->Forms_Model->getFormLink($form_id);
			echo json_encode([
				'status' => 'success',
				'form_link' => base_url($form_link)
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => "Form Not Authorized!"
			]);
		}
	}


	// ==================

	//  end added 27 on september


	//  end added 21 august
	// create by Ansari Kamran 07/10/24
	function attendance_students()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();

		$data['classes'] = $this->Stats_model->getAllClasses();

		$data['current_class'] = $this->input->get('std') ? $this->input->get('std') : '';
		$data['current_div'] = $this->input->get('div') ? $this->input->get('div') : '';
		// $data['current_date'] = $this->input->get('cur_date') && !empty($this->input->get('cur_date')) ? $this->input->get('cur_date') : date('Y-m-d');
		$data['current_date'] = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');

		$data['lastLoginData'] = $lastLoginData;

		$data['students'] = $this->Student_model->getStudAttendance($data['current_class'], $data['current_div'], $data['current_date']);
		$data['filter_view'] = 'student-report';
		$data['user'] = $userdetail;
		$this->load->view('Attendance', $data);
	}

	// create by Ansari Kamran 12/10/24
	function fees_massage()
	{
		// Get user details and last login data
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();

		// Prepare data
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$curr_acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$data['current_acdm'] = $this->input->get('acdm-year') ? $this->input->get('acdm-year') : $curr_acdm['stvschool_acdm_year'];
		$data['classes'] = $this->Stats_model->getAllClasses();

		// Last login data and user details
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;

		$data['current_class'] = $this->input->get('std') ? $this->input->get('std') : '';
		$data['current_div'] = $this->input->get('div') ? $this->input->get('div') : '';
		// Get fees massage data

		// $data['fees'] = $this->Student_model->getFeesMassage($data['current_class'], $data['current_div'], $data['current_acdm']);
		$data['user'] = $userdetail;
		$this->load->view('fees-massage', $data);
	}

	// create by Ansari Kamran 23/10/24
	function send_msg()
	{
		// Retrieve POST data
		$std = $this->input->post('std');
		$div = $this->input->post('div');
		$cur_acdm = $this->input->post('cur_acdm');

		// Check if all parameters are present (Good practice)
		if (empty($std) || empty($div) || empty($cur_acdm)) {
			// Return an empty array or an error message if parameters are missing
			echo json_encode([]);
			return;
		}

		$fees_data = $this->Student_model->getFeesMassage($std, $div, $cur_acdm);

		// 🔥 Calculate 'balance' for each student and ensure 'total' and 'paid' are numbers
		foreach ($fees_data as &$student) {
			$total_fees = (float) $student['total_fees'];
			$paid_amount = (float) $student['total_paid'];
			$student['balance'] = $total_fees - $paid_amount;
		}
		unset($student); // Break the reference

		echo json_encode($fees_data);
	}

	function fees_details_view()
	{
		$id = $this->input->get('id');
		if (!$id) {
			echo "Student ID is Not received.";
			return;
		}

		$data['student_fees'] = $this->Student_model->getFeesDetails($id);
		$this->load->view('fees-massage_report', $data);
	}

	// Generate Meetings
	function generate_meeting()
	{

		//Get User Details
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();

		$lastThreeEvents = $this->dashboard_model->getAllmeeting();

		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();

		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$data['classes'] = $this->Stats_model->getAllClasses();

		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['all_meetings'] = $lastThreeEvents;
		// $data['filter_view'] = "event";
		$this->load->view('Generate-Meeting', $data);
	}


	function meeting_add()
	{

		//Check avaialable

		$event_id = $this->input->post('event_id');

		$if_exist = $this->dashboard_model->checkExistmeeting($event_id);

		if ($if_exist > 0) {

			$data = array(

				'prameet_date' => $this->input->post('m_date'),

				'prameet_time' => $this->input->post('m_time'),

				'prameet_vanue' => $this->input->post('title'),

				'prameet_agenda' => $this->input->post('agenda'),

				'prameet_class' => $this->input->post('m_class'),

				'prameet_academic_year' => $this->input->post('acdm_year')

			);

			//add teacher assigned data into database

			$status = $this->dashboard_model->update_meeting($data, $event_id);

			if ($status) {

				$this->session->set_flashdata('message', "Event Updated Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'welcome/generate_meeting');
			} else {

				$this->session->set_flashdata('message', "Failed to Update Event !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'welcome/generate_meeting');
			}
		} else {

			$data = array(

				'prameet_date' => $this->input->post('m_date'),

				'prameet_time' => $this->input->post('m_time'),

				'prameet_vanue' => $this->input->post('title'),

				'prameet_agenda' => $this->input->post('agenda'),

				'prameet_class' => $this->input->post('m_class'),

				'prameet_academic_year' => $this->input->post('acdm_year')

			);

			//add teacher assigned data into database

			$status = $this->dashboard_model->add_meeting($data);

			if ($status) {

				$this->session->set_flashdata('message', "Event Created Successfully !!!");

				$this->session->set_flashdata('alert_class', "alert-success");

				redirect(base_url() . 'welcome/generate_meeting');
			} else {

				$this->session->set_flashdata('message', "Failed to Create Event !!!");

				$this->session->set_flashdata('alert_class', "alert-danger");

				redirect(base_url() . 'welcome/generate_meeting');
			}
		}
	}


	function getmeetingByID()
	{

		$data = $this->dashboard_model->getmeetingByID();

		echo json_encode($data);
	}

	// Aadhar info report (Ansari Kamran)
	public function get_aadhar_data()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$curr_acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$data['current_acdm'] = $this->input->get('acdm-year') ?? $curr_acdm['stvschool_acdm_year'];

		$data['classes'] = $this->Stats_model->getAllClasses();

		// These grab the values from the URL (?std=10&div=A)
		$data['current_class'] = $this->input->get('std') ?? '';
		$data['current_div'] = $this->input->get('div') ?? '';

		// Fetches the data and sends it to the view
		$data['getnote'] = $this->dashboard_model->get_filtered_notes($data['current_class'], $data['current_div']);

		$this->load->view('aadhar-info-report', $data);
	}

	// generate notes for student
	function generate_notes()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();



		$curr_acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$data['current_acdm'] = $this->input->get('acdm-year') ? $this->input->get('acdm-year') : $curr_acdm['stvschool_acdm_year'];
		$data['classes'] = $this->Stats_model->getAllClasses();

		$data['current_class'] = $this->input->get('std') ? $this->input->get('std') : '';
		$data['current_div'] = $this->input->get('div') ? $this->input->get('div') : '';

		$data['nots'] = $this->dashboard_model->get_notes();

		$data['lastLoginData'] = $lastLoginData;

		$this->load->view('Generate-Notes', $data);
	}


	function get_subject()
	{
		$std = $this->input->post('std');
		$data['subjects'] = $this->dashboard_model->get_subject($std);

		echo json_encode($data);
	}

	public function save_notes()
	{
		echo json_encode($this->dashboard_model->add_note());
	}


	public function notes_report()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$curr_acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$data['current_acdm'] = $this->input->get('acdm-year') ?? $curr_acdm['stvschool_acdm_year'];

		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['current_class'] = $this->input->get('std') ?? '';
		$data['current_div'] = $this->input->get('div') ?? '';

		$data['getnote'] = $this->dashboard_model->get_notes_by_id($data['current_acdm']);

		$this->load->view('Generate-Notes-report', $data);
	}

	public function filter_data()
	{
		$cur_acdm = $this->input->post('cur_acdm');
		$std = $this->input->post('std');
		$div = $this->input->post('div');
		$sub = $this->input->post('sub');

		$data = $this->dashboard_model->get_notes($cur_acdm, $std, $div, $sub);
		echo json_encode($data);
	}

	public function delete_note()
	{
		$note_id = $this->input->post('id');
		if ($note_id) {
			$this->db->where('id', $note_id);
			$deleted = $this->db->delete('notes');

			echo json_encode(['success' => $deleted]);
		} else {
			echo json_encode(['success' => false]);
		}
	}
}
