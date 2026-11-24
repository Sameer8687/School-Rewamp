<?php
class Hall_ticket_model extends CI_Model
{
    function getAllExams()
    {
        $sql = "SELECT stvexam_id,stvexam_name FROM stvexam";
        return $this->db->query($sql)->result_array();
    }

    function getSubject($std, $acdm = null)
    {
        if ($acdm == null) {
            $acdm_year = $this->db->query("select max(stvacdm_id) acdm from stvacdm")->row_array();
            $acdm_year = $acdm_year['acdm'];
        } else
            $acdm_year = $acdm;


        $sql = "SELECT stvsubj_id,stvsubj_subject FROM stvsubj where stvsubj_std = '$std' and stvsubj_acdm='$acdm'";

        return $this->db->query($sql)->result_array();
    }

    function fetchSubjectsList($std)
    {
        return $this->db->query("select * from stvsubj where stvsubj_std = '$std' and stvsubj_acdm = (select stvschool_acdm_year from stvschool)")->result_array();
    }


    function getSSubject($std)
    {
        $sql = "SELECT stvsubj_id,stvsubj_subject FROM stvsubj where stvsubj_std = '$std' and stvsubj_acdm=(select max(stvacdm_id) from stvacdm)";
        return $this->db->query($sql)->result_array();
    }

    function fetchAllRecords()
    {
        $acdm = $this->db->query("select max(stvacdm_id) acdm from stvacdm")->row_array();
        $acdm = $acdm['acdm'];
        return $this->db->query("SELECT ht.stvhtkt_id, ht.stvhtkt_class, ht.stvhtkt_div, ht.stvhtkt_exam, ht.stvhtkt_subject, ht.stvhtkt_date,
        ht.stvhtkt_time_start, ht.stvhtkt_time_end, ht.stvhtkt_type,(select stvacdm_year from stvacdm where stvacdm_id=ht.stvhtkt_acdm)stvhtkt_acdm, ht.stvhtkt_added_by,
        sbj.stvsubj_subject, ex.stvexam_name
        FROM stvhtkt ht, stvsubj sbj,stvexam ex
        WHERE sbj.stvsubj_id = ht.stvhtkt_subject and sbj.stvsubj_acdm = ht.stvhtkt_acdm
        and ht.stvhtkt_acdm = $acdm and ex.stvexam_id= ht.stvhtkt_exam order by 1 desc")->result_array();
    }

    function fetchTimeTableOfExam($std, $exam, $acdm = null)
    {
        if ($acdm == null) {
            $acdm_detail = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
            $acdm = $acdm_detail['stvschool_acdm_year'];
        }

        // $sql = "SELECT distinct hh.stvhtkt_id, hl.stvhtkt_date, concat(hl.stvhtkt_time_start, '-', hl.stvhtkt_time_end) as time,hl.stvhtkt_type, sub.stvsubj_subject
        //     FROM stvhtkt_header hh
        //     JOIN stvhtkt_line hl ON hh.stvhtkt_id = hl.stvhtkt_form_id
        //     JOIN stvsubj sub ON sub.stvsubj_id = hl.stvhtkt_subject
        //     WHERE hh.stvhtkt_acdm = $acdm AND hh.stvhtkt_class = '$std' AND hh.stvhtkt_exam = $exam
        //     ORDER BY hl.stvhtkt_date asc";
        // ---------------
        // $sql = "SELECT DISTINCT hh.stvhtkt_id, hl.stvhtkt_date, 
        // CONCAT(hl.stvhtkt_time_start, '-', hl.stvhtkt_time_end) AS time,
        // hl.stvhtkt_type, sub.stvsubj_subject
        // FROM stvhtkt_header hh
        // JOIN stvhtkt_line hl ON hh.stvhtkt_id = hl.stvhtkt_form_id
        // JOIN stvsubj sub ON sub.stvsubj_id = hl.stvhtkt_subject
        // WHERE hh.stvhtkt_acdm = $acdm 
        // AND hh.stvhtkt_class = '$std' 
        // AND hh.stvhtkt_exam = $exam
        // ORDER BY STR_TO_DATE(hl.stvhtkt_date, '%Y-%m-%d') ASC";
        // ---------------
        $sql = "SELECT DISTINCT 
    hh.stvhtkt_id, 
    hl.stvhtkt_date, 
    CONCAT(hl.stvhtkt_time_start, '-', hl.stvhtkt_time_end) AS time, 
    hl.stvhtkt_type, 
    sub.stvsubj_subject 
FROM stvhtkt_header hh 
JOIN stvhtkt_line hl ON hh.stvhtkt_id = hl.stvhtkt_form_id 
JOIN stvsubj sub ON sub.stvsubj_id = hl.stvhtkt_subject 
WHERE hh.stvhtkt_acdm = $acdm 
    AND hh.stvhtkt_class = '$std' 
    AND hh.stvhtkt_exam = $exam 
ORDER BY hl.stvhtkt_date ASC;
        
        
        
        
        ";
        return $this->db->query($sql)->result_array();
    }



    function getData_Old($std, $div, $exam, $fees, $rte, $student_id = null, $date = null)
    {
        $acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
        $acdm = $acdm['stvschool_acdm_year'] ?? '-';

        $sql = "SELECT
            s.id,
            s.SPRIDEN_student_id, cls.STVCLAS_standard, cls.STVCLAS_class,
            CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS student_name,
            SUM(f.sprfees_amount) AS total_paid
           ,(select sprfees_amount from sprfees where sprfees_pk  = (select max(sprfees_pk) from sprfees where sprfees_studentid = s.id AND academic_year = $acdm)) as
        last_paid,
        (select sprfees_feesdate from sprfees where sprfees_pk  = (select max(sprfees_pk) from sprfees where sprfees_studentid = s.id AND academic_year = $acdm)) as 
        last_date
                FROM
                    spriden s
                INNER JOIN
                    sprfees f
                ON
                    s.id = f.sprfees_studentid
                    LEFT JOIN stvclas cls ON cls.STVCLAS_student_id = s.id AND cls.STVCLAS_acdm = '$acdm'
                    ";

        // If student_id is not null, apply the filter for that student
        if ($student_id != null) {
            $sql .= " WHERE s.id = $student_id";
        } else {
            // Else, construct the query based on class and division filters
            $inner_where = false;
            $sql .= " WHERE s.id IN (
                        SELECT DISTINCT c.STVCLAS_student_id
                        FROM stvclas c
                        JOIN spriden sp ON sp.id = c.STVCLAS_student_id ";
            if ($std != '') {
                $sql .= $inner_where ? " AND " : "WHERE ";
                $sql .= " c.STVCLAS_standard = '$std'";
                $inner_where = true;
            }
            if ($div != '') {
                $sql .= $inner_where ? " AND " : "WHERE ";
                $sql .= " c.STVCLAS_class = '$div'";
                $inner_where = true;
            }

            $sql .= $inner_where ? " AND " : " WHERE ";
            $sql .= "c.STVCLAS_acdm = '$acdm'
                        AND sp.SPRIDEN_user_type = 'st'
                        AND sp.SPRIDEN_student_status = 'A'";

            // Add additional condition for RTE status if applicable
            if ($rte == 'yes') {
                $sql .= $inner_where ? " AND " : " WHERE ";
                $sql .= " sp.rte_status = 'Yes'";
            }

            $sql .= " )";
        }
        if ($date != '') {
            $sql .= " AND f.sprfees_feesdate = '$date' ";
        }
        // Apply academic year filter
        $sql .= " AND f.academic_year = $acdm";

        // Group by necessary fields
        $sql .= " GROUP BY s.id, s.SPRIDEN_student_id, CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name)";

        // If RTE is not 'yes', apply the fee and date filter in the HAVING clause
        if ($rte != 'yes' && $fees != '') {
            $sql .= " HAVING SUM(f.sprfees_amount) >= $fees";

            // Apply the date condition if a date is provided
            // if (isset($date) && $date != '') {
            //     $sql .= " OR (MAX(f.sprfees_feesdate) > '$date' AND SUM(f.sprfees_amount) > 1000)"; 
            // }
        }


        // die($sql);
        return $this->db->query($sql)->result_array();
    }
    function getData($std, $div, $exam, $fees, $rte, $student_id = null, $date = null, $mode = null)
    {
        $acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
        $acdm = $acdm['stvschool_acdm_year'] ?? '-';

        $sql = "SELECT
        s.id,
        s.SPRIDEN_student_id, 
        cls.STVCLAS_standard, 
        cls.STVCLAS_class,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS student_name,";

        // Handle RTE condition
        if ($rte != 'yes') {
            $sql .= " SUM(f.sprfees_amount) AS total_paid,
            SUM(f.sprfees_exumption) AS total_exumpted,
            (SUM(f.sprfees_amount) + SUM(f.sprfees_exumption)) AS sum_total_paid,
                        (SELECT 
                            sprfees_amount 
                        FROM 
                            sprfees 
                        WHERE 
                            sprfees_pk = (SELECT 
                                                MAX(sprfees_pk) 
                                            FROM 
                                                sprfees 
                                            WHERE 
                                                sprfees_studentid = s.id 
                                                    AND academic_year = '$acdm')) AS last_paid,
                        (SELECT 
                            sprfees_feesdate 
                        FROM 
                            sprfees 
                        WHERE 
                            sprfees_pk = (SELECT 
                                                MAX(sprfees_pk) 
                                            FROM 
                                                sprfees 
                                            WHERE 
                                                sprfees_studentid = s.id AND academic_year = '$acdm')) AS last_date";
        } else {
            $sql .= " 0 AS total_paid, 0 AS last_paid, NULL AS last_date";
        }

        $sql .= " FROM 
                    spriden s
                LEFT JOIN 
                    sprfees f ON s.id = f.sprfees_studentid
                LEFT JOIN 
                    stvclas cls ON cls.STVCLAS_student_id = s.id AND cls.STVCLAS_acdm = '$acdm'";

        // If student_id is not null, apply the filter for that student
        if ($student_id != null) {
            $sql .= " WHERE s.id = $student_id";
        } else {
            // Else, construct the query based on class and division filters
            $inner_where = false;
            $sql .= " WHERE s.id IN (
                    SELECT DISTINCT c.STVCLAS_student_id
                    FROM stvclas c
                    JOIN spriden sp ON sp.id = c.STVCLAS_student_id 
                    WHERE c.STVCLAS_acdm = '$acdm'
                    AND sp.SPRIDEN_user_type = 'st'
                    AND sp.SPRIDEN_student_status = 'A'";
            $inner_where = true;
            // Apply standard filter
            if ($std != '') {
                $sql .= $inner_where ? " AND " : "WHERE ";
                $sql .= "  c.STVCLAS_standard = '$std'";
                $inner_where = true;
            }

            // Apply division filter
            if ($div != '') {
                $sql .= $inner_where ? " AND " : "WHERE ";
                $sql .= "  c.STVCLAS_class = '$div'";
                $inner_where = true;
            }

            // Add additional condition for RTE status if applicable
            if ($rte == 'yes') {
                $sql .= $inner_where ? " AND " : "WHERE ";
                $sql .= "  sp.rte_status = 'Yes'";
                $inner_where = true;
            }

            $sql .= ")";
        }

        // Apply date filter
        if ($date != '' && $rte != 'yes') {
            $sql .= $inner_where ? " AND " : "WHERE ";
            $sql .= "  f.sprfees_feesdate = '$date'";
            $inner_where = true;
        }

        // Apply academic year filter
        if ($rte != 'yes') {
            $sql .= $inner_where ? " AND " : "WHERE ";
            $sql .= "  f.academic_year = '$acdm'";
            $inner_where = true;
        }

        // Group by necessary fields
        $sql .= " GROUP BY s.id, s.SPRIDEN_student_id, cls.STVCLAS_standard, cls.STVCLAS_class, CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name)";

        // If RTE is not 'yes', apply the fee and date filter in the HAVING clause
        if ($rte != 'yes' && $fees != '') {

            $sql .= " HAVING (SUM(f.sprfees_amount) + SUM(f.sprfees_exumption)) >= $fees";

        }

        return $this->db->query($sql)->result_array();
    }

    function getDataOfHallTicketByID($exam, $student_list)
    {
        $acdm_detail = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
        $acdm = $acdm_detail['stvschool_acdm_year'];
        $sql = "SELECT s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ',s.SPRIDEN_father_name) as name,
        c.STVCLAS_standard, c.STVCLAS_class
        FROM spriden s 
        LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = $acdm
        WHERE s.id IN ($student_list)";
        $students = $this->db->query($sql)->result_array();
        $hall_tickets = [];
        foreach ($students as $student) {
            $hall_ticket = $student;
            $student_std = $student['STVCLAS_standard'] ?? '-';
            $student_div = $student['STVCLAS_class'] ?? '-';
            $hall_ticket['hallticket'] = $this->fetchTimeTableOfExam($student_std, $exam, $acdm);
            $hall_tickets[] = $hall_ticket;
        }
        return $hall_tickets;
    }


    function fetchData($std = null, $div = null, $exam = null, $acdm = null)
    {
        $sql = "SELECT
                h.stvhtkt_id,
                h.stvhtkt_hidden_id,
                h.stvhtkt_class,
                h.stvhtkt_div,
                a.stvacdm_year,
                e.stvexam_name
            FROM 
                stvhtkt_header h
            JOIN 
                stvacdm a ON a.stvacdm_id = h.stvhtkt_acdm
            JOIN 
                stvexam e ON e.stvexam_id = h.stvhtkt_exam
            ";

        if ($std != '') {
            $sql .= " AND h.stvhtkt_class = '$std'";
        }

        if ($div != '') {
            $sql .= " AND h.stvhtkt_div = '$div'";
        }

        if ($exam != '') {
            $sql .= " AND h.stvhtkt_exam = '$exam'";
        }

        if ($acdm != '') {
            $sql .= " AND h.stvhtkt_acdm = '$acdm'";
        }

        // die($sql);

        return $this->db->query($sql)->result_array();
    }

    // function fetchDataForHallTicketReport($acdm = null)
    // {
    //     if ($acdm == null) {
    //         $acdm_detail = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
    //         $acdm = $acdm_detail['stvschool_acdm_year'];
    //     }
    //     $sql = "SELECT ht.stvhtkt_id, ht.stvhtkt_class, ht.shift, ex.stvexam_name, u.spruser_name
    //     FROM stvhtkt_header ht
    //     LEFT JOIN stvexam ex ON ex.stvexam_id = ht.stvhtkt_exam
    //     LEFT JOIN spruser u ON u.spruser_id = ht.stvhtkt_created_by
    //     WHERE ht.stvhtkt_acdm = $acdm
    //     ORDER BY ht.stvhtkt_id desc";
    //     return $this->db->query($sql)->result_array();
    // }

    public function fetchDataForHallTicketReport($acdm = null, $class = null, $div = null, $exam = null)
    {
        if (empty($acdm)) {
            $acdm_detail = $this->db->select('stvschool_acdm_year')
                ->from('stvschool')
                ->get()
                ->row_array();
            $acdm = $acdm_detail['stvschool_acdm_year'] ?? '';
        }

        $this->db->select('ht.stvhtkt_id, ht.stvhtkt_class, ht.shift, ex.stvexam_name, u.spruser_name');
        $this->db->from('stvhtkt_header ht');
        $this->db->join('stvexam ex', 'ex.stvexam_id = ht.stvhtkt_exam', 'left');
        $this->db->join('spruser u', 'u.spruser_id = ht.stvhtkt_created_by', 'left');
        $this->db->where('ht.stvhtkt_acdm', $acdm);

        if (!empty($class))
            $this->db->where('ht.stvhtkt_class', $class);
        if (!empty($div))
            $this->db->where('ht.stvhtkt_div', $div);
        if (!empty($exam))
            $this->db->where('ht.stvhtkt_exam', $exam);

        $this->db->order_by('ht.stvhtkt_id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function add_data()
    {

        // check if the exam is already created for the same academic year, exam and class

        $data = $this->input->post();
        $mode = $status = '';
        $current = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
        $acdm = $current['stvschool_acdm_year'];

        // Get POST data
        $id = $data['header_id'];
        $exist = $this->db->query("SELECT * FROM stvhtkt_header WHERE stvhtkt_id = ?", array($id))->row_array();
        $__id = $data['header_id'];
        if (empty($exist)) {
            $exam_created = $this->db->query("select * from stvhtkt_header where stvhtkt_class = '" . $data['class'] . "' and stvhtkt_exam = '" . $data['exam'] . "' and stvhtkt_acdm = '$acdm'")->row_array();
            if (!empty($exam_created)) {
                echo json_encode(array(
                    'header_id' => $exam_created['stvhtkt_id'],
                    'status' => 'exist',
                    'mode' => 'redirect',
                    'url' => base_url() . "hall_ticket?__id=" . $exam_created['stvhtkt_id']
                ));
                exit(0);
            }
            // Prepare header data
            $header_data = array(
                'stvhtkt_class' => $data['class'],
                'stvhtkt_exam' => $data['exam'],
                'shift' => $data['shift'],
                'stvhtkt_acdm' => $acdm,
                'stvhtkt_created_by' => 1,
                'stvhtkt_created_at' => date('Y-m-d')
            );
            $mode = 'inserted';
            // Insert header data
            $action = $this->db->insert('stvhtkt_header', $header_data);
            $status = $action ? 'success' : 'fail';
            $__id = $this->db->insert_id();
        } else {
            // Update header data
            $header_data = array(
                'stvhtkt_class' => $data['class'],
                'stvhtkt_exam' => $data['exam'],
                'shift' => $data['shift'],
                'stvhtkt_updated_by' => 1,
                'stvhtkt_updated_at' => date('Y-m-d')
            );
            $mode = 'updated';
            $this->db->where('stvhtkt_id', $__id);
            $action = $this->db->update('stvhtkt_header', $header_data);
            $status = $action ? 'success' : 'fail';
        }

        if ($status == 'success') {
            // Delete existing line records
            $this->db->where('stvhtkt_form_id', $__id);
            $this->db->delete('stvhtkt_line');
            // Add Lines 
            $subjects = $data['subject'];
            $exam_dates = $data['exam_date'];
            $time_starts = $data['time_start'];
            $time_ends = $data['time_end'];
            $types = $data['type'];
            $total = count($subjects);
            for ($i = 0; $i < $total; $i++) {
                $line_data = array(
                    'stvhtkt_form_id' => $__id,
                    'stvhtkt_line_id' => $i + 1,
                    'stvhtkt_subject' => $subjects[$i],
                    'stvhtkt_date' => $exam_dates[$i],
                    'stvhtkt_time_start' => $time_starts[$i],
                    'stvhtkt_time_end' => $time_ends[$i],
                    'stvhtkt_type' => $types[$i]
                );
                // Insert new record
                $this->db->insert('stvhtkt_line', $line_data);
            }
        }
        echo json_encode(array(
            'header_id' => $__id,
            'status' => $status,
            'mode' => $mode
        ));
    }

    function fetchRow_lines($class, $exam, $div, $acdm)
    {
        $sql = "select * from stvhtkt_header where stvhtkt_class = '$class' and stvhtkt_div = '$div' and stvhtkt_exam = '$exam' and stvhtkt_acdm = '$acdm'";
        return $this->db->query($sql)->row_array();
    }

    function getline($id)
    {
        $sql = "select * from stvhtkt_line where stvhtkt_form_id = '$id'";
        return $this->db->query($sql)->result_array();
    }
    function getheader($id)
    {
        $sql = "select * from stvhtkt_header where stvhtkt_id = '$id'";
        return $this->db->query($sql)->row_array();
    }
}
