<?php


class Online_Application extends CI_Controller
{

    function list($id = '')
    {
        if ($id) {
            $data['user_details'] = $this->online_application_model->user_data($id);
            $this->load->view('online_application/teacher_details', $data);
        } else {
            $data['applications'] = $this->online_application_model->list();
            $this->load->view('online_application/teacher', $data);
        }
    }

    function delete_application()
    {
        $id = $this->input->get('id');
        echo json_encode($this->online_application_model->delete_application($id));
    }

    function update_status()
    {
        echo json_encode($this->online_application_model->update_status());
    }

    function junior_clg($id = '')
    {
        if ($id) {
            $data['user_details'] = $this->online_application_model->user_data($id);
            $this->load->view('online_application/teacher_details', $data);
        } else {
            $data['applications'] = $this->online_application_model->jr_clg();
            $this->load->view('online_application/jr_clg', $data);
        }
    }

    function delete_jr_clg($id = null)
    {
        if ($id != null) {
            $this->db->where('Id', $id)->delete('clg_stu_det');
            return redirect(base_url("Online_Application/junior_clg"));
        }
    }
    // function update_college($id)
    // {
    //     $this->load->model('College_Form');
    //     $data['details'] = $this->College_Form->getData($id);

    //     $this->load->view('Update_clg', $data);
    // }
    public function update_college($id)
    {
        $this->load->model('College_Form');
        $data['details'] = $this->College_Form->getData($id);

//    ========================================changed code====================================
        // if (isset($data['details']['aadhar_card'])) {
        //     $data['details']['aadhar_card'] = json_decode($data['details']['aadhar_card'], true);
        // }

        $this->load->view('Update_clg', $data);
    }
    public function edit($id)
    {
        $data['student'] = $this->College_Form->get_student_clg($id);
        $this->load->view('Update_clg', $data);
    }

    // Method to update student details
    public function update()
    {

        $id = $this->input->post('id');

        die($id);

        $data = array(
            'db_branch' => $this->input->post('db_branch'),
            'db_stream' => $this->input->post('db_stream'),
            'db_admissiontype' => $this->input->post('db_admissiontype'),
            'db_class' => $this->input->post('db_class'),
            'db_division' => $this->input->post('db_division'),
            'db_medium' => $this->input->post('db_medium'),
            'firstname' => $this->input->post('firstname'),
            'middlename' => $this->input->post('middlename'),
            'lastname' => $this->input->post('lastname'),
            'date' => $this->input->post('date'),
            'db_nationality' => $this->input->post('db_nationality'),
            'db_place' => $this->input->post('db_place'),
            'db_gender' => $this->input->post('db_gender'),
            'db_mothertounge' => $this->input->post('db_mothertounge'),
            'db_religion' => $this->input->post('db_religion'),
            'db_caste' => $this->input->post('db_caste'),
            'db_adharnumber' => $this->input->post('db_adharnumber'),
            'choice_subj_hin_mar' => $this->input->post('choice_subj_hin_mar'),
            'choice_math_geog_bio' => $this->input->post('choice_math_geog_bio'),
            'commerce_optional_sub' => $this->input->post('commerce_optional_sub'),
            'commerce_sub_h_m' => $this->input->post('commerce_sub_h_m')
        );

        if ($this->College_Form->update_student_clg($id, $data)) {
            $response = array('status' => 'success', 'message' => 'Data updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to update data');

        }


        echo json_encode($response);
    }
    // ===============================changes 20june===========================================

    // function views(){
    //     $this->load->view('view_clg');
    // }
    // ========================================================================================
}
