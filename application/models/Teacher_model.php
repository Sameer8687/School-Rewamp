<?php
class Teacher_model extends CI_model
{
    //Function to check Tecaher record exist or not
    function check_if_exist($aahdhar_no)
    {
        $this->db->where('id', $aahdhar_no);
        $this->db->where('SPRIDEN_user_type', "tc");
        $student = $this->db->get('spriden')->result_array();
        if ($student == null) {
            return 0;
        } else {
            return count($student);
        }
    }
    //Add Student Data
    function register_teacher($data)
    {
        $status = $this->db->insert('spriden', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //Search Student Names in database
    function search_teacher_names($teacher_name)
    {
        $this->db->select('*');
        $this->db->from('spriden');
        $this->db->where('SPRIDEN_user_type', "tc");
        $this->db->like('SPRIDEN_surname', $teacher_name);
        $teacher = $this->db->get();
        return $teacher->result_array();
    }
    function get_selected_student_data($student_id)
    {
        $this->db->where('SPRIDEN_student_id', $student_id);
        return $student_info = $this->db->get('spriden')->row_array();
    }

    //Update Student Data
    function update_teacher($data, $student_id)
    {
        $this->db->where('id', $student_id);
        $status = $this->db->update('spriden', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //Get All Teachers From DB
    //Get All Teachers From DB (optionally filtered by academic year)
    function getAllTeachers($acdm_year_id = null)
    {
        $this->db->where('SPRIDEN_user_type', "tc");
        $this->db->where('spriden_student_status', "A");
        $this->db->where('SPRIDEN_student_id IS NOT NULL');

        if (!empty($acdm_year_id)) {
            // Convert stvacdm_id -> year code, e.g. "25-26" -> "2526"
            $row = $this->db
                ->select('stvacdm_year')
                ->where('stvacdm_id', $acdm_year_id)
                ->get('stvacdm')
                ->row_array();

            if (!empty($row['stvacdm_year'])) {
                $code = str_replace('-', '', $row['stvacdm_year']); // "2526"
                $this->db->like('SPRIDEN_student_id', 'E' . $code, 'after');
            }
        }

        return $this->db->get('spriden')->result_array();
    }

    //Add Student Data
    function add_remark($data)
    {
        $status = $this->db->insert('stvrmrk', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_remark()
    {
        $this->db->select('stvrmrk.*, spriden.SPRIDEN_surname,spriden.SPRIDEN_father_name,spriden.SPRIDEN_student_name');
        $this->db->from('stvrmrk');
        $this->db->order_by('stvrmrk_date', 'desc');
        $this->db->where('stvrmrk_teacher_id', $this->session->userdata('oliveSchoolDashboardUser'));
        $this->db->where('stvrmrk_status', "New");
        $this->db->join('spriden', 'spriden.SPRIDEN_student_id = stvrmrk.stvrmrk_student_id');
        $data = $this->db->get();
        return $data->result_array();
    }

    function getSelectedIdDataForFeedback($id)
    {
        $this->db->select('stvrmrk.*, spriden.SPRIDEN_surname,spriden.SPRIDEN_father_name,spriden.SPRIDEN_student_name');
        $this->db->from('stvrmrk');
        $this->db->where('stvrmrk_id', $id);
        $this->db->join('spriden', 'spriden.SPRIDEN_student_id = stvrmrk.stvrmrk_student_id');
        $data = $this->db->get();
        return $data->row_array();
    }
    function update_student_remark_feedback($data, $id)
    {
        $this->db->where('stvrmrk_id', $id);
        $status = $this->db->update('stvrmrk', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    function getSubjectByStd($class)
    {
        $this->db->where('stvsubj_std', $class);
        $this->db->where('stvsubj_acdm', 8);
        return $data = $this->db->get('stvsubj')->result_array();
    }


    //----------------Exam Master ------------------------------
    //check availability
    function checkIfExistTeacherSubject($id)
    {
        $this->db->where('stvstcr_id', $id);
        $data = $this->db->get('stvstcr')->row_array();
        if ($data == null) {
            return 0;
        } else {
            return count($data);
        }
    }
    //subject master
    function assign_subject_teacher($data)
    {
        $status = $this->db->insert('stvstcr', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
    function get_all_subject_teacher()
    {

        return $this->db->query("SELECT spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name,
        (select stvacdm_year from stvacdm where stvacdm_id=stvsubj_acdm)acdm_year, stvstcr.*, stvsubj.* FROM spriden, stvsubj, 
        stvstcr where stvstcr.stvstcr_teacher_id = spriden.SPRIDEN_student_id 
        and stvsubj.stvsubj_id = stvstcr.stvstcr_subject and spriden.SPRIDEN_student_id is not null")->result_array();
    }
    //get school data by id
    function getSubjectTeacherDataByID()
    {
        $this->db->where('stvstcr_id', $this->input->post('id'));
        $data['stvstcr'] = $this->db->get('stvstcr')->row_array();
        $class = $data['stvstcr']['stvstcr_std'];
        $this->db->where('stvsubj_std', $class);
        $data['subj'] = $this->db->get('stvsubj')->result_array();
        return $data;
    }
    function delete_data()
    {
        $this->db->where('stvstcr_id', $this->input->get('header-id'));
        if ($this->db->delete('stvstcr')) {
            return "success";
        } else {
            return "fail";
        }
    }
    //Update data
    function update_subject_teacher($data, $id)
    {
        $this->db->where(
            'stvstcr_id',
            $id
        );
        $status = $this->db->update('stvstcr', $data);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    //fetch greater id 
    function get_MaxID()
    {
        $maxid = 0;
        $row = $this->db->query('SELECT MAX(SPRIDEN_student_id) AS `maxid` FROM spriden WHERE SPRIDEN_user_type="tc"')->row();
        if ($row) {
            return $maxid = $row->maxid;
        }
    }

    //fetch greater id 
    function get_ExistedMaxID($acdm_substr)
    {
        $maxid = 0;
        $row = $this->db->query('SELECT MAX(SPRIDEN_student_id) AS `maxid` from spriden WHERE SPRIDEN_student_id like "E' . $acdm_substr . '%" AND SPRIDEN_user_type = "tc"')->row();
        if ($row) {
            return $maxid = $row->maxid;
        }
    }
    function already_subject_assigned($std, $class, $sub)
    {
        $this->db->where('stvstcr_std', $std);
        $this->db->where('stvstcr_div', $class);
        $this->db->where('stvstcr_subject', $sub);
        return $this->db->get('stvstcr')->row_array();
    }

    function fetchAllTeachers()
    {
        return $this->db->query("select * from spriden where SPRIDEN_user_type = 'tc'")->result_array();
    }

    // -------------------add changes-----------------------------------------------------------------
    function student_data()
    {
        $date = $this->input->post('att_date');
        $class = $this->input->post('class');
        $div = $this->input->post('div');

        $sql = "select s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
		c.STVCLAS_standard, c.STVCLAS_class, c.STVCLAS_rollno,
		(select SPRATND_status from spratnd where SPRATND_student_id = s.id and SPRATND_date = '$date') attendance_status,
		ifnull((select SPRATND_id from spratnd where SPRATND_student_id = s.id and SPRATND_date = '$date'),0) attendance_id
		from spriden s, stvclas c
		where spriden_student_status = 'A' and SPRIDEN_user_type = 'st'
		and s.id = c.STVCLAS_student_id and c.STVCLAS_standard = '$class' and c.STVCLAS_class = '$div' and c.STVCLAS_acdm = (select stvschool_acdm_year from stvschool)
		ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED)";

        return $this->db->query($sql)->result();
    }

    function insert_att()
    {
        $acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();

        $acdm = $acdm['stvschool_acdm_year'];

        $rowmax = $this->input->post('rowMax');

        $post = $this->input->post();

        $update = [];
        $insert = [];

        for ($i = 1; $i <= $rowmax; $i++) {

            $present = $this->input->post('attendance' . $i);

            $status = $present == 'p' ? 'P' : 'A';

            $id = $this->input->post('id' . $i);

            $div = $this->input->post('div' . $i);

            $std = $this->input->post('std' . $i);

            $stud_id = $this->input->post('stud_id' . $i);

            $exist = $this->db->query("SELECT * FROM spratnd WHERE SPRATND_id = '$id'")->row_array();

            if ($exist != null) {
                $data = array(

                    'SPRATND_status' => $status,
                );

                $update = $this->db->where('SPRATND_id', $id)->update('spratnd', $data);
            } else {

                $data = array(

                    'SPRATND_teacher_id' => $this->session->userdata('oliveMobileSystemUser'),
                    'SPRATND_student_id' => $stud_id,
                    'SPRATND_date' => $post['attendancedate'],
                    'SPRATND_status' => $status,
                    'SPRATND_std' => $std,
                    'SPRATND_div' => $div,
                    'SPRATND_acdm_year' => $acdm,
                );


                $insert = $this->db->insert('spratnd', $data);
            }
        }

        if ($update != null) {
            return "Attendance Updated Successfully";
        } else if ($insert != null) {
            return "Attendance Inserted Successfully";
        }
    }

    function re_stud($class, $div)
    {


        $academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
        $academic_year = $academic_year['stvschool_acdm_year'];

        $sql = "SELECT s.id,s.spriden_student_status,s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as 'name', concat(c.STVCLAS_standard) as class , (c.STVCLAS_class) as division 
        FROM spriden s, stvclas c 
        where SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A' and s.id = c.STVCLAS_student_id and c.stvclas_acdm = '$academic_year' and c.STVCLAS_standard = '$class' 
        and c.STVCLAS_class = '$div' 
        -- union SELECT s.id,s.spriden_student_status,s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as 'name', concat(c.STVCLAS_standard) as class , (c.STVCLAS_class) as division 
        -- FROM spriden s, stvclas c where SPRIDEN_user_type = 'st' and s.id = c.STVCLAS_student_id and c.stvclas_acdm = '$academic_year' and c.STVCLAS_standard = '$class' and s.spriden_student_status = 'A' 
        -- and c.STVCLAS_class = '*'
        ";

        return $this->db->query($sql)->result_array();
    }
    // -------------------add changes-----------------------------------------------------------------

    // Updated 2024 Aug 02 - kamran Ansari
    function updateStudentStatus($student_id, $status)
    {

        if ($this->db->query("update spriden set spriden_student_status = '$status' where id = $student_id")) {

            echo "success";
        } else {

            echo "fail";
        }
    }
}
