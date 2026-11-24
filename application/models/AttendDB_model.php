<?php
use Mpdf\Tag\Input;
defined('BASEPATH') or exit('No direct script access allowed');
class AttendDB_model extends CI_Model
{
    public function total_student()
    {
        $teacher_id = $this->input->get('teacher_id');
        $dt = $this->input->get('date');
        $date = $dt != null ? $dt : date('y-m-d');
        $academic_year = $this->db->query("SELECT stvacdm_id FROM stvacdm WHERE '$date' BETWEEN stvacdm_start AND stvacdm_end")->row_array();
        $acdm = $academic_year['stvacdm_id'];
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $acdm")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT count(s.stvclas_student_id)Total_students FROM stvclas s, spriden sp WHERE s.stvclas_standard='$std' AND s.stvclas_class='$div' AND s.stvclas_acdm = $acdm AND s.stvclas_student_id=sp.id AND sp.SPRIDEN_student_status='A'";
        return $this->db->query($sql)->result_array();
    }

    public function present_student()
    {
        $dt = $this->input->get('date');
        $date = $dt != null ? $dt : date('y-m-d');
        $teacher_id = $this->input->get('teacher_id');
        $academic_year = $this->db->query("SELECT stvacdm_id FROM stvacdm WHERE '$date' BETWEEN stvacdm_start AND stvacdm_end")->row_array();
        $acdm = $academic_year['stvacdm_id'];
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $acdm")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT count(spratnd_status) present_student FROM spratnd WHERE spratnd_status='P' AND spratnd_std='$std' AND spratnd_div='$div' AND spratnd_acdm_year= $acdm AND spratnd_teacher_id='$teacher_id' AND spratnd_date = '$date'";
        return $this->db->query($sql)->result_array();
    }

    function Absent_student()
    {
        $dt = $this->input->get('date');
        $date = $dt != null ? $dt : date('y-m-d');
        $teacher_id = $this->input->get('teacher_id');
        $academic_year = $this->db->query("SELECT stvacdm_id FROM stvacdm WHERE '$date' BETWEEN stvacdm_start AND stvacdm_end")->row_array();
        $acdm = $academic_year['stvacdm_id'];
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $acdm")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT count(spratnd_status)absent_student FROM spratnd WHERE spratnd_status='A' AND spratnd_std='$std' AND spratnd_div='$div' AND spratnd_acdm_year= $acdm AND spratnd_teacher_id='$teacher_id' AND spratnd_date = '$date'";
        return $this->db->query($sql)->result_array();
    }

    // newly added
    function DayData($month, $year, $teacher_id, $selectedDate = null)
    {
        $query = $this->db->query("SELECT SPATESD_std,SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id'")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $dateCondition = $selectedDate ? "AND spratnd_date = '$selectedDate'" : "";

        $sql = "SELECT DISTINCT spratnd_date,count(*) total, 
        (SELECT count(*) FROM spratnd WHERE `spratnd_date` = s.`spratnd_date` AND spratnd_std = '$std' AND `spratnd_div` = '$div' AND MONTH(`spratnd_date`) = '$month' AND year(`spratnd_date`) = '$year' AND `spratnd_status` = 'P') total_present,(SELECT count(*) FROM spratnd WHERE `spratnd_date` = s.`spratnd_date` AND spratnd_std = '$std' AND `spratnd_div` = '$div' AND MONTH(`spratnd_date`) = '$month' AND year(`spratnd_date`) = '$year' AND `spratnd_status` = 'A') total_absent FROM spratnd s WHERE spratnd_std` = '$std' and `spratnd_div` = '$div' and MONTH(`spratnd_date`) = '$month' 
        AND year(`spratnd_date`) = '$year' $dateCondition GROUP BY spratnd_date ORDER BY spratnd_date;";
        return  $this->db->query($sql)->result_array();
    }

    public function getDailyStudentDetails($teacher_id, $selectedDate, $type) {
        $academic_year = $this->db->query("SELECT stvacdm_id FROM stvacdm WHERE '$selectedDate' BETWEEN stvacdm_start AND stvacdm_end")->row_array();
        $acdm = $academic_year['stvacdm_id'];
        $query = $this->db->query("SELECT SPATESD_std, SPATESD_div, SPATESD_academic_year FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $acdm")->row_array();
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $statusCondition = "";
        if ($type == 'P') {
            $statusCondition = "AND spratnd_status = 'P'";
        } else if ($type == 'A') {
            $statusCondition = "AND spratnd_status = 'A'";
        }
        $sql = "SELECT sp.spratnd_date, sp.spratnd_status, sp.spratnd_div, sp.spratnd_std, sp.spratnd_acdm_year, s.SPRIDEN_student_id, CONCAT(s.spriden_surname, ' ', s.spriden_student_name, ' ', s.spriden_father_name) AS student_name FROM spratnd sp JOIN spriden s ON sp.spratnd_student_id = s.id WHERE sp.spratnd_acdm_year = '$acdm' AND sp.spratnd_div = '$div' AND sp.spratnd_std = '$std' AND sp.spratnd_date = '$selectedDate' $statusCondition";
        return $this->db->query($sql)->result_array();
    }

    function DateData()
    {
        $date = $this->input->get('date');
        $teacher_id = $this->input->get('teacher_id');
        $query = $this->db->query("select SPATESD_std,SPATESD_div,SPATESD_academic_year from spatesd where SPATESD_teacher_id = '$teacher_id'")->row_array();
        $year = $query['SPATESD_academic_year'];
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $sql = "SELECT sp.spratnd_date,sp.spratnd_status,sp.spratnd_div,sp.spratnd_std,
           sp.spratnd_acdm_year,s.SPRIDEN_student_id, concat(s.spriden_surname,' ',s.spriden_student_name,' ',s.spriden_father_name ) AS student_name FROM spratnd sp, spriden s WHERE sp.spratnd_acdm_year = '$year' AND sp.spratnd_div = '$div' AND sp.spratnd_std = '$std' AND sp.spratnd_date = '$date' AND sp.spratnd_student_id=s.id";
        return $this->db->query($sql)->result_array();
    }

    function daily_data()
    {
        $teacher_id = $this->input->get('teacher_id');
        $dt = $this->input->get('date');
        $date = $dt != null ? $dt : date('y-m-d');

        $academic_year = $this->db->query("select stvacdm_id from stvacdm WHERE '$date' BETWEEN stvacdm_start AND stvacdm_end")->row_array();
        $acdm = $academic_year['stvacdm_id'];

        // newly added 23 july
        $query = $this->db->query("select SPATESD_std,SPATESD_div,SPATESD_academic_year from spatesd where SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $acdm")->row_array();

        // end added 23 july
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
        $data = $this->input->get('type');

        $type = $data == 'All' ? '' : "and spratnd_status = '$data'";
        // newly added 23 july
        $sql = "SELECT sp.spratnd_date,sp.spratnd_status,sp.spratnd_div,sp.spratnd_std,
        sp.spratnd_acdm_year,s.SPRIDEN_student_id, concat(s.spriden_surname,' ',s.spriden_student_name,' ',s.spriden_father_name ) as student_name FROM spratnd sp, spriden s  WHERE sp.spratnd_acdm_year = '$acdm' and sp.spratnd_div = '$div' and sp.spratnd_std = '$std' and sp.spratnd_date = '$date' and sp.spratnd_student_id=s.id $type";
        
        // end added 23 july
        return $this->db->query($sql)->result_array();
    }
    
    // function monthwise()
    // {
    //     $teacher_id = $this->input->get('teacher_id');

    //     $query = $this->db->query("select SPATESD_std,SPATESD_div from spatesd where SPATESD_teacher_id = '$teacher_id'")->row_array();

    //     $div  = $query['SPATESD_div'];
    //     $std  = $query['SPATESD_std'];

    //     $Month = $this->input->get('month');

    //     $Monthwise = $Month != null ? $Month : date('m');

    //     $sql = "select MONTH(spratnd_date) Month,YEAR(spratnd_date)Year,
    //     (select count(*) from spratnd s
    //             WHERE spratnd_std = '$std'
    //               AND spratnd_div = '$div'
    //               AND MONTH(spratnd_date) = '$Monthwise'
    //               AND YEAR(spratnd_date) = '2023'
    //               AND spratnd_status in ('A'))Absent,
    //     (select count(*) from spratnd s
    //             WHERE spratnd_std = '$std'
    //               AND spratnd_div = '$div'
    //               AND MONTH(spratnd_date) = '$Monthwise'
    //               AND YEAR(spratnd_date) = '2023'
    //               AND spratnd_status in ('P'))Present,
    //     count(*) Total_stud from spratnd s
    //             WHERE spratnd_std = '$std'
    //               AND spratnd_div = '$div'
    //               AND MONTH(spratnd_date) = '$Monthwise'
    //               AND YEAR(spratnd_date) = '2023'
    //               AND spratnd_status in ('A','P')";

    //     return  $this->db->query($sql)->result_array();
    // }

    function report_db($month, $year, $teacher_id)
    {
        // $Monthwise = $this->input->get('month');

        // $month = $Monthwise != null ? $Monthwise : date('m');
        // $sql = "select distinct month(`spratnd_date`) as month, concat(SPRIDEN_surname, ' ',SPRIDEN_student_name , ' ' ,SPRIDEN_father_name) as name  ,`spratnd_student_id`, count(`spratnd_status`) total_days,
        // (select count(`spratnd_status`) from spratnd where month(spratnd_date) = '06' and year(spratnd_date)=2023 and `spratnd_student_id`  = atn.spratnd_student_id and spratnd_status = 'P')total_present,
        // (select count(`spratnd_status`) from spratnd where month(spratnd_date) = '06' and year(spratnd_date)=2023 and `spratnd_student_id`  = atn.spratnd_student_id and spratnd_status = 'A')total_absent
        // from spratnd atn ,spriden s where month(spratnd_date) = '$month' and year(spratnd_date)=2023 
        // and s.id = atn.`spratnd_student_id`
        // group by month(`spratnd_date`), `spratnd_student_id`";

        // Calculate the academic year based on the month and year
        $academic_year = $this->db->query("SELECT stvacdm_id FROM stvacdm WHERE '$year-$month-01' BETWEEN stvacdm_start AND stvacdm_end")->row_array();
        $acdm = $academic_year['stvacdm_id'];

        $query = $this->db->query("select SPATESD_std,SPATESD_div from spatesd where SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $acdm")->row_array();

        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];

        $sql = "SELECT 
                    DISTINCT MONTH(spratnd_date) AS month, $year AS year,
                    CONCAT(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name, 
                    SPRIDEN_student_id, 
                    COUNT(spratnd_status) AS total_days,
                    (SELECT COUNT(spratnd_status) FROM spratnd WHERE MONTH(spratnd_date) = '$month' AND YEAR(spratnd_date) = '$year' AND spratnd_student_id = atn.spratnd_student_id AND spratnd_status = 'P') AS total_present,
                    (SELECT COUNT(spratnd_status) FROM spratnd WHERE MONTH(spratnd_date) = '$month' AND YEAR(spratnd_date) = '$year' AND spratnd_student_id = atn.spratnd_student_id AND spratnd_status = 'A') AS total_absent
                FROM spratnd atn
                JOIN spriden s ON s.id = atn.spratnd_student_id
                WHERE MONTH(spratnd_date) = '$month' AND YEAR(spratnd_date) = '$year' AND
                spratnd_std='$std' and spratnd_div='$div' and spratnd_acdm_year= $acdm and spratnd_teacher_id='$teacher_id'
                GROUP BY month, spratnd_student_id";
        
        return  $this->db->query($sql)->result_array();
    }
    
    public function getSingleStudentDetails($student_id, $month, $year, $type, $teacher_id) {
        $academic_year = $this->db->query("SELECT stvacdm_id FROM stvacdm WHERE '$year-$month-01' BETWEEN stvacdm_start AND stvacdm_end")->row_array();
        $acdm = $academic_year['stvacdm_id'];
    
        $query = $this->db->query("SELECT SPATESD_std, SPATESD_div FROM spatesd WHERE SPATESD_teacher_id = '$teacher_id' AND SPATESD_academic_year = $acdm")->row_array();
    
        if (!$query) {
            return [];
        }
        $div  = $query['SPATESD_div'];
        $std  = $query['SPATESD_std'];
    
        // Filter the attendance type based on the parameter
        $attendance_status = $type == 'P' ? 'P' : 'A';
    
        // Construct the query to get daily attendance details
        $this->db->select('atn.spratnd_date, s.SPRIDEN_student_id, atn.spratnd_std, atn.spratnd_div, atn.spratnd_status, 
                           CONCAT(s.SPRIDEN_surname, " ", s.SPRIDEN_student_name, " ", s.SPRIDEN_father_name) AS student_name');
        $this->db->from('spratnd atn');
        $this->db->join('spriden s', 's.id = atn.spratnd_student_id', 'left');
        $this->db->where('s.SPRIDEN_student_id', $student_id);
        $this->db->where('MONTH(atn.spratnd_date)', $month);
        $this->db->where('YEAR(atn.spratnd_date)', $year);
        $this->db->where('atn.spratnd_status', $attendance_status);
        $this->db->where('atn.spratnd_std', $std);
        $this->db->where('atn.spratnd_div',  $div);
        $this->db->where('atn.spratnd_acdm_year', $acdm);
    
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function teacher_fetch()
    {
        $sql = "select SPRIDEN_student_id,concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) as name
        from spriden where SPRIDEN_user_type = 'tc' and spriden_student_status = 'A' and SPRIDEN_student_id in (select distinct SPATESD_teacher_id from spatesd)";
        return $this->db->query($sql)->result_array();
    }
}
/* End of file attendanceDb_model.php */