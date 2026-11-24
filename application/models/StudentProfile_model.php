<?php
class StudentProfile_model extends CI_Model
{
    function getStudentList()
    {
        $sql = "select sp.id, concat(sp.SPRIDEN_student_name, ' ' ,sp.SPRIDEN_father_name,' ',sp.SPRIDEN_surname) name ,
    st.STVCLAS_standard std,
    st.STVCLAS_class class
    from spriden sp 
    join stvclas st
    on sp.id=st.STVCLAS_student_id
    where sp.SPRIDEN_user_type='st' 
    and sp.spriden_student_status='A' 
    and st.STVCLAS_acdm=(select max(stvacdm_id) from stvacdm)";
        return $this->db->query($sql)->result_array();
    }

    function getStudentData($id, $acdm)
    {
        $sql = "SELECT 
    sp.id,
    sp.SPRIDEN_student_id stud_id,
    CONCAT(sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name, ' ', sp.SPRIDEN_surname) AS name,
    st.STVCLAS_rollno roll_no,
    st.STVCLAS_standard std,
    st.STVCLAS_class class,
    spat.SPATESD_teacher_id,
    (select CONCAT(SPRIDEN_student_name, ' ', SPRIDEN_father_name, ' ', SPRIDEN_surname) from spriden 
    where spriden_student_id=spat.SPATESD_teacher_id and spriden_user_type='tc')class_teacher
    FROM 
    spriden sp
    JOIN 
    stvclas st
    ON sp.id = st.STVCLAS_student_id
    JOIN 
    spatesd spat
    ON spat.SPATESD_std = st.STVCLAS_standard
    AND spat.SPATESD_div = st.STVCLAS_class
    WHERE 
    sp.SPRIDEN_student_status = 'A'
    and sp.spriden_user_type='st'
    AND st.STVCLAS_acdm =$acdm and sp.id='$id'";
        return $this->db->query($sql)->row_array();
    }

    function getQuizData($id, $acdm)
    {
        $sql = "Select  distinct
                sub.stvsubj_id,
                sub.stvsubj_subject,
                qz.quiz_name,
                res.total_questions,
                res.marks_scored,
                res.exam_date,
                res.status,
                res.student_id
                From stvsubj sub
                Join quiz qz
                On sub.stvsubj_id=qz.subject
                Join result res
                On res.quiz_id=qz.quiz_id
                Where res.student_id=$id
                and res.academic_year=$acdm";
        // die($sql);
        return $this->db->query($sql)->result_array();
    }


    function getExamData($id, $acdm)
    {
        $sql = " select 
        sp.sprmark_student_id,
        st.stvexam_name,
        sub.stvsubj_subject,
        sp.sprmark_total_marks,
        sp.sprmark_mark 
        from sprmark sp
        join stvexam st
        on st.stvexam_id=sp.sprmark_exam_name
        join stvsubj sub
        on sub.stvsubj_id=sp.sprmark_subject
        where sp.sprmark_student_id=$id 
        and sp.sprmark_academic_year=$acdm
        order by stvexam_name";
        // die($sql);
        return $this->db->query($sql)->result_array();
    }

    function getRemarkData($id, $acdm)
    {
        $sql = "select st.stvrmrk_date,
      st.stvrmrk_remark,
      st.stvrmrk_reply,
      st.stvrmrk_date_of_reply,
      st.stvrmrk_status,
      CONCAT(sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name, ' ', sp.SPRIDEN_surname) remark_by
      from stvrmrk st
      join spriden sp
      on sp.spriden_student_id=st.stvrmrk_teacher_id
      where st.stvrmrk_student_id=$id 
      and st.stvrmrk_academic_year=$acdm";
        // die($sql);
        return $this->db->query($sql)->result_array();
    }

    function getAttendanceData($id, $acdm)
    {
        // $sql = "select SPRATND_date,SPRATND_status from spratnd where SPRATND_student_id=$id and SPRATND_acdm_year=$acdm order by 1";
        $sql = " SELECT 
    MONTH(SPRATND_date) AS month_number,
    MONTHNAME(SPRATND_date) AS month_name,
    YEAR(SPRATND_date) AS year,
    COUNT(CASE WHEN SPRATND_status = 'P' THEN 1 END) AS total_days_present,
    COUNT(CASE WHEN SPRATND_status = 'A' THEN 1 END) AS total_days_absent,
    GROUP_CONCAT(DAY(SPRATND_date) SEPARATOR ', ') AS dates_present,
    GROUP_CONCAT(CASE WHEN SPRATND_status = 'A' THEN DAY(SPRATND_date) END SEPARATOR ', ') AS dates_absent
FROM 
    spratnd
WHERE 
    SPRATND_student_id = $id
    AND SPRATND_acdm_year = $acdm
GROUP BY 
    MONTH(SPRATND_date),
    YEAR(SPRATND_date)
ORDER BY 
    YEAR(SPRATND_date), 
    MONTH(SPRATND_date)";
        // die($sql);
        return $this->db->query($sql)->result_array();
    }

    function get_all_fees_data_of_student($student_id, $acdm)
    {
        $sql = "Select * from (SELECT *,
				(class_fees - total_paid) as balance,
				((class_fees - total_paid) - total_exumpted) as final_balance
				FROM (SELECT acd.stvacdm_year,f.academic_year,c.STVCLAS_standard sprfees_standard,f.sprfees_studentid,sum(f.sprfees_amount) total_paid,
				sum(f.sprfees_exumption) total_exumpted,
				(select stvfstr_fees from stvfstr where stvfstr_std = c.STVCLAS_standard and stvfstr_acdm_year = f.academic_year) class_fees,
				(select group_concat(sf.sprfees_standard,'->', sf.sprfees_amount,'->', sf.sprfees_feesdate,'->',sf.sprfees_exumption,'->',
				IFNULL(u.spruser_name,'NA')) 
				from sprfees sf
				LEFT JOIN  spruser u on u.spruser_id = sf.sprfees_recivedby
				where sf.academic_year = f.academic_year and sf.sprfees_studentid = $student_id
				) installments
				from sprfees f, stvclas c,stvacdm acd
				where f.academic_year not in (1,2,3,4)
				and f.sprfees_studentid = 
				and acd.stvacdm_id = f.academic_year and c.STVCLAS_acdm = acd.stvacdm_id
				and c.STVCLAS_student_id = f.sprfees_studentid and c.STVCLAS_acdm = f.academic_year
				group by f.academic_year,f.sprfees_studentid
				order by 2 desc ) as temp) as temp where sprfees_studentid=$student_id and academic_year=$acdm";
        return $this->db->query($sql)->result_array();
    }
    function getMaxAcdm()
    {
        $sql = "select max(stvacdm_id)max_acdm from stvacdm";
        return $this->db->query($sql)->row_array();
    }
}
