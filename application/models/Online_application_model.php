<?php


class Online_application_model extends CI_Model
{

    function list()
    {
        return $sql = $this->db->query("SELECT * FROM ext_application_header")->result_array();
    }

    function delete_application($id)
    {

        $sql = $this->db->where('id', $id);
        $this->db->delete('ext_application_header');

        if ($sql) {
            $lines =   $this->db->where('header_id', $id);
            $this->db->delete('ext_application_lines');

            if ($lines) {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
        } else {
            $data['status'] = 0;
        }

        return  $data;
    }

    function user_data($id)
    {
        $data['header'] =  $this->db->query("Select * from ext_application_header where id = $id")->row_array();

        $data['lines'] =  $this->db->query("Select * from ext_application_lines where header_id = $id")->result_array();


        return $data;
    }

    function update_status()
    {
        $teacher_id = $this->input->post('teacher_id');
        $status = $this->input->post('status');
        $comment = $this->input->post('comment');

        $sql = $this->db->query("UPDATE ext_application_header SET application_status = '$status', comments = '$comment'  WHERE id = $teacher_id");

        if ($sql) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        return $data;
    }
    function jr_clg()
    {
        return $sql = $this->db->query("SELECT Id, Application_id, db_stream, db_admissiontype, db_class, db_division,concat(firstname, ' ' ,Middlename,  ' ', lastname) as studentname FROM clg_stu_det ORDER BY 1 DESC")->result_array();
    }
}
