<?php
class M_lesson_model extends CI_Model
{
    public function get_academic_years()
    {
        return $this->db->get('stvacdm')->result();
    }

    public function get_current_academic_year()
    {
        return $this->db->get('stvschool')->row()->stvschool_acdm_year;
    }

    public function get_subjects($class, $academic_year_id)
    {
        $sql = "SELECT stvsubj_id AS id, stvsubj_subject AS name 
            FROM stvsubj 
            WHERE stvsubj_acdm = ? AND stvsubj_std = ?";
        return $this->db->query($sql, [$academic_year_id, $class])->result_array();
    }

    public function deleteLesson($lesson_id)
    {
        return $this->db->delete('lessons', ['id' => $lesson_id]);
    }

    public function save_lessons($data)
    {
        // Delete previous entries
        $this->db->where([
            'academic_year' => $data['academic_year'],
            'class' => $data['class'],
            'subject' => $data['subject']
        ])->delete('lessons');

        // Insert new
        $lesson_nos = $data['lesson_no'];
        $lesson_names = $data['lesson_name'];
        $types = $data['type'];

        for ($i = 0; $i < count($lesson_nos); $i++) {
            $this->db->insert('lessons', [
                'academic_year' => $data['academic_year'],
                'class' => $data['class'],
                'subject' => $data['subject'],
                'lesson_no' => $lesson_nos[$i],
                'lesson_name' => $lesson_names[$i],
                'type' => $types[$i]
            ]);
        }
    }

    public function fetch_lessons($academic_year, $class, $subject)
    {
        $this->db->select('id, class, type, subject, lesson_no, lesson_name');
        $this->db->from('lessons');
        $this->db->where('academic_year', $academic_year);
        $this->db->where('class', $class);
        $this->db->where('subject', $subject);
        return $this->db->get()->result_array();
    }

    public function update_lesson($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('lessons', $data);
    }

    public function delete_lesson($id)
    {
        return $this->db->delete('lessons', array('id' => $id));
    }

    public function get_lesson_by_id($id)
    {
        return $this->db->get_where('lessons', array('id' => $id))->row_array();
    }

    public function fetch_lessonss($academic_year)
    {
        $sql = "SELECT 
                    l.id,
                    l.class,
                    l.type,
                    l.subject,
                    s.stvsubj_subject AS subject_name,
                    l.lesson_no,
                    l.lesson_name
                FROM lessons l
                LEFT JOIN stvsubj s ON s.stvsubj_id = l.subject
                WHERE l.academic_year = ?";

        return $this->db->query($sql, [$academic_year])->result_array();
    }
}