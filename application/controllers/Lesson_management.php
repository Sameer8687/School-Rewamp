<?php
class Lesson_management extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_lesson_model');
    }

    public function index()
    {
        $data['academic_years'] = $this->M_lesson_model->get_academic_years();
        $data['current_year'] = $this->M_lesson_model->get_current_academic_year();
        $this->load->view('lesson_view', $data);  // ✅
    }

    public function get_subjects()
    {
        $class = $this->input->post('class');
        $academic_year = $this->input->post('academic_year');
        $this->load->model('M_lesson_model');
        $subjects = $this->M_lesson_model->get_subjects($class, $academic_year);
        echo json_encode($subjects);
    }

    public function save_lessons()
    {
        $data = [
            'academic_year' => $this->input->post('academic_year'),
            'class' => $this->input->post('class'),
            'subject' => $this->input->post('subject'),
            'lesson_no' => $this->input->post('lesson_no'),
            'lesson_name' => $this->input->post('lesson_name'),
            'type' => $this->input->post('type')
        ];
        $this->load->model('M_lesson_model');
        $this->M_lesson_model->save_lessons($data);
        echo 'success';
    }

    public function fetch_lessons()
    {
        $academic_year = $this->input->post('academic_year');
        $class = $this->input->post('class');
        $subject = $this->input->post('subject');
        $lessons = $this->M_lesson_model->fetch_lessons($academic_year, $class, $subject);
        echo json_encode($lessons);
    }

    public function update_lesson()
    {
        $id = $this->input->post('id');
        $data = [
            'class' => $this->input->post('class'),
            'subject' => $this->input->post('subject'),
            'lesson_no' => $this->input->post('lesson_no'),
            'lesson_name' => $this->input->post('lesson_name'),
            'type' => $this->input->post('type'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $updated = $this->M_lesson_model->update_lesson($id, $data);
        echo $updated ? 'success' : 'failed';
    }

    public function get_lesson_by_id()
    {
        $id = $this->input->post('id');
        $lesson = $this->M_lesson_model->get_lesson_by_id($id);
        echo json_encode($lesson);
    }

    public function fetch_lessonss()
    {
        $academic_year = $this->input->post('academic_year');
        $lessons = $this->M_lesson_model->fetch_lessons($academic_year);
        foreach ($lessons as $i => &$lesson) {
            if (isset($lesson['subject_name'])) {
                $lesson['subject'] = $lesson['subject_name'];
            }
        }
        echo json_encode($lessons);
    }

    public function delete_lesson()
    {
        $lesson_id = $this->input->post('lesson_id');
        $this->load->model('M_lesson_model');
        $deleted = $this->M_lesson_model->deleteLesson($lesson_id);

        if ($deleted) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Could not delete lesson']);
        }
    }



}