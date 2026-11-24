<?php
class Lesson_model extends CI_Model
{
	function getCurrentAcademicYear()
	{
		$acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		return $acdm['stvschool_acdm_year'];
	}

	function all_lessons($class, $sub, $div)
	{
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$sql = "select * from stvlmst where stvlmst_std = '$class' and stvlmst_div = '$div' and stvlmst_sub = $sub and stvlmst_lesson_status_created_by = '$teacher_id' order by 1 desc";
		return $this->db->query($sql)->result_array();
	}

	function change_status()
	{
		$header_id = $this->input->post('id');
		$type = $this->input->post('type');
		$value = $this->input->post('value');
		if ($type == 'teaching') {
			$colomn_name = "stvlmst_teached";
			$colomn_date = "stvlmst_teached_at";
		} else {
			$colomn_name = "stvlmst_notes";
			$colomn_date = "stvlmst_notes_at";
		}
		$data = array(
			$colomn_name => $value,
			$colomn_date => date('Y-m-d')
		);
		$this->db->where('stvlmst_id', $header_id);
		$status = $this->db->update('stvlmst', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function add_review()
	{
		$review = $this->input->post('lesson_review');
		$header_id = $this->input->post('hidden_header_id');
		$data = array(
			'stvlmst_review' => $review,
			'stvlmst_review_on	' => date('Y-m-d')
		);

		$this->db->where('stvlmst_id', $header_id);
		$status = $this->db->update('stvlmst', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	// function lesson_data($id)
	// {
	// 	$this->db->select('
    //     stvlmst.stvlmst_id, stvlmst.stvlmst_std, stvlmst.stvlmst_div, stvlmst.stvlmst_sub, 
    //     stvlmst.stvlmst_lesson, stvlmst.stvlmst_created_at, stvlmst.stvlmst_updated_at,
    //     stvlmst.stvlmst_teached, stvlmst.stvlmst_teached_at, stvlmst.stvlmst_notes,
    //     stvlmst.stvlmst_start_date, stvlmst.stvlmst_end_date, stvlmst.stvlmst_working_days,
    //     stvlmst.stvlmst_exam_status, stvlmst.stvlmst_lesson_status_created_by,
    //     stvlmst.stvlmst_notes_status_updated_by, stvlmst.stvlmst_notes_at,
    //     stvlmst.stvlmst_teacher_comment, stvlmst.stvlmst_teacher_comment_on,
    //     stvlmst.stvlmst_review, stvlmst.stvlmst_review_on, stvlmst.academic_year,
    //     lesson.lesson_id, lesson.standard, lesson.division, lesson.subject_id, lesson.chapter,
    //     lesson.lesson, lesson.lesson_pdf, lesson.quiz_pdf, lesson.question_pdf,
    //     lesson.worksheet_pdf, lesson.pre_request, lesson.created_at as lesson_created_at,
    //     lesson.updated_at as lesson_updated_at');
	// 	$this->db->from('stvlmst');
	// 	$this->db->join('lesson', 'stvlmst.stvlmst_id = lesson.lesson_id', 'left');
	// 	$this->db->where('stvlmst.stvlmst_id', $id);
		
	// 	return $this->db->get()->row_array();
	// }

	function lesson_data($id)
	{
		$sql = "SELECT 
    stvlmst.stvlmst_id, 
    stvlmst.stvlmst_std, 
    stvlmst.stvlmst_div, 
    stvlmst.stvlmst_sub, 
    stvlmst.stvlmst_lesson, 
    stvlmst.stvlmst_created_at, 
    stvlmst.stvlmst_updated_at,
    stvlmst.stvlmst_teached, 
    stvlmst.stvlmst_teached_at, 
    stvlmst.stvlmst_notes,
    stvlmst.stvlmst_start_date, 
    stvlmst.stvlmst_end_date, 
    stvlmst.stvlmst_working_days,
    stvlmst.stvlmst_exam_status, 
    stvlmst.stvlmst_lesson_status_created_by,
    stvlmst.stvlmst_notes_status_updated_by, 
    stvlmst.stvlmst_notes_at,
    stvlmst.stvlmst_teacher_comment, 
    stvlmst.stvlmst_teacher_comment_on,
    stvlmst.stvlmst_review, 
    stvlmst.stvlmst_review_on, 
    stvlmst.academic_year,
    lesson.lesson_id, 
    lesson.standard, 
    lesson.division, 
    lesson.subject_id, 
    lesson.chapter,
    lesson.lesson, 
    lesson.lesson_pdf, 
    lesson.quiz_pdf, 
    lesson.question_pdf,
    lesson.worksheet_pdf, 
    lesson.pre_request, 
    lesson.created_at as lesson_created_at,
    lesson.updated_at as lesson_updated_at
FROM 
    stvlmst
LEFT JOIN 
    lesson ON stvlmst.stvlmst_lesson = lesson.lesson
WHERE 
    stvlmst.stvlmst_id = '$id'";
	
		return $this->db->query($sql)->row_array();
	}

	function add_lesson_comment_data()
	{
		$header_id = $this->input->post('id');
		$comment = $this->input->post('comment');
		$data = array(
			'stvlmst_teacher_comment' => $comment,
			'stvlmst_teacher_comment_on' => date('Y-m-d')
		);
		$this->db->where('stvlmst_id', $header_id);
		$status = $this->db->update('stvlmst', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	// public function add_data()
	// {
	// 	// print_r($this->input->post());
	// 	// die;
	// 	$crrent_academic_year = $this->getCurrentAcademicYear();
	// 	$header_id = $this->input->post('hidden_header_id');
	// 	$this->db->where('stvlmst_id', $header_id);
	// 	$check_exist = $this->db->get('stvlmst')->row_array();
	// 	// Input values
	// 	$std = $this->input->post('class');
	// 	$sub = $this->input->post('subject');
	// 	$div = $this->input->post('division');
	// 	$lesson_name = $this->input->post('lesson_name');
	// 	$chapter = $this->input->post('chapter');
	// 	$teaching = $this->input->post('teaching');
	// 	$notes = $this->input->post('notes');
	// 	$start_date = $this->input->post('start_date');
	// 	$end_date = $this->input->post('end_date');
	// 	$teacher_comment = $this->input->post('lesson_remark');
	// 	$acdm = $crrent_academic_year;
	// 	$today = date('Y-m-d');
	// 	$user = $this->session->userdata('oliveMobileSystemUser');
	// 	// Upload files
	// 	$lesson_pdf = $this->upload_pdf('lesson_pdf');
	// 	$quiz_excel = $this->upload_pdf('quiz_pdf'); // Make sure this is an Excel file
	// 	$question_pdf = $this->upload_pdf('question_pdf');
	// 	$worksheet_pdf = $this->upload_pdf('worksheet_pdf');
	// 	$pre_req_pdf = $this->upload_pdf('pre_req_pdf');
	// 	$learning_pdf = $this->upload_pdf('learning_pdf');
	// 	// stvlmst data
	// 	$mst_data = [
	// 		'stvlmst_std' => $std,
	// 		'stvlmst_sub' => $sub,
	// 		'stvlmst_div' => $div,
	// 		'stvlmst_lesson' => $lesson_name,
	// 		'stvlmst_teached' => $teaching,
	// 		'stvlmst_notes' => $notes,
	// 		'stvlmst_teached_at' => $today,
	// 		'stvlmst_teacher_comment' => $teacher_comment,
	// 		'stvlmst_teacher_comment_on' => $today,
	// 		'stvlmst_start_date' => $start_date,
	// 		'stvlmst_end_date' => $end_date,
	// 		'stvlmst_created_at' => $today,
	// 		'stvlmst_lesson_status_created_by' => $user,
	// 		'academic_year' => $acdm,
	// 	];

	// 	// lesson table data
	// 	$lesson_data = [
	// 		'standard' => $std,
	// 		'division' => $div,
	// 		'subject_id' => $sub,
	// 		'chapter' => $chapter,
	// 		'lesson' => $lesson_name,
	// 		'updated_at' => $today,
	// 	];

	// 	if ($lesson_pdf)
	// 		$lesson_data['lesson_pdf'] = $lesson_pdf;
	// 	if ($quiz_excel)
	// 		$lesson_data['quiz_pdf'] = $quiz_excel;
	// 	if ($question_pdf)
	// 		$lesson_data['question_pdf'] = $question_pdf;
	// 	if ($worksheet_pdf)
	// 		$lesson_data['worksheet_pdf'] = $worksheet_pdf;
	// 	if ($pre_req_pdf)
	// 		$lesson_data['pre_request'] = $pre_req_pdf;
	// 	if ($learning_pdf)
	// 		$lesson_data['learning_outcome'] = $learning_pdf;

	// 	if ($check_exist == null) {
	// 		$this->db->insert('stvlmst', $mst_data);
	// 		$lesson_data['created_at'] = $today;
	// 		$this->db->insert('lesson', $lesson_data);
	// 	} else {
	// 		$this->db->where('stvlmst_id', $header_id);
	// 		$this->db->update('stvlmst', $mst_data);

	// 		$this->db->where([
	// 			'standard' => $std,
	// 			'division' => $div,
	// 			'subject_id' => $sub,
	// 			'lesson' => $lesson_name
	// 		]);
	// 		$lesson_exist = $this->db->get('lesson')->row_array();

	// 		if ($lesson_exist) {
	// 			// Delete old files if new uploaded

	// 			$file_fields = [
	// 				'lesson_pdf' => $lesson_pdf,
	// 				'quiz_pdf' => $quiz_excel,
	// 				'question_pdf' => $question_pdf,
	// 				'worksheet_pdf' => $worksheet_pdf,
	// 				'pre_request' => $pre_req_pdf,
	// 				'learning_outcome' => $learning_pdf
	// 			];

	// 			foreach ($file_fields as $field => $new_file) {
	// 				if ($new_file && !empty($lesson_exist[$field]) && file_exists($lesson_exist[$field])) {
	// 					unlink($lesson_exist[$field]);
	// 				}
	// 			}

	// 			$this->db->where('lesson_id', $lesson_exist['lesson_id']);
	// 			$this->db->update('lesson', $lesson_data);
	// 		} else {
	// 			$lesson_data['created_at'] = $today;
	// 			$this->db->insert('lesson', $lesson_data);
	// 		}
	// 	}

	// 	// Import quiz only if a valid Excel was uploaded
	// 	if ($quiz_excel) {
	// 		$this->import_quiz_csv($quiz_excel, $sub, $chapter, $lesson_name, $std);
	// 	}

	// 	return true;
	// }

	public function add_data()
{
    $crrent_academic_year = $this->getCurrentAcademicYear();
    $header_id = $this->input->post('hidden_header_id');
    
    // Input values
    $std = $this->input->post('class');
    $sub = $this->input->post('subject');
    $div = $this->input->post('division');
    $lesson_name = $this->input->post('lesson_name');
    $chapter = $this->input->post('chapter');
    $teaching = $this->input->post('teaching');
    $notes = $this->input->post('notes');
    $exam_status = $this->input->post('exam_status');
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');
    $teacher_comment = $this->input->post('lesson_remark');
    $acdm = $crrent_academic_year;
    $today = date('Y-m-d');
    $user = $this->session->userdata('oliveMobileSystemUser');

    // Upload files
    $lesson_pdf = $this->upload_pdf('lesson_pdf');
    $quiz_excel = $this->upload_pdf('quiz_pdf');
    $question_pdf = $this->upload_pdf('question_pdf');
    $worksheet_pdf = $this->upload_pdf('worksheet_pdf');
    $pre_req_pdf = $this->upload_pdf('pre_req_pdf');
    $learning_pdf = $this->upload_pdf('learning_pdf');

    // Check if this is an update or insert
    if (!empty($header_id)) {
        // UPDATE MODE - Delete old data and insert new
        
        // 1. First delete from stvlmst
        $this->db->where('stvlmst_id', $header_id);
        $this->db->delete('stvlmst');
        
        // 2. Delete from lesson table
        $this->db->where([
            'standard' => $std,
            'division' => $div,
            'subject_id' => $sub,
            'lesson' => $lesson_name
        ]);
        $this->db->delete('lesson');
        
        // 3. Delete related quiz data
        $this->db->where([
            'std' => $std,
            'sub_id' => $sub,
            'lesson' => $lesson_name
        ]);
        $this->db->delete('stvquiz');
        
        // Now insert fresh data
        $mst_data = [
            'stvlmst_std' => $std,
            'stvlmst_sub' => $sub,
            'stvlmst_div' => $div,
            'stvlmst_lesson' => $lesson_name,
            'stvlmst_teached' => $teaching,
            'stvlmst_notes' => $notes,
            'stvlmst_exam_status' => $exam_status,
            'stvlmst_teached_at' => $today,
            'stvlmst_teacher_comment' => $teacher_comment,
            'stvlmst_teacher_comment_on' => $today,
            'stvlmst_start_date' => $start_date,
            'stvlmst_end_date' => $end_date,
            'stvlmst_created_at' => $today,
            'stvlmst_lesson_status_created_by' => $user,
            'academic_year' => $acdm,
        ];
        
        $this->db->insert('stvlmst', $mst_data);
        $new_stvlmst_id = $this->db->insert_id();

        // Insert into lesson table
        $lesson_data = [
            'standard' => $std,
            'division' => $div,
            'subject_id' => $sub,
            'chapter' => $chapter,
            'lesson' => $lesson_name,
            'created_at' => $today,
            'updated_at' => $today,
        ];

        if ($lesson_pdf) $lesson_data['lesson_pdf'] = $lesson_pdf;
        if ($quiz_excel) $lesson_data['quiz_pdf'] = $quiz_excel;
        if ($question_pdf) $lesson_data['question_pdf'] = $question_pdf;
        if ($worksheet_pdf) $lesson_data['worksheet_pdf'] = $worksheet_pdf;
        if ($pre_req_pdf) $lesson_data['pre_request'] = $pre_req_pdf;
        if ($learning_pdf) $lesson_data['learning_outcome'] = $learning_pdf;

        $this->db->insert('lesson', $lesson_data);

        // Import quiz if Excel file uploaded
        if ($quiz_excel) {
            $this->import_quiz_csv($quiz_excel, $sub, $chapter, $lesson_name, $std);
        }

    } else {
        // INSERT MODE - Original insert logic
        $mst_data = [
            'stvlmst_std' => $std,
            'stvlmst_sub' => $sub,
            'stvlmst_div' => $div,
            'stvlmst_lesson' => $lesson_name,
            'stvlmst_teached' => $teaching,
            'stvlmst_notes' => $notes,
            'stvlmst_exam_status' => $exam_status,
            'stvlmst_teached_at' => $today,
            'stvlmst_teacher_comment' => $teacher_comment,
            'stvlmst_teacher_comment_on' => $today,
            'stvlmst_start_date' => $start_date,
            'stvlmst_end_date' => $end_date,
            'stvlmst_created_at' => $today,
            'stvlmst_lesson_status_created_by' => $user,
            'academic_year' => $acdm,
        ];

        $this->db->insert('stvlmst', $mst_data);

        $lesson_data = [
            'standard' => $std,
            'division' => $div,
            'subject_id' => $sub,
            'chapter' => $chapter,
            'lesson' => $lesson_name,
            'created_at' => $today,
            'updated_at' => $today,
        ];

        if ($lesson_pdf) $lesson_data['lesson_pdf'] = $lesson_pdf;
        if ($quiz_excel) $lesson_data['quiz_pdf'] = $quiz_excel;
        if ($question_pdf) $lesson_data['question_pdf'] = $question_pdf;
        if ($worksheet_pdf) $lesson_data['worksheet_pdf'] = $worksheet_pdf;
        if ($pre_req_pdf) $lesson_data['pre_request'] = $pre_req_pdf;
        if ($learning_pdf) $lesson_data['learning_outcome'] = $learning_pdf;

        $this->db->insert('lesson', $lesson_data);

        if ($quiz_excel) {
            $this->import_quiz_csv($quiz_excel, $sub, $chapter, $lesson_name, $std);
        }
    }

    return true;
}	
	// Upload PDF function

	private function upload_pdf($field_name)
	{
		if (!empty($_FILES[$field_name]['name'])) {
			$ext = pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION);

			$config['upload_path'] = './uploads/lessons/';
			$config['allowed_types'] = 'pdf|csv|xlsx|xls|doc|docx|ppt|pptx|png|jpg|jpeg|gif';
			$config['max_size'] = 51200; // 50MB
			$config['file_name'] = time() . '_' . $field_name . '.' . $ext;

			if (!is_dir($config['upload_path'])) {
				mkdir($config['upload_path'], 0777, true);
			}

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload($field_name)) {
				$upload_data = $this->upload->data();
				return 'uploads/lessons/' . $upload_data['file_name'];
			}
		}
		return null;
	}

	// Import quiz from Excel file
	private function import_quiz_csv($file_path, $sub_id, $chapter, $lesson, $std)
	{
		if (($handle = fopen($file_path, 'r')) !== FALSE) {
			// Static data (sirf ek baar banate hain)
			$base_data = [
				'sub_id' => $sub_id,
				'chapter' => $chapter,
				'lesson' => $lesson,
				'std' => $std
			];

			fgetcsv($handle);
			while (($data = fgetcsv($handle, 1000)) !== FALSE) {
				if (count($data) >= 6) {
					$question = trim($data[0]);
					$option_a = trim($data[1]);
					$option_b = trim($data[2]);
					$option_c = trim($data[3]);
					$option_d = trim($data[4]);
					$correct_answer = trim($data[5]);

					if (!empty($question)) {
						// Base + dynamic data combine karke insert karenge
						$quiz_data = array_merge($base_data, [
							'question' => $question,
							'option_a' => $option_a,
							'option_b' => $option_b,
							'option_c' => $option_c,
							'option_d' => $option_d,
							'correct_answer' => $correct_answer
						]);
						// check data
						// print_r($quiz_data);
						// die;
						$this->db->insert('stvquiz', $quiz_data);
					}
				} else {
					log_message('error', 'Skipping row due to insufficient columns: ' . implode(',', $data));
				}
			}
			fclose($handle);
		} else {
			log_message('error', 'Unable to open CSV file: ' . $file_path);
		}
		return true;
	}

	private function showlesson()
	{
		$sql = "SELECT 
            spriden.SPRIDEN_student_id, 
            CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name,
            stvlmst.stvlmst_std,
            stvsubj.stvsubj_subject,
            stvlmst.stvlmst_start_date,
            stvlmst.stvlmst_id
        FROM spriden
        JOIN stvlmst ON spriden.SPRIDEN_student_id = stvlmst.stvlmst_lesson_status_created_by
        JOIN stvsubj ON stvlmst.stvlmst_sub = stvsubj.stvsubj_id";
		return $this->db->query($sql)->result_array();
	}

	// changes made here
	function all_lessons_for_sup()
	{
		$date = date('Y-m-d');
		$data['all_lessons'] = $this->db->query("select spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) name,
		stvlmst.stvlmst_std,
		stvsubj.stvsubj_subject,
		stvlmst.stvlmst_start_date,
		stvlmst.stvlmst_id
		from spriden, stvlmst, stvsubj
		where spriden.SPRIDEN_student_id = stvlmst.stvlmst_lesson_status_created_by
		and stvlmst.stvlmst_sub = stvsubj.stvsubj_id")->result_array();
		$data['daily_lessons'] = $this->db->query("select spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) name,
		stvlmst.stvlmst_std,
		stvsubj.stvsubj_subject,
		stvlmst.stvlmst_start_date,
		stvlmst.stvlmst_id
		from spriden, stvlmst, stvsubj
		where spriden.SPRIDEN_student_id = stvlmst.stvlmst_lesson_status_created_by
		and stvlmst.stvlmst_sub = stvsubj.stvsubj_id
		and stvlmst.stvlmst_created_at = '$date'")->result_array();
		return $data;
	}

	public function fetchChapters($standard, $subject)
	{
		$sql = "SELECT id, academic_year, class,type, subject, lesson_no, lesson_name, created_at, created_by, updated_at, updated_by FROM lessons WHERE type='LMS' AND class= '$standard' AND subject='$subject'";
		return $this->db->query($sql)->result_array();
	}

	public function fetchLessons($standard, $subject)
	{
		$sql = "SELECT id, academic_year, class,type, `subject`, lesson_no, lesson_name, created_at, created_by, updated_at, updated_by FROM lessons WHERE type='LMS' AND class= '$standard' AND `subject`='$subject'";
		return $this->db->query($sql)->result_array();
	}

	function getAllclass($tid)
	{

		return $this->db->get('stvfees')->result_array();
	}

	function get_allocated_classs($userID)
	{

		$this->db->where('SPATESD_teacher_id', $userID);

		return $this->db->get('spatesd')->row_array();
	}

	public function getAllSubjectsByStd($std, $acdm_year)
	{
		$sql = "SELECT stvsubj_id, stvsubj_subject FROM stvsubj WHERE stvsubj_std = '$std' AND stvsubj_acdm = '$acdm_year'";
		return $this->db->query($sql)->result_array();

	}

}