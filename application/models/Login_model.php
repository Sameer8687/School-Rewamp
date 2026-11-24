<?php

class Login_model extends CI_model
{

    function validateUser()
    {

        $data = array(

            'spruser_username' => $this->input->post('username'),

            'spruser_password' => $this->input->post('password')

        );

        return $user = $this->db->get_where('spruser', $data)->row_array();

    }

    //Login History

    function addLoginHistory($data)
    {

        $this->db->insert('stvlgin', $data);

    }

    //Logout History

    function addLogoutHistory($data)
    {

        $this->db->insert('stvlgin', $data);

    }





    //Student Section

    function validateStudent($id)
    {

        $data = array(

            'SPRIDEN_student_id' => $id,

            'SPRIDEN_user_type' => "st"

        );

        return $user = $this->db->get_where('spriden', $data)->row_array();

    }





    //Teacher Section

    function validateTeacher()
    {

        $data = array(

            'SPRIDEN_student_id' => $this->input->post('username'),
            'SPRIDEN_tc_pass' => $this->input->post('password'),
            'SPRIDEN_user_type' => "tc"

        );

        return $user = $this->db->get_where('spriden', $data)->row_array();

    }

    // --------- 28-Oct-2026----
    // Get assigned classes for a teacher (for login page dropdown)
    function getAssignedClasses($teacher_id)
    {
        // get current running academic year
        $acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

        $this->db->select('SPATESD_id, SPATESD_std, SPATESD_div, SPATESD_academic_year');
        $this->db->from('spatesd');
        $this->db->where('SPATESD_teacher_id', $teacher_id);

        if (!empty($acdm['stvschool_acdm_year'])) {
            $this->db->where('SPATESD_academic_year', $acdm['stvschool_acdm_year']);
        }

        $this->db->order_by('SPATESD_std', 'ASC');
        $this->db->order_by('SPATESD_div', 'ASC');
        return $this->db->get()->result_array();
    }

    // ----------------------------- start -------------------------------------------
    function manageLoginHistory($id)
    {
        date_default_timezone_set('Asia/Kolkata');
        $today = date('Y-m-d');
        $exist = $this->db->query("SELECT * FROM app_tracking where user_id = '$id' and login_date = '$today'")->row_array();
        if ($exist == null) {
            // insert code here
            $data['user_id'] = $id;
            $data['user_type'] = 'tc';
            $data['login_date'] = $today;
            $data['login_time'] = date('H:i:s');
            $data['last_login'] = date('H:i:s');
            $data['login_count'] = 1;
            $this->db->insert('app_tracking', $data);
        } else {
            // update code here
            $__id = $exist['Id'];
            $data['last_login'] = date('H:i:s');
            $data['login_count'] = (int) $exist['login_count'] + 1;
            $this->db->where('Id', $__id);
            $this->db->update('app_tracking', $data);
        }
    }

    function studentLoginHistory($id)
    {
        date_default_timezone_set('Asia/Kolkata');
        $today = date('Y-m-d');
        $exist = $this->db->query("SELECT * FROM app_tracking where user_id = '$id' and login_date = '$today'")->row_array();
        if ($exist == null) {
            // insert code here
            $data['user_id'] = $id;
            $data['user_type'] = 'st';
            $data['login_date'] = $today;
            $data['login_time'] = date('H:i:s');
            $data['last_login'] = date('H:i:s');
            $data['login_count'] = 1;
            $this->db->insert('app_tracking', $data);
        } else {
            // update code here
            $__id = $exist['Id'];
            $data['last_login'] = date('H:i:s');
            $data['login_count'] = (int) $exist['login_count'] + 1;
            $this->db->where('Id', $__id);
            $this->db->update('app_tracking', $data);
        }
    }
    // ----------------------------- end -------------------------------------------

}

