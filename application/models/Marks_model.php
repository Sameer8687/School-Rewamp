<?php
use Mpdf\Tag\Input;
class Marks_model extends CI_model
{
    function student_marks($year, $class, $division, $exam, $subject)
    {
        $sql = "SELECT s.id as st_id,s.spriden_student_id, concat(s.spriden_surname, ' ', s.spriden_student_name, ' ', s.spriden_father_name) name,
        c.STVCLAS_standard, c.STVCLAS_class,
        ifnull((select ifnull(id,0) id from stamrks where student_id = s.id and academic_year = $year and class='$class'
        and division = '$division' and exam_id = $exam and subject_id = $subject),0) id,
         ifnull((select marks from stamrks where student_id = s.id and academic_year = $year and class='$class'
        and division = '$division' and exam_id = $exam and subject_id = $subject),0) marks
        from spriden s, stvclas c
        where s.id = c.STVCLAS_student_id
        and s.SPRIDEN_user_type = 'st'
        and s.spriden_student_status = 'A'
        and c.STVCLAS_standard = '$class'
        and c.STVCLAS_class = '$division'
        and c.STVCLAS_acdm = $year";
        $data = $this->db->query($sql)->result_array();
        return $data;
    }

    function exam_setup($exam_setup)
    {
        $this->db->where('subject', $exam_setup);
        return $this->db->get('exam_setup')->result_array();
    }

    function insert_marks()
    {
        $year = $this->input->post('year');
        $class = $this->input->post('class');
        $division = $this->input->post('division');
        $exam = $this->input->post('exam');
        $max_rows = $this->input->post('row_max');
        $created_at = date('m/d/Y h:i:s', time());
        $subject = $this->input->post('subject');
        $hidden_id = $this->input->post('id');
     
        for ($i = 1; $i <= $max_rows; $i++) {

            $marks = $this->input->post('marks' . $i);
            $student_id = $this->input->post('student_id' . $i);
            $__id = $this->input->post('id' . $i);

            $this->db->where('id', $__id);
            $stamrks = $this->db->get('stamrks')->row_array();

            if ($stamrks != null) {
                $this->db->query("UPDATE stamrks SET marks = $marks WHERE id= $__id");
            } else {
                if ($this->db->query("INSERT INTO stamrks (id,student_id, academic_year, class, division, exam_id, marks,subject_id,created_at) VALUES ('$hidden_id','$student_id', '$year', '$class', '$division', '$exam', '$marks','$subject', '$created_at ');")) {
                    // echo 'Data Inserted'; 

                }
            }
        }
        redirect(base_url('index.php/Marks/Student_marks'));
    }

    function fetch_stvsub()
    {
        return $this->db->get('stvsubj')->result_array();
    }

    function get_marks($id, $exam)
    {
        $sql = "select s.student_id,s.marks, e.exam,e.Total_marks,e.passing_marks,
        sb.stvsubj_subject 
        from stamrks s , exam_setup e, stvsubj sb
        where student_id = $id and exam_id = $exam
        and e.subject = sb.stvsubj_id
        and e.exam = s.exam_id and e.subject = s.subject_id";
        return $this->db->query($sql)->result_array();
    }
    function get_student_name($id)
    {
        $q = $this->db->query("SELECT s.id as st_id,s.spriden_student_id, concat(s.spriden_surname, ' ', s.spriden_student_name, ' ', s.spriden_father_name) name,
        c.STVCLAS_standard, c.STVCLAS_class
        from spriden s, stvclas c
        where s.id = c.STVCLAS_student_id
        and s.SPRIDEN_user_type = 'st'
        and s.spriden_student_status = 'A' and s.id = $id;");
        return $q->result_array();
    }


    function getSubjectByClassForMarksheet($class, $exam_id, $sid, $acdm = 5)
    {
        // return $this->db->where('stvsubj_std', $class)->get('stvsubj')->result_array();
        $sql = "select s.stvsubj_id, s.stvsubj_subject,
        ifnull((select Total_marks from exam_setup where exam = $exam_id and class = '$class' and subject = s.stvsubj_id),0) total_marks,
        ifnull((select passing_marks from exam_setup where exam = $exam_id and class = '$class' and subject = s.stvsubj_id),0) passing_marks,
        ifnull((select marks from stamrks where exam_id = $exam_id and class = '$class' and subject_id = s.stvsubj_id and academic_year = $acdm and student_id = $sid),0) marks
        from stvsubj s 
        where s.stvsubj_std = '$class' order by 1";
        return $this->db->query($sql)->result_array();
    }

    function quizResult($qid, $stid)
    {
        $quizDB = $this->load->database('QuizDB', TRUE);
        $sql = "SELECT s.SPRIDEN_student_id as student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name) as name,
        q.class, q.division, q.quiz_name, concat(r.marks_scored,' / ',r.total_questions) as score
         from spriden s, quiz q, result r where s.id= '$stid' and q.quiz_id=$qid and r.quiz_id = q.quiz_id";
        return $quizDB->query($sql)->row_array();
    }

    function quizreview($qid, $sid)
    {
        $quizDB = $this->load->database('QuizDB', TRUE);
        $questions = $quizDB->query("SELECT DISTINCT questions_id, question_no,  question_text from questions where quizNo = $qid order by questions_id")->result_array();
        $quiz_data = [];
        foreach ($questions as $que) {
            $que_num = $que['question_no'];
            $que_pan['id'] = $que['questions_id'];
            $que_pan['no'] = $que['question_no'];
            $que_pan['text'] = $que['question_text'];
            $que_pan['options'] = $quizDB->query("SELECT o.option_id, o.question_no, o.quiznumber, o.is_correct, o.optionText,
            (select option_selected from result_line where result_header_id in (select r.result_id from result r where r.quiz_id = o.quiznumber and r.student_id = $sid) and student_id = $sid and quiz_id = o.quiznumber and question_id = o.question_no)
             selected_option
            FROM `options` o WHERE o.`question_no` = $que_num and o.`quiznumber` = $qid order by o.`option_id`")->result_array();
            $quiz_data[] = $que_pan;
        }
        return $quiz_data;
    }
}