<?php

class Report_card_model extends CI_Model
{

    function getAllClasses()
    {
        return $classes = $this->db->get('stvfees')->result_array();
    }

    function get_all_exams()
    {
        return $data = $this->db->get('stvexam')->result_array();
    }

    function get_all_academic_years()
    {
        return $this->db->select('stvacdm.*, spruser.*')
            ->from('stvacdm')
            ->join('spruser', 'stvacdm.stvacdm_added_by = spruser.spruser_id', 'left')
            ->get()->result_array();
    }

    function get_subject_by_std($std)
    {
        // Query Binder used for security
        $academic_year_row = $this->db->select('stvschool_acdm_year')->get('stvschool')->row_array();

        if (!$academic_year_row)
            return array();

        $academic_year = $academic_year_row['stvschool_acdm_year'];

        $this->db->where('stvsubj_std', $std);
        $this->db->where('stvsubj_acdm', $academic_year);
        return $this->db->get('stvsubj')->result_array();
    }

    public function get_report_card_data($class, $div, $exam, $academic_year)
    {
        // Input sanitization
        $class = $this->db->escape_str($class);
        $div = $this->db->escape_str($div);
        $exam = $this->db->escape_str($exam);
        $academic_year = (int) $academic_year;

        $subject_sql = "
            SELECT DISTINCT sub.stvsubj_subject
            FROM sprmark mrk
            JOIN stvsubj sub ON sub.stvsubj_id = mrk.sprmark_subject
            WHERE mrk.sprmark_class = ?
              AND mrk.sprmark_div = ?
              AND mrk.sprmark_exam_name = ?
              AND mrk.sprmark_exam_type = 'written'
              AND mrk.sprmark_academic_year = ?
            ORDER BY sub.stvsubj_subject
        ";

        $subjects = $this->db->query($subject_sql, array($class, $div, $exam, $academic_year))->result_array();

        if (empty($subjects)) {
            return array();
        }

        $pivot_columns = array();

        foreach ($subjects as $row) {
            $subject = $row['stvsubj_subject'];
            // Sanitize for SQL
            $subject_value = str_replace("'", "''", $subject);
            $subject_alias = str_replace("`", "``", $subject);

            $pivot_columns[] = "
                MAX(
                    CASE
                        WHEN sub.stvsubj_subject = '{$subject_value}'
                        THEN CONCAT(mrk.sprmark_mark, '/', mrk.sprmark_total_marks)
                    END
                ) AS `{$subject_alias}`";
        }

        $pivot_sql = "
            SELECT
                mrk.sprmark_student_id,
                s.id,
                CONCAT(
                    s.SPRIDEN_surname, ' ',
                    s.SPRIDEN_student_name, ' ',
                    s.SPRIDEN_father_name
                ) AS student_name,
                mrk.sprmark_exam_type AS exam_type,
                " . implode(",\n", $pivot_columns) . "
            FROM sprmark mrk
            JOIN spriden s ON s.id = mrk.sprmark_student_id
            JOIN stvsubj sub ON sub.stvsubj_id = mrk.sprmark_subject
            WHERE mrk.sprmark_class = ?
              AND mrk.sprmark_div = ?
              AND mrk.sprmark_exam_name = ?
              AND mrk.sprmark_exam_type = 'written'
              AND mrk.sprmark_academic_year = ?
            GROUP BY
                mrk.sprmark_student_id,
                s.id,
                CONCAT(
                    s.SPRIDEN_surname, ' ',
                    s.SPRIDEN_student_name, ' ',
                    s.SPRIDEN_father_name
                ),
                mrk.sprmark_exam_type
            ORDER BY student_name, exam_type
        ";

        return $this->db->query($pivot_sql, array($class, $div, $exam, $academic_year))->result_array();
    }

    function getMarks($student_id, $acdm, $class, $div)
    {
        $sql = "SELECT 
            sub.stvsubj_subject AS subject_name,
            MAX(CASE WHEN mrk.sprmark_exam_name = '3' THEN mrk.sprmark_mark END) AS sem1_marks,
            MAX(CASE WHEN mrk.sprmark_exam_name = '3' THEN mrk.sprmark_total_marks END) AS sem1_total,
            MAX(CASE WHEN mrk.sprmark_exam_name = '4' THEN mrk.sprmark_mark END) AS sem2_marks,
            MAX(CASE WHEN mrk.sprmark_exam_name = '4' THEN mrk.sprmark_total_marks END) AS sem2_total,
            MAX(CASE WHEN mrk.sprmark_exam_name = '7' THEN mrk.sprmark_mark END) AS prelim_marks,
            MAX(CASE WHEN mrk.sprmark_exam_name = '7' THEN mrk.sprmark_total_marks END) AS prelim_total,
            MAX(CASE WHEN mrk.sprmark_exam_name = '8' THEN mrk.sprmark_mark END) AS final_marks,
            MAX(CASE WHEN mrk.sprmark_exam_name = '8' THEN mrk.sprmark_total_marks END) AS final_total
        FROM stvsubj sub
        LEFT JOIN sprmark mrk ON sub.stvsubj_id = mrk.sprmark_subject
        WHERE mrk.sprmark_student_id = ? 
          AND mrk.sprmark_academic_year = ?
          AND mrk.sprmark_class = ?
          AND mrk.sprmark_div = ?
          AND mrk.sprmark_exam_type = 'written'
        GROUP BY sub.stvsubj_id, sub.stvsubj_subject";

        // ✅ FIX: Parameter order must match SQL ? placeholders
        // SQL order: student_id, academic_year, class, div
        $result = $this->db->query($sql, [$student_id, $acdm, $class, $div])->result_array();

        $grouped = [];

        foreach ($result as $row) {
            $sub_name = $row['subject_name'];
            if (empty($sub_name))
                continue;

            // ✅ FIX: Read from pivoted columns directly (no if/elseif needed)

            // --- Sem 1 ---
            $sem1_marks = $row['sem1_marks'];
            $sem1_total = $row['sem1_total'];
            $sem1_percent = ($sem1_total > 0 && $sem1_marks !== null)
                ? ($sem1_marks / $sem1_total) * 100 : 0;
            $sem1_grade = ($sem1_percent > 0) ? $this->calculateGrade($sem1_percent) : '-';

            // --- Sem 2 ---
            $sem2_marks = $row['sem2_marks'];
            $sem2_total = $row['sem2_total'];
            $sem2_percent = ($sem2_total > 0 && $sem2_marks !== null)
                ? ($sem2_marks / $sem2_total) * 100 : 0;
            $sem2_grade = ($sem2_percent > 0) ? $this->calculateGrade($sem2_percent) : '-';

            // --- Prelim ---
            $prelim_marks = $row['prelim_marks'];
            $prelim_total = $row['prelim_total'];
            $prelim_percent = ($prelim_total > 0 && $prelim_marks !== null)
                ? ($prelim_marks / $prelim_total) * 100 : 0;
            $prelim_grade = ($prelim_percent > 0) ? $this->calculateGrade($prelim_percent) : '-';

            // --- Final ---
            $final_marks = $row['final_marks'];
            $final_total = $row['final_total'];
            $final_percent = ($final_total > 0 && $final_marks !== null)
                ? ($final_marks / $final_total) * 100 : 0;
            $final_grade = ($final_percent > 0) ? $this->calculateGrade($final_percent) : '-';

            // --- Average (for 9-10 view) ---
            if ($sem1_percent > 0 && $sem2_percent > 0) {
                $average = ($sem1_percent + $sem2_percent) / 2;
            } else {
                $average = ($sem1_percent > 0) ? $sem1_percent : $sem2_percent;
            }

            $grouped[$sub_name] = [
                'subject_name' => $sub_name,
                // Sem 1
                'sem1_marks' => ($sem1_marks !== null) ? $sem1_marks : '-',
                'sem1_total' => ($sem1_total > 0) ? $sem1_total : '-',
                'sem1_grade' => $sem1_grade,
                'sem1_percent' => $sem1_percent,
                // Sem 2
                'sem2_marks' => ($sem2_marks !== null) ? $sem2_marks : '-',
                'sem2_total' => ($sem2_total > 0) ? $sem2_total : '-',
                'sem2_grade' => $sem2_grade,
                'sem2_percent' => $sem2_percent,
                // Prelim
                'prelim_marks' => ($prelim_marks !== null) ? $prelim_marks : '-',
                'prelim_total' => ($prelim_total > 0) ? $prelim_total : '-',
                'prelim_grade' => $prelim_grade,
                'prelim_percent' => $prelim_percent,
                // Final
                'final_marks' => ($final_marks !== null) ? $final_marks : '-',
                'final_total' => ($final_total > 0) ? $final_total : '-',
                'final_grade' => $final_grade,
                'final_percent' => $final_percent,
                // Average
                'average' => $average
            ];
        }

        return array_values($grouped);
    }

    private function calculateGrade($total)
    {
        if ($total >= 91)
            return 'A1';
        elseif ($total >= 81)
            return 'A2';
        elseif ($total >= 71)
            return 'B1';
        elseif ($total >= 61)
            return 'B2';
        elseif ($total >= 51)
            return 'C1';
        elseif ($total >= 41)
            return 'C2';
        elseif ($total >= 33)
            return 'D';
        elseif ($total >= 21)
            return 'E1';
        else
            return 'E2';
    }

    function getStudentData($id, $acdm)
    {
        $sql = "SELECT 
        s.id,
        s.spriden_gr_number,
        s.SPRIDEN_student_id,
        CONCAT(s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name,' ',s.SPRIDEN_surname) AS Full_name,
        s.SPRIDEN_birthdate,
        (SELECT STVCLAS_standard FROM stvclas WHERE STVCLAS_student_id = s.id AND STVCLAS_acdm = ?) AS STD,
        (SELECT STVCLAS_class FROM stvclas WHERE STVCLAS_student_id = s.id AND STVCLAS_acdm = ?) AS DIVISION,
        (SELECT STVCLAS_rollno FROM stvclas WHERE STVCLAS_student_id = s.id AND STVCLAS_acdm = ?) AS ROLL_NO
        FROM spriden s 
        WHERE s.id = ?";

        return $this->db->query($sql, [$acdm, $acdm, $acdm, $id])->row_array();
    }

    function getAcdmYear($acdm)
    {
        // FIX: SQL Injection fix using Query Binding
        $sql = "select stvacdm_year from stvacdm where stvacdm_id = ?";
        return $this->db->query($sql, array($acdm))->row_array();
    }

    public function getReportCardData($acdm, $class, $div)
    {
        // Using Query Binding (?) for security against SQL Injection
        $sql = "SELECT 
                st.STVCLAS_student_id s_id,
                st.STVCLAS_rollno,
                CONCAT(s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name, ' ', s.SPRIDEN_surname) AS full_name,
                -- Subject-wise total marks (oral + written)
                SUM(CASE WHEN subj.stvsubj_subject = 'Algebra' THEN marks.total_marks ELSE 0 END) AS Algebra,
                SUM(CASE WHEN subj.stvsubj_subject = 'English' THEN marks.total_marks ELSE 0 END) AS English,
                SUM(CASE WHEN subj.stvsubj_subject = 'Geography' THEN marks.total_marks ELSE 0 END) AS Geography,
                SUM(CASE WHEN subj.stvsubj_subject = 'Geometry' THEN marks.total_marks ELSE 0 END) AS Geometry,
                SUM(CASE WHEN subj.stvsubj_subject = 'Hindi' THEN marks.total_marks ELSE 0 END) AS Hindi,
                SUM(CASE WHEN subj.stvsubj_subject = 'History' THEN marks.total_marks ELSE 0 END) AS History,
                SUM(CASE WHEN subj.stvsubj_subject = 'Marathi' THEN marks.total_marks ELSE 0 END) AS Marathi,
                SUM(CASE WHEN subj.stvsubj_subject = 'Science 1' THEN marks.total_marks ELSE 0 END) AS Science_1,
                SUM(CASE WHEN subj.stvsubj_subject = 'Science 2' THEN marks.total_marks ELSE 0 END) AS Science_2
                -- Removed EVS 1 & 2 to match the Frontend Table and Total/600 logic
            FROM 
                spriden s
            JOIN 
                stvclas st ON st.STVCLAS_student_id = s.id
            LEFT JOIN (
                -- Distinct oral + written per subject per student
                SELECT 
                    sprmark_student_id,
                    sprmark_subject,
                    SUM(sprmark_mark) AS total_marks
                FROM (
                    SELECT 
                        sprmark_student_id,
                        sprmark_subject,
                        sprmark_exam_type,
                        MAX(sprmark_mark) AS sprmark_mark
                    FROM 
                        sprmark
                    WHERE 
                        sprmark_class = ?
                        AND sprmark_div = ?
                        AND sprmark_academic_year = ?
                        AND sprmark_exam_name = 4
                        AND sprmark_exam_type IN ('oral', 'written')
                    GROUP BY 
                        sprmark_student_id, sprmark_subject, sprmark_exam_type
                ) AS distinct_marks
                GROUP BY 
                    sprmark_student_id, sprmark_subject
            ) AS marks ON marks.sprmark_student_id = s.id

            LEFT JOIN stvsubj subj 
                ON subj.stvsubj_id = marks.sprmark_subject AND subj.stvsubj_acdm = ?

            WHERE 
                st.STVCLAS_standard = ?
                AND st.STVCLAS_class = ?
                AND st.STVCLAS_acdm = ?
                AND s.spriden_student_status = 'A'

            GROUP BY 
                st.STVCLAS_rollno, s.id
            ORDER BY 
                st.STVCLAS_rollno";

        // Pass parameters in order: class, div, acdm (from subquery), acdm (from join), class, div, acdm (from where)
        return $this->db->query($sql, array($class, $div, $acdm, $acdm, $class, $div, $acdm))->result_array();
    }
}
?>