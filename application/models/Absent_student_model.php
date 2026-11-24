<?php defined('BASEPATH') or exit('No direct script access allowed');
class Absent_student_model extends CI_Model
{
    private function calculate_streaks($dates)
    {
        if (empty($dates))
            return [];
        usort($dates, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });
        $streaks = [];
        $current_streak = [];
        foreach ($dates as $i => $date) {
            if (empty($current_streak)) {
                $current_streak[] = $date;
            } else {
                $prev_date = end($current_streak);
                $prev_timestamp = strtotime($prev_date);
                $current_timestamp = strtotime($date);
                $diff_days = ($current_timestamp - $prev_timestamp) / (60 * 60 * 24);
                if ($diff_days == 1) {
                    $current_streak[] = $date;
                } else {
                    if (count($current_streak) >= 2) {
                        $streaks[] = [
                            'days' => count($current_streak),
                            'dates' => $current_streak
                        ];
                    }
                    $current_streak = [$date];
                }
            }
        }
        if (count($current_streak) >= 2) {
            $streaks[] = [
                'days' => count($current_streak),
                'dates' => $current_streak
            ];
        }
        return $streaks;
    }

    public function getCurrentAcademicYear()
    {
        $sql = "SELECT stvschool_acdm_year FROM stvschool";
        return $this->db->query($sql)->row_array();
    }

    public function getSelectedAcademicYear()
    {
        $data = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
        return $data['stvschool_acdm_year'] ?? 0;
    }

    public function get_absent_students($std, $div, $year)
    {
        $sql = "SELECT s.id,s.SPRIDEN_student_id,CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS student_name,c.STVCLAS_standard,c.STVCLAS_class,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 1 THEN 1 END) AS jan,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 2 THEN 1 END) AS feb,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 3 THEN 1 END) AS mar,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 4 THEN 1 END) AS apr,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 5 THEN 1 END) AS may,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 6 THEN 1 END) AS jun,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 7 THEN 1 END) AS jul,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 8 THEN 1 END) AS aug,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 9 THEN 1 END) AS sep,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 10 THEN 1 END) AS oct,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 11 THEN 1 END) AS nov,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 12 THEN 1 END) AS `dec`,
        COUNT(att.SPRATND_id) AS total_absent
        FROM spriden s
        JOIN stvclas c 
            ON c.STVCLAS_student_id = s.id
        LEFT JOIN spratnd att 
            ON att.SPRATND_student_id = s.id 
            AND att.SPRATND_acdm_year = c.STVCLAS_acdm 
            AND att.SPRATND_status = 'A'
        WHERE c.STVCLAS_acdm = ?
          AND c.STVCLAS_standard = ?
          AND c.STVCLAS_class = ?
          AND s.spriden_student_status = 'A'
        GROUP BY 
        s.id, s.SPRIDEN_student_id, student_name, c.STVCLAS_standard, c.STVCLAS_class
        ORDER BY student_name ASC";
        $students = $this->db->query($sql, [$year, $std, $div])->result_array();
        if (!empty($students)) {
            $this->db->select("SPRATND_student_id, SPRATND_date");
            $this->db->from("spratnd");
            $this->db->where("SPRATND_acdm_year", $year);
            $this->db->where("SPRATND_status", "A");
            $this->db->where_in("SPRATND_student_id", array_column($students, 'id'));
            $this->db->order_by("SPRATND_student_id, SPRATND_date", "ASC");
            $all_absent = $this->db->get()->result_array();
            $absent_by_student = [];
            foreach ($all_absent as $row) {
                $absent_by_student[$row['SPRATND_student_id']][] = $row['SPRATND_date'];
            }
            foreach ($students as &$student) {
                $dates = isset($absent_by_student[$student['id']]) ? $absent_by_student[$student['id']] : [];
                $student['continuous_absent'] = $this->calculate_streaks($dates);
            }
        }
        return $students;
    }

    public function get_all_academic_years()
    {
        return $this->db->select('stvacdm.stvacdm_id, stvacdm.stvacdm_year')
            ->from('stvacdm')
            ->order_by('stvacdm_year', 'DESC')
            ->get()
            ->result_array();
    }

    function daily_absent($id, $acdm, $month = null)
    {
        $this->db->select('SPRATND_date, COUNT(*) AS daily_absent');
        $this->db->from('spratnd');
        $this->db->where('SPRATND_student_id', $id);
        $this->db->where('SPRATND_acdm_year', $acdm);
        $this->db->where('SPRATND_status', 'A');
        if ($month) {
            $this->db->where('MONTH(SPRATND_date)', $month);
        }
        $this->db->group_by('SPRATND_date');
        $this->db->order_by('SPRATND_date', 'ASC');
        return $this->db->get()->result_array();
    }

    public function getDailyAbsent($id, $acdm_year, $month)
    {
        $sql = "SELECT SPRATND_date FROM spratnd WHERE SPRATND_student_id = '$id' AND SPRATND_acdm_year = $acdm_year AND MONTH(SPRATND_date) = $month AND SPRATND_status = 'A'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getDailyAbsentDates($student_id, $acdm_year)
    {
        $this->db->select('spratnd.SPRATND_date, stvpcnf.stvpcnf_whatsapp');
        $this->db->from('spratnd');
        $this->db->join('stvpcnf', 'stvpcnf.stvpcnf_student_id = spratnd.SPRATND_student_id', 'left');
        $this->db->where('spratnd.SPRATND_student_id', $student_id);
        $this->db->where('spratnd.SPRATND_acdm_year', $acdm_year);
        $this->db->where('spratnd.SPRATND_status', 'A');
        $this->db->order_by('spratnd.SPRATND_date', 'ASC');
        $result = $this->db->get()->result_array();
        $dates = array();
        foreach ($result as $row) {
            $dates[] = [
                'date' => $row['SPRATND_date'],
                'phone' => $row['stvpcnf_whatsapp']
            ];
        }
        return $dates;
    }
}