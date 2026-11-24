<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Report extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Student_model');
		$this->load->model('MasterForm_model');
		$this->load->model('Report_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Teacher_model');
		$this->load->model('Stats_model');
		$this->load->model('Report_card_model');
		$this->load->model('DB_model');
		date_default_timezone_set('Asia/Kolkata');
		if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
	}

	public function index()
	{
		$data['teachers'] = $this->Teacher_model->getAllTeachers();
		$this->load->view('admin-report', $data);
	}

	function getStatsForReport()
	{
		$teacher = $this->input->post('teacher');
		$date = $this->input->post('date');
		echo json_encode($this->Stats_model->getStatsForReport($teacher, $date));
	}

	///====================== Application Tracking =====================================================
	function app_track()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$date = $this->input->get('date');
		$user = $this->input->get('user');
		$data['fetch'] = [];
		$data['date'] = date('Y-m-d');
		$data['user'] = 'st';
		if ($this->input->get('action') == "search") {
			$data['date'] = $date;
			$data['user'] = $user;
			$data['fetch'] = $this->Stats_model->track_rep($data['date'], $data['user']);
		} else {
			$data['fetch'] = $this->Stats_model->track_rep($data['date'], $data['user']);
		}
		$this->load->view('all_reports/app_track', $data);
	}

	///====================== All Reports =========================================================
	function balance_fees()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['filter_view'] = 'balance_fee_rep';
		$this->load->view('all_reports/balance_fees', $data);
	}

	function getStudentRecords()
	{
		$status = $this->input->post('status');
		$std = $this->input->post('std');
		echo json_encode($this->Report_model->getStudentRecords($std, $status));
	}

	

	public function daily_fees_report()
	{
		$From = $this->input->get('from-date') ? $this->input->get('from-date') : date('Y-m-d');
		$to = $this->input->get('to-date') ? $this->input->get('to-date') : date('Y-m-d');
		$acdm_year = $this->uri->segment(3) ? $this->uri->segment(3) : '';
		$new_admission = $this->input->get('new_admission') ? $this->input->get('new_admission') : '';
		$userdetail = $this->Stats_model->getUserDetails(
			$this->session->userdata('oliveSchoolDashboardUser')
		);
		$data['lastLoginData'] = $this->Stats_model->last_login();
		$data['user'] = $userdetail;
		$data['fees_data'] = $this->Stats_model->daily_fees_report($From, $to, 'records', $acdm_year, $new_admission);
		$data['stats'] = $this->Stats_model->daily_fees_report($From, $to, 'stats', $acdm_year, $new_admission);
		$data['collections'] = $this->Stats_model->daily_fees_report($From, $to, 'collections', $acdm_year, $new_admission);
		// $data['curr_date'] = $date;
		$data['selected_year'] = $acdm_year;
		$data['today'] = date('Y-m-d');

		$data['from_date'] = $From;
		$data['to_date'] = $to;
		$data['new_admission'] = $new_admission;
		$data['total_fees'] = array_sum(array_column($data['fees_data'], 'sprfees_amount'));
		$this->load->view('all_reports/daily_fees_report', $data);
	}

	///----------- /Daily Report ------------------------------
	// ---------- changes made from here ---- 25-Oct-2021
	// -------- LC-----------------
	function report_stats_bf()
	{
		$data['user'] = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['lastLoginData'] = $this->Stats_model->last_login();
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$acdm_year = $this->input->get('acdm_year');
		$data['all_records'] = [];
		$data['filter_acdm'] = "";

		if ($acdm_year != '') {
			$data['all_records'] = $this->Report_model->student_stats_report_lc_updated($acdm_year);
			$data['filter_acdm'] = $acdm_year;
		}
		$this->load->view('all_reports/bfc', $data);
	}
	function student_report_bfc()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['user'] = $userdetail;
		$student_id = $this->input->get('student-id');
		$acdm = $this->input->get('year');
		$data['action'] = $this->input->get('type');
		$data['record'] = $this->Report_model->student_report_to_remark_bfc($student_id, $acdm);
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$this->load->view('all_reports/get_bfc', $data);
	}
	function report_stats_lc()
	{
		//Get All Classes from DB
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['lastLoginData'] = $this->Stats_model->last_login();
		$data['user'] = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$acdm_year = $this->input->get('acdm-year');
		$class = $this->input->get('class');

		$data['all_records'] = [];
		$data['filter_class'] = '';
		$data['filter_acdm'] = '';

		if ($acdm_year != '' && $class != '') {
			$data['all_records'] = $this->Report_model->fetchRecordsForLC($acdm_year, $class);
			$data['filter_class'] = $class;
			$data['filter_acdm'] = $acdm_year;
		}
		$this->load->view('all_reports/lc', $data);
	}

	// -------- LC End-----------------
	function report_stats_22Mar()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['type'] = $this->input->get('type');
		$data['checkBox'] = 0;
		$data['classes'] = $this->Stats_model->getAllClasses();
		// $data['record1'] = $this->Report_model->dueDate();
		$action = $this->input->get('action');

		if ($action == 'search') {
			$data['all_records'] = $this->Report_model->student_stats_report($this->input->get('due-date'), $this->input->get('type'));
			$data['filter_option'] = $this->input->get('due-date');
			$data['class_filter'] = $this->input->get('class');
			// $data['all_records'] = $this->Report_model->student_stats_report("-");
		} else {
			$data['all_records'] = [];
			$data['filter_option'] = '';
			$data['class_filter'] = 'ALL';
		}
		$this->load->view('all_reports/report-all-stats', $data);
	}

	function report_stats()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();

		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['type'] = $this->input->get('type');
		$data['checkBox'] = 0;
		$data['classes'] = $this->Stats_model->getAllClasses();

		// $data['record1'] = $this->Report_model->dueDate();
		$action = $this->input->get('action');

		if ($action == 'search') {
			$data['all_records'] = $this->Report_model->student_stats_report(
				$this->input->get('due-date'),
				$this->input->get('type'),
				$this->input->get('class'),
				$this->input->get('division'),
				$this->input->get('acdm_year')
			);

			$data['checkBox'] = $this->input->get('type');
			$data['filter_option'] = $this->input->get('due-date');
			$data['class_filter'] = $this->input->get('class');
			$data['division_filter'] = $this->input->get('division');
			$data['filter_acdm'] = $this->input->get('acdm_year');
		} else {
			$data['all_records'] = [];
			$data['filter_acdm'] = $data['selected_academic_year'];
			$data['filter_option'] = '';
			$data['class_filter'] = 'ALL';
			$data['division_filter'] = '';
		}

		$data['filter_view'] = 'all-status-report';
		$this->load->view('all_reports/report-all-stats', $data);
	}

	// function report_stats()
	// {
	// 	$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
	// 	//Get All Classes from DB
	// 	$lastLoginData = $this->Stats_model->last_login();
	// 	$data['lastLoginData'] = $lastLoginData;
	// 	$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
	// 	$data['classes'] = $this->Stats_model->getAllClasses();
	// 	$data['status'] = $this->Stats_model->getStatus(); // Fixed variable assignment
	// 	$data['user'] = $userdetail;
	// 	$data['type'] = $this->input->get('type');
	// 	$data['checkBox'] = 'no'; // Default value
	// 	$action = $this->input->get('action');

	// 	if ($action == 'search') {
	// 		$due_date = $this->input->get('due-date') ?: '-';
	// 		$type = $this->input->get('type') ?: 'no';
	// 		$class = $this->input->get('class') ?: 'ALL';
	// 		$division = $this->input->get('division') ?: '0';
	// 		$acdm_year = $this->input->get('acdm_year');
	// 		$status = $this->input->get('status') ?: 'A';

	// 		$data['all_records'] = $this->Report_model->student_stats_report($due_date, $type, $class, $division, $acdm_year, $status);
	// 		$data['checkBox'] = $type;
	// 		$data['filter_option'] = $this->input->get('due-date');
	// 		$data['class_filter'] = $class;
	// 		$data['division_filter'] = $division;
	// 		$data['status_filter'] = $status;
	// 		$data['filter_acdm'] = $acdm_year;
	// 	} else {
	// 		$data['all_records'] = [];
	// 		$data['filter_acdm'] = '';
	// 		$data['filter_option'] = '';
	// 		$data['class_filter'] = 'ALL';
	// 		$data['division_filter'] = '0';
	// 		$data['status_filter'] = 'A';
	// 	}

	// 	$data['filter_view'] = 'all-status-report';
	// 	$this->load->view('all_reports/report-all-stats', $data);
	// }

	// ---------- changes made to here ---- 25-Oct-2021
	function student_report()
	{
		$student_id = $this->input->get('student-id');
		$data['action'] = $this->input->get('type');
		$data['record'] = $this->Report_model->student_report_to_remark($student_id);
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$this->load->view('all_reports/student_report', $data);
	}

	// function student_report_lc()

	// {
	// 	$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

	// 	$data['user'] = $userdetail;

	// 	$student_id = $this->input->get('student-id');

	// 	$student_std = $this->input->get('std');

	// 	$data['action'] = $this->input->get('type');

	// 	// $data['record'] = $this->Report_model->student_report_to_remark_lc($student_id);
	// 	$data['student_std'] = $data['record']['current_std'];
	// 	print_r($data['student_std']);
	// 	die();

	// 	if ($data['student_std'] == 'XI' || $data['student_std'] == 'XII') {
	// 		$data['record'] = $this->Report_model->student_report_to_remark_lc_jc($student_id);
	// 	} else {
	// 		$data['record'] = $this->Report_model->student_report_to_remark_lc($student_id);
	// 	}

	// 	$data['lc_no'] = $this->Report_model->generate_lc_no($student_std);

	// 	$lastLoginData = $this->Stats_model->last_login();

	// 	$data['lastLoginData'] = $lastLoginData;

	// 	$this->load->view('all_reports/get_lc', $data);
	// }

	function student_report_lc()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['user'] = $userdetail;
		$student_id = $this->input->get('student-id');
		$student_std = $this->input->get('std');
		$data['action'] = $this->input->get('type');
		// Initially set $data['record'] to an empty array to avoid undefined index error
		$data['record'] = [];
		// Attempt to fetch the student record based on the student ID
		$initial_record = $this->Report_model->student_report_to_remark_lc($student_id);

		if ($initial_record) {
			$data['record'] = $initial_record;
			$data['student_std'] = $data['record']['current_std'];
			// Determine the correct report to fetch based on the student's current standard
			if ($data['student_std'] == 'XI' || $data['student_std'] == 'XII') {
				$data['record'] = $this->Report_model->student_report_to_remark_lc_jc($student_id);
			} else {
				$data['record'] = $this->Report_model->student_report_to_remark_lc($student_id);
			}
			$data['lc_no'] = $this->Report_model->generate_lc_no($student_std);
			$lastLoginData = $this->Stats_model->last_login();
			$data['lastLoginData'] = $lastLoginData;
			// Load the view with the gathered data
			$this->load->view('all_reports/get_lc', $data);
		} else {
			// Handle the case where no initial record is found
			show_error('No student record found for the provided ID.');
		}
	}

	// ===================== Changes by Aasif for New LC on 25-05-24 ===========================
	function student_jc_lc()
	{
		$data['user'] = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$student_id = $this->input->get('student-id');
		$acdm = $this->input->get('year');
		$token = $this->input->get('token');
		$data['action'] = $this->input->get('type');
		$data['record'] = $this->Report_model->student_report_to_remark_lc($student_id, $acdm);
		$data['student_std'] = $data['record']['current_std'];
		if ($data['student_std'] == 'XI' || $data['student_std'] == 'XII') {
			$data['record'] = $this->Report_model->student_report_to_remark_lc_jc($student_id, $acdm, $token);
		} else {
			$data['record'] = $this->Report_model->student_report_to_remark_lc($student_id, $acdm, $token);
		}
		$data['lc_no'] = $this->Report_model->generate_lc_no($data['student_std']);
		$data['lastLoginData'] = $this->Stats_model->last_login();
		$this->load->view('all_reports/get_jc_lc', $data);
	}

	function save_lc_details()
	{
		// Validate if it's an AJAX request
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$result = $this->Report_model->lc_data();
		echo $result;
	}

	function add_report()
	{
		$action = $this->input->post('action_type');
		$status = $this->Report_model->add_report();

		if ($status) {
			$this->session->set_flashdata('message', "Student Information added Successfully");
			$this->session->set_flashdata('alert_class', "alert-success");
		} else {
			$this->session->set_flashdata('message', "Failed to Update");
			$this->session->set_flashdata('alert_class', "alert-danger");
		}

		if ($action == 'alert') {
			redirect(base_url('Report/report/'));
		} else {
			redirect(base_url('Report/report_stats/'));
		}
	}

	function report()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		//Get All Classes from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		// Get academic year from database
		$academic_year_result = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$data['academic_filter'] = $academic_year_result ? $academic_year_result['stvschool_acdm_year'] : '';

		$data['all_records'] = [];
		$data['selected_class'] = '0';
		$data['selected_amount'] = '8000';
		$data['div_filter'] = '0';

		if ($this->uri->segment(3)) {
			// Sanitize inputs
			$class = $this->uri->segment(3);
			$amount = $this->uri->segment(4);
			$div = $this->uri->segment(5);
			$academic_year = $this->uri->segment(6);

			$data['all_records'] = $this->Report_model->getReport($class, $amount, $div, $academic_year);
			$data['selected_class'] = $class;
			$data['selected_amount'] = $amount;
			$data['div_filter'] = $div;
			$data['academic_filter'] = $academic_year;
		}

		$data['filter_view'] = 'report1';
		$this->load->view('all_reports/report1', $data);
	}

	function reports()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['all_records'] = $this->Report_model->getTotalReport();
		$data['standard'] = $this->Report_model->getStandard();
		$data['filter_view'] = 'report2';
		$this->load->view('all_reports/report2', $data);
	}

	function admission_report()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$classes = $this->Stats_model->getAllClasses();
		$data['user'] = $userdetail;
		$data['classes'] = $classes;
		$data['class'] = '';
		$data['type'] = '';

		if ($this->input->get('type') != '') {
			$data['all_records'] = $this->Report_model->admission_report($this->input->get('class'), $this->input->get('type'));
			$data['class'] = $this->input->get('class');
			$data['type'] = $this->input->get('type');
		} else {
			$data['all_records'] = $this->Report_model->admission_report("", "page");
		}
		$data['filter_view'] = 'admission_report';
		$this->load->view('all_reports/admission-report', $data);
	}

	function readmission()
	{
		$this->load->model('MasterForm_model');
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$acdm_data = $this->Stats_model->getAcademicDetails();
		$data['acdm'] = $acdm_data['stvacdm_id'];
		$data['type'] = 'readmission';

		if ($this->input->get('academic-year') != '' && $this->input->get('type') != '') {
			$data['all_records'] = $this->Report_model->readmission_report($this->input->get('academic-year'), $this->input->get('type'));
			$data['acdm'] = $this->input->get('academic-year');
			$data['type'] = $this->input->get('type');
		} else {
			$data['all_records'] = $this->Report_model->readmission_report();
		}
		$data['filter_view'] = 'readmission_report';
		$this->load->view('all_reports/readmission-report', $data);
	}

	function admissionreport()
	{
		$this->load->model('MasterForm_model');
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$acdm_data = $this->Stats_model->getAcademicDetails();
		$data['acdm'] = $acdm_data['stvacdm_id'];
		// if ($this->uri->segment(3)) {
		// 	$data['all_records'] = $this->Report_model->readmission_report($this->uri->segment(3));
		// 	$data['acdm'] = $this->uri->segment(3);
		// } else {
		// }
		$data['all_records'] = $this->Report_model->all_admission_report();
		$data['filter_view'] = 'All_admission_report';
		$this->load->view('all_reports/all-admission-report', $data);
	}

	//Rte Student Report
	function student_list_by_class()
	{
		echo json_encode($this->Stats_model->student_list_by_class());
	}

	function rte_report()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['list'] = array();
		$data['std_selected'] = 0;
		$data['div_selected'] = 0;

		if ($this->uri->total_segments() >= 3) {
			$class = $this->uri->segment(3);
			$div = $this->uri->segment(4);
			$year = ($this->uri->total_segments() >= 4) ? $this->uri->segment(5) : date('Y'); // Default to current year

			$data['list'] = $this->Stats_model->student_list_by_class($class, $div, $year);
			$data['std_selected'] = $class;
			$data['div_selected'] = $div;
		}

		$data['filter_view'] = 'rte_report';
		$this->load->view('all_reports/rte_report', $data);
	}

	//Admission Summary Report
	function admission_summary()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['all_records'] = array();
		$data['from_date'] = 0;
		$data['to_date'] = 0;

		if ($this->uri->total_segments() >= 3) {
			$data['all_records'] = $this->Report_model->admission_summary($this->uri->segment(3), $this->uri->segment(4));
			$data['from_date'] = $this->uri->segment(3);
			$data['to_date'] = $this->uri->segment(4);
		} else {
			$data['all_records'] = $this->Report_model->admission_summary(0, 0);
		}
		$data['filter_view'] = 'admission_summary';
		$this->load->view('all_reports/admission-summary', $data);
	}

	function fees_instment_report()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['std_selected'] = '0';
		$data['div_selected'] = '0';
		$data['all_records'] = array();

		if ($this->uri->total_segments() >= 3) {
			$data['all_records'] = $this->Report_model->fetchInstallment($this->uri->segment(3), $this->uri->segment(4));
			$data['std_selected'] = $this->uri->segment(3);
			$data['div_selected'] = $this->uri->segment(4);
		}
		//print_r($data['classes']);
		$data['filter_view'] = 'fees_ins_report';
		$this->load->view('all_reports/fees_ins_report', $data);
	}

	// =============== Fees Alert Report
	// changes live on 10_Oct_25
	public function fees_alert()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['academic_year'] = $this->Report_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();

		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$data['lastLoginData'] = $this->Stats_model->last_login();
		$data['user'] = $userdetail;
		$data['all_records'] = [];
		$data['year_filter'] = 0;
		$data['std_filter'] = 0;
		$data['div_filter'] = 0;
		$data['amount'] = 0;
		$data['classes'] = $this->Stats_model->getAllClasses();
		// ✅ Expecting 4 segments: year / class / div / amount
		if ($this->uri->total_segments() >= 6) {
			$year = $this->uri->segment(3);
			$std = $this->uri->segment(4);
			$div = $this->uri->segment(5);
			$amount = $this->uri->segment(6);
			$data['all_records'] = $this->Report_model->feesAlertNotification($year, $std, $div, $amount);
			$data['year_filter'] = $year;
			$data['std_filter'] = $std;
			$data['div_filter'] = $div;
			$data['amount'] = $amount;
		}
		$data['filter_view'] = 'fees-alert-rec';
		$this->load->view('all_reports/fees_alert', $data);
	}

	// changes live on 26_sep_22
	public function fees_date_wise()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['all_records'] = array();
		$data['from_date'] = 0;
		$data['to_date'] = 0;
		$data['class_type'] = 0;
		$data['type_filter'] = 0;

		if ($this->uri->total_segments() == 6) {
			$data['all_records'] = $this->Report_model->datewise_fees($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5), $this->uri->segment(6));
			$data['from_date'] = $this->uri->segment(3);
			$data['to_date'] = $this->uri->segment(4);
			$data['class_type'] = $this->uri->segment(5);
			$data['type_filter'] = $this->uri->segment(6);
		} else {
			$data['all_records'] = array();
		}
		$data['filter_view'] = 'fees_datewise';
		// echo "<pre>"; die(print_r($data['all_records'] ));
		$this->load->view('all_reports/fees_datewise', $data);
	}

	public function exemption_fees()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		//Get All Login from DB
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['stats'] = array();
		$data['from_date'] = 0;
		$data['to_date'] = 0;

		if ($this->uri->total_segments() == 4) {
			$data['stats'] = $this->Report_model->exemption_fees($this->uri->segment(3), $this->uri->segment(4));
			$data['from_date'] = $this->uri->segment(3);
			$data['to_date'] = $this->uri->segment(4);
		}
		$this->load->view('all_reports/exemption_r', $data);
	}

	function update_exemption_fees()
	{
		$this->Report_model->update_exemption_fees();
	}

	function fees_recipts()
	{
		ini_set("pcre.backtrack_limit", "10000000000");
		$this->load->model('Stats_model');
		$this->load->model('report_model');
		if ($this->uri->total_segments() >= 4) {
			$from = $this->uri->segment(3);
			$to = $this->uri->segment(4);
			$action = $this->uri->segment(5);
			$data['all_records'] = $this->report_model->fetchreceipt($from, $to);
			if ($action == 'excel') {
				$file_name = "Olive-English-School-Fees-" . date('Ymd') . rand(100, 1000) . '.csv';
				header("Content-Description: File Transfer");
				header("Content-Disposition: attachment; filename=$file_name");
				header("Content-Type: application/csv");
				$file = fopen('php://output', 'w');
				$header = array('Sr.No', 'Record ID', 'Student ID', 'Student Name', 'Class', 'Fees Amount', 'Fees Date');
				fputcsv($file, $header);
				$i = 0;
				foreach ($data['all_records'] as $key => $value) {
					array_unshift($value, ++$i);
					fputcsv($file, $value);
				}
				fclose($file);
				exit();
			}
			$html = $this->load->view('all_reports/receipt', $data, true);
			require_once __DIR__ . '/vendor/autoload.php';
			$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5, 'margin_header' => 5, 'margin_footer' => 5]);
			$mpdf->showImageErrors = true;
			$mpdf->autoScriptToLang = true;
			$mpdf->autoLangToFont = true;
			$mpdf->SetHTMLFooter();
			$mpdf->SetProtection(array('print'));
			$mpdf->WriteHTML($html);
			$file_name = "Fees_Receipt-" . time();
			$mpdf->Output($file_name . '.pdf', "I");
		} else {
			$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
			//Get All Login from DB
			$lastLoginData = $this->Stats_model->last_login();
			$data['lastLoginData'] = $lastLoginData;
			$data['user'] = $userdetail;
			$this->load->view('all_reports/fees_receipt_ex', $data);
		}
	}

	// ==========================================

	function balance_rep()
	{
		$this->load->model('MasterForm_model');
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$acdm_data = $this->Stats_model->getAcademicDetails();
		$data['acdm'] = $acdm_data['stvacdm_id'];
		$data['vls'] = $this->input->get('vls') == '' ? 'Readmission' : $this->input->get('vls');
		$data['all_records'] = $this->Report_model->balanc_report($data['vls']);
		$data['filter_view'] = 'ballance_report';
		$this->load->view('all_reports/ballance_report', $data);
	}

	function balance_report()
	{
		echo json_encode($this->Report_model->balanc_report($this->input->post('vls')));
	}

	// ====================================================
	// Developer : Ansari Kamran 
	// Date : 11-09-23

	/// ------------------ start tracking report --------------------
	function login_tracking()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$data['type'] = $this->input->get('type');
		$data['from_date'] = $this->input->get('from');
		$data['to_date'] = $this->input->get('to');
		$data['application'] = $this->Report_model->application_data($data['from_date'], $data['to_date'], $data['type']);
		$data['filter_view'] = "tracking_report";
		$this->load->view('all_reports/tracking_report', $data);
	}

	// Date : 11-09-23
	function teacher_activity()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$from_date = $this->input->get('from');
		$to_date = $this->input->get('to');
		$data['activity'] = $this->Report_model->teacher_activity($from_date, $to_date);
		$data['filter_view'] = "teacher_activity_report";
		$this->load->view('all_reports/teacher_activity_report', $data);
	}

	/// ------------------ end tracking report --------------------
	// ============================= AASIF 23-9-23 ============================== 
	function teacher_activity_db()
	{
		$data['teachers'] = $this->Report_model->get_teachers();
		$data['source'] = $this->Report_model->get_source();
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['t_id'] = $this->input->get('teacher_name');
		$data['from'] = $this->input->get('from');
		$data['to'] = $this->input->get('to');
		$data['attend'] = $this->Report_model->get_attend($data['t_id'], $data['from'], $data['to']);
		$data['fees_alert'] = $this->Report_model->get_fees_alert($data['t_id'], $data['from'], $data['to']);
		$data['remark'] = $this->Report_model->get_remark($data['t_id'], $data['from'], $data['to']);
		$data['event'] = $this->Report_model->get_event($data['t_id'], $data['from'], $data['to']);
		$this->load->view('all_reports/teacher_activity_db', $data);
	}

	function fetch_sub()
	{
		$std = $this->input->get('std');

		echo json_encode($this->Report_model->syllabus($std));
	}
	// Ansari Kamran
	// date 23-09-23

	function syllabus()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));

		$lastLoginData = $this->Stats_model->last_login();

		$data['lastLoginData'] = $lastLoginData;

		$data['user'] = $userdetail;

		$teachers = $this->Teacher_model->getAllTeachers();
		$data['teachers'] = $teachers;

		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['filter_view'] = "syllables_report";

		// $data['activity'] = $this->Report_model->teacher_activity($from_date, $to_date, $source, $tc_id);
		// <!-- -------------------- end --------------------------------------- -->
		$this->load->view('all_reports/syllabus_report', $data);
	}

	function fetch_data()
	{
		echo json_encode($this->Report_model->fetch_data());
	}

	function syllabus_calender()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$data['user'] = $userdetail;
		$currentTimestamp = time();
		// Calculate the start date of the current week (Sunday)
		$startDate = date('Y-m-d', strtotime('this week', $currentTimestamp));
		$data['start'] = $startDate;
		// Calculate the end date of the current week (Saturday)
		$endDate = date('Y-m-d', strtotime('this week +5 days', $currentTimestamp));
		$data['end'] = $endDate;
		$class = $this->input->get('std');
		$div = $this->input->get('div');
		$teacher_id = $this->input->get('teacher_id');
		$teachers = $this->Teacher_model->getAllTeachers();
		$data['teachers'] = $teachers;
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['class'] = $class;
		$data['div'] = $div;
		$data['teacher_id'] = $teacher_id;
		$data['subjects'] = $this->Report_model->syllabus($class);
		$data['syllabus'] = $this->Report_model->syllabus_calender($class, $div, $teacher_id, $startDate, $endDate);
		$data['filter_view'] = "syllabus_calender";
		$this->load->view('all_reports/syllabus_calender', $data);
	}

	// function Aadhar_report($class = null, $div = null)
	// {
	// 	$classes = $this->Stats_model->getAllClasses();
	// 	$data['classes'] = $classes;
	// 	$data['details'] = $this->Report_model->aadhar_report($this->input->get('std'), $this->input->get('div'));
	// 	$this->load->view('all_reports/aadhar_report', $data);
	// }

	function Aadhar_report($class = null, $div = null)
	{
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;

		// Arguments ko directly model mein bhej rahe hain (URL segments se)
		$data['details'] = $this->Report_model->aadhar_report($class, $div);

		// View mein dropdowns ko selected rakhne ke liye data bhej rahe hain
		$data['selected_class'] = $class;
		$data['selected_div'] = $div;

		$this->load->view('all_reports/aadhar_report', $data);
	}

	function Adhar_report()
	{
		echo json_encode($this->Report_model->aadhar_report($this->input->get('std'), $this->input->get('divi'), $this->input->get('year')));
	}

	function student_dataChart()
	{
		$year = 6;
		// $year = $this->input->get('year');
		$std = $this->input->get('std');
		echo json_encode($this->Report_model->student_dataChart($year, $std));
	}

	function stud_fetch()
	{
		$acdm_year = 6;
		$student_id = $this->input->post('id');
		$stud_id = $this->DB_model->fetch_stud_id($student_id);
		$stud_id = $stud_id['id'];
		echo json_encode($this->Report_model->stud_fetch($stud_id, $acdm_year));
	}

	function monthfilter()
	{
		$year = $this->input->post('year');
		echo json_encode($this->Report_model->monthfilter($year));
	}

	function leaving()
	{
		$id = $this->input->post('id');
		echo json_encode($this->Report_model->leaving($id));
	}
	function class_report()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$student_id = $this->input->post('student_id');
		$data['user'] = $userdetail;
		$ye = 6;
		$data['total_stud'] = $this->Report_model->total_stud();
		$data['total_fees'] = $this->Report_model->total_fees();
		$data['fatch_class'] = $this->Report_model->fetch_class();
		$data['fatch_rte'] = $this->Report_model->fetch_rte();
		$data['fatch_act'] = $this->Report_model->fetch_active();
		$data['fatch_inact'] = $this->Report_model->fetch_inactive();
		$data['academic_years'] = $this->DB_model->get_all_academic_years();
		$data['records'] = $this->DB_model->date();
		$data['total_exemption'] = $this->DB_model->total_exemption();
		$data['Paid_chart'] = $this->DB_model->paid_chart($ye);
		$data['Chart_Stud'] = $this->DB_model->chart_stud($ye);
		$data['Details'] = $this->DB_model->fetch_data($ye);
		$data['fedetails'] = $this->DB_model->stud_fetch($student_id, $ye);
		$data['class_fetch'] = $this->Report_model->class_fetch();
		if ($this->uri->total_segments() == 4) {
			$data['stats'] = $this->Report_model->exemption_fees($this->uri->segment(3), $this->uri->segment(4));
			$data['from_date'] = $this->uri->segment(3);
			$data['to_date'] = $this->uri->segment(4);
		}
		$this->load->view('all_reports/class_report_1', $data);
	}

	function paid_filter()
	{
		$year = $this->input->get('year');
		$from_date = $this->input->get('from');
		$to_date = $this->input->get('to');

		if ($to_date == null) {
			echo json_encode($this->Report_model->paid_chart($year));
		} else {
			echo json_encode($this->Report_model->filter($from_date, $to_date));
		}
	}

	function class_report2()
	{
		$data['current_academic_data'] = $this->Report_model->getSelectedAcademicYear();
		$data['start_date'] = $data['current_academic_data']['stvacdm_start'];
		$data['end_date'] = $data['current_academic_data']['stvacdm_end'];
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['all_records'] = [];
		$this->load->view('all_reports/class_report_2', $data);
	}

	function fatch_rep2()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->total_cls_stud($std, $div, $from_date, $to_date));
	}

	function fetch_paid()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->fetch_paid($std, $div, $from_date, $to_date));
	}

	function fetch_exemption()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->fetch_exemption($std, $div, $from_date, $to_date));
	}

	function fetch_balance()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		$type = $this->input->get('type');
		echo json_encode($this->Report_model->fetch_balance($std, $div, $from_date, $to_date, null, $type));
	}

	function fetch_trte()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->fetch_trte($std, $div, $from_date, $to_date));
	}

	function fetch_inactive()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->fetch_IInactive($std, $div, $from_date, $to_date));
	}

	function fetch_table()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->fetch_table_data($std, $div, $from_date, $to_date));
	}

	function fatch_prev()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->fatch_prev($std, $div, $from_date, $to_date));
	}

	function fatch_rte()
	{
		$std = $this->input->get('std');
		$div = $this->input->get('div');
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		echo json_encode($this->Report_model->fatch_rte_modal($std, $div, $from_date, $to_date));
	}

	function current_inactive()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['user'] = $userdetail;
		$to_date = $this->input->get("to_date");
		$from_date = $this->input->get("from_date");
		$data['to_date'] = $to_date;
		$data['from_date'] = $from_date;
		$data['students'] = $this->Report_model->inactive_student($to_date, $from_date);
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$this->load->view('all_reports/current', $data);
	}

	function lc_isshu_report()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$student_id = $this->uri->segment(3);
		$data['user'] = $userdetail;
		$to_date = $this->input->get("to_date");
		$from_date = $this->input->get("from_date");
		$data['to_date'] = $to_date;
		$data['from_date'] = $from_date;
		$data['students'] = $this->Report_model->lc_isshu($to_date, $from_date);
		// $data['lc_no'] = $this->Report_model->generate_lc_no($data['students']['class']);
		$lastLoginData = $this->Stats_model->last_login();
		$data['lastLoginData'] = $lastLoginData;
		$this->load->view('all_reports/lc-isshu-report', $data);
	}

	public function requestForlc()
	{
		$stud_id = $this->input->post('stud_id');
		$stud_class = $this->input->post('stud_class');
		$status = "requested";
		$acdm_year = $this->input->post('acdm_year');
		$this->Report_model->request_lc($stud_id, $stud_class, $status, $acdm_year);
	}

	public function updateForlc()
	{
		$stud_id = $this->input->post('stud_id');
		$stud_class = $this->input->post('stud_class');
		$status = $this->input->post('status');
		$acdm_year = $this->input->post('acdm_year');
		// update status where student id and academic year
		$this->Report_model->request_lc($stud_id, $stud_class, $status, $acdm_year);
	}

	public function manage_lc_request()
	{
		$stud_id = $this->input->post('stud_id');
		$stud_class = $this->input->post('stud_class');
		$status = $this->input->post('status'); // 'requested', 'approved', ya 'rejected'
		$acdm_year = $this->input->post('acdm_year');

		// Agar 'request' button se aaya hai aur status pass nahi hua, toh default 'requested' rakh do
		if (empty($status)) {
			$status = "requested";
		}

		$requested_by = $this->session->userdata('oliveSchoolDashboardUser');

		// Merged model function ko call karo
		$result = $this->Report_model->manage_lc_request($stud_id, $stud_class, $status, $acdm_year, $requested_by);

		if ($result) {
			echo "Success";
		} else {
			echo "Failed";
		}
	}

	

	function teacher_activity_report()
	{
		$data['date'] = date('Y-m-d');
		$data['toDateValue'] = date('Y-m-d');
		if ($this->input->get('activity_date') != '') {
			$data['date'] = $this->input->get('activity_date');
		}
		if ($this->input->get('toDateValue') != '') {
			$data['toDateValue'] = $this->input->get('toDateValue');
		}
		// Get all active teachers
		$data['teachers'] = $this->Report_model->fetchActiveTeacherList();

		// Load view with default data (showing all teachers initially)
		$this->load->view('all_reports/tc-activity-report', $data);
	}

	function get_teacher_activities()
	{
		$teacher_id = $this->input->post('teacher_id');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$activities = $this->Report_model->fetchTeacherActivities(
			$from_date,
			$to_date,
			($teacher_id === 'all' ? null : $teacher_id)
		);
		// Prepare HTML response
		$html = '';
		if (!empty($activities)) {
			$counter = 0;
			$html .= '<div class="table-responsive"><table class="table table-sm table-hover">';
			$html .= '<thead><tr class="">';
			$html .= '<th class="fw-normal app-bg text-white text-center"><i class="bi bi-calendar3"></i> Attendance</th>';
			$html .= '<th class="fw-normal app-bg text-white text-center"><i class="bi bi-file-earmark-medical"></i> Remarks</th>';
			$html .= '<th class="fw-normal app-bg text-white text-center"><i class="bi bi-chat-left-text"></i> Absent Message</th>';
			$html .= '<th class="fw-normal app-bg text-white text-center"><i class="bi bi-laptop"></i> Quiz</th>';
			$html .= '<th class="fw-normal app-bg text-white text-center"><i class="bi bi-receipt"></i> Marks</th>';
			$html .= '</tr></thead><tbody>';

			foreach ($activities as $activity) {
				$html .= '<tr class="align-middle">';
				// Attendance
				$html .= '<td class="cursor-pointer fw-normal text-center py-2" onclick="attendanceSummary(\'' . $activity['user_id'] . '\', \'' . $activity['date'] . '\')">';
				$html .= '<div class="circle mx-auto"><small>' . ($activity['total_attendance'] ?? 0) . '</small></div></td>';
				// Remarks
				$html .= '<td class="cursor-pointer fw-normal text-center py-2" onclick="remarkDetail(\'' . $activity['user_id'] . '\', \'' . $activity['date'] . '\')">';
				$html .= '<div class="circle mx-auto"><small>' . ($activity['total_remark'] ?? 0) . '</small></div></td>';
				// Absent Message
				$html .= '<td class="cursor-pointer fw-normal text-center py-2" onclick="absentSMSDetail(\'' . $activity['user_id'] . '\', \'' . $activity['date'] . '\')">';
				$html .= '<div class="circle mx-auto"><small>' . ($activity['total_absent_sms'] ?? 0) . '</small></div></td>';
				// Quiz
				$html .= '<td class="cursor-pointer fw-normal text-center py-2" onclick="quizDetail(\'' . $activity['user_id'] . '\', \'' . $activity['date'] . '\')">';
				$html .= '<div class="circle mx-auto"><small>' . ($activity['total_quiz'] ?? 0) . '</small></div></td>';
				// Marks
				$html .= '<td class="cursor-pointer fw-normal text-center py-2" onclick="markDetail(\'' . $activity['user_id'] . '\', \'' . $activity['date'] . '\')">';
				$html .= '<div class="circle mx-auto"><small>' . ($activity['total_marks'] ?? 0) . '</small></div></td>';
				$html .= '</tr>';
			}
			$html .= '</tbody></table></div>';
		} else {
			$html .= '<div class="text-center p-5">';
			$html .= '<img src="' . base_url() . 'img/no-data.JPG" class="no-data" alt="">';
			$html .= '<h6 class="text-danger fw-normal">No activities found for the selected criteria.</h6>';
			$html .= '</div>';
		}
		echo $html;
	}

	// Keep all your existing detail functions (fetchAttendanceActivityStats, etc.) unchanged
	function fetchAttendanceActivityStats()
	{
		echo json_encode($this->Report_model->fetchAttendanceActivityStats($this->input->get('teacher_id'), $this->input->get('activity_date'), $this->input->get('toDateValue')));
	}

	function fetchAttendanceDetails()
	{
		echo json_encode($this->Report_model->fetchAttendanceDetails($this->input->get('teacher_id'), $this->input->get('activity_date'), $this->input->get('std'), $this->input->get('div'), $this->input->get('status')));
	}

	function fetchRemarkActivityDetails()
	{
		echo json_encode($this->Report_model->fetchRemarkActivityDetails($this->input->get('teacher_id'), $this->input->get('activity_date'), $this->input->get('toDateValue')));
	}

	function fetchAbsentSMSActivityDetails()
	{
		echo json_encode($this->Report_model->fetchAbsentSMSActivityDetails($this->input->get('teacher_id'), $this->input->get('activity_date'), $this->input->get('toDateValue')));
	}

	function fetchQuizActivityDetails()
	{
		echo json_encode($this->Report_model->fetchQuizActivityDetails($this->input->get('teacher_id'), $this->input->get('activity_date'), $this->input->get('toDateValue')));
	}

	function fetchMarkActivityDetails()
	{
		echo json_encode($this->Report_model->fetchMarkActivityDetails($this->input->get('teacher_id'), $this->input->get('activity_date'), $this->input->get('toDateValue')));
	}

	// -----------------------------------------------------------------------------
	public function lc_report()
	{
		$this->load->model('Report_model');
		// Academic year filter hata diya
		$data['records'] = $this->Report_model->get_lc_report();
		$this->load->view('report/lc_report', $data);
	}

	function student_get_lc()
	{
		$data['user'] = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$student_id = $this->input->get('student-id');
		$acdm = $this->input->get('year');
		$token = $this->input->get('token');
		$data['action'] = $this->input->get('type');
		$data['record'] = $this->Report_model->student_get_lc($student_id, $acdm);
		$data['student_std'] = $data['record']['current_std'];
		if ($this->input->get('lc-type') == 'college') {
			$data['record'] = $this->Report_model->student_get_lc_jc($student_id, $acdm, $token);
		} else {
			$data['record'] = $this->Report_model->student_get_lc($student_id, $acdm, $token);
		}
		$data['lc_no'] = $this->Report_model->generate_lc_no($data['student_std']);
		$data['lastLoginData'] = $this->Stats_model->last_login();


		$this->load->view('report/studentgetlc', $data);
	}

	public function monthly_fees()
	{
		$this->load->model('Report_model');
		$from_date = $this->input->get('from');
		$data['from'] = $from_date;
		$to_date = $this->input->get('to');
		$data['to'] = $to_date;
		if (!empty($from_date) && !empty($to_date)) {
			$data['records'] = $this->Report_model->get_monthly_fees($from_date, $to_date);
		} else {
			$data['records'] = [
				'cash_amount' => 0,
				'online_amount' => 0,
				'total_amount' => 0
			];
		}
		$this->load->view('report/monthly_fees', $data);
	}

	// ------------------ 26-03-26 ---------------------
	// ------------------ Ansari Kamran ---------------------

	public function exam_alert()
	{
		$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
		$data['academic_year'] = $this->Report_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();

		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$data['lastLoginData'] = $this->Stats_model->last_login();
		$data['user'] = $userdetail;
		$data['all_records'] = [];
		$data['year_filter'] = 0;
		$data['std_filter'] = 0;
		$data['div_filter'] = 0;
		$data['classes'] = $this->Stats_model->getAllClasses();

		// now only 3 segments
		if ($this->uri->total_segments() >= 5) {
			$year = $this->uri->segment(3);
			$std = $this->uri->segment(4);
			$div = $this->uri->segment(5);

			$data['all_records'] = $this->Report_model->examAlertNotification($year, $std, $div);
			$data['year_filter'] = $year;
			$data['std_filter'] = $std;
			$data['div_filter'] = $div;
		}

		$data['filter_view'] = 'fees-alert-rec';
		$this->load->view('all_reports/exam_alert', $data);
	}

	public function reques_for_lc()
	{
		$stud_id = $this->input->post('stud_id');
		$stud_class = $this->input->post('stud_class');
		$status = "requested"; // Jaise aapne kaha
		$acdm_year = $this->input->post('acdm_year');

		// Yeh line tab useful hai agar db mein error aaye toh pata chal jaye
		$requested_by = $this->session->userdata('oliveSchoolDashboardUser');

		// Model ko call kar rahe hain
		$result = $this->Report_model->reques_for_lc($stud_id, $stud_class, $status, $acdm_year, $requested_by);

		// AJAX ko response bhej rahe hain
		if ($result) {
			echo "Success";
		} else {
			echo "Failed";
		}
	}

	public function Student_atnd($acdm_year = null, $class = null, $div = null, $year = null)
	{
		$data['classes'] = $this->Report_card_model->getAllClasses();
		$data['academic_years'] = $this->Report_card_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';

		$data['selected_acdm_year'] = $acdm_year;
		$data['selected_class'] = $class;
		$data['selected_division'] = $div;
		$data['selected_year'] = $year;

		$data['attendance_data'] = $this->Report_model->attendanceData($acdm_year, $class, $div);

		$this->load->view('report/student_atnd', $data);
	}

	public function current_year_fees()
	{
		$this->load->model('Report_model');

		$data['records'] = $this->Report_model->get_current_year_fees();

		$this->load->view('report/current_year_fees', $data);
	}

	public function convert_online()
	{
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$year = $this->input->post('year');

		$result = $this->Report_model->convert_online($from, $to, $year);

		echo json_encode($result);
	}
	public function fees_balance_report()
	{
		$type = $this->input->get('type', TRUE);

		$data['records'] = [];
		$data['academic_years'] = [];


		if (in_array($type, ['all', 'last', 'paid', 'zero', 'balanced'])) {
			$data['records'] = $this->Report_model->get_fees_balance_report($type);
		}

		// Only required for type = all
		if ($type === 'all') {
			$data['academic_years'] = $this->Report_model
				->get_fees_balance_academic_years();
		}
		$this->load->view('report/fees_balance_report', $data);
	}
	// public function fees_balance_report()
	// {
	// 	$type = $this->input->get('type', TRUE);
	// 	$data['records'] = [];
	// 	if (in_array($type, ['all', 'last', 'paid','zero','balanced'])) {
	// 		$data['records'] = $this->Report_model->get_fees_balance_report($type);
	// 	}
	// 	$this->load->view('report/fees_balance_report', $data);
	// }

	public function Fees_comment()
	{
		$data['comments'] = $this->Report_model->Fees_comment();
		$this->load->view('all_reports/fees_comment', $data);
	}

	public function all_previous_fees_report()
	{
		$search = trim((string) $this->input->get('search', true));

		$allowedPerPage = [20, 50, 100];
		$perPage = (int) $this->input->get('per_page', true);

		if (!in_array($perPage, $allowedPerPage, true)) {
			$perPage = 20;
		}

		$page = max(1, (int) $this->input->get('page', true));
		$start = ($page - 1) * $perPage;

		$result = $this->Report_model->get_all_previous_fees_report([
			'search' => $search,
			'start' => $start,
			'length' => $perPage,
		]);

		$filteredRows = (int) ($result['recordsFiltered'] ?? 0);
		$totalPages = max(1, (int) ceil($filteredRows / $perPage));

		/*
		 * Redirect instead of executing the expensive model query again.
		 */
		if ($page > $totalPages && $filteredRows > 0) {
			redirect(
				site_url('Report/all_previous_fees_report') . '?' .
				http_build_query([
					'search' => $search,
					'per_page' => $perPage,
					'page' => $totalPages,
				])
			);

			return;
		}

		$data = [
			'records' => $result['data'] ?? [],
			'academic_years' => $result['academicYears'] ?? [],
			'summary' => $result['summary'] ?? [],
			'records_total' => $filteredRows,
			'records_filtered' => $filteredRows,
			'search' => $search,
			'page' => $page,
			'per_page' => $perPage,
			'total_pages' => $totalPages,
			'start_record' => $filteredRows > 0 ? $start + 1 : 0,
			'end_record' => min($start + $perPage, $filteredRows),
		];

		$this->load->view('report/all_previous_fees_report', $data);
	}
	public function last_year_fees_report()
	{
		$search = trim((string) $this->input->get('search', true));

		$allowedPerPage = [20, 50, 100];
		$perPage = (int) $this->input->get('per_page', true);

		if (!in_array($perPage, $allowedPerPage, true)) {
			$perPage = 20;
		}

		$page = max(1, (int) $this->input->get('page', true));
		$start = ($page - 1) * $perPage;

		$result = $this->Report_model->get_last_year_fees_report([
			'search' => $search,
			'start' => $start,
			'length' => $perPage,
		]);

		$filteredRows = (int) ($result['recordsFiltered'] ?? 0);
		$totalPages = $filteredRows > 0
			? (int) ceil($filteredRows / $perPage)
			: 1;

		if ($page > $totalPages && $filteredRows > 0) {
			$page = $totalPages;
			$start = ($page - 1) * $perPage;

			$result = $this->Report_model->get_last_year_fees_report([
				'search' => $search,
				'start' => $start,
				'length' => $perPage,
			]);

			$filteredRows = (int) ($result['recordsFiltered'] ?? $filteredRows);
		}

		$data = [
			'records' => $result['data'] ?? [],
			'summary' => $result['summary'] ?? [
				'students' => 0,
				'totalFee' => 0,
				'totalPending' => 0,
				'totalPaid' => 0,
				'totalExempted' => 0,
				'totalAdjustedPaid' => 0,
				'averagePending' => 0,
			],
			'records_total' => (int) ($result['recordsTotal'] ?? $filteredRows),
			'records_filtered' => $filteredRows,
			'search' => $search,
			'page' => $page,
			'per_page' => $perPage,
			'total_pages' => $totalPages,
			'start_record' => $filteredRows > 0 ? $start + 1 : 0,
			'end_record' => $filteredRows > 0
				? min($start + $perPage, $filteredRows)
				: 0,
		];

		$this->load->view('report/last_year_fees_report', $data);
	}

	public function current_zero_paid_report()
	{
		$search = trim((string) $this->input->get('search', true));

		$allowedPerPage = [20, 50, 100];
		$perPage = (int) $this->input->get('per_page', true);

		if (!in_array($perPage, $allowedPerPage, true)) {
			$perPage = 20;
		}

		$page = max(1, (int) $this->input->get('page', true));
		$start = ($page - 1) * $perPage;

		$params = [
			'search' => $search,
			'start' => $start,
			'length' => $perPage,
		];

		$result = $this->Report_model
			->get_current_zero_paid_report($params);

		$filteredRows = (int) ($result['recordsFiltered'] ?? 0);

		$totalPages = $filteredRows > 0
			? (int) ceil($filteredRows / $perPage)
			: 1;

		/*
		 * If the requested page is greater than the available pages,
		 * reload the final valid page.
		 */
		if ($page > $totalPages && $filteredRows > 0) {
			$page = $totalPages;
			$start = ($page - 1) * $perPage;

			$result = $this->Report_model
				->get_current_zero_paid_report([
					'search' => $search,
					'start' => $start,
					'length' => $perPage,
				]);

			$filteredRows = (int) (
				$result['recordsFiltered'] ?? $filteredRows
			);
		}

		$data = [
			'records' => $result['data'] ?? [],

			'summary' => $result['summary'] ?? [
				'students' => 0,
				'totalPending' => 0,
				'totalFee' => 0,
				'totalPaid' => 0,
				'averagePending' => 0,
			],

			'records_total' => (int) (
				$result['recordsTotal'] ?? $filteredRows
			),

			'records_filtered' => $filteredRows,
			'search' => $search,
			'page' => $page,
			'per_page' => $perPage,
			'total_pages' => $totalPages,

			'start_record' => $filteredRows > 0
				? $start + 1
				: 0,

			'end_record' => $filteredRows > 0
				? min($start + $perPage, $filteredRows)
				: 0,
		];

		$this->load->view(
			'report/current_zero_paid_report',
			$data
		);
	}
	public function last_year_balance_report()
	{
		$data['filters'] =
			$this->Report_model
				->get_last_year_balance_report([
					'filters_only' => true
				]);

		$this->load->view(
			'report/last_year_balance_report',
			$data
		);
	}

	public function last_year_balance_report_ajax()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}

		$params = [
			'start' =>
				(int) $this->input->post('start'),

			'length' =>
				(int) $this->input->post('length'),

			'search' => trim(
				(string) $this->input->post(
					'search_value',
					true
				)
			),

			'class' => trim(
				(string) $this->input->post(
					'class_filter',
					true
				)
			),

			'division' => trim(
				(string) $this->input->post(
					'division_filter',
					true
				)
			),

			'order_column' =>
				(int) $this->input->post(
					'order_column'
				),

			'order_direction' =>
				(string) $this->input->post(
					'order_direction',
					true
				)
		];

		$result =
			$this->Report_model
				->get_last_year_balance_report($params);

		$result['draw'] =
			(int) $this->input->post('draw');

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	// public function all_previous_fees_report()
	// {
	// 	$search = trim((string) $this->input->get('search', true));

	// 	$allowedPerPage = [20, 50, 100];
	// 	$perPage = (int) $this->input->get('per_page', true);

	// 	if (!in_array($perPage, $allowedPerPage, true)) {
	// 		$perPage = 20;
	// 	}

	// 	$page = max(1, (int) $this->input->get('page', true));
	// 	$start = ($page - 1) * $perPage;

	// 	$result = $this->Report_model->get_all_previous_fees_report([
	// 		'search' => $search,
	// 		'start' => $start,
	// 		'length' => $perPage,
	// 	]);

	// 	$filteredRows = (int) ($result['recordsFiltered'] ?? 0);
	// 	$totalPages = max(1, (int) ceil($filteredRows / $perPage));

	// 	/*
	// 	 * Redirect instead of executing the expensive model query again.
	// 	 */
	// 	if ($page > $totalPages && $filteredRows > 0) {
	// 		redirect(
	// 			site_url('Report/all_previous_fees_report') . '?' .
	// 			http_build_query([
	// 				'search' => $search,
	// 				'per_page' => $perPage,
	// 				'page' => $totalPages,
	// 			])
	// 		);

	// 		return;
	// 	}

	// 	$data = [
	// 		'records' => $result['data'] ?? [],
	// 		'academic_years' => $result['academicYears'] ?? [],
	// 		'summary' => $result['summary'] ?? [],
	// 		'records_total' => $filteredRows,
	// 		'records_filtered' => $filteredRows,
	// 		'search' => $search,
	// 		'page' => $page,
	// 		'per_page' => $perPage,
	// 		'total_pages' => $totalPages,
	// 		'start_record' => $filteredRows > 0 ? $start + 1 : 0,
	// 		'end_record' => min($start + $perPage, $filteredRows),
	// 	];

	// 	$this->load->view('report/all_previous_fees_report', $data);
	// }


	// export data 

	private function excelColumnLetter($number)
	{
		$column = '';

		while ($number > 0) {

			$modulo = ($number - 1) % 26;

			$column =
				chr(65 + $modulo) .
				$column;

			$number =
				(int) (($number - $modulo) / 26);
		}

		return $column;
	}

	public function export_all_previous_fees_excel()
	{
		$search = trim(
			(string) $this->input->get(
				'search',
				true
			)
		);

		$records =
			$this->Report_model
				->export_all_previous_fees_report([
					'search' => $search,
				]);

		$academicYears = array_values(
			array_filter(
				$this->Report_model
					->get_fees_balance_academic_years(),
				static function ($year) {

					$yearId =
						(int) (
							$year['academic_year_id']
							?? 0
						);

					return
						$yearId >= 5 &&
						$yearId <= 8;
				}
			)
		);

		require_once FCPATH . 'vendor/autoload.php';

		$spreadsheet =
			new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$sheet =
			$spreadsheet->getActiveSheet();

		$sheet->setTitle(
			'Previous Fees'
		);

		$sheet->mergeCells(
			'A1:' .
			$this->excelColumnLetter(
				count($academicYears) + 5
			) .
			'1'
		);

		$sheet->setCellValue(
			'A1',
			'All Previous Fees Report'
		);

		$sheet->getStyle('A1')->getFont()->setBold(true);
		$sheet->getStyle('A1')->getFont()->setSize(16);

		$sheet
			->getStyle('A1')
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		$sheet->setCellValue(
			'A2',
			'Search: ' . (
				$search !== ''
				? $search
				: 'All Students'
			)
		);

		$sheet->mergeCells(
			'A2:' .
			$this->excelColumnLetter(
				count($academicYears) + 5
			) .
			'2'
		);

		$headerRow = 4;
		$column = 1;
		$headers = [
			'Sr. No.',
			'Student ID',
			'Student Name',
			'Mobile No.',
		];

		foreach ($academicYears as $year) {

			$headers[] =
				(string) (
					$year['academic_year_name']
					?? ''
				);
		}

		$headers[] = 'Total Outstanding';
		foreach ($headers as $header) {
			$cell =
				$this->excelColumnLetter($column) .
				$headerRow;
			$sheet->setCellValue(
				$cell,
				$header
			);
			$column++;
		}

		$lastColumn =
			$this->excelColumnLetter(
				count($headers)
			);

		$headerStyle = [
			'font' => [
				'bold' => true,
				'size' => 11,
			],
			'alignment' => [
				'horizontal' =>
					\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' =>
					\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],
			'borders' => [
				'allBorders' => [
					'borderStyle' =>
						\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];

		$sheet
			->getStyle(
				'A' . $headerRow . ':' .
				$lastColumn . $headerRow
			)
			->applyFromArray(
				$headerStyle
			);

		$rowNumber = 5;
		$serial = 1;
		$grandTotals = [
			5 => 0,
			6 => 0,
			7 => 0,
			8 => 0,
		];

		$grandTotalPending = 0;
		foreach ($records as $row) {
			$column = 1;

			$sheet->setCellValue(
				$this->excelColumnLetter($column) .
				$rowNumber,
				$serial
			);
			$column++;

			$sheet->setCellValueExplicit(
				$this->excelColumnLetter($column) .
				$rowNumber,
				(string) (
					$row['SPRIDEN_student_id']
					?? ''
				),
				\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
			);
			$column++;

			$sheet->setCellValue(
				$this->excelColumnLetter($column) .
				$rowNumber,
				(string) (
					$row['name']
					?? ''
				)
			);
			$column++;

			$sheet->setCellValueExplicit(
				$this->excelColumnLetter($column) .
				$rowNumber,
				(string) (
					$row['MobileNumber']
					?? ''
				),
				\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
			);
			$column++;

			foreach ($academicYears as $year) {
				$yearId =
					(int) (
						$year['academic_year_id']
						?? 0
					);
				$pendingAmount =
					(float) (
						$row['Pending_' . $yearId]
						?? 0
					);
				$sheet->setCellValue(
					$this->excelColumnLetter($column) .
					$rowNumber,
					$pendingAmount
				);

				if (isset($grandTotals[$yearId])) {

					$grandTotals[$yearId] +=
						$pendingAmount;
				}
				$column++;
			}

			$studentTotal =
				(float) (
					$row['TotalPending']
					?? 0
				);

			$sheet->setCellValue(
				$this->excelColumnLetter($column) .
				$rowNumber,
				$studentTotal
			);
			$grandTotalPending +=
				$studentTotal;

			$rowNumber++;
			$serial++;
		}
		$totalRow = $rowNumber;

		$sheet->setCellValue(
			'A' . $totalRow,
			'GRAND TOTAL'
		);

		$sheet->mergeCells(
			'A' . $totalRow . ':D' . $totalRow
		);

		$column = 5;
		foreach ($academicYears as $year) {
			$yearId =
				(int) (
					$year['academic_year_id']
					?? 0
				);

			$sheet->setCellValue(
				$this->excelColumnLetter($column) .
				$totalRow,
				$grandTotals[$yearId] ?? 0
			);
			$column++;
		}

		$sheet->setCellValue(
			$this->excelColumnLetter($column) .
			$totalRow,
			$grandTotalPending
		);

		$sheet
			->getStyle(
				'A' . $totalRow . ':' .
				$lastColumn . $totalRow
			)
			->applyFromArray([
				'font' => [
					'bold' => true,
				],
				'borders' => [
					'allBorders' => [
						'borderStyle' =>
							\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			]);

		$amountStartColumn = 5;
		$amountEndColumn =
			count($headers);
		if ($rowNumber > 5) {
			$sheet
				->getStyle(
					$this->excelColumnLetter(
						$amountStartColumn
					) . '5:' .
					$this->excelColumnLetter(
						$amountEndColumn
					) . $totalRow
				)
				->getNumberFormat()
				->setFormatCode(
					'#,##0.00'
				);
		}

		$sheet
			->getStyle(
				'A4:' . $lastColumn . $totalRow
			)
			->getAlignment()
			->setVertical(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			);

		$sheet
			->getStyle(
				'A5:A' . $totalRow
			)
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		for (
			$i = 1;
			$i <= count($headers);
			$i++
		) {
			$columnLetter =
				$this->excelColumnLetter($i);

			$sheet
				->getColumnDimension(
					$columnLetter
				)
				->setAutoSize(true);
		}

		$sheet
			->getColumnDimension('A')
			->setWidth(10);
		$sheet
			->getColumnDimension('B')
			->setWidth(18);
		$sheet
			->getColumnDimension('C')
			->setWidth(30);
		$sheet
			->getColumnDimension('D')
			->setWidth(18);
		$sheet->freezePane('A5');

		$sheet->setAutoFilter(
			'A4:' .
			$lastColumn .
			$totalRow
		);

		$fileName =
			'all_previous_fees_report_' .
			date('Y-m-d_H-i-s') .
			'.xlsx';

		if (ob_get_level()) {
			ob_end_clean();
		}

		header(
			'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);
		header(
			'Content-Disposition: attachment; filename="' .
			$fileName .
			'"'
		);
		header(
			'Cache-Control: max-age=0'
		);
		$writer =
			new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
				$spreadsheet
			);
		$writer->save('php://output');
		$spreadsheet->disconnectWorksheets();
		unset($spreadsheet);
		exit;
	}


	public function export_last_year_fees_excel()
	{
		$search = trim(
			(string) $this->input->get(
				'search',
				true
			)
		);

		$records =
			$this->Report_model
				->export_last_year_fees_report([
					'search' => $search,
				]);

		require_once FCPATH . 'vendor/autoload.php';

		$spreadsheet =
			new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$sheet =
			$spreadsheet->getActiveSheet();

		$sheet->setTitle(
			'Last Year Fees'
		);

		$lastColumn =
			$this->excelColumnLetter(10);

		$sheet->mergeCells(
			'A1:' . $lastColumn . '1'
		);

		$sheet->setCellValue(
			'A1',
			'Last Year Fees Report'
		);

		$sheet
			->getStyle('A1')
			->getFont()
			->setBold(true);

		$sheet
			->getStyle('A1')
			->getFont()
			->setSize(16);

		$sheet
			->getStyle('A1')
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		$sheet->mergeCells(
			'A2:' . $lastColumn . '2'
		);

		$sheet->setCellValue(
			'A2',
			'Search: ' .
			(
				$search !== ''
				? $search
				: 'All Students'
			)
		);

		$headerRow = 4;

		$headers = [
			'Sr. No.',
			'Student ID',
			'Student Name',
			'Mobile',
			'Academic Year',
			'Total Fee',
			'Paid',
			'Exempted',
			'Adjusted Paid',
			'Pending',
		];

		foreach ($headers as $index => $header) {

			$columnLetter =
				$this->excelColumnLetter(
					$index + 1
				);

			$sheet->setCellValue(
				$columnLetter . $headerRow,
				$header
			);
		}

		$sheet
			->getStyle(
				'A' . $headerRow . ':' .
				$lastColumn . $headerRow
			)
			->applyFromArray([
				'font' => [
					'bold' => true,
					'size' => 11,
				],

				'alignment' => [
					'horizontal' =>
						\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,

					'vertical' =>
						\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],

				'borders' => [
					'allBorders' => [
						'borderStyle' =>
							\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			]);

		$rowNumber = 5;
		$serial = 1;

		$grandTotalFee = 0;
		$grandTotalPaid = 0;
		$grandTotalExempted = 0;
		$grandTotalAdjustedPaid = 0;
		$grandTotalPending = 0;

		foreach ($records as $row) {

			$studentId = (string) (
				$row['SPRIDEN_student_id']
				?? ''
			);

			$studentName = (string) (
				$row['name']
				?? ''
			);

			$mobile = (string) (
				$row['MobileNumber']
				?? ''
			);

			$academicYear = (string) (
				$row['Academic_year']
				?? ''
			);

			$totalFee = (float) (
				$row['Total_fee']
				?? 0
			);

			$totalPaid = (float) (
				$row['Total_paid']
				?? 0
			);

			$totalExempted = (float) (
				$row['Total_exumpted']
				?? 0
			);

			$totalAdjustedPaid = (float) (
				$row['Total_paid_icluding_exumption']
				?? 0
			);

			$pendingFees = (float) (
				$row['Pending_fees']
				?? 0
			);

			$sheet->setCellValue(
				'A' . $rowNumber,
				$serial
			);

			$sheet->setCellValueExplicit(
				'B' . $rowNumber,
				$studentId,
				\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
			);

			$sheet->setCellValue(
				'C' . $rowNumber,
				$studentName
			);

			$sheet->setCellValueExplicit(
				'D' . $rowNumber,
				$mobile,
				\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
			);

			$sheet->setCellValue(
				'E' . $rowNumber,
				$academicYear
			);

			$sheet->setCellValue(
				'F' . $rowNumber,
				$totalFee
			);

			$sheet->setCellValue(
				'G' . $rowNumber,
				$totalPaid
			);

			$sheet->setCellValue(
				'H' . $rowNumber,
				$totalExempted
			);

			$sheet->setCellValue(
				'I' . $rowNumber,
				$totalAdjustedPaid
			);

			$sheet->setCellValue(
				'J' . $rowNumber,
				$pendingFees
			);

			$grandTotalFee += $totalFee;

			$grandTotalPaid += $totalPaid;

			$grandTotalExempted +=
				$totalExempted;

			$grandTotalAdjustedPaid +=
				$totalAdjustedPaid;

			$grandTotalPending +=
				$pendingFees;

			$rowNumber++;
			$serial++;
		}

		$totalRow = $rowNumber;

		$sheet->mergeCells(
			'A' . $totalRow . ':E' . $totalRow
		);

		$sheet->setCellValue(
			'A' . $totalRow,
			'GRAND TOTAL'
		);

		$sheet->setCellValue(
			'F' . $totalRow,
			$grandTotalFee
		);

		$sheet->setCellValue(
			'G' . $totalRow,
			$grandTotalPaid
		);

		$sheet->setCellValue(
			'H' . $totalRow,
			$grandTotalExempted
		);

		$sheet->setCellValue(
			'I' . $totalRow,
			$grandTotalAdjustedPaid
		);

		$sheet->setCellValue(
			'J' . $totalRow,
			$grandTotalPending
		);

		$sheet
			->getStyle(
				'A' . $totalRow . ':' .
				$lastColumn . $totalRow
			)
			->applyFromArray([
				'font' => [
					'bold' => true,
				],

				'borders' => [
					'allBorders' => [
						'borderStyle' =>
							\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			]);

		if ($totalRow >= 5) {

			$sheet
				->getStyle(
					'F5:J' . $totalRow
				)
				->getNumberFormat()
				->setFormatCode(
					'#,##0.00'
				);
		}

		$sheet
			->getStyle(
				'A4:J' . $totalRow
			)
			->getAlignment()
			->setVertical(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			);

		$sheet
			->getStyle(
				'A5:A' . $totalRow
			)
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		$sheet
			->getStyle(
				'E5:E' . $totalRow
			)
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		for ($i = 1; $i <= count($headers); $i++) {

			$columnLetter =
				$this->excelColumnLetter($i);

			$sheet
				->getColumnDimension(
					$columnLetter
				)
				->setAutoSize(true);
		}

		$sheet
			->getColumnDimension('A')
			->setWidth(10);

		$sheet
			->getColumnDimension('B')
			->setWidth(18);

		$sheet
			->getColumnDimension('C')
			->setWidth(30);

		$sheet
			->getColumnDimension('D')
			->setWidth(18);

		$sheet
			->getColumnDimension('E')
			->setWidth(20);

		$sheet->freezePane('A5');

		$sheet->setAutoFilter(
			'A4:' .
			$lastColumn .
			$totalRow
		);

		$fileName =
			'last_year_fees_report_' .
			date('Y-m-d_H-i-s') .
			'.xlsx';

		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		header(
			'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		header(
			'Content-Disposition: attachment; filename="' .
			$fileName .
			'"'
		);

		header(
			'Cache-Control: max-age=0'
		);

		$writer =
			new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
				$spreadsheet
			);

		$writer->save(
			'php://output'
		);

		$spreadsheet->disconnectWorksheets();

		unset($spreadsheet);

		exit;
	}


	public function export_current_zero_paid_excel()
	{
		/*
		 * --------------------------------------------------
		 * SEARCH FILTER
		 * --------------------------------------------------
		 */
		$search = trim(
			(string) $this->input->get(
				'search',
				true
			)
		);

		/*
		 * --------------------------------------------------
		 * GET ALL FILTERED RECORDS
		 * --------------------------------------------------
		 */
		$records =
			$this->Report_model
				->export_current_zero_paid_report([
					'search' => $search,
				]);

		/*
		 * --------------------------------------------------
		 * LOAD PHPSPREADSHEET
		 * --------------------------------------------------
		 */
		require_once FCPATH . 'vendor/autoload.php';

		$spreadsheet =
			new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$sheet =
			$spreadsheet->getActiveSheet();

		$sheet->setTitle(
			'Zero Paid Report'
		);

		/*
		 * --------------------------------------------------
		 * TITLE
		 * --------------------------------------------------
		 */
		$lastColumn =
			$this->excelColumnLetter(9);

		$sheet->mergeCells(
			'A1:' . $lastColumn . '1'
		);

		$sheet->setCellValue(
			'A1',
			'Current Year Zero Paid Report'
		);

		$sheet
			->getStyle('A1')
			->getFont()
			->setBold(true);

		$sheet
			->getStyle('A1')
			->getFont()
			->setSize(16);

		$sheet
			->getStyle('A1')
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		/*
		 * --------------------------------------------------
		 * SEARCH INFORMATION
		 * --------------------------------------------------
		 */
		$sheet->mergeCells(
			'A2:' . $lastColumn . '2'
		);

		$sheet->setCellValue(
			'A2',
			'Search: ' .
			(
				$search !== ''
				? $search
				: 'All Students'
			)
		);

		/*
		 * --------------------------------------------------
		 * HEADER
		 * --------------------------------------------------
		 */
		$headerRow = 4;

		$headers = [
			'Sr. No.',
			'Student ID',
			'Student Name',
			'Class',
			'Division',
			'Mobile',
			'Total Fee',
			'Paid',
			'Pending Fee',
		];

		foreach ($headers as $index => $header) {

			$columnLetter =
				$this->excelColumnLetter(
					$index + 1
				);

			$sheet->setCellValue(
				$columnLetter . $headerRow,
				$header
			);
		}

		/*
		 * --------------------------------------------------
		 * HEADER STYLE
		 * --------------------------------------------------
		 */
		$sheet
			->getStyle(
				'A' . $headerRow . ':' .
				$lastColumn . $headerRow
			)
			->applyFromArray([
				'font' => [
					'bold' => true,
					'size' => 11,
				],

				'alignment' => [
					'horizontal' =>
						\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,

					'vertical' =>
						\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				],

				'borders' => [
					'allBorders' => [
						'borderStyle' =>
							\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			]);

		/*
		 * --------------------------------------------------
		 * DATA
		 * --------------------------------------------------
		 */
		$rowNumber = 5;
		$serial = 1;

		$grandTotalFee = 0;
		$grandTotalPaid = 0;
		$grandTotalPending = 0;

		foreach ($records as $row) {

			/*
			 * ----------------------------------------------
			 * VALUES
			 * ----------------------------------------------
			 */
			$studentId = (string) (
				$row['StudentID']
				?? ''
			);

			$studentName = (string) (
				$row['name']
				?? ''
			);

			$class = (string) (
				$row['Class']
				?? ''
			);

			$division = (string) (
				$row['Division']
				?? ''
			);

			$mobile = (string) (
				$row['MobileNumber']
				?? ''
			);

			$totalFee = (float) (
				$row['TotalFee']
				?? 0
			);

			$totalPaid = (float) (
				$row['TotalPaid']
				?? 0
			);

			$pendingFee = (float) (
				$row['PendingFee']
				?? 0
			);

			/*
			 * ----------------------------------------------
			 * SERIAL
			 * ----------------------------------------------
			 */
			$sheet->setCellValue(
				'A' . $rowNumber,
				$serial
			);

			/*
			 * ----------------------------------------------
			 * STUDENT ID
			 *
			 * Keep as string.
			 * ----------------------------------------------
			 */
			$sheet->setCellValueExplicit(
				'B' . $rowNumber,
				$studentId,
				\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
			);

			/*
			 * ----------------------------------------------
			 * STUDENT NAME
			 * ----------------------------------------------
			 */
			$sheet->setCellValue(
				'C' . $rowNumber,
				$studentName
			);

			/*
			 * ----------------------------------------------
			 * CLASS
			 * ----------------------------------------------
			 */
			$sheet->setCellValue(
				'D' . $rowNumber,
				$class
			);

			/*
			 * ----------------------------------------------
			 * DIVISION
			 * ----------------------------------------------
			 */
			$sheet->setCellValue(
				'E' . $rowNumber,
				$division
			);

			/*
			 * ----------------------------------------------
			 * MOBILE
			 * ----------------------------------------------
			 */
			$sheet->setCellValueExplicit(
				'F' . $rowNumber,
				$mobile,
				\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
			);

			/*
			 * ----------------------------------------------
			 * AMOUNTS
			 * ----------------------------------------------
			 */
			$sheet->setCellValue(
				'G' . $rowNumber,
				$totalFee
			);

			$sheet->setCellValue(
				'H' . $rowNumber,
				$totalPaid
			);

			$sheet->setCellValue(
				'I' . $rowNumber,
				$pendingFee
			);

			/*
			 * ----------------------------------------------
			 * GRAND TOTALS
			 * ----------------------------------------------
			 */
			$grandTotalFee +=
				$totalFee;

			$grandTotalPaid +=
				$totalPaid;

			$grandTotalPending +=
				$pendingFee;

			$rowNumber++;
			$serial++;
		}

		/*
		 * --------------------------------------------------
		 * GRAND TOTAL ROW
		 * --------------------------------------------------
		 */
		$totalRow = $rowNumber;

		/*
		 * Merge A:F
		 */
		$sheet->mergeCells(
			'A' . $totalRow . ':F' . $totalRow
		);

		$sheet->setCellValue(
			'A' . $totalRow,
			'GRAND TOTAL'
		);

		/*
		 * Total Fee
		 */
		$sheet->setCellValue(
			'G' . $totalRow,
			$grandTotalFee
		);

		/*
		 * Total Paid
		 */
		$sheet->setCellValue(
			'H' . $totalRow,
			$grandTotalPaid
		);

		/*
		 * Total Pending
		 */
		$sheet->setCellValue(
			'I' . $totalRow,
			$grandTotalPending
		);

		/*
		 * --------------------------------------------------
		 * TOTAL ROW STYLE
		 * --------------------------------------------------
		 */
		$sheet
			->getStyle(
				'A' . $totalRow . ':' .
				$lastColumn . $totalRow
			)
			->applyFromArray([
				'font' => [
					'bold' => true,
				],

				'borders' => [
					'allBorders' => [
						'borderStyle' =>
							\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					],
				],
			]);

		/*
		 * --------------------------------------------------
		 * AMOUNT FORMAT
		 * --------------------------------------------------
		 */
		if ($totalRow >= 5) {

			$sheet
				->getStyle(
					'G5:I' . $totalRow
				)
				->getNumberFormat()
				->setFormatCode(
					'#,##0.00'
				);
		}

		/*
		 * --------------------------------------------------
		 * ALIGNMENT
		 * --------------------------------------------------
		 */
		$sheet
			->getStyle(
				'A4:I' . $totalRow
			)
			->getAlignment()
			->setVertical(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			);

		/*
		 * Center serial
		 */
		$sheet
			->getStyle(
				'A5:A' . $totalRow
			)
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		/*
		 * Center class & division
		 */
		$sheet
			->getStyle(
				'D5:E' . $totalRow
			)
			->getAlignment()
			->setHorizontal(
				\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			);

		/*
		 * --------------------------------------------------
		 * AUTO WIDTH
		 * --------------------------------------------------
		 */
		for (
			$i = 1;
			$i <= count($headers);
			$i++
		) {

			$columnLetter =
				$this->excelColumnLetter($i);

			$sheet
				->getColumnDimension(
					$columnLetter
				)
				->setAutoSize(true);
		}

		/*
		 * Better widths
		 */
		$sheet
			->getColumnDimension('A')
			->setWidth(10);

		$sheet
			->getColumnDimension('B')
			->setWidth(18);

		$sheet
			->getColumnDimension('C')
			->setWidth(30);

		$sheet
			->getColumnDimension('D')
			->setWidth(15);

		$sheet
			->getColumnDimension('E')
			->setWidth(12);

		$sheet
			->getColumnDimension('F')
			->setWidth(18);

		/*
		 * --------------------------------------------------
		 * FREEZE HEADER
		 * --------------------------------------------------
		 */
		$sheet->freezePane('A5');

		/*
		 * --------------------------------------------------
		 * AUTO FILTER
		 * --------------------------------------------------
		 */
		$sheet->setAutoFilter(
			'A4:' .
			$lastColumn .
			$totalRow
		);

		/*
		 * --------------------------------------------------
		 * DOWNLOAD
		 * --------------------------------------------------
		 */
		$fileName =
			'current_zero_paid_report_' .
			date('Y-m-d_H-i-s') .
			'.xlsx';

		/*
		 * Clear output buffer
		 */
		while (ob_get_level() > 0) {
			ob_end_clean();
		}

		header(
			'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		header(
			'Content-Disposition: attachment; filename="' .
			$fileName .
			'"'
		);

		header(
			'Cache-Control: max-age=0'
		);

		$writer =
			new \PhpOffice\PhpSpreadsheet\Writer\Xlsx(
				$spreadsheet
			);

		$writer->save(
			'php://output'
		);

		$spreadsheet->disconnectWorksheets();

		unset($spreadsheet);

		exit;
	}
}
