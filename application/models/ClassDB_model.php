<?php defined('BASEPATH') or exit('No direct script access allowed');
class ClassDB_model extends CI_Model
{
    function exam_name()
    {
        $sql = "SELECT * FROM `stvexam`";
        return $this->db->query($sql)->result_array();
    }

    function class_stud()
    {
        $tc_id = $this->session->userdata('oliveMobileSystemUser');
        $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
        $year = $q['stvschool_acdm_year'];
        $class_data = $this->db->query("SELECT * FROM `spatesd` WHERE `SPATESD_teacher_id` = '$tc_id' AND `SPATESD_academic_year` = $year")->row_array();
        $div = $class_data['SPATESD_div'];
        $std = $class_data['SPATESD_std'];
        $sql = "SELECT COUNT(s.stvclas_student_id) AS Total_students FROM stvclas s, spriden sp WHERE s.stvclas_standard = '$std' AND s.stvclas_class = '$div' AND s.stvclas_acdm = (SELECT stvschool_acdm_year FROM stvschool) AND s.stvclas_student_id = sp.id AND sp.SPRIDEN_student_status = 'A'";
        return $this->db->query($sql)->row_array();
    }

    function student_name()
    {
        $tc_id = $this->session->userdata('oliveMobileSystemUser');
        $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
        $year = $q['stvschool_acdm_year'];
        $class_data = $this->db->query("SELECT * FROM `spatesd` WHERE `SPATESD_teacher_id` = '$tc_id' AND `SPATESD_academic_year` = $year")->row_array();
        $div = $class_data['SPATESD_div'];
        $std = $class_data['SPATESD_std'];
        $sql = "SELECT sp.id, SPRIDEN_student_id,concat(sp.spriden_surname,' ',sp.spriden_student_name,' ',sp.spriden_father_name ) AS student_name FROM stvclas s, spriden sp WHERE s.stvclas_standard='$std' AND s.stvclas_class='$div' AND s.stvclas_acdm = $year AND s.stvclas_student_id=sp.id AND sp.SPRIDEN_student_status='A'";
        return $this->db->query($sql)->result_array();
    }
    function subject_name($teacher_id)
    {
        $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
        $year = $q['stvschool_acdm_year'];
        $sql = "SELECT DISTINCT stvsubj_id,stvsubj_subject,stvsubj_std FROM stvsubj WHERE stvsubj_std = (SELECT SPATESD_std FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $year) AND stvsubj_acdm = $year";
        return $this->db->query($sql)->result_array();
    }

    function top_stud()
    {
        $tc_id = $this->session->userdata('oliveMobileSystemUser');
        $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
        $year = $q['stvschool_acdm_year'];
        $class_data = $this->db->query("SELECT * FROM `spatesd` WHERE `SPATESD_teacher_id` = '$tc_id' AND `SPATESD_academic_year` = $year")->row_array();
        $div = $class_data['SPATESD_div'];
        $std = $class_data['SPATESD_std'];
        $id = $this->input->get('id');
        $ex_id = $this->input->get('exam');
        $sub_id = $this->input->get('sub');
        $sql = "SELECT student_id, obtain_marks, total_marks,ROUND((obtain_marks/total_marks)*100,2)percentage,concat(spriden.SPRIDEN_surname,' ', spriden.SPRIDEN_student_name) AS name FROM (SELECT DISTINCT student_id, sum(marks) obtain_marks,(SELECT SUM(es.Total_marks) total_marks FROM exam_setup es WHERE es.exam = $ex_id AND es.class= '$std') total_marks FROM stamrks WHERE student_id IN (SELECT DISTINCT c.STVCLAS_student_id FROM stvclas c JOIN spriden sp ON sp.id = c.STVCLAS_student_id WHERE c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div' AND c.STVCLAS_acdm = '$year' AND sp.SPRIDEN_user_type = 'st' AND sp.SPRIDEN_student_status = 'A') AND exam_id IN (SELECT DISTINCT id FROM exam_setup es WHERE es.exam = $ex_id AND es.class= '$std') AND academic_year = $year GROUP BY student_id ORDER BY 2 DESC) AS temp,spriden WHERE spriden.id = temp.student_id LIMIT 3 ";
        $data['subject_data'] =  $this->db->query($sql)->result_array();
        $data['average_score'] = $this->db->query("SELECT subject_name, total_marks, passing_marks, obtained_marks,ROUND(((obtained_marks/total_marks) * 100),2) percentage FROM (SELECT sbj.stvsubj_subject AS subject_name, es.Total_marks AS total_marks, es.passing_marks AS passing_marks,mrk.marks AS obtained_marks FROM stamrks mrk, stvsubj sbj, exam_setup es WHERE mrk.subject_id = sbj.stvsubj_id AND mrk.stvexam_id = $ex_id AND mrk.academic_year = $year AND es.id = mrk.exam_id ORDER BY mrk.marks DESC LIMIT 3) AS temp")->result_array();

        if ($sub_id != null) {
            $data['all_submark'] = $this->db->query("SELECT s.student_id,s.subject_id,SUM(s.marks) AS obtain_marks,es.total_marks,es.passing_marks,subj.stvsubj_subject AS subject_name,CONCAT(sp.spriden_surname, ' ', sp.spriden_student_name) AS name FROM stamrks s JOIN (SELECT DISTINCT c.STVCLAS_student_id FROM stvclas c JOIN spriden sp ON sp.id = c.STVCLAS_student_id WHERE c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div' AND c.STVCLAS_acdm = '$year' AND sp.SPRIDEN_user_type = 'st' AND sp.SPRIDEN_student_status = 'A') student_filter ON s.student_id = student_filter.STVCLAS_student_id JOIN exam_setup es ON s.exam_id = es.id LEFT JOIN stvsubj subj ON s.subject_id = subj.stvsubj_id LEFT JOIN spriden sp ON s.student_id = sp.id WHERE es.exam = $ex_id AND es.class = '$std' AND s.academic_year = $year AND subject_id = $sub_id GROUP BY s.student_id, s.subject_id, es.total_marks, es.passing_marks, subj.stvsubj_subject, sp.spriden_surname, sp.spriden_student_name ORDER BY s.student_id, s.subject_id ;")->result_array();
        } else {
            $data['all_submark'] = $this->db->query("SELECT s.student_id, s.subject_id, SUM(s.marks) AS  obtain_marks, es.total_marks, es.passing_marks, subj.stvsubj_subject AS subject_name, CONCAT(sp.spriden_surname, ' ', sp.spriden_student_name) AS name FROM stamrks s JOIN (SELECT DISTINCT c.STVCLAS_student_id FROM stvclas c JOIN spriden sp ON sp.id = c.STVCLAS_student_id WHERE c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div' AND c.STVCLAS_acdm = '$year' AND sp.SPRIDEN_user_type = 'st' AND sp.SPRIDEN_student_status = 'A') student_filter ON s.student_id = student_filter.STVCLAS_student_id JOIN exam_setup es ON s.exam_id = es.id LEFT JOIN stvsubj subj ON s.subject_id = subj.stvsubj_id LEFT JOIN spriden sp ON s.student_id = sp.id WHERE es.exam = $ex_id AND es.class = '$std' AND s.academic_year = $year GROUP BY s.student_id, s.subject_id, es.total_marks, es.passing_marks, subj.stvsubj_subject, sp.spriden_surname, sp.spriden_student_name ORDER BY s.student_id, s.subject_id;")->result_array();
        }

        $data['weak_subject'] = $this->db->query("SELECT subject_name, total_marks, passing_marks, obtained_marks FROM (SELECT sbj.stvsubj_subject AS subject_name, es.Total_marks AS total_marks, es.passing_marks AS passing_marks, mrk.marks AS obtained_marks FROM stamrks mrk, stvsubj sbj, exam_setup es WHERE mrk.subject_id = sbj.stvsubj_id AND mrk.stvexam_id = $ex_id AND mrk.academic_year = $year AND es.id = mrk.exam_id AND mrk.marks < 45 ORDER BY mrk.marks DESC LIMIT 3) AS temp;")->result_array();
        $data['ClassTop_one'] = $this->db->query("SELECT student_id, obtain_marks, total_marks, ROUND((obtain_marks/total_marks)*100,2)percentage,CONCAT(spriden.SPRIDEN_surname,' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name FROM (SELECT DISTINCT student_id, SUM(marks) obtain_marks,(SELECT SUM(es.Total_marks) total_marks FROM exam_setup es WHERE es.exam = $ex_id AND es.class= '$std') total_marks FROM stamrks WHERE student_id IN (SELECT DISTINCT c.STVCLAS_student_id FROM stvclas c JOIN spriden sp ON sp.id = c.STVCLAS_student_id WHERE c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div' AND c.STVCLAS_acdm = '$year' AND sp.SPRIDEN_user_type = 'st' AND sp.SPRIDEN_student_status = 'A') and exam_id in (SELECT DISTINCT id FROM exam_setup es WHERE es.exam = $ex_id AND es.class= '$std') AND academic_year = $year GROUP BY student_id ORDER BY 2 DESC) AS temp,spriden WHERE spriden.id = temp.student_id LIMIT 1")->result_array();
        $data['stud_top_attend'] = $this->db->query("SELECT s.spratnd_student_id AS id,CONCAT(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) AS student_name,SPRATND_status AS status,COUNT(SPRATND_status) AS total,DATEDIFF(CURRENT_DATE(), '2023-06-15') AS total_days,FORMAT((COUNT(SPRATND_status) / DATEDIFF(CURRENT_DATE(), '2023-06-15')) * 100, 2) AS attend_percentage FROM spratnd s JOIN spriden sp ON s.spratnd_student_id = sp.id WHERE s.spratnd_std = '$std' AND s.spratnd_div = '$div' AND SPRATND_date BETWEEN '2023-06-15' AND CURRENT_DATE() AND s.SPRATND_acdm_year = (SELECT FROM stvschool) AND s.SPRATND_status = 'P' AND sp.SPRIDEN_student_status = 'A' GROUP BY s.spratnd_student_id ORDER BY total DESC LIMIT 1")->result_array();
        $data['Subject_chart'] = $this->db->query("SELECT subject_name,SUM(obtained_marks) AS total_obtained_marks FROM (SELECT sbj.stvsubj_subject AS subject_name,es.Total_marks AS total_marks,es.passing_marks AS ,mrk.marks AS obtained_marks FROM stamrks mrk JOIN stvsubj sbj ON mrk.subject_id = sbj.stvsubj_id JOIN exam_setup es ON es.id = mrk.exam_id WHERE mrk.stvexam_id = $ex_id AND mrk.academic_year = $year) AS temp GROUP BY subject_name")->result_array();
        return $data;
    }

    function class_attend()
    {
        $tc_id = $this->session->userdata('oliveMobileSystemUser');
        $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
        $year = $q['stvschool_acdm_year'];
        $class_data = $this->db->query("SELECT * FROM `spatesd` WHERE `SPATESD_teacher_id` = '$tc_id' AND `SPATESD_academic_year` = $year")->row_array();
        $div = $class_data['SPATESD_div'];
        $std = $class_data['SPATESD_std'];
        $sql = "SELECT COUNT(*) AS total,(SELECT COUNT(*) FROM spratnd WHERE SPRATND_status = 'P' AND SPRATND_date BETWEEN '2023-06-15' AND CURRENT_DATE() AND SPRATND_div = '$div' AND SPRATND_std = '$std') AS present,ROUND(LEAST(100.0, (( (SELECT COUNT(*) FROM spratnd WHERE SPRATND_status = 'P' AND SPRATND_date BETWEEN '2023-06-15' AND CURRENT_DATE() AND SPRATND_div = '$div' AND SPRATND_std = '$std')/ count(*)) * 100)), 2) AS percentage FROM spratnd WHERE SPRATND_date BETWEEN '2023-06-15' AND CURRENT_DATE() AND SPRATND_div = '$div' AND SPRATND_std = '$std'";
        return $this->db->query($sql)->row_array();
    }

    function GetSems()
    {
        $tc_id = $this->session->userdata('oliveMobileSystemUser');
        $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
        $year = $q['stvschool_acdm_year'];
        $class_data = $this->db->query("SELECT * FROM `spatesd` WHERE `SPATESD_teacher_id` = '$tc_id' and `SPATESD_academic_year` = $year")->row_array();
        $div = $class_data['SPATESD_div'];
        $std = $class_data['SPATESD_std'];
        $id = $this->input->get('id');
        $ex_id = $this->input->get('exam');
        $sql = "SELECT concat (s.spriden_student_name,s.spriden_father_name, s.spriden_surname)name,
        s.spriden_student_id,st.student_id,st.academic_year,st.class,st.division,st.exam_id,st.marks,st.subject_id,sub.stvsubj_subject ,ex.passing_marks,ex.Total_marks FROM stamrks st,spriden s ,stvsubj sub, exam_setup ex WHERE s.id=student_id AND s.spriden_student_status='A' AND s.id= $id AND st.academic_year= $year AND st.stvexam_id= $ex_id AND ex.class=st.class AND ex.subject=st.subject_id AND sub.stvsubj_id=st.subject_id GROUP BY s.spriden_student_id,st.student_id,st.academic_year,st.class,st.division,st.exam_id,
        st.marks,st.subject_id,sub.stvsubj_subject,ex.passing_marks,ex.Total_marks";
        $data['GetSems'] =  $this->db->query($sql)->result_array();
        $data['improvement'] = $this->db->query("SELECT sm.student_id,sm.class,sm.division,CONCAT(spr.SPRIDEN_surname, ' ', spr.SPRIDEN_student_name, ' ', spr.SPRIDEN_father_name) AS name,sm.stvexam_id,sm.subject_id,sm.marks,
        CASE
            WHEN sm.stvexam_id = $ex_id THEN
                CASE
                    WHEN sm.marks >= 90 THEN 'Excellent'
                    WHEN sm.marks >= 80 THEN 'Very Good'
                    WHEN sm.marks >= 70 THEN 'Good'
                    WHEN sm.marks >= 60 THEN 'Fair'
                WHEN sm.marks >= 50 THEN 'Need Improvement '
                ELSE 'Needs to work hard'
                END
            WHEN (SELECT marks
                  FROM stamrks
                  WHERE spr.id = sm.student_id
                  AND subject_id = sm.subject_id
                  AND stvexam_id = sm.stvexam_id - 1 -- Compare with the previous stvexam_id
                  LIMIT 1) < sm.marks THEN 'Improvement'
            ELSE 'No Improvement'
        END AS improvement_status
    FROM
        stamrks AS sm
    INNER JOIN
        spriden AS spr
    ON
        sm.student_id = spr.id
    WHERE
        sm.student_id = $id -- Specify the desired student_id
    GROUP BY
        sm.student_id, sm.class, sm.division, sm.stvexam_id
    ORDER BY
        sm.student_id, sm.class, sm.division, sm.stvexam_id;")->row_array();
        $data['attends'] = $this->db->query("SELECT
        CONCAT(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,
        DATEDIFF(CURDATE(), '2023-06-20') AS days,
        (SELECT COUNT(SPRATND_status) FROM spratnd WHERE SPRATND_status = 'A'
        AND `SPRATND_date` BETWEEN '2023-06-15' AND CURDATE() AND `SPRATND_student_id` = d.`id`) AS total_absent,
        (SELECT COUNT(SPRATND_status) FROM spratnd WHERE SPRATND_status = 'P' AND
        `SPRATND_date` BETWEEN '2023-06-15' AND CURDATE() AND `SPRATND_student_id` = d.`id`) AS total_present,
        ROUND(((SELECT COUNT(SPRATND_status) FROM spratnd WHERE SPRATND_status = 'P'
        AND `SPRATND_date` BETWEEN '2023-06-15' AND CURDATE() AND `SPRATND_student_id` = d.`id`) / DATEDIFF(CURDATE(), '2023-06-20')) * 100, 2) AS percentage
    FROM
        spratnd AS s
    INNER JOIN
        spriden AS d ON s.`SPRATND_student_id` = d.`id`
    WHERE
        s.`SPRATND_std` = '$std'
        AND s.`SPRATND_div` = '$div'
        AND YEAR(s.`SPRATND_date`) 
        AND s.`SPRATND_student_id` = $id
        AND s.`SPRATND_date` BETWEEN '2023-06-15' AND CURDATE()
    GROUP BY
        d.`id`")->row_array();
        $data['exam_average'] = $this->db->query("SELECT SUM(st.marks) AS ob_total,
        (SELECT sum(Total_marks)total FROM stamrks st, exam_setup ex WHERE st.exam_id = ex.id AND st.student_id = $id AND st.stvexam_id= $ex_id) AS total_marks,(select ROUND(((SUM(st.marks)/total_marks) * 100),2) ) AS percentage,(SELECT count(sub.stvsubj_subject) FROM stvsubj sub, stamrks st WHERE sub.stvsubj_id = st.subject_id AND st.student_id= $id) sub_count FROM stamrks st, spriden s WHERE s.id = st.student_id AND st.student_id = $id AND academic_year = $year and stvexam_id = $ex_id")->row_array();
        return $data;
    }
}