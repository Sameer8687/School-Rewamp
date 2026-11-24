<?php

class Marksheet_model extends CI_model

{


    function add_exam_data()
    {
        $stvexam_name = $this->input->post('stvexam_name');
        $year = $this->input->post('year');
        if ($this->db->query("INSERT INTO stvexam (stvexam_name,year) VALUES ( '$stvexam_name','$year')")) {
            redirect(base_url('index.php/Marks/Exam_master'));
        } else {
            echo json_encode("fail");
        }
    }

    function getExamDataByID()
    {
        $this->db->where('stvexam_id', $this->input->post('id'));
        return $this->db->get('stvexam')->row_array();
    }

    function add_subject()
    {
        $stvsubj_subject = $this->input->post('stvsubj_subject');
        $stvsubj_std = $this->input->post('stvsubj_std');
        if ($this->db->query("INSERT INTO stvsubj (stvsubj_subject,stvsubj_std) VALUES ( '$stvsubj_subject','$stvsubj_std')")) {
            redirect(base_url('index.php/Marks/Subject_master'));
        } else {
            echo json_encode("fail");
        }
    }
    function all_exams()
    {
        $exam = $this->input->post('exam');
        $subject = $this->input->post('subject');
        $class = $this->input->post('class');
        $Total_marks = $this->input->post('Total_marks');
        $passing_marks = $this->input->post('passing_marks');
        $year = $this->input->post('year');
        if ($this->db->query("INSERT INTO exam_setup (exam,subject,class,Total_marks,passing_marks,year) VALUES ('$exam', '$subject','$class','$Total_marks','$passing_marks','$year')")) {
            redirect(base_url('index.php/Marks/Examsetup'));
        } else {
            echo json_encode("fail");
        }
    }
    function fetch_exam()
    {
        $sql = "select distinct stvexam_name from stvexam";
        $sqls = $this->db->query($sql)->result_array();
        return $sqls;
    }

    function fetch_sub()
    {
        $sql = "select distinct stvsubj_subject from stvsubj";
        $sqls = $this->db->query($sql)->result_array();
        return $sqls;
        $this->db->where('stvsubj_std');
        return $this->db->get('stvsubj')->result_array();
    }
    function fetch_data()
    {
        $sql = "select * from exam_setup";
        $sqls = $this->db->query($sql)->result_array();
        return $sqls;
    }
    function fetch_exam_data()
    {
        return  $this->db->get('stvexam')->result_array();
    }
    function fetchs_Data($year, $class)
    {
        $sql = "SELECT * FROM exam_setup where year='$year' and class='$class';";
        $sqls = $this->db->query($sql)->result_array();
        return $sqls;
    }

    function getStuData($std, $div)
    {
        $sql = "SELECT st.STVCLAS_student_id, concat(s.spriden_surname, ' ', s.spriden_student_name, ' ', s.spriden_father_name) name from stvclas st, spriden s where s.id=st.stvclas_student_id and st.STVCLAS_standard='$std' and st.STVCLAS_class='$div'";
        $sqls = $this->db->query($sql)->result_array();
        return $sqls;
    }
    function fetch_acadmic($year, $class)
    {
        return $this->db->query("select e.exam,s.stvexam_name
            from exam_setup e, stvexam s
            where e.year = $year
            and e.class = '$class' 
            and e.exam = s.stvexam_id")->result_array();
    }

    function student_marks($class, $division, $year, $exam)
    {
        $sql = "SELECT s.id,s.spriden_student_id, concat(s.spriden_surname, ' ', s.spriden_student_name, ' ', s.spriden_father_name) name,
         c.STVCLAS_standard, c.STVCLAS_class,
         ifnull((select ifnull(id,0) id from stamrks where student_id = s.id and academic_year = $year and class=c.STVCLAS_standard 
         and division = c.STVCLAS_class and exam_id = $exam),0) id,
         ifnull((select ifnull(marks,0) id from stamrks where student_id = s.id and academic_year =$year and class=c.STVCLAS_standard 
         and division = c.STVCLAS_class and exam_id = $exam),0) marks
         from spriden s, stvclas c
         where s.id = c.STVCLAS_student_id
         and s.SPRIDEN_user_type = 'st'
         and s.spriden_student_status = 'A'
         and c.STVCLAS_standard = '$class'
         and c.STVCLAS_class = '$division'
         and c.STVCLAS_acdm = $year;";
        $data['records'] = $this->db->query($sql)->result_array();
        return $data;
    }

    function getAllSubjetcsByStd($std)
    {
        $this->db->where('stvsubj_std', $std);
        return $this->db->get('stvsubj')->result_array();
    }

    function fetch()
    {
        $sql = "SELECT distinct spriden.id,
		concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name,
		stvclas.STVCLAS_acdm,
		concat(stvclas.STVCLAS_standard, ' - ', stvclas.STVCLAS_class) class_name,
		stvclas.STVCLAS_standard
		from spriden,stvclas
		where stvclas.STVCLAS_student_id = spriden.id and 
		spriden.spriden_user_type='st';";
        $sqls = $this->db->query($sql)->result_array();
        return $sqls;
    }



    public function details_fetch($class, $division)
    {
        $sql = "SELECT s.id,s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as 'name', concat(c.STVCLAS_standard , ' - ',c.STVCLAS_class) as class, (select `id` from driver_details where `spriden_id` = s.id )as detail_id, (select `driver_id` from driver_details where `spriden_id` = s.id )as driver_id FROM SPRIDEN s, stvclas c where s.id = c.STVCLAS_student_id and c.STVCLAS_standard = '$class' and c.STVCLAS_class = '$division'";
        // $sql = "select sr_no,Driver_name from driver_master ";
        return $this->db->query($sql)->result_array();
    }

    // function fetch_data()
    // {
    //     $sql = "SELECT s.id,s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as 'name', concat(c.STVCLAS_standard , ' - ',c.STVCLAS_class) as class, (select 'detail_id' from driver_details where 'student_id'= s.id )as detail_id, (select 'driver_id' from driver_details where 'student_id'= s.id )as driver_id FROM SPRIDEN s, stvclas c where s.id = c.STVCLAS_student_id and c.STVCLAS_standard = 'V' and c.STVCLAS_class = 'A';";
    //     $this->db->query($sql)->result_array();
    // }
    // public function getdrivrDetails()
    // {
    //     return $this->db->query("select sr_no,Driver_name from driver_master")->result_array();
    // }


    public function driver_fetch()
    {
        $sr_no = "SELECT * FROM driver_master";
        return $this->db->query($sr_no)->result_array();
    }
    public function vehicle_fetch()
    {
        $sr_no = "SELECT * FROM vehicle_master";
        return $this->db->query($sr_no)->result_array();
    }


    public  function delete($id)
    {
        $this->db->where('sr_no', $id);
        return $this->db->delete('driver_master');
    }
    public function vehicle_delete($id)
    {
        $this->db->where('sr_no', $id);
        return $this->db->delete('vehicle_master');
    }
}
