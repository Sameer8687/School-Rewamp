<?php
class Approval_model extends CI_Model
{
    public function get_academic_years()
    {
        return $this->db->get('stvacdm')->result();
    }

    public function get_current_academic_year()
    {
        return $this->db->get('stvschool')->row()->stvschool_acdm_year;
    }

    public function get_subjects_by_class_year($academic_year, $class)
    {
        $sql = "SELECT * FROM stvsubj WHERE stvsubj_acdm = '$academic_year' AND stvsubj_std = '$class';";
        return $this->db->query($sql)->result_array();
    }

    public function get_lessons_by_class_subject($class, $subject, $academic_year)
    {
        $sql = "SELECT id, lesson_name FROM lessons WHERE class = '$class' AND subject = '$subject' AND academic_year = '$academic_year'";
        return $this->db->query($sql)->result_array();
    }
    public function insert_lesson($data)
    {
        return $this->db->insert('lesson_type', $data);
    }

    public function get_lessons_by_filters($academic_year, $class, $subject)
    {
        $sql = "SELECT distinct lt.class, lt.id, s.stvsubj_id, s.stvsubj_subject AS subject_name, lt.lesson FROM lesson_type lt LEFT JOIN stvsubj s ON lt.subject = s.stvsubj_id WHERE s.stvsubj_acdm = '$academic_year' AND lt.class = '$class' AND lt.subject = '$subject' ORDER BY lt.id DESC";
        return $this->db->get($sql)->result_array();
    }

    public function get_filtered_lesson_data($class, $division, $subject, $lesson)
    {
        $sql = "SELECT * FROM lesson WHERE standard = '$class' AND division = '$division' AND subject_id = '$subject' AND lesson = '$lesson'";
        return $this->db->query($sql)->result_array();
    }

    public function update_status_column($lesson_id, $column_name, $column_value)
    {
        $this->db->where('lesson_id', $lesson_id);
        return $this->db->update('lesson', [$column_name => $column_value]);
    }
}