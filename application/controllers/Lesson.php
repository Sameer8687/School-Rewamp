<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lesson extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Student_model');
		$this->load->model('MasterForm_model');
		$this->load->model('Dashboard_model');
		$this->load->model('Teacher_model');
		$this->load->model('Lesson_model');
		$this->load->model('Stats_model');
		date_default_timezone_set('Asia/Kolkata');
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

	function index()
	{
		$id = $this->uri->segment(3);
		if ($this->uri->segment(3)) {
			$data['userData'] = $this->get_dashboard_user();
			if ($this->session->userdata('oliveMobileSystemUser') == 'E20210031') {
				$classes = $this->Stats_model->getAllClasses();
				$data['classes'] = $classes;
				$this->load->view('lessons/lesson-review', $data);
			} else {
				$data['allocated_class'] = $this->Lesson_model->get_allocated_classs($id);
				$data['classes'] = $this->Lesson_model->getAllclass($id);
				$this->load->view('lessons/lessons', $data);
			}
		}
	}

	public function getAllSubjectsByStd()
	{
		$academic_year = $this->Lesson_model->getCurrentAcademicYear();
		$std = $this->input->get('std');

		if (!empty($std) && !empty($academic_year)) {
			$subjects = $this->Lesson_model->getAllSubjectsByStd($std, $academic_year);
			echo json_encode($subjects);
		} else {
			echo json_encode([]);
		}
	}


	/// like an api
	function all_lessons()
	{
		echo json_encode($this->Lesson_model->all_lessons($this->input->get('class'), $this->input->get('sub'), $this->input->get('div')));
	}

	function lesson_data()
	{
		echo json_encode($this->Lesson_model->lesson_data($this->input->get('id')));
	}

	public function getChapters()
	{
		$standard = $this->input->get('class');
		$subject = $this->input->get('sub');

		$this->load->model('Lesson_model');
		$chapters = $this->Lesson_model->fetchChapters($standard, $subject);

		echo json_encode(['chapters' => $chapters]);
	}

	public function getLesson()
	{
		$standard = $this->input->get('class');
		$subject = $this->input->get('sub');

		$this->load->model('Lesson_model');
		$lessons = $this->Lesson_model->fetchLessons($standard, $subject);

		echo json_encode(['lessons' => $lessons]);
	}

	// function to change status
	function change_status()
	{
		$std = $this->input->post('std');
		$sub = $this->input->post('sub');
		$status = $this->Lesson_model->change_status();
		if ($status) {
			echo json_encode($this->Lesson_model->all_lessons($std, $sub));
		} else {
			echo json_encode("fail");
		}
	}

	function add_lesson_comment_data()
	{
		$std = $this->input->post('std');
		$sub = $this->input->post('sub');
		$id = $this->input->post('id');
		$status = $this->Lesson_model->add_lesson_comment_data();
		if ($status) {
			echo json_encode($this->Lesson_model->all_lessons($std, $sub));
		} else {
			echo json_encode("fail");
		}
	}

	// add review
	function add_review()
	{
		$std = $this->input->post('class');
		$sub = $this->input->post('subject');
		$status = $this->Lesson_model->add_review();
		echo json_encode($this->Lesson_model->all_lessons($std, $sub));
	}

	// add lesson data
	// function add_lesson()
	// {
	// 	$std = $this->input->post('class');
	// 	$sub = $this->input->post('subject');
	// 	$div = $this->input->post('division');
	// 	$status = $this->Lesson_model->add_data();
	// 	if ($status) {
	// 		echo json_encode($this->Lesson_model->all_lessons($std, $sub, $div));
	// 	} else {
	// 		echo json_encode("fail");
	// 	}
	// }
	// add lesson data
	function add_lesson()
	{
		$std = $this->input->post('class');
		$sub = $this->input->post('subject');
		$div = $this->input->post('division');

		// Debugging
		log_message('debug', 'Add Lesson - Class: ' . $std . ', Subject: ' . $sub . ', Division: ' . $div);

		$status = $this->Lesson_model->add_data();
		if ($status) {
			$lessons = $this->Lesson_model->all_lessons($std, $sub, $div);
			log_message('debug', 'Lessons returned: ' . count($lessons));
			echo json_encode($lessons);
		} else {
			echo json_encode("fail");
		}
	}
}