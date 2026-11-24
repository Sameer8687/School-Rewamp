<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ClassReport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        // Load view with empty data initially
        $data['classes'] = [];
        $this->load->view('all_reports/report_view', $data);
    }


    public function report()
    {
        // Load view with empty data initially
        $data['classes'] = [];
        if ($this->input->get('mode') == 'dev')
            $this->load->view('all_reports/report_view_2', $data);
        else
            $this->load->view('all_reports/report_view_new', $data);
    }

    public function get_students($class_id)
    {
        // Forward this request to your API
        $api_url = "http://school.oliveschool.org.in/scst-api/api/v1/classes?class_id=" . $class_id;
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            $students = json_decode($response);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($students));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to fetch students']));
        }
    }

    public function get_student_data($student_id)
    {
        // Generate dummy data since we don't have an API for this
        $data = [
            'info' => (object)[
                'id' => $student_id,
                'name' => 'Student ' . $student_id,
                'roll_number' => 'R' . $student_id,
                'class_name' => 'Class ' . substr($student_id, 0, 1),
                'father_name' => 'Father of Student ' . $student_id,
                'dob' => date('d-m-Y', strtotime('-' . rand(10, 15) . ' years')),
                'contact' => '98' . rand(10000000, 99999999),
                'photo' => 'default.jpg'
            ],
            'marks' => [],
            'attendance' => [],
            'remarks' => []
        ];

        // Generate marks
        $subjects = ['Math', 'Science', 'English', 'History', 'Geography'];
        shuffle($subjects);
        $subjectCount = rand(3, 5);

        for ($i = 0; $i < $subjectCount; $i++) {
            $max = rand(80, 100);
            $data['marks'][] = (object)[
                'subject' => $subjects[$i],
                'obtained_marks' => rand(50, $max),
                'max_marks' => $max
            ];
        }

        // Generate attendance
        for ($i = 1; $i <= 30; $i++) {
            $status = ['Present', 'Absent', 'Leave'][rand(0, 2)];
            $data['attendance'][] = (object)[
                'date' => date('d-m-Y', strtotime("-$i days")),
                'status' => $status
            ];
        }

        // Generate remarks
        $teachers = ['Mr. Smith', 'Ms. Johnson', 'Mr. Williams'];
        $comments = [
            'Good performance',
            'Needs improvement',
            'Excellent work',
            'Should participate more'
        ];

        for ($i = 0; $i < rand(2, 4); $i++) {
            $data['remarks'][] = (object)[
                'date' => date('d-m-Y', strtotime('-' . rand(1, 90) . ' days')),
                'teacher_name' => $teachers[array_rand($teachers)],
                'remark' => $comments[array_rand($comments)]
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}