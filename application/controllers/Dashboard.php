<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('Dashboard_model');
		$this->load->model('Student_model');
		$this->load->model('Timetable_model');
		$this->load->model('Teacher_model');
		$this->load->model('Stats_model');
		$this->load->model('DB_model');
		// $this->load->model('fillStudentsMarks');
		if (!$this->session->userdata('oliveMobileSystemUser'))
			redirect(base_url() . 'teacher_login');
	}

	//Function to get User Data
	public function get_dashboard_user()
	{
		$userID = $this->session->userdata('oliveMobileSystemUser');
		$userdata = $this->Dashboard_model->get_dashboard_user($userID);
		return $userdata;
	}

	//Function to get User Data
	public function get_student_under_teacher($std, $div)
	{
		$userID = $this->session->userdata('oliveMobileSystemUser');
		$student = $this->Dashboard_model->get_student_under_teacher($std, $div);
		return $student;
	}

	//================================ Teacher Module ===================================
	function stud_data()
	{
		$this->load->model('Dashboard_model');
		echo json_encode($this->Dashboard_model->student_data());
	}

	function insert_att()
	{
		$this->load->model('Dashboard_model');
		echo json_encode($this->Dashboard_model->insert_att());
	}

	public function index()
	{
		$data['all_remarks'] = $this->Dashboard_model->get_allRemarks($this->session->userdata('oliveMobileSystemUser'));
		$data['all_remarks_masters'] = $this->Dashboard_model->get_all_remarks_masters();
		$data['all_exams'] = $this->Dashboard_model->get_all_exams();
		$data['total_pending'] = $this->Dashboard_model->get_pending_remarks($this->session->userdata('oliveMobileSystemUser'));
		$data['total_replied'] = $this->Dashboard_model->get_replied_remarks($this->session->userdata('oliveMobileSystemUser'));

		// $data['allocated_class'] = $this->Dashboard_model->get_allocated_classs($this->session->userdata('oliveMobileSystemUser'));
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$session_std = $this->session->userdata('session_std');
		$session_div = $this->session->userdata('session_div');

		if (!empty($session_std) && !empty($session_div)) {
			// Use the class picked at login
			$data['allocated_class'] = $this->Dashboard_model
				->get_allocated_class_by_std_div($teacher_id, $session_std, $session_div);
		} else {
			// fallback to old behaviour
			$data['allocated_class'] = $this->Dashboard_model
				->get_allocated_classs($teacher_id);
		}

		$data['all_students'] = $this->Dashboard_model->get_student_under_teacher($this->session->userdata('oliveMobileSystemUser'));
		$data['userData'] = $this->get_dashboard_user();
		$data['classes'] = $this->Dashboard_model->getAllClasses($this->session->userdata('oliveMobileSystemUser'));
		$data['class'] = $this->Dashboard_model->getAllclass($this->session->userdata('oliveMobileSystemUser'));
		$data['fees_details'] = $this->Dashboard_model->fees_details_assigned($this->session->userdata('oliveMobileSystemUser'));

		if ($data['allocated_class'] != null) {
			$data['all_subjects'] = $this->Dashboard_model->get_allSubjectsUnderStd($data['allocated_class']['SPATESD_std']);
			$data['timetable'] = $this->Timetable_model->getTimetableForTeachersAndStudents($data['allocated_class']['SPATESD_std'], $data['allocated_class']['SPATESD_div']);
		} else {
			$data['all_subjects'] = '';
			$data['timetable'] = '';
		}
		$data['events'] = [];

		$this->load->view('Teacher/tc-db-new', $data);
	}
	public function forms()
	{
		$data['forms'] = teacher_forms();
		$data['userData'] = $this->get_dashboard_user();
		$this->load->view('Teacher/forms-links', $data);
	}

	function fetchWhatsappNumber()
	{
		echo json_encode($this->Dashboard_model->fetchWhatsappNumber($this->input->get('ids')));
	}

	function student_attendance($month = null, $year = null)
	{
		$data['forms'] = teacher_forms();
		$data['userData'] = $this->get_dashboard_user();
		$data['all_students'] = $this->Dashboard_model->getStudentsForContacts($this->session->userdata('oliveMobileSystemUser'));
		$data['selected_month'] = $month == null ? date('m') : $month;
		$data['selected_year'] = $year == null ? date('Y') : $year;
		$data['attendance_data'] = $this->Dashboard_model->attendanceData($this->session->userdata('oliveMobileSystemUser'), $data['selected_month'], $data['selected_year']);
		$this->load->view('Teacher/absent-list', $data);
	}

	public function student_contacts()
	{
		$data['all_students'] = $this->Dashboard_model->getStudentsForContacts($this->session->userdata('oliveMobileSystemUser'));
		$data['userData'] = $this->get_dashboard_user();
		$this->load->view('Teacher/student-contact', $data);
	}

	function save_contacts()
	{
		$this->Dashboard_model->save_contacts();
	}

	function get_student_details_for_contact()
	{
		$data = $this->Dashboard_model->get_student_details_for_contact($this->input->post('rowID'));
		$data2 = $this->Dashboard_model->get_student_details_for_contact_pcnf($this->input->post('rowID'));
		$newArray['data1'] = $data;
		$newArray['data2'] = $data2;
		echo json_encode($newArray);
	}

	function update_rollno()
	{
		$this->load->model('Dashboard_model');
		$sql = $this->Dashboard_model->update_rollno($this->session->userdata('oliveMobileSystemUser'));
		if ($sql) {
			redirect(base_url('dashboard'));
		}
	}

	function getDataForUpdateFeedback()
	{
		$data = $this->Dashboard_model->getDataForUpdateFeedback($this->input->post('rowID'));
		echo json_encode($data);
	}

	function updateParentsFeedbackByTeacher()
	{
		$status = $this->Dashboard_model->updateParentsFeedbackByTeacher();
		if ($status) {
			echo "success";
		} else {
			echo "fail";
		}
	}

	function add_event()
	{
		$academic_data = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$academic_year = $academic_data == null ? 0 : $academic_data['stvschool_acdm_year'];
		$data = array(
			'stvevnt_start_date' => $this->input->post('start_date'),
			'stvevnt_end_date' => $this->input->post('end_date'),
			'stvevnt_title' => $this->input->post('title'),
			'stvevnt_details' => $this->input->post('details'),
			'stvevnt_std' => $this->input->post('std'),
			'stvevnt_div' => $this->input->post('div'),
			'stvevnt_academic_year' => $academic_year
		);

		$status = $this->Dashboard_model->add_event($data);
		if ($status) {
			echo "success";
		} else {
			echo "fail";
		}
	}
	function addFees()
	{
		$students = [];
		$student_id = $this->input->post("student_id");
		array_push($students, $student_id);
		$type = 'fees_alert';
		$data = $this->Dashboard_model->teacher_activity($students, $type);
		echo json_encode($data);
	}

	//fill Marks Student
	function fillStudentsMarks()
	{
		$std = $this->input->post('stad');
		$division = $this->input->post('divi');
		$post_student_id = $this->input->post('student_id_marks');
		$post_att_marks = $this->input->post('marks');
		$subjects = $this->input->post('subject');
		$exam_name = $this->input->post('exam_name');
		$total_marks = $this->input->post('total_marks');
		// update
		$exam_type = $this->input->post('exam_type');
		$individual_exam = $this->input->post('individual_exam');
		if (empty($post_student_id) || empty($post_att_marks)) {
			echo "fail";
			return;
		}
		$status = $this->Dashboard_model->fillStudentsMarks($std, $division, $post_student_id, $post_att_marks, $subjects, $exam_name, $total_marks, $exam_type, $individual_exam);

		if ($status) {
			echo json_encode(['status' => 'success', 'message' => 'Marks saved successfully.']);
		} else {
			echo json_encode(['status' => 'fail', 'message' => 'Failed to save marks. Please try again.']);
		}
	}

	public function getReportCardData()
	{
		$data['acdm'] = $this->uri->segment(4);
		$data['class'] = $this->uri->segment(5);
		$data['div'] = $this->uri->segment(6);
		// Get all academic years for dropdown
		$data['academic_years'] = $this->Dashboard_model->get_all_academic_years();
		// If parameters exist, get the filtered data
		if ($data['acdm'] && $data['class'] && $data['div']) {
			$data['report_data'] = $this->Dashboard_model->getReportCardData($data['acdm'], $data['class'], $data['div']);
		}

		$this->load->view('Teacher/report_card_list', $data);
	}

	public function Report_card($student_id, $acdm, $class, $div)
	{
		$data['marks'] = $this->Dashboard_model->getMarks($student_id, $acdm, $class, $div);
		$data['student'] = $this->Dashboard_model->getStudentData($student_id, $acdm);
		$data['acdm_year'] = $this->Dashboard_model->getAcdmYear($acdm);
		if ($class == 'IX')
			$this->load->view('Report_card/form-v2', $data);
		else
			$this->load->view('Report_card/form', $data);
	}

	public function getClasses()
	{
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$acdm = $this->input->get('acdm_year');
		echo json_encode($this->Dashboard_model->getClasses($teacher_id, $acdm));
	}

	public function getDivisions()
	{
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$acdm = $this->input->get('acdm_year');
		echo json_encode($this->Dashboard_model->getDivisions($teacher_id, $acdm));
	}
	public function report_list()
	{
		$acdm = $this->input->get('acdm');
		$class = $this->input->get('class');
		$div = $this->input->get('div');
		echo json_encode($this->Dashboard_model->getReportCardData($acdm, $class, $div));
	}

	//===================== STudent SeCtioN ===================================
	//Attendance MAster Details start-----------------------------------------------
	function attendance()
	{
		$data['allocated_class'] = $this->Dashboard_model->get_allocated_class($this->session->userdata('oliveMobileSystemUser'));
		$data['userData'] = $this->get_dashboard_user();
		$this->load->view('Teacher/attendance', $data);
	}

	//fill Attendance Student
	function fillStudentsAttendance()
	{
		$std = $this->input->post('std');
		$division = $this->input->post('division');
		$dateofatte = $this->input->post('dateOfAtt');
		$post_student_id = $this->input->post('student_ids');
		$post_att_status = $this->input->post('attendanceStatus');
		$status = $this->Dashboard_model->fillStudentsAttendance($dateofatte, $std, $division, $post_student_id, $post_att_status);
		sleep(1);
		if ($status) {
			echo "success";
		} else {
			echo "fail";
		}
	}

	//Attendance MAster Details end-----------------------------------------------
	// Remark Details start-----------------------------------------------
	function student_remark()
	{
		$data['userData'] = $this->get_dashboard_user();
		//$data['all_students'] = $this->get_student_under_teacher();
		$this->load->view('Teacher/student_remark', $data);
	}

	function add_remark()
	{
		$status = $this->Dashboard_model->add_remark();
		if ($status) {
			echo "success";
		} else {
			echo "fail";
		}
	}

	function get_student_for_remark()
	{
		$data = $this->Dashboard_model->get_student_for_remark($this->input->post('rowID'));
		echo json_encode($data);
	}

	function get_student_for_remark_data()
	{
		$data = $this->Dashboard_model->get_student_for_remark_data($this->input->post('rowID'));
		echo json_encode($data);
	}

	function get_student_for_remark_by_modalbutton()
	{
		$data = $this->Dashboard_model->get_student_for_remark_by_modalbutton($this->input->post('rowID'));
		echo json_encode($data);
	}

	function get_students_for_teacher_remark_by_std_and_div()
	{
		echo json_encode($this->Dashboard_model->get_students_for_teacher_remark_by_std_and_div());
	}

	// Remark Details end-----------------------------------------------
	// Marks Details start-----------------------------------------------
	function student_marks()
	{
		$data['userData'] = $this->get_dashboard_user();
		$this->load->view('Teacher/attendance', $data);
	}

	// Marks Details end-----------------------------------------------
	function get_subject_by_std()
	{
		$data['subjects'] = $this->Dashboard_model->get_subject_by_std($this->input->post('std'));
		// $data['subjects'] = $this->Dashboard_model->get_subject_by_std($this->input->post('std'), $this->session->userdata('oliveMobileSystemUser'));
		echo json_encode($data);
	}

	//============== Dashboard Logout Function=============
	// function logout()
	// {
	// 	$this->session->unset_userdata('oliveMobileSystemUser');
	// 	redirect(base_url() . 'teacher_login');
	// }

	function logout()
	{
		// unset only the login-related keys
		$this->session->unset_userdata([
			'oliveMobileSystemUser',
			'session_type',
			'session_std',
			'session_div',
			'login_data'
		]);

		// Optional: nuke everything (safest)
		// $this->session->sess_destroy();

		redirect(base_url() . 'teacher_login');
	}

	//till here

	function getStudentsForFillMarks()
	{
		echo json_encode($this->Dashboard_model->getStudentsForFillMarks());
	}

	function getDivByStd()
	{
		$data['division'] = $this->Dashboard_model->getDivByStd($this->input->post('std'), $this->session->userdata('oliveMobileSystemUser'));
		echo json_encode($data);
	}

	function teacherProfile()
	{
		$post = $this->input->post();
		$this->Dashboard_model->teacherProfile($post);
	}
	function fetchone()
	{
		$id = $this->input->post('id');
		$q = "select SPRIDEN_student_id,SPRIDEN_surname,SPRIDEN_student_name,SPRIDEN_father_name,SPRIDEN_EMAIL,SPRIDEN_BGRP,SPRIDEN_aadhar_no,SPRIDEN_birthdate,
        (select mobile from stvtcnf where teacher_id = SPRIDEN_student_id) mobile,
        (select whatsapp from stvtcnf where teacher_id = SPRIDEN_student_id) whatsapp
        from spriden where SPRIDEN_student_id = '$id'";
		$data = $this->db->query($q)->row_array();
		echo json_encode($data);
	}

	function restud()
	{
		$data['userData'] = $this->get_dashboard_user();
		$class = $this->input->get('std');
		$div = $this->input->get('div');
		$data['class'] = $class;
		$data['div'] = $div;
		$data['re_stud'] = $this->Teacher_model->re_stud($class, $div);
		$this->load->view('studremove/re_stud', $data);
		// $data['re_stud'] = $this->Teacher_model->re_stud($class, $div);
		// $this->load->view('studremove\re_stud.php', $data);
	}

	function set_class_division()
	{
		$this->Dashboard_model->set_class_division();
	}

	function teacherPassword()
	{
		$post = $this->input->post();
		// $exist = $this->Dashboard_model->teacherPassword($post);
		$id = $post['id'];
		$curpass = $post['curpass'];
		$newpass = $post['newpass'];
		$conpass = $post['conpass'];
		//CHECK IF exist
		$exist = $this->db->where('spriden_student_id', $id)->get('spriden')->row_array();
		if ($exist) {
			if ($curpass == $exist['spriden_tc_pass']) {
				if ($newpass == $conpass) {
					$sql = "UPDATE spriden SET spriden_tc_pass='$conpass' where spriden_student_id='$id'";
					$this->db->query($sql);
					$this->session->set_flashdata('message', 'Password updated successfully..!');
					redirect(base_url() . 'index.php/dashboard');
				} else {
					$this->session->set_flashdata('message', 'New password and Confirm password do not match..!');
				}
			} else {
				$this->session->set_flashdata('message', 'Current Password is Incorrect..!');
			}
		} else {
			$this->session->set_flashdata('message', 'Please Login Again..!');
		}
	}

	// -------------------------- new ---------------------------------
	function tracking()
	{
		$data['dataforClass'] = $this->Student_model->getDataForClass();

		// $data['allocated_class'] = $this->Dashboard_model->get_allocated_classs($this->session->userdata('oliveMobileSystemUser'));
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$session_std = $this->session->userdata('session_std');
		$session_div = $this->session->userdata('session_div');

		if (!empty($session_std) && !empty($session_div)) {
			// Use the class picked at login
			$data['allocated_class'] = $this->Dashboard_model
				->get_allocated_class_by_std_div($teacher_id, $session_std, $session_div);
		} else {
			// fallback to old behaviour
			$data['allocated_class'] = $this->Dashboard_model
				->get_allocated_classs($teacher_id);
		}

		$data['all_students'] = $this->Dashboard_model->get_student_under_teacher($this->session->userdata('oliveMobileSystemUser'));
		$data['userData'] = $this->get_dashboard_user();
		$data['classes'] = $this->Dashboard_model->getAllClasses($this->session->userdata('oliveMobileSystemUser'));
		$data['class'] = $this->Dashboard_model->getAllclass($this->session->userdata('oliveMobileSystemUser'));
		if ($data['allocated_class'] != null) {
			// print_r($data['allocated_class']);
			// die();
			$data['all_subjects'] = $this->Dashboard_model->get_allSubjectsUnderStd($data['allocated_class']['SPATESD_std']);
			$data['timetable'] = $this->Timetable_model->getTimetableForTeachersAndStudents($data['allocated_class']['SPATESD_std'], $data['allocated_class']['SPATESD_div']);
		} else {
			$data['all_subjects'] = '';
			$data['timetable'] = '';
		}

		$this->load->view('Teacher/tracking', $data);
	}

	function add_data()
	{
		echo json_encode($this->Dashboard_model->insert_data());
	}

	function fetch_data()
	{
		echo json_encode($this->Dashboard_model->fetch_data());
	}

	// ------------------------- Ansari Kamran -------------------------------
	public function share_links()
	{
		$data['userData'] = $this->get_dashboard_user();
		$class = $this->input->get('std');
		$div = $this->input->get('div');
		$type = $this->input->get('type');
		$classes = $this->Stats_model->getAllClasses(); //load Status_model 
		$data['classes'] = $classes;
		$data['class'] = $class;
		$data['div'] = $div;
		$data['type'] = $type;
		$data['students'] = $this->Dashboard_model->get_student_D($class, $div, $type);
		$this->load->view('Teacher/share_links', $data);
	}

	// Ansari Kamran
	public function syllabus()
	{
		$data['userData'] = $this->get_dashboard_user();
		$class = $this->input->get('std');
		$div = $this->input->get('div');
		$classes = $this->Stats_model->getAllClasses(); //load Status_model 
		$data['classes'] = $classes;
		$data['class'] = $class;
		$data['div'] = $div;
		$data['students'] = $this->Dashboard_model->get_student_D($class, $div);
		$data['all_sly'] = $this->Dashboard_model->fetch_syllabus();
		$this->load->view('Teacher/syllabus', $data);
	}

	function fetch_sub()
	{
		$std = $this->input->get('std');
		echo json_encode($this->Dashboard_model->syllabus($std));
	}

	function insert_syllabus()
	{
		echo json_encode($this->Dashboard_model->insert_syllabus());
	}

	// Ansari Kamran 
	public function syllabus_calendar()
	{
		$data['userData'] = $this->get_dashboard_user();
		$currentTimestamp = time();
		// Calculate the start date of the current week (Sunday)
		$startDate = date('Y-m-d', strtotime('this week', $currentTimestamp));
		$data['start'] = $startDate;
		// Calculate the end date of the current week (Saturday)
		$endDate = date('Y-m-d', strtotime('this week +5 days', $currentTimestamp));
		$data['end'] = $endDate;
		$class = $this->input->get('std');
		$div = $this->input->get('div');
		$classes = $this->Stats_model->getAllClasses();
		$data['classes'] = $classes;
		$data['class'] = $class;
		$data['div'] = $div;
		$data['subjects'] = $this->Dashboard_model->syllabus($class);
		$data['syllabus'] = $this->Dashboard_model->syllabus_calender($class, $div, $startDate, $endDate);
		$this->load->view('Teacher/syllabus_calendar', $data);
	}

	// --- Ansari Kamran ---
	function evaluation()
	{
		$data['academic_years'] = $this->DB_model->get_all_academic_years();
		$data['userData'] = $this->get_dashboard_user();
		$class = $this->input->get('std');
		$div = $this->input->get('div');
		$exam = $this->input->get('exam');
		$sub = $this->input->get('sub');
		$acdm = $this->input->get('acdm');
		$classes = $this->Stats_model->getAllClasses(); //load Status_model 
		$data['classes'] = $classes;
		$data['class'] = $class;
		$data['div'] = $div;
		$data['exam'] = $exam;
		$data['sub'] = $sub;

		$data['students'] = [];
		$data['subjects'] = $this->Dashboard_model->syllabus($class);
		if ($this->input->get('std') != '')
			$data['students'] = $this->Dashboard_model->fetch_sprsumeval_data($class, $exam, $div, $sub, $acdm);
		$data['all_exams'] = $this->Dashboard_model->get_all_exams();
		$this->load->view('Teacher/formative', $data);
	}

	function insert_evaluation()
	{
		echo json_encode($this->Dashboard_model->insert_evaluation());
	}
	// Updated 2024 Jul4 23
	function addActivity()
	{
		$students[] = $this->input->get('sid');
		$status = $this->Dashboard_model->teacher_activity($students, 'absent-sms');
		if ($status)
			echo "success";
		else
			echo "fail";
	}

	public function parents_meeting()
	{
		$data['userData'] = $this->get_dashboard_user();
		$class = $this->input->get('std');
		$div = $this->input->get('div');
		$meet = $this->input->get('meet');
		$meetings = $this->Dashboard_model->getParentsMeet();
		$classes = $this->Stats_model->getAllClasses(); //load Status_model
		$data['classes'] = $classes;
		$data['meetings'] = $meetings;
		$data['class'] = $class;
		$data['div'] = $div;
		$data['meet'] = $meet;
		$data['students'] = $this->Dashboard_model->getParentsMeeting($class, $div, $meet);
		$this->load->view('Teacher/Parents-Meeting', $data);
	}

	public function fetchMeetings()
	{
		$postData = json_decode(file_get_contents('php://input'), true);
		if (isset($postData['std'])) {
			$class = $postData['std'];
			$meetings = $this->Dashboard_model->getMeetingsByClass($class);
			if (!empty($meetings)) {
				echo json_encode(['success' => true, 'meetings' => $meetings]);
			} else {
				echo json_encode(['success' => false, 'message' => 'No meetings found.']);
			}
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid input.']);
		}
	}



	//  Code by Hassan starts here 
	public function personal_details_form()
	{
		$user_id = $this->session->userdata('oliveMobileSystemUser');

		if (!$user_id) {
			redirect('login');
		}

		$data['userData'] = $this->get_dashboard_user();
		$data['profile'] = $this->Dashboard_model->get_teacher_profile($user_id);
		$data['education'] = $this->Dashboard_model->get_teacher_education($user_id);
		$data['skills'] = $this->Dashboard_model->get_teacher_skills($user_id);
		$data['certs'] = $this->Dashboard_model->get_teacher_certs($user_id);
		$data['awards'] = $this->Dashboard_model->get_teacher_awards($user_id);

		$data['acdm'] = $this->uri->segment(4);
		$data['class'] = $this->uri->segment(5);
		$data['div'] = $this->uri->segment(6);

		$this->load->view('Teacher/personal_details_form', $data);
	}

	public function update_tc_profile()
	{
		// Ensure it's a POST request
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->session->set_flashdata('error', 'Invalid request method');
			redirect('dashboard/personal_details_form');
		}

		$user_id = $this->session->userdata('oliveMobileSystemUser');
		if (!$user_id) {
			$this->session->set_flashdata('error', 'Session expired. Please login again.');
			redirect('login');
		}

		$profile_id = $this->input->post('profile_id');

		// Prepare Profile Data
		// $profile_data = [
		// 	'speciality' => $this->input->post('speciality'),
		// 	'secondary_languages' => $this->input->post('languages'),
		// 	'email' => $this->input->post('email'),
		// 	'phone' => $this->input->post('phone'),
		// 	'location' => $this->input->post('location'),
		// 	'about_me' => $this->input->post('about_me'),
		// ];

		$profile_data = [
			'speciality' => $this->input->post('speciality'),
			'secondary_languages' => $this->input->post('languages'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'location' => $this->input->post('location'),
			'about_me' => $this->input->post('about_me'),
			'joining_date' => $this->input->post('joining_date'),
			'designation' => $this->input->post('designation'),
			'department' => $this->input->post('department'),
			'work_start' => $this->input->post('work_start'),
			'work_end' => $this->input->post('work_end'),
		];

		// Prepare Education Array
		$education = [];
		$edu_titles = $this->input->post('edu_title');
		$edu_inst = $this->input->post('edu_inst');
		$edu_years = $this->input->post('edu_year');
		$edu_ids = $this->input->post('edu_id');

		if (!empty($edu_titles) && is_array($edu_titles)) {
			foreach ($edu_titles as $key => $title) {
				// Skip completely empty entries
				if (empty($title) && empty($edu_inst[$key]) && empty($edu_years[$key])) {
					continue;
				}

				$edu_data = [
					'degree_title' => trim($title ?? ''),
					'institution' => trim($edu_inst[$key] ?? ''),
					'passing_year' => trim($edu_years[$key] ?? '')
				];

				// If ID exists, add it for update
				if (!empty($edu_ids[$key])) {
					$edu_data['id'] = $edu_ids[$key];
				}

				$education[] = $edu_data;
			}
		}

		// Skills & Certs (with IDs for deletion)
		$skills = $this->input->post('skills');
		$skill_ids = $this->input->post('skill_id');
		$certs = $this->input->post('certs');
		$cert_ids = $this->input->post('cert_id');

		// Ensure arrays are set
		$skills = is_array($skills) ? $skills : [];
		$skill_ids = is_array($skill_ids) ? $skill_ids : [];
		$certs = is_array($certs) ? $certs : [];
		$cert_ids = is_array($cert_ids) ? $cert_ids : [];

		// Awards (with IDs)
		$awards = [];
		$award_names = $this->input->post('award_name');
		$award_metas = $this->input->post('award_meta');
		$award_ids = $this->input->post('award_id');

		if (!empty($award_names) && is_array($award_names)) {
			foreach ($award_names as $key => $name) {
				// Skip completely empty entries
				if (empty($name) && empty($award_metas[$key])) {
					continue;
				}

				$award_data = [
					'award_name' => trim($name ?? ''),
					'award_meta' => trim($award_metas[$key] ?? '')
				];

				if (!empty($award_ids[$key])) {
					$award_data['id'] = $award_ids[$key];
				}

				$awards[] = $award_data;
			}
		}

		$status = $this->Dashboard_model->save_complete_profile(
			$user_id,
			$profile_id,
			$profile_data,
			$education,
			$skills,
			$skill_ids,
			$certs,
			$cert_ids,
			$awards
		);

		if ($status) {
			$this->session->set_flashdata('success', 'Profile updated successfully.');
		} else {
			$this->session->set_flashdata('error', 'Something went wrong while updating. Please try again.');
		}

		redirect('dashboard/personal_details_form');
	}

	/**
	 * AJAX: Delete education
	 */
	public function delete_education()
	{
		try {
			$user_id = $this->session->userdata('oliveMobileSystemUser');

			if (!$user_id) {
				echo json_encode(['success' => false, 'message' => 'User not logged in']);
				return;
			}

			$id = $this->input->post('id');
			if (!$id) {
				echo json_encode(['success' => false, 'message' => 'ID is required']);
				return;
			}

			// Pass user_id to model for ownership verification
			$result = $this->Dashboard_model->delete_education($id, $user_id);

			if ($result) {
				echo json_encode(['success' => true, 'message' => 'Education deleted successfully']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to delete or record not found']);
			}

		} catch (Exception $e) {
			log_message('error', 'Delete education error: ' . $e->getMessage());
			echo json_encode(['success' => false, 'message' => 'An error occurred']);
		}
	}

	/**
	 * AJAX: Delete tag (skill/certification)
	 */
	public function delete_tag()
	{
		try {
			$user_id = $this->session->userdata('oliveMobileSystemUser');

			if (!$user_id) {
				echo json_encode(['success' => false, 'message' => 'User not logged in']);
				return;
			}

			$id = $this->input->post('id');
			if (!$id) {
				echo json_encode(['success' => false, 'message' => 'ID is required']);
				return;
			}

			// Pass user_id to model for ownership verification
			$result = $this->Dashboard_model->delete_tag($id, $user_id);

			if ($result) {
				echo json_encode(['success' => true, 'message' => 'Tag deleted successfully']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to delete or record not found']);
			}

		} catch (Exception $e) {
			log_message('error', 'Delete tag error: ' . $e->getMessage());
			echo json_encode(['success' => false, 'message' => 'An error occurred']);
		}
	}

	/**
	 * AJAX: Delete award
	 */
	public function delete_award()
	{
		try {
			$user_id = $this->session->userdata('oliveMobileSystemUser');

			if (!$user_id) {
				echo json_encode(['success' => false, 'message' => 'User not logged in']);
				return;
			}

			$id = $this->input->post('id');
			if (!$id) {
				echo json_encode(['success' => false, 'message' => 'ID is required']);
				return;
			}

			// Pass user_id to model for ownership verification
			$result = $this->Dashboard_model->delete_award($id, $user_id);

			if ($result) {
				echo json_encode(['success' => true, 'message' => 'Award deleted successfully']);
			} else {
				echo json_encode(['success' => false, 'message' => 'Failed to delete or record not found']);
			}

		} catch (Exception $e) {
			log_message('error', 'Delete award error: ' . $e->getMessage());
			echo json_encode(['success' => false, 'message' => 'An error occurred']);
		}
	}

	//  Code by Hassan ends here 
}