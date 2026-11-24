<?php

class Profile_model extends CI_model

{

	function getAcademicYears($mode = 'all')
	{
		$data['all'] = $this->db->get('stvacdm')->result_array();
		$acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$data['current'] = $acdm['stvschool_acdm_year'];

		if ($mode == 'all')
			return $data;
		else return $data['current'];
	}

	function get_teacher_data($std, $div)

	{

		return $this->db->query("select d.SPATESD_teacher_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
		(select mobile from stvtcnf where teacher_id = d.SPATESD_teacher_id limit 1) mobile,
		(select whatsapp from stvtcnf where teacher_id = d.SPATESD_teacher_id limit 1) whatsapp
		from spatesd d, spriden s where d.SPATESD_std = '$std' and d.SPATESD_div = '$div' and d.SPATESD_academic_year = (select stvschool_acdm_year from stvschool)
		and s.SPRIDEN_student_id = d.SPATESD_teacher_id")->row_array();
	}

	function get_allsubjects($std, $acdm)

	{

		$this->db->where('stvsubj_acdm', $acdm);
		$this->db->where('stvsubj_std', $std);

		return $this->db->get('stvsubj')->result_array();

		// $sql = "SELECT * FROM stvsubj WHERE stvsubj_std = '$std' and stvsubj_subject IN ('English','Science', 'Maths','English Grammer','Algebra','ScienceI','ScienceII','Geometry','Hindi','Marathi','History','Geography','Grammer','Islamic Studies','Computer','EVS 1','EVS 2','Learning')";

		// $data = $this->db->query($sql);

		// return $data->result_array();
	}



	function updateVisiting()

	{

		$all_data = $this->db->get('visiting')->result_array();

		$status_array = array();

		for ($i = 0; $i < count($all_data); $i++) {

			if ($all_data[$i]['visiting_student_fees_paid'] == 0) {

				$vi_data = array(

					'visiting_fees_status_of' => "Unpaid"

				);

				$this->db->where('visiting_id', $all_data[$i]['visiting_id']);

				$status_array[$i] = $this->db->update('visiting', $vi_data);
			}
		}

		return $status_array;
	}



	function get_attendance_data_by_month($id, $month)

	{

		$this->db->where('SPRATND_student_id', $id);

		$this->db->where('MONTH(SPRATND_date)', $month);

		$this->db->where('YEAR(SPRATND_date)', date('Y'));

		return $this->db->get('spratnd')->result_array();
	}

	function fetchStudentMarks()
	{
		$exam = $this->input->post('exam');
		$sub = $this->input->post('sub');
		$student_id = $this->input->post('student_id');
		$acdm = $this->getAcademicYears('current');

		$sql = "SELECT sb.stvsubj_subject,m.sprmark_total_marks,m.sprmark_mark, m.sprmark_exam_type, m.sprmark_individual_exam,
					e.stvexam_name, m.sprmark_class, m.sprmark_div
					FROM sprmark m
					JOIN stvexam e ON e.stvexam_id = m.sprmark_exam_name
					JOIN stvclas c ON c.STVCLAS_student_id = m.sprmark_student_id
					JOIN stvsubj sb ON sb.stvsubj_id = m.sprmark_subject
					WHERE m.sprmark_student_id = $student_id
					AND m.sprmark_exam_name = $exam
					AND m.sprmark_academic_year = $acdm
					AND m.sprmark_class = c.STVCLAS_standard
					AND m.sprmark_div = c.STVCLAS_class
					AND c.STVCLAS_acdm = $acdm";
		if ($sub != '')
			$sql .= " AND m.sprmark_subject = $sub ";
		$sql .= " ORDER BY e.stvexam_name,m.sprmark_exam_type, sb.stvsubj_subject";

		return $this->db->query($sql)->result_array();
	}


	function get_all_exam()

	{

		return $data = $this->db->get('stvexam')->result_array();
	}





	function getAllMarksBySubject($id)

	{

		$this->load->model('Student_model');

		$academic_year = $this->Student_model->getCurrentAcademicYear();

		// $sql = "select concat (spriden.SPRIDEN_surname, ' ' , spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name, stvexam.* , stvsubj.* , sprmark.* from spriden, stvsubj, sprmark,stvexam where sprmark.sprmark_student_id = '" . $id . "' and sprmark.sprmark_student_id = spriden.id and sprmark.sprmark_exam_name = stvexam.stvexam_id and sprmark.sprmark_subject = stvsubj.stvsubj_id and sprmark.sprmark_subject = '" . $this->input->post('subname') . "' and sprmark.sprmark_academic_year = $academic_year";
		$sql = "select concat (spriden.SPRIDEN_surname, ' ' , spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name, stvexam.* , stvsubj.* , 
		stamrks.* from spriden, stvsubj, stamrks,stvexam where stamrks.student_id = '" . $id . "' and stamrks.student_id = spriden.id 
		and stamrks.exam_id = stvexam.stvexam_id and stamrks.subject_id = stvsubj.stvsubj_id and stamrks.subject_id = '" . $this->input->post('subname') . "' 
		and stamrks.academic_year = $academic_year;";

		$data = $this->db->query($sql);

		return $data->result_array();
	}
}
