<?php

class RandomClass_model extends CI_model
{
    function student_class_wise($class)
    {
        return $this->db->query("select spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name, spriden.id, stvclas.* from spriden,stvclas where spriden.id = stvclas.STVCLAS_student_id and stvclas.STVCLAS_standard = '" . $class . "'")->result_array();
    }

    function student_class_wise1($class)
    {
        $data['stu_details'] = $this->db->query("select spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name, spriden.id, stvclas.* from spriden,stvclas where spriden.id = stvclas.STVCLAS_student_id and stvclas.STVCLAS_standard = '" . $class . "'")->result_array();

        $data['rndm_details'] = $this->db->query("select * from stvrndm where stvrndm_class = '" . $class . "'")->result_array();
        return $data;
    }

    function add_data()
    {
        $class = $this->input->post('class');
        $teacher = $this->input->post('teacher_name');
        $total_rows = $this->input->post('total_rows');
        $status = false;

        for ($i = 1; $i <= $total_rows; $i++) {
            // Check Data Exist or not
            $rndm_id_hidden = $this->input->post('random_id_hidden' . $i);
            $rndm_id_hidden = $rndm_id_hidden != '' ? $rndm_id_hidden : 0;
            $exist = $this->db->query("select * from stvrndm where stvrndm_id = " . $rndm_id_hidden . "")->row_array();
            $status_of = $this->input->post('status' . $i);
            if ($status_of == "Yes") {
                $flag = 1;
            } else {
                $flag = 0;
            }
            $techar_id_rndm = $this->input->post('random_teacher_id' . $i);
            if ($exist == null) {
                if ($status_of == "Yes") {
                    $data = array(
                        'stvrndm_class' => $class,
                        'stvrndm_student_id' => $this->input->post('student_id_hidden' . $i),
                        'stvrndm_teacher_id' => $techar_id_rndm,
                        'stvrndm_status' => '1'
                    );
                    $status = $this->db->insert('stvrndm', $data);
                }
            } else {
                $status = $this->db->query("update stvrndm set stvrndm_status = '" . $flag . "', stvrndm_teacher_id = '" . $techar_id_rndm . "' where stvrndm_id = " . $rndm_id_hidden . "");
            }
        }
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
}
