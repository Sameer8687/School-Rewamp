<?php
use Mpdf\Tag\Input;
defined('BASEPATH') or exit('No direct script access allowed');
class AttendDB_model extends CI_Model
{
    public function total_student()
    {
        $teacher_id = $this->input->get('teacher_id');
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT count(s.stvclas_student_id)Total_students FROM stvclas s, spriden sp WHERE s.stvclas_standard='$std' AND s.stvclas_class='$div' AND s.stvclas_acdm = 6 AND s.stvclas_student_id=sp.id AND sp.SPRIDEN_student_status='A'";
        return $this->db->query($sql)->result_array();
    }

    public function present_student()
    {
        $date = $this->input->get('date');
        $att = $date != null ? $date : date('y-m-d');
        $teacher_id = $this->input->get('teacher_id');
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div,SPATESD_academic_year FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $year = $query['SPATESD_academic_year'];
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT count(spratnd_status) present_student FROM spratnd WHERE spratnd_status='P' AND spratnd_std='$std' AND spratnd_div='$div' AND spratnd_acdm_year=$year AND spratnd_teacher_id='$teacher_id' AND spratnd_date = '$att'";
        return $this->db->query($sql)->result_array();
    }

    function Absent_student()
    {
        $date = $this->input->get('date');
        $att = $date != null ? $date : date('y-m-d');
        $teacher_id = $this->input->get('teacher_id');
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div,SPATESD_academic_year FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $year = $query['SPATESD_academic_year'];
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT count(spratnd_status)absent_student FROM spratnd WHERE spratnd_status='A' AND spratnd_std='$std' AND spratnd_div='$div' AND spratnd_acdm_year=$year AND spratnd_teacher_id='$teacher_id' AND spratnd_date = '$att'";
        return $this->db->query($sql)->result_array();
    }

    function DayData()
    {
        $teacher_id = $this->input->get('teacher_id');
        $Month = $this->input->get('month');
        $Monthwise = $Month != null ? $Month : date('m');
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT DISTINCT spratnd_date,count(*) total, 
        (SELECT count(*) FROM spratnd WHERE `spratnd_date` = s.`spratnd_date` AND spratnd_std = '$std' AND `spratnd_div` = '$div' AND MONTH(`spratnd_date`) = '$Monthwise' AND year(`spratnd_date`) = '2023' AND `spratnd_status` = 'P') total_present,(SELECT count(*) FROM spratnd WHERE `spratnd_date` = s.`spratnd_date` AND spratnd_std = '$std' AND `spratnd_div` = '$div' AND MONTH(`spratnd_date`) = '$Monthwise' AND year(`spratnd_date`) = '2023'  AND `spratnd_status` = 'A') total_absent
        FROM spratnd  s WHERE `spratnd_std` = '$std' AND `spratnd_div` = '$div' AND MONTH(`spratnd_date`) = '$Monthwise' AND year(`spratnd_date`) = '2023' GROUP BY spratnd_date ORDER BY spratnd_date;";
        return  $this->db->query($sql)->result_array();
    }

    function DateData()
    {
        $date = $this->input->get('date');
        $teacher_id = $this->input->get('teacher_id');
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div,SPATESD_academic_year FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $year = $query['SPATESD_academic_year'];
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT sp.spratnd_date,sp.spratnd_status,sp.spratnd_div,sp.spratnd_std,
           sp.spratnd_acdm_year,s.SPRIDEN_student_id, concat(s.spriden_surname,' ',s.spriden_student_name,' ',s.spriden_father_name ) AS student_name FROM spratnd sp, spriden s  WHERE sp.spratnd_acdm_year = '$year' AND sp.spratnd_div = '$div' AND sp.spratnd_std = '$std' AND sp.spratnd_date = '$date' AND sp.spratnd_student_id=s.id";
        return $this->db->query($sql)->result_array();
    }

    function daily_data()
    {
        $teacher_id = $this->input->get('teacher_id');
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div,SPATESD_academic_year FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $year = $query['SPATESD_academic_year'];
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $date = $this->input->get('date');
        $data = $this->input->get('type');
        $type = $data == 'All' ? '' : "and spratnd_status = '$data'";
        $sql = "SELECT sp.spratnd_date,sp.spratnd_status,sp.spratnd_div,sp.spratnd_std,
        sp.spratnd_acdm_year,s.SPRIDEN_student_id, concat(s.spriden_surname,' ',s.spriden_student_name,' ',s.spriden_father_name ) AS student_name FROM spratnd sp, spriden s  WHERE sp.spratnd_acdm_year = '$year' AND sp.spratnd_div = '$div' AND sp.spratnd_std = '$std' AND sp.spratnd_date = '$date' AND sp.spratnd_student_id=s.id $type";
        return $this->db->query($sql)->result_array();
    }

    function monthwise()
    {
        $teacher_id = $this->input->get('teacher_id');
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $Month = $this->input->get('month');
        $Monthwise = $Month != null ? $Month : date('m');
        $sql = "SELECT MONTH(spratnd_date) Month,YEAR(spratnd_date)Year,
        (SELECT count(*) FROM spratnd s WHERE spratnd_std = '$std' AND spratnd_div = '$div' AND MONTH (spratnd_date) = '$Monthwise' AND YEAR(spratnd_date) = '2023' AND spratnd_status IN ('A'))Absent,(SELECT count(*) FROM spratnd s WHERE spratnd_std = '$std' AND spratnd_div = '$div' AND MONTH(spratnd_date) = '$Monthwise' AND YEAR(spratnd_date) = '2023' AND spratnd_status IN ('P'))Present, count(*) Total_stud FROM spratnd s WHERE spratnd_std = '$std' AND spratnd_div = '$div' AND MONTH(spratnd_date) = '$Monthwise' AND YEAR(spratnd_date) = '2023' AND spratnd_status IN ('A','P')";
        return  $this->db->query($sql)->result_array();
    }

    function report_db()
    {
        $Month = $this->input->get('month');
        $Monthwise = $Month != null ? $Month : date('m');
        $sql = "SELECT DISTINCT month(`spratnd_date`) AS month, concat(SPRIDEN_surname, ' ',SPRIDEN_student_name , ' ' ,SPRIDEN_father_name) AS name  ,`spratnd_student_id`, count(`spratnd_status`) total_days,(SELECT count(`spratnd_status`) FROM spratnd WHERE month(spratnd_date) = '06' AND year(spratnd_date)=2023 AND `spratnd_student_id` = atn.spratnd_student_id AND spratnd_status = 'P')total_present,(SELECT count(`spratnd_status`) FROM spratnd WHERE month(spratnd_date) = '06' AND year(spratnd_date)=2023 AND `spratnd_student_id` = atn.spratnd_student_id AND spratnd_status = 'A')total_absent FROM spratnd atn ,spriden s WHERE month(spratnd_date) = '$Monthwise' AND year(spratnd_date)=2023 AND s.id = atn.`spratnd_student_id` group by month(`spratnd_date`), `spratnd_student_id`";
        return  $this->db->query($sql)->result_array();
    }

    function teacher_fetch()
    {
        $sql = "SELECT SPRIDEN_student_id,concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name FROM spriden WHERE SPRIDEN_user_type = 'tc' AND spriden_student_status = 'A' AND SPRIDEN_student_id IN (SELECT DISTINCT SPATESD_teacher_id FROM spatesd)";
        return $this->db->query($sql)->result_array();
    }
}