<?php
class Hall_ticket extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Student_model');
		$this->load->model('MasterForm_model');
		$this->load->model('Teacher_model');
		$this->load->model('Stats_model');
		$this->load->model('Login_model');
		$this->load->model('Hall_ticket_model');
		$this->load->model('Timetable_model');
		date_default_timezone_set('Asia/Kolkata');
		if (!$this->session->userdata('oliveSchoolDashboardUser'))
			redirect(base_url() . 'login');
	}

	function index()
	{
		if ($this->session->userdata('oliveSchoolDashboardUser')) {
			$data['classes'] = $this->Stats_model->getAllClasses();
			$data['exams'] = $this->Hall_ticket_model->getAllExams();
			$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
			//Get Books & Result Data with Joins
			$lastLoginData = $this->Stats_model->last_login();
			$data['lastLoginData'] = $lastLoginData;
			$data['user'] = $userdetail;
			$id = $this->input->get('__id');
			$data['header'] = [];
			$data['lines'] = [];
			$data['subjects'] = [];
			if ($id != '') {
				$data['header'] = $this->Hall_ticket_model->getheader($id);
				$data['lines'] = $this->Hall_ticket_model->getline($id);
				$selected_class = isset($data['header']['stvhtkt_class']) ? $data['header']['stvhtkt_class'] : '-';
				$data['subjects'] = $this->Hall_ticket_model->fetchSubjectsList($selected_class);
			}
			$data['filter_view'] = "hall_ticket";
			$this->load->view('hall_ticket/hall_ticket', $data);
		}
	}

	function fetchTimeTable()
	{
		$subjects = $this->Hall_ticket_model->fetchSubjectsList($this->input->get('std'));
	}

	function update()
	{
		if ($this->session->userdata('oliveSchoolDashboardUser')) {
			$id = $this->input->get('id');
			$data['academic_details'] = $this->Stats_model->getAcademicDetails();
			$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
			$data['classes'] = $this->Stats_model->getAllClasses();
			$data['exams'] = $this->Hall_ticket_model->getAllExams();
			$userdetail = $this->Stats_model->getUserDetails($this->session->userdata('oliveSchoolDashboardUser'));
			//Get Books & Result Data with Joins
			$lastLoginData = $this->Stats_model->last_login();
			$data['lastLoginData'] = $lastLoginData;
			$studentsbooksDetails = $this->Student_model->get_books_and_result_data_all();
			$data['user'] = $userdetail;
			$data['students_books'] = $studentsbooksDetails;
			$data['headers'] = $this->Hall_ticket_model->getheader($id);
			$data['lines'] = $this->Hall_ticket_model->getline($id);
			$selected_class = isset($data['headers']['stvhtkt_class']) ? $data['headers']['stvhtkt_class'] : '-';
			$selected_acdm = isset($data['headers']['stvhtkt_acdm']) ? $data['headers']['stvhtkt_acdm'] : '-';
			$data['subjects'] = $this->Hall_ticket_model->getSubject($selected_class, $selected_acdm);
			// beautify_array($data['subjects'], true);
			$data['all_records'] = $this->Hall_ticket_model->fetchAllRecords();
			$data['filter_view'] = "hall_ticket";
			$this->load->view('hall_ticket/hall_ticket_update', $data);
		}
	}

	function fetchSubjectsList()
	{
		echo json_encode($this->Hall_ticket_model->fetchSubjectsList($this->input->get('std')));
	}

	function getLOVS()
	{
		$type = $this->input->post('type');
		$data = [];
		if ($type == 'subjects')
			$data = $this->Hall_ticket_model->getSSubject($this->input->post('std_name'));
		if ($type == 'exams')
			$data = $this->Hall_ticket_model->getAllExams();
		echo json_encode($data);
	}

	public function add_data()
	{
		$this->Hall_ticket_model->add_data(); // Update by Ansari Kamran 11-08-2024 
		exit(0);
	}

	function report()
	{
		$data['all_records'] = array();
		$data['std'] = "";
		$data['div'] = "";
		$data['test'] = "";
		$data['rte_status'] = "";
		$data['fees'] = "";
		$data['crn_date'] = "";
		if ($this->input->get('action') == 'search') {
			$std = $this->input->get('std');
			$div = $this->input->get('div');
			$exam = $this->input->get('exam');
			$rte_status = $this->input->get('rte');
			$fees = $this->input->get('paid_fees');
			$date = $this->input->get('date');
			$data['std'] = $std;
			$data['div'] = $div;
			$data['test'] = $exam;
			$data['fees'] = $fees;
			$data['rte_status'] = $rte_status;
			$data['crn_date'] = $date;
			$data['all_records'] = $this->Hall_ticket_model->getData($std, $div, $exam, $fees, $rte_status, null, $date, $this->input->get('mode'));
		}

		$data['academic_details'] = $this->Stats_model->getAcademicDetails();
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['exams'] = $this->Hall_ticket_model->getAllExams();
		$data['filter_view'] = "hall_ticket_report";
		$this->load->view('hall_ticket/hall_ticket_report', $data);
	}

	public function fetchreport()
	{
		// Dropdown data (static page load)
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();

		$current_academic_year = $this->Stats_model->getCurrentAcademicYear();
		$data['selected_academic_year'] = !empty($current_academic_year)
			? $current_academic_year['stvacdm_id']
			: '';
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['tests'] = $this->Hall_ticket_model->getAllExams();

		$this->load->view('hall_ticket/hall_ticket_fetch-report', $data);
	}

	// 🔹 AJAX: fetch records dynamically
	public function getHallTicketRecords()
	{
		$acdm = $this->input->post('acdm');
		$std = $this->input->post('std');
		$div = $this->input->post('div');
		$exam = $this->input->post('exam');

		$records = $this->Hall_ticket_model->fetchDataForHallTicketReport($acdm, $std, $div, $exam);
		echo json_encode($records);
	}

	function delete_hall_ticket($id = 0)
	{
		$this->db->where('stvhtkt_id', $id)->delete('stvhtkt_header');
		$this->db->where('stvhtkt_form_id', $id)->delete('stvhtkt_line');
		redirect(base_url("hall_ticket/fetchreport"));
	}

	function generate()
	{
		$data['all_records'] = array();
		$data['timetable'] = [];
		$data['std'] = "";
		$data['test'] = "";
		$data['div'] = "";
		$data['rte_status'] = "";
		$data['fees'] = "";
		$data['student_id'] = "";
		$data['search_mode'] = $this->input->get('search-mode');
		if ($this->input->get('action') == 'search') {
			$div = $this->input->get('div');
			$fees = $this->input->get('paid_fees');
			$std = $this->input->get('std');
			$exam = $this->input->get('exam');
			$rte_status = $this->input->get('rte');
			$student_id = $this->input->get('student-id');
			$student_list = base64_decode($student_id);
			$data['std'] = $std;
			$data['test'] = $exam;
			$data['div'] = $div;
			$data['fees'] = $fees;
			$data['rte_status'] = $rte_status;
			$data['student_id'] = $student_id;
			$data['timetable'] = $this->Hall_ticket_model->fetchTimeTableOfExam($std, $exam);
			if ($data['search_mode'] == 'classwise')
				$data['all_records'] = $this->Hall_ticket_model->getData($std, $div, $exam, $fees, $rte_status, $student_id);
			else
				$data['all_records'] = $this->Hall_ticket_model->getDataOfHallTicketByID($exam, $student_list);
		}
		$data['academic_details'] = $this->Stats_model->getAcademicDetails();
		$data['academic_years'] = $this->MasterForm_model->get_all_academic_years();
		$data['classes'] = $this->Stats_model->getAllClasses();
		$data['exams'] = $this->Hall_ticket_model->getAllExams();
		$data['filter_view'] = "hall_ticket_generate";
		$this->load->view('hall_ticket/hall_ticket_generate', $data);
	}

	function delete()
	{
		$header_id = $this->input->get('header-id');
		$this->Hall_ticket_model->deleteHeader($header_id);
		$this->Hall_ticket_model->deleteLines($header_id);
		redirect('Hall_ticket/add_data');
	}

	// ======================== Update by Ansari Kamran 11-08-2024 =====================================
	function fetchRow()
	{
		$class = $this->input->post('class');
		$exam = $this->input->post('exam');
		$div = $this->input->post('div');
		$acdm = $this->input->post('acdm');
		echo json_encode($this->Hall_ticket_model->fetchRow_lines($class, $exam, $div, $acdm));
	}
}