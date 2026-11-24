<?php

class TCM_model extends CI_Model
{
    function customernumbersdetails()
    {

        return $this->db->query("SELECT SPRIDEN_student_id, CONCAT(SPRIDEN_surname, ' ',SPRIDEN_student_name , ' ',SPRIDEN_father_name) as name FROM spriden WHERE SPRIDEN_user_type = 'tc' and SPRIDEN_student_id is not null")->result_array();
    }

    function get_fees_data_of_student($id)
    {
        return $this->db->query("select s.id, s.SPRIDEN_student_id,s.SPRIDEN_surname, s.SPRIDEN_student_name,  s.SPRIDEN_father_name,
            c.STVCLAS_standard,
            c.STVCLAS_class,
            (select stvpcnf_mobile from stvpcnf where stvpcnf_student_id = s.id) as phone,
            (select sum(sprfees_amount) from sprfees where sprfees_studentid = s.id and academic_year = 2 ) as fees_paid
            from spriden s, stvclas c
            where s.id = c.STVCLAS_student_id
            and s.SPRIDEN_user_type = 'st'
            and s.id = $id")->row_array();
    }
    function add_data()
    {
        $data = array(
            'stvtcmr_student_id' => $this->input->post('student_id_hidden'),
            'stvtcmr_teacher_id' => $this->input->post('teahcer_name'),
            'stvtcmr_notes' => $this->input->post('notes'),
            'stvtcmr_landmark' => $this->input->post('landmark'),
            'created_at' => date('Y-m-d'),
            'created_by' => $this->session->userdata('oliveSchoolDashboardUser')
        );
        if ($this->db->insert('stvtcmr', $data)) {
            return true;
        } else {
            return false;
        }
    }

    function getAllRecords(){
        return $this->db->query("select stvtcmr.*, s.id, s.SPRIDEN_student_id,concat(s.SPRIDEN_surname, ' ' ,s.SPRIDEN_student_name, ' ' , s.SPRIDEN_father_name) as name,
        (select concat(SPRIDEN_surname, ' ' ,SPRIDEN_student_name, ' ' , SPRIDEN_father_name) from spriden where SPRIDEN_student_id = stvtcmr.stvtcmr_teacher_id) teacher_name,
                    c.STVCLAS_standard,
                    c.STVCLAS_class,
                    (select stvpcnf_mobile from stvpcnf where stvpcnf_student_id = s.id) as phone,
                    (select sum(sprfees_amount) from sprfees where sprfees_studentid = s.id and academic_year = 2 ) as fees_paid
                    
                    from spriden s, stvclas c, stvtcmr 
                    where s.id = c.STVCLAS_student_id
                    and s.SPRIDEN_user_type = 'st'
                    and stvtcmr.stvtcmr_student_id = s.id")->result_array();
    }
    function search_from_spofees($student_name)
	{
		return $this->db->query("select * from spofees where concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) like '%$student_name%' and spIdInc not in (select stvtcmr_student_id from stvtcmr)")->result_array();
	}
}
