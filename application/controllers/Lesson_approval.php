<?php
class Lesson_approval extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Approval_model');
        $this->load->model('Dashboard_model');
    }

    public function get_dashboard_user()
	{
		$userID = $this->session->userdata('oliveMobileSystemUser');
		$userdata = $this->Dashboard_model->get_dashboard_user($userID);
		return $userdata;
	}

    public function index()
    {
        $data['userData'] = $this->get_dashboard_user();
        $data['academic_years'] = $this->Approval_model->get_academic_years();
        $data['current_year'] = $this->Approval_model->get_current_academic_year();
        $this->load->view('lesson_aproval_view', $data);
    }

    public function get_subjects()
    {
        $class = $this->input->get('class');
        $academic_year = $this->input->get('academic_year');
        $subjects = $this->Approval_model->get_subjects_by_class_year($academic_year, $class);
        echo json_encode($subjects);
    }

    public function get_lessons_by_class_subject()
    {
        $class = $this->input->get('class');
        $subject = $this->input->get('subject');
        $academic_year = $this->input->get('academic_year');
        $lessons = $this->Approval_model->get_lessons_by_class_subject($class, $subject, $academic_year);
        echo json_encode($lessons);
    }
    public function fetch_lessons()
    {
        $academic_year = $this->input->post('academic_year');
        $class = $this->input->post('class');
        $subject = $this->input->post('subject');

        $lessons = $this->Approval_model->get_lessons_by_filters($academic_year, $class, $subject);
        echo json_encode($lessons);
    }

    public function fetch_lesson_data()
    {
        $class = $this->input->post('class');
        $division = $this->input->post('division');
        $subject = $this->input->post('subject');
        $lesson = $this->input->post('lesson');

        $data = $this->Approval_model->get_filtered_lesson_data($class, $division, $subject, $lesson);
        echo json_encode($data);
    }

    public function update_status()
    {
        $lesson_id = $this->input->post('lesson_id');
        $column_name = $this->input->post('column_name');
        $column_value = $this->input->post('column_value');
        // Optional: validate column name
        $allowed_columns = ['lesson_status', 'quiz_status', 'question_status', 'worksheet_status', 'pre_request_status', 'learning_outcome_status'];
        if (!in_array($column_name, $allowed_columns)) {
            echo json_encode(['success' => false, 'message' => 'Invalid column']);
            return;
        }
        $this->load->model('Lesson_model');
        $result = $this->Approval_model->update_status_column($lesson_id, $column_name, $column_value);

        echo json_encode(['success' => $result]);
    }
}