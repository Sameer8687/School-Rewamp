<?php
class Student extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Profile_model");
        $this->load->model("Student_model");
        $this->load->model("");
        $this->load->model("");
        $this->load->model("");
        $this->load->model("");
    }

    public function aadhar_info()
    {
        $sid = $this->input->get("sid");
        $data['stud'] = $sid;
        $token = $this->input->get("token");

        if ($sid != '' && $token != '') {
            $data['acdm'] = $this->Student_model->getSelectedAcademicYear();
            $data['spriden_details'] = $this->Student_model->spriden_data($sid, $token);
            $data['stvclas_data'] = $this->Student_model->get_stvclas_data($sid, $data['acdm']);

            $data['all_subjects'] = [];
            if (!empty($data['stvclas_data'])) {
                $data['all_subjects'] = $this->Profile_model->get_allsubjects(
                    $data['stvclas_data']['STVCLAS_standard'],
                    $data['acdm']
                );
            }

            $data['academic_year_detail'] = $data['acdm'];
            $data['academic_years'] = $this->Profile_model->getAcademicYears();
            $data['aadhar_information'] = $this->Student_model->check_student_exists($this->input->get("sid"), 'data');

            $this->load->view('Student/info/aadhar-info', $data);
        } else {
            $this->load->view('Student/info/info-login', $data);
        }
    }

    public function save_aadhar_details()
    {
        // Get POST data from AJAX request
        $student_id = $this->input->post('student_id');
        $student_name = $this->input->post('stud_name');
        $student_dob = $this->input->post('stud_dob');
        $student_aadhar_number = $this->input->post('stud_aadahr');
        $father_name = $this->input->post('father_name');
        $father_dob = $this->input->post('father_dob');
        $father_aadhar_number = $this->input->post('father_aadahr');
        $father_mobile = $this->input->post('father_mobile');
        $mother_name = $this->input->post('mother_name');
        $mother_dob = $this->input->post('mother_dob');
        $mother_aadhar_number = $this->input->post('mother_aadahr');

        // Common data for insert/update
        $data = array(
            'student_name' => $student_name,
            'student_dob' => $student_dob,
            'student_aadhar_number' => $student_aadhar_number,
            'father_name' => $father_name,
            'father_dob' => $father_dob,
            'father_aadhar_number' => $father_aadhar_number,
            'fat_mob_no' => $father_mobile,
            'mother_name' => $mother_name,
            'mother_dob' => $mother_dob,
            'mother_aadhar_number' => $mother_aadhar_number,
        );

        $status = $this->Student_model->check_student_exists($student_id);

        if ($status) {
            // Update the data (student_id will NOT be updated)
            $update = $this->Student_model->update_student_details($student_id, $data);
            if ($update) {
                echo json_encode(['status' => 'success', 'message' => 'Student details updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update student details.']);
            }
        } else {
            // Insert new data (include student_id in $data)
            $data['student_id'] = $student_id; // Add student_id only for insert
            $insert = $this->Student_model->insert_student_details($data);
            if ($insert) {
                echo json_encode(['status' => 'success', 'message' => 'Student details inserted successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert student details.']);
            }
        }
    }

    function validate($login_type)
    {
        $mainUrlPath = base_url("public/student/aadhar_info");
        $student_id = $this->input->post('student_id');

        if (!$student_id) {
            $this->session->set_flashdata('Message', 'Invalid Student ID');
            return redirect($mainUrlPath);
        }
        // Get Student information
        $student = $this->db->where('SPRIDEN_student_id', $student_id)->get('spriden')->row();

        if (empty($student)) {
            $this->session->set_flashdata('Message', 'Invalid Student Credentials, Student Details not found.');
            return redirect($mainUrlPath);
        }
        if (!isset($student->id) || !isset($student->spriden_auth_token)) {
            $this->session->set_flashdata('Message', 'Invalid Student Credentials, Student Details not found.');
            return redirect($mainUrlPath);
        }
        // Make url to redirect
        $id = $student->id;
        $token = $student->spriden_auth_token;
        $urlToRedirect = $mainUrlPath . "?sid=$id&source=link&token=$token";
        return redirect($urlToRedirect);
    }

    function logout()
    {
        $this->session->unset_userdata('StudentProfileSession');
        $this->session->unset_userdata('application_acdm');

        redirect(base_url() . 'public/student/aadhar_info');
    }
}