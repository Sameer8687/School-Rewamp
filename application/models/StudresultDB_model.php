<?php

defined('BASEPATH') or exit('No direct script access allowed');

class StudresultDB_model extends CI_Model
{

    public function student_name()
    {


        $div = $this->session->userdata('spatesd_div');
        $std = $this->session->userdata('spatesd_std');


        $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

        $year = $q['stvschool_acdm_year'];



        $sql = "SELECT sp.id, SPRIDEN_student_id,concat(sp.spriden_surname,' ',sp.spriden_student_name,' ',sp.spriden_father_name ) as student_name
            FROM stvclas s, spriden sp where s.stvclas_standard='$std' and s.stvclas_class='$div'
            and s.stvclas_acdm = $year and s.stvclas_student_id=sp.id and sp.SPRIDEN_student_status='A'";
        return $this->db->query($sql)->result_array();
    }
    function exam_name()
    {
        $sql = "SELECT * FROM `stvexam`";
        return $this->db->query($sql)->result_array();
    }

    function getData()
    {
        $id = $this->input->get('id');
        $year = $this->input->get('year');
        $exam = $this->input->get('exam');

        // For Subject Marks

        $sql = "SELECT concat (s.spriden_student_name,s.spriden_father_name, s.spriden_surname)name,
        s.spriden_student_id,st.student_id,st.academic_year,st.class,st.division,st.exam_id,st.marks,st.subject_id,sub.stvsubj_subject ,
        ex.passing_marks,ex.Total_marks
        from stamrks st,spriden s ,stvsubj sub, exam_setup ex where s.id=student_id and s.spriden_student_status='A' and s.id=$id
         and st.academic_year=$year and st.stvexam_id=$exam
        and ex.class=st.class and ex.subject=st.subject_id
        and sub.stvsubj_id=st.subject_id group by s.spriden_student_id,st.student_id,st.academic_year,st.class,st.division,st.exam_id,
        st.marks,st.subject_id,sub.stvsubj_subject,ex.passing_marks,ex.Total_marks;";

        $data['subject_data'] = $this->db->query($sql)->result_array();

        // For Average Subject

        $data['average_score'] = $this->db->query("select subject_name, total_marks, passing_marks, obtained_marks,
        ROUND(((obtained_marks/total_marks) * 100),2) percentage
        from (select sbj.stvsubj_subject as subject_name, es.Total_marks as total_marks, es.passing_marks as passing_marks,mrk.marks as obtained_marks
        
        from 
        stamrks mrk, stvsubj sbj, exam_setup es
        where mrk.student_id = $id and mrk.subject_id = sbj.stvsubj_id
        and mrk.stvexam_id = $exam and mrk.academic_year = $year
        and es.id = mrk.exam_id
        order by mrk.marks desc limit 3) as temp")->result_array();

        // For Student Subject Remarks 

        $data['remarks'] = $this->db->query("select mrk.subject_id,sbj.stvsubj_subject, es.Total_marks, es.passing_marks,mrk.marks,mrk.remarks,
        (select distinct concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) as name from spriden 
        where SPRIDEN_student_id in(select stvstcr_teacher_id from stvstcr where stvstcr_subject = mrk.subject_id)) teacher
        from 
        stamrks mrk, stvsubj sbj, exam_setup es
        where mrk.student_id = $id and mrk.subject_id = sbj.stvsubj_id
        and mrk.stvexam_id = $exam and mrk.academic_year = $year
        and es.id = mrk.exam_id
        order by mrk.marks desc")->result_array();

        $div = $this->session->userdata('spatesd_div');
        $std = $this->session->userdata('spatesd_std');

        $data['student_attendance'] = $this->db->query("SELECT DISTINCT DATEDIFF(CURDATE() , '2023-06-20') days,
        (select COUNT(SPRATND_status) FROM spratnd where SPRATND_status = 'A' AND `SPRATND_date` BETWEEN '2023-06-15' AND CURDATE() 
        AND `SPRATND_student_id` = $id) total_absent,
        (select COUNT(SPRATND_status) FROM spratnd where SPRATND_status = 'P' AND `SPRATND_date` BETWEEN '2023-06-15' AND CURDATE() 
        AND `SPRATND_student_id` = $id) total_present,
        (select ROUND(((total_present/days) * 100),2) ) as percentage
        
        FROM spratnd s 
        WHERE `SPRATND_std` = '$std' 
            AND `SPRATND_div` = '$div' 
            AND YEAR(`SPRATND_date`) = '2023' 
            AND `SPRATND_student_id` = $id
            AND `SPRATND_date` BETWEEN '2023-06-15' AND CURDATE()  -- Modify the date range here
        GROUP BY SPRATND_status
        ORDER BY SPRATND_date;")->result_array();

        $data['exam_average'] = $this->db->query("select SUM(st.marks) as ob_total,
        (select sum(Total_marks)total from stamrks st, exam_setup ex where st.exam_id = ex.id and st.student_id = $id and st.stvexam_id=$exam) total_marks,
        (select ROUND(((SUM(st.marks)/total_marks) * 100),2) ) as percentage,
        (select count(sub.stvsubj_subject) from stvsubj sub, stamrks st where sub.stvsubj_id = st.subject_id  and st.student_id=$id) sub_count
        from stamrks st, spriden s   where s.id = st.student_id and st.student_id = $id and academic_year = $year and stvexam_id = $exam")->row_array();

        $data['weak_subject'] = $this->db->query("  SELECT 
        subject_name, 
        total_marks, 
        passing_marks, 
        obtained_marks
    FROM (
        SELECT 
            sbj.stvsubj_subject AS subject_name, 
            es.Total_marks AS total_marks, 
            es.passing_marks AS passing_marks,
            mrk.marks AS obtained_marks
        FROM 
            stamrks mrk, 
            stvsubj sbj, 
            exam_setup es
        WHERE 
            mrk.student_id = $id 
            AND mrk.subject_id = sbj.stvsubj_id
            AND mrk.stvexam_id = $exam 
            AND mrk.academic_year = $year
            AND es.id = mrk.exam_id
           AND mrk.marks < 45
        ORDER BY 
            mrk.marks DESC limit 3
    
    ) AS temp")->result_array();
        return $data;
    }

    function GetSems()
    {

        $year = $this->input->get('year');
        $id = $this->input->get('id');
        $exam_1 = $this->input->get('exam_1');
        $exam_2 = $this->input->get('exam_2');

        return $this->db->query("SELECT  distinct sub.stvsubj_subject  as subject,
        MAX(CASE when st.stvexam_id = $exam_1 then st.marks end) as marks1,
         MAX(CASE when  st.stvexam_id = $exam_2 then st.marks end) as marks2
       FROM exam_setup ex,spriden s,stvsubj sub, 
       stamrks st where st.student_id=s.id and st.student_id = $id and st.academic_year = $year and s.spriden_student_status = 'A' and
       st.subject_id = sub.stvsubj_id and st.stvexam_id in ($exam_1,$exam_2) group by sub.stvsubj_subject;")->result_array();
    }
}

   


/* End of file attendanceDb_model.php */