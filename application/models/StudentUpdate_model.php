<?php

class StudentUpdate_model extends CI_Model

{

    function get_allsubjects($std)

    {

        // $this->db->where('stvsubj_std', $std);

        // return $this->db->get('stvsubj')->result_array();

        $sql = "SELECT * FROM stvsubj WHERE stvsubj_std = '$std' and stvsubj_subject IN ('English','Science', 'Maths','English Grammer','Algebra','ScienceI','ScienceII','Geometry','Hindi','Marathi','History','Geography','Grammer','Islamic Studies','Computer','EVS 1','EVS 2','Learning')";

        $data = $this->db->query($sql);

        return $data->result_array();
    }

    function student_list_nursery_to_ssc($acdm_year = '', $status = 'A')

    {
        $sql = "SELECT
                sp.id,
                sp.SPRIDEN_student_id,
                sp.spriden_gr_number,
                sp.SPRIDEN_reg_number,
                sp.spriden_student_status,
                CONCAT(sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name, ' ', sp.SPRIDEN_surname) AS name,
                cl.STVCLAS_standard,
                cl.STVCLAS_class,
                (select distinct lc_status from lc_status where student_id= sp.id)lc_status
            FROM
                spriden sp
            INNER JOIN
                stvclas cl ON sp.id = cl.STVCLAS_student_id
            WHERE
                sp.SPRIDEN_user_type = 'st'
                AND cl.STVCLAS_acdm = $acdm_year
                and STVCLAS_standard not in ('XI','XII')";
        if ($status != 'ALL') {
            if ($status != 'IS_NULL')
                $sql .= " and sp.spriden_student_status = '$status'";
            else
                $sql .= " and sp.spriden_student_status IS NULL";
        }
        $sql .= " ORDER BY
                sp.spriden_gr_number ASC";
        return $this->db->query($sql)->result_array();
    }

    function student_list_jc($acdm_year = '', $status = 'A')

    {
        $sql = "SELECT
                sp.id,
                sp.SPRIDEN_student_id,
                sp.spriden_gr_number,
                sp.SPRIDEN_reg_number,
                sp.spriden_student_status,
                CONCAT(sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name, ' ', sp.SPRIDEN_surname) AS name,
                cl.STVCLAS_standard,
                cl.STVCLAS_class,
                (select distinct lc_status from lc_status where student_id= sp.id)lc_status
            FROM
                spriden sp
            INNER JOIN
                stvclas cl ON sp.id = cl.STVCLAS_student_id
            WHERE
                sp.SPRIDEN_user_type = 'st'
                AND cl.STVCLAS_acdm = $acdm_year
                and STVCLAS_standard in ('XI','XII')";
        if ($status != 'ALL') {
            if ($status != 'IS_NULL')
                $sql .= " and sp.spriden_student_status = '$status'";
            else
                $sql .= " and sp.spriden_student_status IS NULL";
        }
        $sql .= " ORDER BY
                sp.spriden_gr_number ASC";
        return $this->db->query($sql)->result_array();
    }


    function update_student_details_by_form($id)
    {
        // $this->db->where('spriden_gr_number', $this->input->post('gr_number'));
        // $checkExist = $this->db->query("SELECT spriden_gr_number,SPRIDEN_reg_number 
        // from spriden Where id = '$id'");
        $checkExist = $this->db->query("SELECT spriden_gr_number, SPRIDEN_reg_number FROM spriden WHERE id = '$id'");
        if ($checkExist != NULL) {
            $data = array(
                'spriden_gr_number' => $this->input->post('gr_number'),
                'SPRIDEN_reg_number' => $this->input->post('SPRIDEN_reg_no'),
                'SPRIDEN_pan_no' => $this->input->post('SPRIDEN_pan_no'),
                'spriden_college_gr' => $this->input->post('spriden_college_gr'),
            );
            $this->db->where('id', $id);
            $this->db->update('spriden', $data);
        }

        $cm_height = '';
        // Step 1: Trim any extra spaces
        $input_height = trim($this->input->post('height'));
        // Step 2: Remove any non-numeric characters, except the dot (.)
        $input_height = preg_replace('/[^0-9.]/', '', $input_height);
        // Step 3: Validate the format (must contain exactly one dot)
        if (substr_count($input_height, '.') == 1) {
            // Split the input into feet and inches
            list($feet, $inches) = explode('.', $input_height);
            // Step 4: Convert to integers
            $feet = (int) $feet;
            $inches = (int) $inches;
            // Step 5: Calculate total inches and convert to cm
            $total_inches = ($feet * 12) + $inches;
            $cm_height = $total_inches * 2.54;
        }
        $personal_details = array(

            'student_name_aadhar' => $this->input->post('student_name_aadhar'),
            'father_aadhar_no' => $this->input->post('father_aadhar_no'),
            'father_name_aadhar' => $this->input->post('father_name_aadhar'),
            'mother_aadhar_no' => $this->input->post('mother_aadhar_no'),
            'mother_name_aadhar' => $this->input->post('mother_name_aadhar'),
            'spriden_college_gr' => $this->input->post('spriden_college_gr'),
            'height' => $this->input->post('height'),
            'weight' => $this->input->post('weight'),
            'SPRIDEN_BGRP' => $this->input->post('SPRIDEN_BGRP'),
            'height_in_cm' => $cm_height,

            'previos_college' => $this->input->post('previos_college'),
            'spriden_gr_number' => $this->input->post('gr_number'),
            'SPRIDEN_reg_number' => $this->input->post('SPRIDEN_reg_no'),
            'SPRIDEN_pan_no' => $this->input->post('SPRIDEN_pan_no'),
            'SPRIDEN_apaar_no' => $this->input->post('SPRIDEN_apaar_no'),
            'SPRIDEN_surname' => $this->input->post('surname'),
            'SPRIDEN_student_name' => $this->input->post('student_name'),
            'SPRIDEN_father_name' => $this->input->post('father_name'),
            'SPRIDEN_mother_name' => $this->input->post('mother_name'),
            'SPRIDEN_religion' => $this->input->post('religion'),
            'SPRIDEN_gender' => $this->input->post('gender'),
            'SPRIDEN_nationality' => $this->input->post('nationality'),
            'SPRIDEN_mother_toungue' => $this->input->post('mother_tongue'),
            'SPRIDEN_birth_place' => $this->input->post('place_of_birth'),
            'SPRIDEN_aadhar_no' => $this->input->post('aadhar_number'),
            'SPRIDEN_admission_class' => $this->input->post('admission_class'),
            'SPRIDEN_birthdate' => $this->input->post('dat_of_birth'),
            'SPRIDEN_last_school' => $this->input->post('pre_school'),
            'SPRIDEN_main_address' => $this->input->post('main_address'),
            'SPRIDEN_alt_address' => $this->input->post('alternate_address'),
            'SPRIDEN_father_quali' => $this->input->post('father_qualification'),
            'SPRIDEN_mother_quali' => $this->input->post('mother_qualification'),
            'SPRIDEN_date_created' => $this->input->post('admission_date'),
            'rte_status' => $this->input->post('rte_status'),
            'cast' => $this->input->post('caste'),
            'sub_cast' => $this->input->post('sub_caste'),
            'taluka' => $this->input->post('taluka'),
            'district' => $this->input->post('district'),
            'state' => $this->input->post('state'),
            'SPRIDEN_last_updated' => date('Y-m-d'),
            'SPRIDEN_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
            'birthdate_words' => $this->input->post('birthdate_words'),
            'progress' => $this->input->post('progress'),
            'conduct' => $this->input->post('conduct'),
            'leaving_date' => $this->input->post('leaving_date'),
            'leaving_reason' => $this->input->post('leaving_reason'),
            'remark' => $this->input->post('remark'),
            'since_when' => $this->input->post('since_when'),
            'lc_date' => $this->input->post('lc_date')
        );

        $this->db->where('id', $id);
        $this->db->update('spriden', $personal_details);
        // Check Whether Data Exist Or Not
        $this->db->where('stvpcnf_student_id', $id);
        $exist = $this->db->get('stvpcnf')->row_array();

        if ($exist == null) {
            // Insert PCNF
            $contact_details = array(
                'stvpcnf_student_id' => $id,
                'stvpcnf_parent_typ' => "Father",
                'stvpcnf_mobile' => $this->input->post('mobile_number1'),
                'stvpcnf_mobile2' => $this->input->post('mobile_number2'),
                'stvpcnf_whatsapp' => $this->input->post('whatsapp_number'),
                'stvpncf_sms_no' => $this->input->post('sms_number')
            );
            $this->db->insert('stvpcnf', $contact_details);
        } else {
            //Update PCNF
            $contact_details = array(
                'stvpcnf_mobile' => $this->input->post('mobile_number1'),
                'stvpcnf_mobile2' => $this->input->post('mobile_number2'),
                'stvpcnf_whatsapp' => $this->input->post('whatsapp_number'),
                'stvpncf_sms_no' => $this->input->post('sms_number')
            );
            $this->db->where('stvpcnf_student_id', $id);
            $this->db->update('stvpcnf', $contact_details);
        }
        // update class of the student
        $class_data = array(
            'STVCLAS_standard' => $this->input->post('current_class')
        );
        $selected_acdm = $this->input->post('hidden_acdm_year');
        $this->db->where('STVCLAS_student_id', $id);
        $this->db->where('STVCLAS_acdm', $selected_acdm);
        $this->db->update('stvclas', $class_data);
        return 1;
    }


    // Updated 30 Sep 24 - Azz Shaikh
    function changeStudentStatus($id, $status, $reason = '')
    {
        $studentStatus = $status;
        $data = array(
            'spriden_student_status' => $status,
            'spriden_status_end_date' => date('Y-m-d')
        );
        $this->db->where('id', $id);
        $status = $this->db->update('spriden', $data);
        if ($status) {
            // update activity status
            $this->trackStudentStatusActivity($id, $studentStatus, $reason);
            $this->db->where('id', $id);
            return $this->db->get('spriden')->row_array();
        } else {
            return "fail";
        }
    }



    function StudentUniqueId($id)

    {

        $this->db->where('SPRIDEN_student_id', $id);

        return $this->db->get('spriden')->row_array();
    }

    function getStudentDetails($acdm_year, $status, $std, $div)
    {
        $sql = "SELECT 
        s.id, 
        s.SPRIDEN_student_id, 
        getfullname(s.id), 
        s.spriden_student_status,
        s.spriden_gr_number, 
        s.SPRIDEN_reg_number,
        s.SPRIDEN_aadhar_no,
        s.SPRIDEN_birthdate, 
        c.STVCLAS_standard, 
        c.STVCLAS_class
        FROM spriden s 
        LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = '$acdm_year'
        WHERE s.SPRIDEN_user_type = 'st'";

        if ($status != '') {
            $sql .= " AND s.spriden_student_status = '$status'";
        }
        if ($std != '') {
            $sql .= " AND c.STVCLAS_standard = '$std'";
        }
        if ($div != '') {
            $sql .= " AND c.STVCLAS_class = '$div'";
        }

        $sql .= " ORDER BY s.id";

        return $this->db->query($sql)->result_array();
    }
    // Updated 30 Sep 24 - Azz Shaikh
    function trackStudentStatusActivity($student_id, $status, $reason)
    {
        $userid = $this->session->userdata('oliveSchoolDashboardUser');
        $user = $this->db->query("select spruser_id,spruser_name from spruser where spruser_id = $userid")->row_array();
        $username = $user['spruser_name'] ?? 'Unknown';
        $activities = $this->db->where('student_id', $student_id)->get('student_status_activity')->row_array();
        if (empty($activities)) {
            // insert activities
            $activity_line = "$username Updated Student Status to '$status' at " . date('d M Y, h:i:s') . " For reason - " . $reason;
            $activity = [
                'student_id' => $student_id,
                'activities' => json_encode([$activity_line])
            ];
            $this->db->insert("student_status_activity", $activity);
        } else {
            // update activities
            $existActivities = [];
            if (isset($activities['activities']) && is_array(json_decode($activities['activities']))) {
                $existActivities = json_decode($activities['activities']);
                $activity_line = "$username Updated Student Status to '$status' at " . date('d M Y, h:i:s') . " For reason - " . $reason;
                $existActivities[] = $activity_line;
                $this->db->where('student_id', $student_id)->update("student_status_activity", ['activities' => json_encode($existActivities)]);
            }
        }
    }
}
