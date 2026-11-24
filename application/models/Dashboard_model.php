<?php
class Dashboard_model extends CI_model
{
	//Get Data For dashboard user 
	function get_dashboard_user($userID)
	{
		$this->db->where('SPRIDEN_student_id', $userID);
		return $this->db->get('spriden')->row_array();
	}

	//Function to get all allocated class
	function get_allocated_class($userID)
	{
		$this->db->where('SPATESD_teacher_id', $userID);
		return $this->db->get('spatesd')->result_array();
	}

	// function get_allocated_classs($userID)
	// {
	// 	$this->db->where('SPATESD_teacher_id', $userID);
	// 	return $this->db->get('spatesd')->row_array();
	// }

	function get_allocated_classs($userID)
	{
		// If a specific class was chosen at login, use that
		$session_std = $this->session->userdata('session_std');
		$session_div = $this->session->userdata('session_div');

		if (!empty($session_std) && !empty($session_div)) {
			$acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$acdm_id = $acdm ? $acdm['stvschool_acdm_year'] : null;

			$this->db->where('SPATESD_teacher_id', $userID);
			$this->db->where('SPATESD_std', $session_std);
			$this->db->where('SPATESD_div', $session_div);
			if ($acdm_id) {
				$this->db->where('SPATESD_academic_year', $acdm_id);
			}
			return $this->db->get('spatesd')->row_array();
		}

		// Fallback: old behaviour (first assigned class)
		$this->db->where('SPATESD_teacher_id', $userID);
		return $this->db->get('spatesd')->row_array();
	}

	//here
	function get_allocated_class_by_std_div($teacher_id, $std, $div)
	{
		$acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$acdm_id = $acdm ? $acdm['stvschool_acdm_year'] : null;

		$this->db->where('SPATESD_teacher_id', $teacher_id);
		$this->db->where('SPATESD_std', $std);
		$this->db->where('SPATESD_div', $div);
		if ($acdm_id) {
			$this->db->where('SPATESD_academic_year', $acdm_id);
		}
		return $this->db->get('spatesd')->row_array();
	}

	/**
	 * Returns the class the teacher is currently "working in".
	 * Priority: session_std/session_div (set at login) → first row in spatesd.
	 * Returns ['SPATESD_std' => ..., 'SPATESD_div' => ...] or null.
	 */
	private function getActiveClass($teacher_id)
	{
		$std = $this->session->userdata('session_std');
		$div = $this->session->userdata('session_div');

		if (!empty($std) && !empty($div)) {
			// Confirm teacher is actually assigned to this class
			$acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$acdm_id = $acdm ? $acdm['stvschool_acdm_year'] : null;

			$this->db->where('SPATESD_teacher_id', $teacher_id);
			$this->db->where('SPATESD_std', $std);
			$this->db->where('SPATESD_div', $div);
			if ($acdm_id) {
				$this->db->where('SPATESD_academic_year', $acdm_id);
			}
			$row = $this->db->get('spatesd')->row_array();

			if ($row) {
				return $row;
			}
		}

		// Fallback: first assignment in DB
		return $this->get_allocated_classs($teacher_id);
	}



	//Function to get all allocated class
	// function get_student_under_teacher($std, $div)
	// {
	// 	$sql = "select spriden.*, stvclas.* from spriden, stvclas where stvclas.STVCLAS_student_id = spriden.id and stvclas.STVCLAS_standard = '".$std."' and stvclas.STVCLAS_class = '".$div."'";
	// 	$data = $this->db->query($sql);
	// 	return $data->result_array();
	// }

	function save_contacts()
	{
		$max_rows = $this->input->post('max_rows');
		for ($i = 1; $i <= $max_rows; $i++) {
			$student_id = $this->input->post('id' . $i);
			$mobile = $this->input->post('mobile' . $i);
			$whatsapp = $this->input->post('whatsapp' . $i);

			$exist = $this->db->query("select * from stvpcnf where stvpcnf_student_id = $student_id")->row_array();
			if ($exist == null) {
				// insert data
				$data['stvpcnf_student_id'] = $student_id;
				$data['stvpcnf_parent_typ'] = 'Father';
				$data['stvpcnf_mobile'] = $mobile;
				$data['stvpcnf_whatsapp'] = $whatsapp;
				$data['stvpncf_date_added'] = date('Y-m-d');
				$this->db->insert('stvpcnf', $data);
			} else {
				// update data
				$data['stvpcnf_mobile'] = $mobile;
				$data['stvpcnf_whatsapp'] = $whatsapp;
				$this->db->where('stvpcnf_student_id', $student_id);
				$this->db->update('stvpcnf', $data);
			}
		}
		$this->session->set_flashdata('msg', array('msg' => 'Student Contacts Updated', 'class' => 'success'));
		redirect(base_url() . "dashboard/student_contacts");
	}

	function getStudentsForContacts($id)
	{
		$allocated_class = $this->getActiveClass($id);
		if ($allocated_class != null) {
			$std = $allocated_class['SPATESD_std'];
			$div = $allocated_class['SPATESD_div'];
		} else {
			$std = '-';
			$div = '-';
		}
		return $this->db->query("select spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name,
		stvclas.STVCLAS_code,stvclas.STVCLAS_student_id,stvclas.STVCLAS_rollno,stvclas.STVCLAS_standard,stvclas.STVCLAS_class,
		(select stvpcnf_mobile from stvpcnf where stvpcnf_student_id = spriden.id) mobile,
		(select stvpcnf_whatsapp from stvpcnf where stvpcnf_student_id = spriden.id) whatsapp
					from spriden, stvclas
					where stvclas.STVCLAS_student_id = spriden.id 
					and stvclas.STVCLAS_standard = '$std' and stvclas.STVCLAS_class = '$div' and spriden.spriden_student_status in ('A') 
					and stvclas.STVCLAS_acdm  in (select stvschool_acdm_year from stvschool) order by CAST(stvclas.STVCLAS_rollno AS SIGNED) ")->result_array();
	}

	function fetchWhatsappNumber($id)
	{
		return $this->db->query("select stvpcnf_whatsapp whatsapp from stvpcnf where stvpcnf_student_id = $id")->row_array();
	}

	function attendanceData($id, $month, $year)
	{
		$allocated_class = $this->getActiveClass($id);
		if ($allocated_class != null) {
			$std = $allocated_class['SPATESD_std'];
			$div = $allocated_class['SPATESD_div'];
		} else {
			$std = '-';
			$div = '-';
		}

		$sql = "select * from spratnd where MONTH(SPRATND_date) = '$month' and YEAR(SPRATND_date) = '$year'
		and SPRATND_student_id in (SELECT DISTINCT c.STVCLAS_student_id AS total_students
		FROM stvclas c
		JOIN spriden s ON s.id = c.STVCLAS_student_id
		WHERE c.STVCLAS_standard = '$std'
		  AND c.STVCLAS_class = '$div'
		  AND c.STVCLAS_acdm in (select stvschool_acdm_year from stvschool)
		  AND s.SPRIDEN_user_type = 'st'
		  AND s.SPRIDEN_student_status = 'A')
		";
		return $this->db->query($sql)->result_array();
	}


	function get_student_under_teacher($id)
	{
		if ($this->session->userdata('session_type') == "Random") {
			return $this->db->query("select spriden.*, stvclas.*, stvrndm.* from spriden, stvclas, stvrndm
		where stvclas.STVCLAS_student_id = spriden.id and spriden.id = stvrndm.stvrndm_student_id
		and stvrndm.stvrndm_teacher_id = '" . $id . "'")->result_array();
		} else {
			$allocated_class = $this->getActiveClass($id);
			if ($allocated_class != null) {
				$std = $allocated_class['SPATESD_std'];
				$div = $allocated_class['SPATESD_div'];
			} else {
				$std = '-';
				$div = '-';
			}
			// $academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
			// $academic_year = $academic_year['stvschool_acdm_year'];

			return $this->db->query("select spriden.*, stvclas.*, stvpcnf.stvpcnf_whatsapp 
			from spriden, stvclas , stvpcnf
			where stvclas.STVCLAS_student_id = spriden.id 
			and spriden.id = stvpcnf.stvpcnf_student_id and stvpcnf.stvpcnf_student_id = stvclas.STVCLAS_student_id
			and stvclas.STVCLAS_standard = '$std' and stvclas.STVCLAS_class = '$div' and spriden.spriden_student_status in ('A') 
			and stvclas.STVCLAS_acdm  in (select stvschool_acdm_year from stvschool) order by CAST(stvclas.STVCLAS_rollno AS SIGNED)")->result_array();
		}
	}

	function update_rollno($id)
	{
		$students = [];
		$total_students = $this->input->post('total_students');
		$action_date = date('Y-m-d');
		for ($i = 1; $i <= $total_students; $i++) {
			array_push($students, $this->input->post('line_sid' . $i));
			$roll_no = $this->input->post('roll_no' . $i);
			$line_id = $this->input->post('line_id' . $i);
			$sid = $this->input->post('line_sid' . $i);
			$this->db->query("update stvclas set STVCLAS_rollno= '$roll_no' where STVCLAS_code=$line_id and STVCLAS_student_id=$sid");
		}
		$this->teacher_activity($students, 'rollno', $action_date);
		return true;
	}

	//Function to get all allocated class
	function get_student_for_remark($rowID)
	{
		$sql = "select spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvclas.* from spriden, stvclas where stvclas.STVCLAS_student_id = spriden.id and stvclas.STVCLAS_code  = '" . $rowID . "'";
		$data = $this->db->query($sql);
		return $data->row_array();
	}

	function get_student_for_remark_data($rowID)
	{
		$sql = "select spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvclas.* from spriden, stvclas where stvclas.STVCLAS_student_id = spriden.id and stvclas.STVCLAS_code  = '" . $rowID . "'";
		$data = $this->db->query($sql);
		return $data->row_array();
	}

	function get_student_for_remark_by_modalbutton($rowID)
	{
		$sql = "select spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvclas.* from spriden, stvclas where stvclas.STVCLAS_student_id = spriden.id and stvclas.STVCLAS_student_id  = '" . $rowID . "'";
		$data = $this->db->query($sql);
		return $data->row_array();
	}

	//Function to get all allocated class
	function get_student_details_for_contact($rowID)
	{
		$sql = "select spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvclas.* from spriden, stvclas where stvclas.STVCLAS_student_id = spriden.id and stvclas.STVCLAS_code = '" . $rowID . "'";
		$data = $this->db->query($sql);
		return $data->row_array();
	}

	function get_student_details_for_contact_pcnf($rowID)
	{
		$sql = "select spriden.id, spriden.SPRIDEN_student_id, stvclas.*, stvpcnf.* from spriden, stvclas, stvpcnf where stvclas.STVCLAS_student_id = spriden.id and stvclas.STVCLAS_student_id = stvpcnf.stvpcnf_student_id and stvclas.STVCLAS_code = '" . $rowID . "'";
		$data = $this->db->query($sql);
		return $data->row_array();
	}

	//Fill Attendance
	// function student_data()
	// {
	// 	$date = $this->input->post('att_date');
	// 	$class = $this->input->post('class');
	// 	$div = $this->input->post('div');

	// 	$duplicate_records = $this->db->query("select SPRATND_date, SPRATND_student_id, COUNT(*) record_count
	// 	from spratnd 
	// 	where SPRATND_date = '$date' and SPRATND_student_id in (select distinct spriden.id
	// 				from spriden, stvclas
	// 				where stvclas.STVCLAS_student_id = spriden.id 
	// 				and stvclas.STVCLAS_standard = '$class' and stvclas.STVCLAS_class = '$div' and spriden.spriden_student_status in ('A') 
	// 				and stvclas.STVCLAS_acdm  in (select stvschool_acdm_year from stvschool) order by CAST(stvclas.STVCLAS_rollno AS SIGNED))
	// 	group by SPRATND_date, SPRATND_student_id
	// 	having record_count > 1")->result_array();

	// 	if ($duplicate_records != null) {
	// 		// delete records of the date 
	// 		$this->db->query("delete from spratnd where SPRATND_date = '$date' and SPRATND_student_id in (select distinct spriden.id
	// 		from spriden, stvclas
	// 		where stvclas.STVCLAS_student_id = spriden.id 
	// 		and stvclas.STVCLAS_standard = '$class' and stvclas.STVCLAS_class = '$div' and spriden.spriden_student_status in ('A') 
	// 		and stvclas.STVCLAS_acdm  in (select stvschool_acdm_year from stvschool) order by CAST(stvclas.STVCLAS_rollno AS SIGNED))");
	// 	}

	// 	$sql = "select s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
	// 	c.STVCLAS_standard, c.STVCLAS_class, c.STVCLAS_rollno,
	// 	(select SPRATND_status from spratnd where SPRATND_student_id = s.id and SPRATND_date = '$date') attendance_status,
	// 	ifnull((select SPRATND_id from spratnd where SPRATND_student_id = s.id and SPRATND_date = '$date'),0) attendance_id,
	// 	ifnull((select SPRATND_comment from spratnd where SPRATND_student_id = s.id and SPRATND_date = '$date'),'') comment
	// 	from spriden s, stvclas c
	// 	where spriden_student_status = 'A' and SPRIDEN_user_type = 'st'
	// 	and s.id = c.STVCLAS_student_id and c.STVCLAS_standard = '$class' and c.STVCLAS_class = '$div' and c.STVCLAS_acdm = (select stvschool_acdm_year from stvschool)
	// 	ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED)";
	// 	return $this->db->query($sql)->result();
	// }

	function student_data()
	{
		$date = $this->input->post('att_date');
		$class = $this->input->post('class');
		$div = $this->input->post('div');

		$sql = "
        SELECT
            s.id,
            s.SPRIDEN_student_id,
            CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
            c.STVCLAS_standard,
            c.STVCLAS_class,
            c.STVCLAS_rollno,
            a.SPRATND_status AS attendance_status,
            IFNULL(a.SPRATND_id, 0) AS attendance_id,
            IFNULL(a.SPRATND_comment, '') AS comment
        FROM spriden s
        INNER JOIN stvclas c
            ON c.STVCLAS_student_id = s.id
        LEFT JOIN (
            SELECT
                SPRATND_student_id,
                MAX(SPRATND_id) AS SPRATND_id
            FROM spratnd
            WHERE SPRATND_date = ?
            GROUP BY SPRATND_student_id
        ) latest
            ON latest.SPRATND_student_id = s.id
        LEFT JOIN spratnd a
            ON a.SPRATND_id = latest.SPRATND_id
        WHERE s.spriden_student_status = 'A'
          AND s.SPRIDEN_user_type = 'st'
          AND c.STVCLAS_standard = ?
          AND c.STVCLAS_class = ?
          AND c.STVCLAS_acdm = (SELECT stvschool_acdm_year FROM stvschool)
        ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED)
    ";

		return $this->db->query($sql, [$date, $class, $div])->result();
	}

	function insert_att()
	{
		$acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$acdm = $acdm['stvschool_acdm_year'];
		$rowmax = $this->input->post('rowMax');
		$post = $this->input->post();

		$update = [];
		$insert = [];
		$action_date = $this->input->post('attendancedate');
		for ($i = 1; $i <= $rowmax; $i++) {
			$present = $this->input->post('attendance' . $i);
			$status = $present == 'p' ? 'P' : 'A';
			$id = $this->input->post('id' . $i);
			$div = $this->input->post('div' . $i);
			$std = $this->input->post('std' . $i);

			$stud_id = $this->input->post('stud_id' . $i);
			$comment = $this->input->post('comment' . $i);
			$atnd_date = $this->input->post('attendancedate');

			$exist = $this->db->query("SELECT * FROM spratnd WHERE SPRATND_id = '$id'")->row_array();
			if ($exist != null) {
				$data = array(
					'SPRATND_comment' => $comment,
					'SPRATND_status' => $status,
				);

				$id_to_update = $exist['SPRATND_id'];
				$update = $this->db->where('SPRATND_id', $id)->update('spratnd', $data);
			} else {
				$data = array(
					'SPRATND_teacher_id' => $this->session->userdata('oliveMobileSystemUser'),
					'SPRATND_student_id' => $stud_id,
					'SPRATND_date' => $post['attendancedate'],
					'SPRATND_status' => $status,
					'SPRATND_std' => $std,
					'SPRATND_div' => $div,
					'SPRATND_acdm_year' => $acdm,
					'SPRATND_comment' => $comment
				);
				$insert = $this->db->insert('spratnd', $data);
			}
		}
		$students = [];
		$this->teacher_activity($students, 'attendance', $action_date);
		return "Attendance Saved Successfully";
	}


	function fillStudentsAttendance($date, $std, $div, $students, $status_of)
	{
		$academic_data = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$academic_year = $academic_data == null ? 0 : $academic_data['stvschool_acdm_year'];
		for ($i = 0; $i < count($students); $i++) {
			$data = array(
				'SPRATND_teacher_id' => $this->session->userdata('oliveMobileSystemUser'),
				'SPRATND_student_id' => $students[$i],
				'SPRATND_date' => $date,
				'SPRATND_std' => $std[$i],
				'SPRATND_div' => $div[$i],
				'SPRATND_status' => $status_of[$i],
				'SPRATND_acdm_year' => $academic_year
			);
			$status = $this->db->insert('spratnd', $data);
		}

		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	//function add_remark
	function add_remark()
	{
		$academic_data = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$academic_year = $academic_data == null ? 0 : $academic_data['stvschool_acdm_year'];
		$action_date = $this->input->post('attendancedate');
		$total = $this->input->post('total_rows');
		$students = [];
		for ($i = 1; $i <= $total; $i++) {
			$rmrk = $this->input->post('stud_remark' . $i);
			if ($rmrk != '') {
				array_push($students, $this->input->post('stud_id' . $i));
				$data = array(
					'stvrmrk_teacher_id' => $this->session->userdata('oliveMobileSystemUser'),
					'stvrmrk_student_id' => $this->input->post('stud_id' . $i),
					'stvrmrk_std' => $this->input->post('stud_std' . $i),
					'stvrmrk_date' => date('Y-m-d'),
					'stvrmrk_remark' => $this->input->post('stud_remark' . $i),
					'stvrmrk_reply' => "",
					'stvrmrk_date_of_reply' => "",
					'stvrmrk_status' => "New",
					'stvrmrk_academic_year' => $academic_year
				);
				$status = $this->db->insert('stvrmrk', $data);
			}
		}

		if ($status) {
			// ADD ACTIVITY CODE
			$this->teacher_activity($students, 'remark', $action_date);
			return true;
		} else {
			return false;
		}
	}

	//function add event
	function add_event($data)
	{
		$status = $this->db->insert('stvevnt', $data);
		if ($status) {
			$type = 'Event';
			$students = [];
			$this->Dashboard_model->teacher_activity($students, $type);
			return true;
		} else {
			return false;
		}
	}

	//function get all remarks
	// function get_allRemarks($userID)
	// {
	// 	$sql = "select spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvrmrk.* from spriden, stvrmrk where stvrmrk.stvrmrk_student_id = spriden.id and stvrmrk.stvrmrk_teacher_id = '" . $userID . "' and stvrmrk.stvrmrk_status = 'New' and stvrmrk.stvrmrk_remark is not null and stvrmrk.stvrmrk_remark != '' and stvrmrk.stvrmrk_academic_year in (select stvschool_acdm_year from stvschool)";
	// 	$data = $this->db->query($sql);
	// 	return $data->result_array();
	// }

	function get_allRemarks($userID)
	{
		// get the class chosen at login (falls back to first assigned class)
		$active = $this->getActiveClass($userID);
		$std = ($active && !empty($active['SPATESD_std'])) ? $active['SPATESD_std'] : '-';

		$sql = "select spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvrmrk.* 
            from spriden, stvrmrk 
            where stvrmrk.stvrmrk_student_id = spriden.id 
              and stvrmrk.stvrmrk_teacher_id = '" . $userID . "' 
              and stvrmrk.stvrmrk_status = 'New' 
              and stvrmrk.stvrmrk_remark is not null 
              and stvrmrk.stvrmrk_remark != '' 
              and stvrmrk.stvrmrk_std = '" . $std . "'
              and stvrmrk.stvrmrk_academic_year in (select stvschool_acdm_year from stvschool)";
		$data = $this->db->query($sql);
		return $data->result_array();
	}

	//Function to get all allocated class
	function getDataForUpdateFeedback($rowID)
	{
		$sql = "select spriden.id,spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvrmrk.* from spriden, stvrmrk where stvrmrk.stvrmrk_student_id = spriden.id and stvrmrk_id = '" . $rowID . "'";
		$data = $this->db->query($sql);
		return $data->row_array();
	}

	//update feedback
	function updateParentsFeedbackByTeacher()
	{
		$total = $this->input->post('total_rows_feedback');
		$students = [];
		for ($i = 0; $i <= $total; $i++) {
			if ($this->input->post('feedback_data' . $i) != '') {
				$rowid = $this->input->post('rowid' . $i);
				$this->db->where('stvrmrk_id', $rowid);
				$stu_data = $this->db->get('stvrmrk')->row_array();
				$student_id = $stu_data['stvrmrk_student_id'];
				array_push($students, $student_id);
				$data = array(
					'stvrmrk_reply' => $this->input->post('feedback_data' . $i),
					'stvrmrk_date_of_reply' => date('Y-m-d'),
					'stvrmrk_status' => "Feedback"
				);
				$this->db->where('stvrmrk_id', $rowid);
				$status = $this->db->update('stvrmrk', $data);
			}
		}

		if ($status) {
			/* ===== Developer: Kamran Ansari ===== Date: 08 Sep 2023 */
			$type = 'Feedback';
			$this->teacher_activity($students, $type);
			return true;
		} else {
			return false;
		}
	}

	//function to get all subject 
	function get_allSubjectsUnderStd($std)
	{
		$this->db->where('stvsubj_std', $std);
		return $sub = $this->db->get('stvsubj')->result_array();
	}

	//Fill Marks All
	function fillStudentsMarks($std, $division, $post_student_id, $post_att_marks, $subjects, $exam_name, $total_marks, $exam_type, $individual_exam)
	{
		// Check if required arrays are empty
		if (empty($post_student_id) || empty($post_att_marks)) {
			return false;
		}
		$academic_year = $this->getCurrentAcademic_year();
		$students = [];
		$len = count($post_student_id);
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		// delete previous marks to enter new marks
		$this->db->query("DELETE FROM sprmark WHERE sprmark_class = '$std' AND sprmark_div = '$division' 
						 AND sprmark_exam_name = '$exam_name' 
						 AND sprmark_academic_year = '$academic_year' 
						 AND sprmark_subject = $subjects 
						 AND sprmark_exam_type = '$exam_type'");
		$status = false;
		for ($i = 0; $i < $len; $i++) {
			// Skip if marks value is not set
			if (!isset($post_att_marks[$i])) {
				continue;
			}
			$students[] = $post_student_id[$i];
			$data = array(
				'sprmark_teacher_id' => $teacher_id,
				'sprmark_student_id' => $post_student_id[$i],
				'sprmark_class' => $std,
				'sprmark_div' => $division,
				'sprmark_exam_name' => $exam_name,
				'sprmark_total_marks' => $total_marks,
				'sprmark_subject' => $subjects,
				'sprmark_mark' => $post_att_marks[$i],
				'sprmark_date' => date('Y-m-d'),
				'sprmark_academic_year' => $academic_year,
				'sprmark_exam_type' => $exam_type,
				'sprmark_individual_exam' => $individual_exam
			);
			$status = $this->db->insert('sprmark', $data);
		}

		if ($status) {
			$type = 'mark';
			$this->teacher_activity($students, $type, null, "$std-$division", "$exam_name-$subjects");
			return true;
		}
		return false;
	}

	public function getAcademicYears()
	{
		return $this->db->query("SELECT stvacdm_id as id, stvacdm_year as year FROM stvacdm where stvacdm_id in (
		select stvschool_acdm_year from stvschool)")->row_array();
	}

	public function getClasses($teacher_id, $acdm_year)
	{
		$sql = "SELECT DISTINCT sprmark_class as class FROM sprmark WHERE sprmark_academic_year=$acdm_year";
		return $this->db->query($sql)->result_array();
	}

	public function getDivisions($teacher_id, $acdm_year)
	{
		$sql = "SELECT DISTINCT sprmark_div as division FROM sprmark WHERE sprmark_academic_year=$acdm_year";
		return $this->db->query($sql)->result_array();
	}

	public function get_all_academic_years()
	{
		return $this->db->select('stvacdm.stvacdm_id, stvacdm.stvacdm_year')
			->from('stvacdm')
			->order_by('stvacdm_year', 'DESC')
			->get()
			->result_array();
	}

	// public function getReportCardData($acdm, $class, $div)
	// {
	// 	$teacher_id = $this->session->userdata('oliveMobileSystemUser');
	// 	// $sql = "select s.id s_id,concat(s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name,' ',s.SPRIDEN_surname) as full_name,
	// 	//        st.STVCLAS_standard as sprmark_class,st.STVCLAS_class as sprmark_div,
	// 	//        st.STVCLAS_acdm as sprmark_academic_year, st.STVCLAS_rollno
	// 	//        from spriden s,stvclas st
	// 	//        where st.STVCLAS_student_id=s.id and st.STVCLAS_acdm=$acdm and st.STVCLAS_standard='$class'
	// 	//        and st.STVCLAS_class='$div' and s.spriden_student_status='A' ORDER BY STVCLAS_rollno";
	// 	$sql = "SELECT 
	// 					st.STVCLAS_student_id s_id,
	// 			    st.STVCLAS_rollno,
	// 			    CONCAT(s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name, ' ', s.SPRIDEN_surname) AS full_name,
	// 			    -- Subject-wise total marks (oral + written)
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'Algebra' THEN marks.total_marks ELSE 0 END) AS Algebra,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'English' THEN marks.total_marks ELSE 0 END) AS English,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'Geography' THEN marks.total_marks ELSE 0 END) AS Geography,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'Geometry' THEN marks.total_marks ELSE 0 END) AS Geometry,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'Hindi' THEN marks.total_marks ELSE 0 END) AS Hindi,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'History' THEN marks.total_marks ELSE 0 END) AS History,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'Marathi' THEN marks.total_marks ELSE 0 END) AS Marathi,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'Science 1' THEN marks.total_marks ELSE 0 END) AS Science_1,
	// 			    SUM(CASE WHEN subj.stvsubj_subject = 'Science 2' THEN marks.total_marks ELSE 0 END) AS Science_2
	// 			FROM 
	// 			    spriden s
	// 			JOIN 
	// 			    stvclas st ON st.STVCLAS_student_id = s.id
	// 			LEFT JOIN (
	// 			    -- Distinct oral + written per subject per student
	// 			    SELECT 
	// 			        sprmark_student_id,
	// 			        sprmark_subject,
	// 			        SUM(sprmark_mark) AS total_marks
	// 			    FROM (
	// 			        SELECT 
	// 			            sprmark_student_id,
	// 			            sprmark_subject,
	// 			            sprmark_exam_type,
	// 			            MAX(sprmark_mark) AS sprmark_mark
	// 			        FROM 
	// 			            sprmark
	// 			        WHERE 
	// 			            sprmark_class = '$class'
	// 			            AND sprmark_div = '$div'
	// 			            AND sprmark_academic_year = $acdm
	// 			            AND sprmark_exam_name = 4
	// 			            AND sprmark_exam_type IN ('oral', 'written')
	// 			        GROUP BY 
	// 			            sprmark_student_id, sprmark_subject, sprmark_exam_type
	// 			    ) AS distinct_marks
	// 			    GROUP BY 
	// 			        sprmark_student_id, sprmark_subject
	// 			) AS marks ON marks.sprmark_student_id = s.id
	// 			LEFT JOIN stvsubj subj 
	// 			    ON subj.stvsubj_id = marks.sprmark_subject AND subj.stvsubj_acdm = $acdm
	// 			WHERE 
	// 			    st.STVCLAS_standard = '$class'
	// 			    AND st.STVCLAS_class = '$div'
	// 			    AND st.STVCLAS_acdm =  $acdm
	// 			    AND s.spriden_student_status = 'A'
	// 			GROUP BY 
	// 			    st.STVCLAS_rollno, s.id
	// 			ORDER BY 
	// 			    st.STVCLAS_rollno";
	// 	return $this->db->query($sql)->result_array();
	// }

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

	function getMarks($student_id, $acdm, $class, $div)
	{
		$isNinthStandard = ($class == 'IX');
		$sql = "SELECT 
        subject_order.subject_name,
        -- Semester 1 (Exam Name 3) - Only if not 9th standard
        " . ($isNinthStandard ? "NULL AS sem1_written, NULL AS sem1_oral, NULL AS sem1_grade, NULL AS sem1_total," :
			"MAX(CASE WHEN s1.sprmark_exam_type = 'written' THEN s1.sprmark_mark END) AS sem1_written,
            MAX(CASE WHEN s1.sprmark_exam_type = 'oral' THEN s1.sprmark_mark END) AS sem1_oral,
            MAX(CASE WHEN s1.sprmark_exam_type = 'grade' THEN s1.sprmark_mark END) AS sem1_grade,
            (COALESCE(MAX(CASE WHEN s1.sprmark_exam_type = 'written' THEN s1.sprmark_mark END), 0) +
             COALESCE(MAX(CASE WHEN s1.sprmark_exam_type = 'oral' THEN s1.sprmark_mark END), 0)) AS sem1_total,") . "
        
        -- Semester 2 (Exam Name 4) - Always included
        MAX(CASE WHEN s2.sprmark_exam_type = 'written' THEN s2.sprmark_mark END) AS sem2_written,
        MAX(CASE WHEN s2.sprmark_exam_type = 'oral' THEN s2.sprmark_mark END) AS sem2_oral,
        MAX(CASE WHEN s2.sprmark_exam_type = 'grade' THEN s2.sprmark_mark END) AS sem2_grade,
        (COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'written' THEN s2.sprmark_mark END), 0) +
         COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'oral' THEN s2.sprmark_mark END), 0)) AS sem2_total,
        
        -- Combined Calculations
        " . ($isNinthStandard ?
			"(COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'written' THEN s2.sprmark_mark END), 0) +
             COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'oral' THEN s2.sprmark_mark END), 0)) AS grand_total,
            (COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'written' THEN s2.sprmark_mark END), 0) +
             COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'oral' THEN s2.sprmark_mark END), 0)) AS average" :
			"((COALESCE(MAX(CASE WHEN s1.sprmark_exam_type = 'written' THEN s1.sprmark_mark END), 0) +
              COALESCE(MAX(CASE WHEN s1.sprmark_exam_type = 'oral' THEN s1.sprmark_mark END), 0)) +
             (COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'written' THEN s2.sprmark_mark END), 0) +
              COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'oral' THEN s2.sprmark_mark END), 0))) AS grand_total,
            (((COALESCE(MAX(CASE WHEN s1.sprmark_exam_type = 'written' THEN s1.sprmark_mark END), 0) +
               COALESCE(MAX(CASE WHEN s1.sprmark_exam_type = 'oral' THEN s1.sprmark_mark END), 0)) +
              (COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'written' THEN s2.sprmark_mark END), 0) +
               COALESCE(MAX(CASE WHEN s2.sprmark_exam_type = 'oral' THEN s2.sprmark_mark END), 0))) / 2) AS average") . "
    FROM 
        (SELECT 'English' AS subject_name, 1 AS sort_order
         UNION SELECT 'Hindi', 2
         UNION SELECT 'Marathi', 3
         UNION SELECT 'Algebra', 4
         UNION SELECT 'Geometry', 5
         UNION SELECT 'Science 1', 6
         UNION SELECT 'Science 2', 7
         UNION SELECT 'History', 8
         UNION SELECT 'Geography', 9
         UNION SELECT 'Water Security', 10
         UNION SELECT 'Scout', 11
         UNION SELECT 'Guide', 12) AS subject_order
    LEFT JOIN 
        stvsubj subj ON subj.stvsubj_subject = subject_order.subject_name";

		// Add joins based on whether it's 9th standard or not
		if (!$isNinthStandard) {
			$sql .= " LEFT JOIN 
            sprmark s1 ON s1.sprmark_subject = subj.stvsubj_id
            AND s1.sprmark_academic_year = $acdm
            AND s1.sprmark_exam_name = 3 
            AND s1.sprmark_class = '$class' 
            AND s1.sprmark_div = '$div' 
            AND s1.sprmark_student_id = $student_id";
		}

		$sql .= " LEFT JOIN 
        sprmark s2 ON s2.sprmark_subject = subj.stvsubj_id
        AND s2.sprmark_academic_year = '$acdm' 
        AND s2.sprmark_exam_name = 4 
        AND s2.sprmark_class = '$class' 
        AND s2.sprmark_div = '$div' 
        AND s2.sprmark_student_id = $student_id
    GROUP BY 
        subject_order.subject_name, subject_order.sort_order
    ORDER BY 
        subject_order.sort_order";

		return $this->db->query($sql)->result_array();
	}

	function getStudentData($id, $acdm)
	{
		$sql = "select distinct s.id,s.spriden_gr_number, s.SPRIDEN_student_id,concat(s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name,' ',s.SPRIDEN_surname) Full_name,
		s.SPRIDEN_birthdate,(select STVCLAS_standard std from stvclas where STVCLAS_student_id=s.id and STVCLAS_acdm=
		$acdm) STD,
		(select STVCLAS_class std from stvclas where STVCLAS_student_id=s.id and STVCLAS_acdm=
		$acdm) DIVISION,
		(select STVCLAS_rollno from stvclas where STVCLAS_student_id=s.id and STVCLAS_acdm=
		$acdm) ROLL_NO,
		(SELECT concat(s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name,' ',s.SPRIDEN_surname)
		 FROM spriden s 
		 WHERE s.spriden_student_id in
		 (select DISTINCT SPATESD_teacher_id from spatesd where SPATESD_std=STD and SPATESD_div=DIVISION))TEACHER
		 from spriden s where SPRIDEN_user_type='st' and s.spriden_student_status='A' AND id=$id order by 1";
		return $this->db->query($sql)->row_array();
	}

	function getAcdmYear($acdm)
	{
		$sql = "select stvacdm_year from stvacdm where stvacdm_id=$acdm";
		return $this->db->query($sql)->row_array();
	}

	function teacher_activity($students = [], $type = null, $action = null, $class = null, $attr = null)
	{
		$tc_id = $this->session->userdata('oliveMobileSystemUser');
		$today = date('Y-m-d');
		$sql = "SELECT id, stud_id FROM teacher_activity where user_id = '$tc_id' and activity_date = '$today' and activity_sorce = '$type'";
		if ($type == 'attendance')
			$sql .= " and action_date = '$action'";
		$ee = $this->db->query($sql)->row_array();
		if (is_array($students) && !empty($students))
			$sts_id = implode(",", $students);
		else
			$sts_id = '-';
		$time = date('H:i:s');
		$sql = "INSERT INTO teacher_activity (user_id, stud_id, activity_date, activity_time, activity_sorce";
		if ($action != null)
			$sql .= " , action_date";
		// Updated 2024 Jul4 23
		if ($class != null)
			$sql .= " , class";
		if ($attr != null)
			$sql .= " , attribute";
		$sql .= " ) VALUES ('$tc_id', '$sts_id','$today','$time','$type'";
		if ($action != null)
			$sql .= ", '$action'";
		// Updated 2024 Jul4 23
		if ($class != null)
			$sql .= ", '$class'";
		if ($attr != null)
			$sql .= ", '$attr'";
		$sql .= ")";
		if ($ee == null) {
			$this->db->query($sql);
		} else {
			// update
			$__id = $ee['id'];
			if (is_array($students) && !empty($students))
				$ss_id = $ee['stud_id'] . "," . implode(",", $students);
			else
				$ss_id = '-';
			$sql = "update teacher_activity set stud_id = '$ss_id',activity_time = '$time' where id = $__id";
			$this->db->query($sql);
		}
	}

	//Get Admission Status  Data
	function get_admission_status($std, $div)
	{
		$sql = "select spriden.id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, visiting.*, stvclas.* from spriden, visiting, stvclas where spriden.id = visiting.visiting_student_id and visiting.visiting_student_id = stvclas.STVCLAS_student_id and stvclas.STVCLAS_standard = '" . $std . "' and stvclas.STVCLAS_class = '" . $div . "'";
		$data = $this->db->query($sql);
		return $data->result_array();
	}

	// function get_replied_remarks($teacher_id)
	// {
	// 	$academic_year = $this->getCurrentAcademic_year();
	// 	$sql = "SELECT * FROM `stvrmrk` WHERE stvrmrk_teacher_id = '" . $teacher_id . "' and stvrmrk_status = 'Feedback' and stvrmrk_academic_year = '$academic_year'";
	// 	$data = $this->db->query($sql)->result_array();
	// 	if ($data == null) {
	// 		return 0;
	// 	} else {
	// 		return count($data);
	// 	}
	// }


	function get_replied_remarks($teacher_id)
	{
		$academic_year = $this->getCurrentAcademic_year();
		$active = $this->getActiveClass($teacher_id);
		$std = ($active && !empty($active['SPATESD_std'])) ? $active['SPATESD_std'] : '-';

		$sql = "SELECT * FROM `stvrmrk` 
            WHERE stvrmrk_teacher_id = '" . $teacher_id . "' 
              and stvrmrk_status = 'Feedback' 
              and stvrmrk_academic_year = '$academic_year' 
              and stvrmrk_std = '" . $std . "'";
		$data = $this->db->query($sql)->result_array();
		if ($data == null) {
			return 0;
		} else {
			return count($data);
		}
	}
	function getCurrentAcademic_year()
	{
		$data = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		return $data['stvschool_acdm_year'];
	}

	// function get_pending_remarks($teacher_id)
	// {
	// 	$academic_year = $this->getCurrentAcademic_year();
	// 	$sql = "SELECT * FROM `stvrmrk` WHERE stvrmrk_teacher_id = '" . $teacher_id . "' and stvrmrk_status = 'New' and stvrmrk_academic_year = '$academic_year' and stvrmrk_remark != '' ";
	// 	$data = $this->db->query($sql)->result_array();
	// 	if ($data == null) {
	// 		return 0;
	// 	} else {
	// 		return count($data);
	// 	}
	// }


	function get_pending_remarks($teacher_id)
	{
		$academic_year = $this->getCurrentAcademic_year();
		$active = $this->getActiveClass($teacher_id);
		$std = ($active && !empty($active['SPATESD_std'])) ? $active['SPATESD_std'] : '-';

		$sql = "SELECT * FROM `stvrmrk` 
            WHERE stvrmrk_teacher_id = '" . $teacher_id . "' 
              and stvrmrk_status = 'New' 
              and stvrmrk_academic_year = '$academic_year' 
              and stvrmrk_remark != '' 
              and stvrmrk_std = '" . $std . "'";
		$data = $this->db->query($sql)->result_array();
		if ($data == null) {
			return 0;
		} else {
			return count($data);
		}
	}
	function getAllClasses($tid)
	{
		if ($this->session->userdata('session_type') == "Random") {
			$sql = "select distinct stvrndm_class as std from stvrndm where stvrndm_teacher_id = '$tid'";
		} else {
			$sql = "SELECT DISTINCT spatesd.SPATESD_std std FROM spatesd WHERE spatesd.SPATESD_teacher_id = '" . $tid . "' 
				union 
				select DISTINCT stvstcr.stvstcr_std std from stvstcr WHERE stvstcr.stvstcr_teacher_id = '" . $tid . "' ORDER BY std";
		}
		return $data = $this->db->query($sql)->result_array();
	}

	function getAllclass($tid)
	{
		return $this->db->get('stvfees')->result_array();
	}

	function get_students_for_teacher_remark_by_std_and_div()
	{
		$std = $this->input->post('std');
		$div = $this->input->post('div');
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$this->db->where('stvstcr_std', $std);
		$this->db->where('stvstcr_div', $div);
		$this->db->where('stvstcr_teacher_id', $teacher_id);
		$isAvail = $this->db->get('stvstcr')->row_array();
		//checkclasstechaer
		$this->db->where('SPATESD_std', $std);
		$this->db->where('SPATESD_div', $div);
		$this->db->where('SPATESD_teacher_id', $teacher_id);
		$isAvailClass = $this->db->get('spatesd')->row_array();
		if ($isAvail == null && $isAvailClass == null) {
			return "fail";
		} else {
			$academic_year = $this->getCurrentAcademic_year();
			$sql = "select spriden.*, stvclas.* from spriden, stvclas where stvclas.STVCLAS_student_id = spriden.id and stvclas.STVCLAS_standard = '" . $this->input->post('std') . "' and stvclas.STVCLAS_class = '" . $this->input->post('div') . "' and stvclas.STVCLAS_acdm = $academic_year and spriden.spriden_student_status = 'A'";

			return $data = $this->db->query($sql)->result_array();
		}
	}

	function get_all_exams()
	{
		return $data = $this->db->get('stvexam')->result_array();
	}

	function get_subject_by_std($std)
	{
		$academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$academic_year = $academic_year['stvschool_acdm_year'];
		$this->db->where('stvsubj_std', $std);
		$this->db->where('stvsubj_acdm', $academic_year);
		return $data = $this->db->get('stvsubj')->result_array();
	}

	function getStudentsForFillMarks()
	{
		$std = $this->input->post('std');
		$div = $this->input->post('div');
		$exam = $this->input->post('exam');
		$subject = $this->input->post('subject');
		$exam_type = $this->input->post('exam_type');
		$academic_year = $this->getCurrentAcademic_year();
		$sql = "SELECT DISTINCT 
					    s.id, 
					    s.SPRIDEN_student_id, 
					    s.SPRIDEN_surname, 
					    s.SPRIDEN_student_name, 
					    s.SPRIDEN_father_name,
					    c.STVCLAS_standard, 
					    c.STVCLAS_class, 
					    c.STVCLAS_rollno, 
					    IFNULL(m.sprmark_exam_type, '') AS sprmark_exam_type, 
					    IFNULL(m.sprmark_total_marks, 0) AS total_marks, 
					    IFNULL(m.sprmark_mark, 0) AS obtain_marks, 
					    IFNULL(m.sprmark_individual_exam, '') AS exam_name_individual 
					FROM spriden s 
					JOIN stvclas c 
					    ON c.STVCLAS_student_id = s.id 
					LEFT JOIN sprmark m 
					    ON m.sprmark_student_id = s.id 
					    AND m.sprmark_class = c.STVCLAS_standard 
					    AND m.sprmark_div = c.STVCLAS_class 
					   AND m.sprmark_exam_name = '$exam' 
					   AND m.sprmark_subject = '$subject' 
					   AND m.sprmark_academic_year = $academic_year
					  AND m.sprmark_exam_type = '$exam_type'
					WHERE 
					   c.STVCLAS_standard = '$std'
									AND c.STVCLAS_class = '$div'
									AND c.STVCLAS_acdm = $academic_year
									AND s.SPRIDEN_student_status = 'A'
					ORDER BY CAST(c.STVCLAS_rollno AS SIGNED)";
		// die($sql);
		$data = $this->db->query($sql);
		return $data->result_array();
	}

	function getDivByStd($std, $user)
	{
		$sql = "SELECT DISTINCT stvstcr.stvstcr_div division, stvstcr.stvstcr_std std from stvstcr WHERE stvstcr.stvstcr_teacher_id = '" . $user . "' and stvstcr.stvstcr_std = '" . $std . "'
		UNION
		select DISTINCT spatesd.SPATESD_div division, spatesd.SPATESD_std std from spatesd WHERE spatesd.SPATESD_teacher_id = '" . $user . "' and spatesd.SPATESD_std = '" . $std . "' ORDER BY division";
		$data = $this->db->query($sql);
		return $data->result_array();
	}

	function get_all_remarks_masters()
	{
		return $this->db->get('stvrmst')->result_array();
	}

	//---- New Changes

	function getAcademicYear()
	{
		$sql = "SELECT stvschool.* , stvacdm.* from stvschool, stvacdm WHERE stvschool.stvschool_acdm_year = stvacdm.stvacdm_id";
		$data = $this->db->query($sql)->row_array();
		if ($data != null) {
			return $data;
		} else {
			return 0;
		}
	}

	function fees_details_assigned($id)
	{
		$acdm = $this->getAcademicYear();
		$year = $acdm['stvschool_acdm_year'];
		if ($this->session->userdata('session_type') == "Random") {
			return $this->db->query("Select concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name,
			sum(sprfees.sprfees_amount) total_paid,
			max(sprfees.sprfees_due_date) due_date,
			10000 - sum(sprfees.sprfees_amount) balance,
			spriden.id,
			spriden.SPRIDEN_student_id,
			stvclas.STVCLAS_standard,
			stvclas.STVCLAS_class,
			stvclas.STVCLAS_rollno,
			(select stvpcnf_whatsapp FROM stvpcnf pcnf WHERE spriden.id = pcnf.stvpcnf_student_id And pcnf.stvpcnf_parent_typ='Father') whatsapp
			from sprfees, stvclas,spriden where spriden.id = stvclas.STVCLAS_student_id 
			and spriden.id = sprfees.sprfees_studentid
			and spriden.id in (select spriden.id from spriden, stvclas, stvrndm
			where stvclas.STVCLAS_student_id = spriden.id and spriden.id = stvrndm.stvrndm_student_id
			and stvrndm.stvrndm_teacher_id = '" . $id . "')
			and sprfees.academic_year = " . $year . "
			group by 
			name,
			spriden.id,
			spriden.SPRIDEN_student_id,
			stvclas.STVCLAS_standard ,
			stvclas.STVCLAS_class
			order by sprfees_pk")->result_array();
		} else {
			$allocated_class = $this->getActiveClass($id);
			if ($allocated_class != null) {
				$std = $allocated_class['SPATESD_std'];
				$div = $allocated_class['SPATESD_div'];
			} else {
				$std = '-';
				$div = '-';
			}
			return $this->db->query("select id,SPRIDEN_student_id, name, STVCLAS_standard, STVCLAS_class, CAST(STVCLAS_rollno AS SIGNED)STVCLAS_rollno, total_paid, due_date, whatsapp, total_class_fees,
			(total_class_fees - total_paid) as balance
			from(select s.id,s.SPRIDEN_student_id, concat(s.SPRIDEN_surname , ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
			c.STVCLAS_standard,c.STVCLAS_class,c.STVCLAS_rollno,
			(select ifnull(sum(sprfees_amount),0) paid from sprfees where sprfees_studentid = s.id and academic_year = c.STVCLAS_acdm) total_paid,
			(select max(sprfees_due_date) due_date from sprfees where sprfees_studentid = s.id and academic_year = c.STVCLAS_acdm) due_date,
			(select stvpcnf_whatsapp from stvpcnf where stvpcnf_student_id = s.id limit 1) whatsapp,
			(select stvfstr_fees from stvfstr where stvfstr_acdm_year = c.STVCLAS_acdm and stvfstr_std = c.STVCLAS_standard) total_class_fees
			from stvclas c, spriden s
			where c.STVCLAS_standard = '$std' and c.STVCLAS_class = '$div' and c.STVCLAS_acdm = $year
			and s.id = c.STVCLAS_student_id and s.spriden_student_status = 'A' and (s.rte_status is null or s.rte_status = 'No')) as temp where (total_class_fees - total_paid) > 0 order by STVCLAS_rollno asc")->result_array();
		}
	}

	function teacherprofile($post)
	{
		$id = $post['id'];
		//CHECK IF exist
		$exist = $this->db->where('teacher_id', $id)->get('stvtcnf')->row_array();
		if ($exist == null) {
			//insert
			$q = "INSERT INTO stvtcnf (teacher_id,mobile,whatsapp) values ('$post[id]','$post[mobile]','$post[whatsapp]')";
			$this->db->query($q);
		} else {
			//update
			$q = "UPDATE stvtcnf SET mobile = '$post[mobile]',whatsapp= '$post[whatsapp]' WHERE teacher_id= '$post[id]'";
			$this->db->query($q);
		}
		//update in spriden
		$qu = "UPDATE spriden set SPRIDEN_student_name ='$post[fname]', SPRIDEN_surname='$post[lname]',SPRIDEN_father_name='$post[mname]', SPRIDEN_aadhar_no = '$post[aadhar_number]',SPRIDEN_birthdate = '$post[date_of_birth]', SPRIDEN_BGRP = '$post[blood_group]', SPRIDEN_EMAIL = '$post[email_address]' WHERE SPRIDEN_student_id='$post[id]'";
		$this->db->query($qu);
		redirect(base_url() . 'index.php/dashboard');
	}

	function set_class_division()
	{
		$class = $this->input->post('hidden_class');
		$div = $this->input->post('hidden_div');
		$total = $this->input->post('total_students');
		for ($i = 1; $i <= $total; $i++) {
			$student_id = $this->input->post('sid' . $i);
			$student_status = $this->input->post('status_student' . $i);
			$class_status = $this->input->post('status_class' . $i);
			// update class details
			$update_class = array(
				'STVCLAS_class' => $class_status == 'on' ? $div : '*'
			);
			$this->db->where('STVCLAS_student_id', $student_id)->update('stvclas', $update_class);
			// update class details
			$update_spriden = array(
				'spriden_student_status' => $student_status == 'on' ? 'A' : 'I'
			);
			$this->db->where('id', $student_id)->update('spriden', $update_spriden);
		}
		redirect(base_url() . "dashboard/restud?std=$class&div=$div");
	}

	function teacherPassword($post)
	{
		$id = $post['id'];
		$curpass = $post['curpass'];
		$newpass = $post['newpass'];
		$conpass = $post['conpass'];
		//CHECK IF exist
		$exist = $this->db->where('spriden_student_id', $id)->get('spriden')->row_array();
		if ($exist) {
			if ($curpass == $exist['spriden_tc_pass']) {
				if ($newpass == $conpass) {
					$sql = "UPDATE spriden SET spriden_tc_pass='$conpass' where spriden_student_id='$id'";
					return $this->db->query($sql);
					if ($sql) {
						$this->session->set_flashdata('message', 'Password updated successfully..!');
					}
				} else {
					$this->session->set_flashdata('message', 'New password and Confirm password do not match..!');
				}
			} else {
				$this->session->set_flashdata('message', 'Current Password is Incorrect..!');
			}
		} else {
			$this->session->set_flashdata('message', 'Please Login Again..!');
		}
	}

	function getStudents($std)
	{
		$academic_data = $this->db->query("select * from stvschool")->row_array();
		$last_academic_year = $academic_data['pre_academic_year'];
		$academic_year = $academic_data['stvschool_acdm_year'];
		$sql = "select s.id,concat (s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,st.STVCLAS_standard,s.SPRIDEN_gender,st.STVCLAS_class,s.spriden_student_status, st.updated_flag
		from spriden s, stvclas st where s.id=st.STVCLAS_student_id and STVCLAS_standard='$std' and stvclas_acdm = $academic_year and s.spriden_student_status = 'A' order by 4,2";
		return $this->db->query($sql)->result_array();
	}

	function updateclass()
	{
		$acdm = $this->getAcademicYear();
		$year = $acdm['stvschool_acdm_year'];
		$post = $this->input->post();
		$max_rows = $this->input->post('max_rows');
		for ($i = 1; $i <= $max_rows; $i++) {
			$update_id = $this->input->post('update_id' . $i);
			$status = isset($post['status' . $i]) ? true : false;
			$active = isset($post['active' . $i]) ? 'A' : 'I';
			if ($status) {
				$exist = $this->db->query("SELECT * FROM `stvclas` WHERE stvclas_student_id = $update_id  and stvclas_acdm = $year ")->row_array();
				if ($exist != null) {
					$update_class = array(
						'STVCLAS_class' => $this->input->post('div' . $i),
						'STVCLAS_standard' => $this->input->post('curr_class'),
						'updated_flag' => 1
					);
					$update = $this->db->where('STVCLAS_student_id', $update_id)->where('STVCLAS_acdm', $year)->update('stvclas', $update_class);
				} else {
					$data = array(
						'STVCLAS_student_id' => $update_id,
						'STVCLAS_class' => $this->input->post('div' . $i),
						'STVCLAS_standard' => $this->input->post('curr_class'),
						'STVCLAS_acdm' => $year,
						'updated_flag' => 1,
						'STVCLAS_rollno' => 0
					);
					$insert = $this->db->insert('stvclas', $data);
				}
			} else {
				$sql = "UPDATE spriden SET spriden_student_status='$active' WHERE id='$update_id'";
				$status_Data = $this->db->query($sql);
			}
			$sql = "UPDATE spriden SET spriden_student_status='$active' WHERE id='$update_id'";
			$this->db->query($sql);
		}
		return "Data Saved SuccessFully";
	}

	// ---------------------/KAMRAN
	// ------------------------ new --------------------------
	function fetch_data()
	{
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$sub = $this->input->get('sub');
		$type = $this->input->get('type');
		$date = $this->input->get('date');

		// use session-aware method
		$class = $this->get_allocated_classs($teacher_id);
		$std = $class ? $class['SPATESD_std'] : '-';
		$div = $class ? $class['SPATESD_div'] : '-';

		return $sql = $this->db->query("select s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
			c.STVCLAS_standard, c.STVCLAS_class, c.STVCLAS_rollno,
			ifnull((select Id from stvsptr where Student_id = s.id and Subjerct_id = $sub and Type = '$type' and Action_date = '$date'),0) action_id,
			(select Grade from stvsptr where Student_id = s.id and Subjerct_id = $sub and Type = '$type' and Action_date = '$date') action_Grade,
			(select Comment from stvsptr where Student_id = s.id and Subjerct_id = $sub and Type = '$type' and Action_date = '$date') action_Comment
			from spriden s, stvclas c
			where spriden_student_status = 'A' and SPRIDEN_user_type = 'st' 
			and s.id = c.STVCLAS_student_id and c.STVCLAS_standard = '$std' and c.STVCLAS_class = '$div' and c.STVCLAS_acdm = (select stvschool_acdm_year from stvschool)
			ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED)")->result_array();
	}

	function insert_data()
	{
		$sub = $this->input->post('sub');
		$type = $this->input->post('type');
		$t_date = $this->input->post('t_date');
		$create_at = date('Y/D/M');
		$acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$acdm = $acdm['stvschool_acdm_year'];
		$rowmax = $this->input->post('max_rows');
		$update = [];
		$insert = [];
		for ($i = 1; $i <= $rowmax; $i++) {
			$grade = $this->input->post('grade' . $i);
			$comment = $this->input->post('comment' . $i);
			$action_id = $this->input->post('action_id' . $i);
			$student_id = $this->input->post('student_id' . $i);
			$exist = $this->db->query("SELECT * FROM stvsptr where `id`= $action_id;")->row_array();
			if ($exist != null) {
				$data = array(
					'Grade' => $grade,
					'Comment' => $comment,
				);
				$update = $this->db->where('id', $action_id)->update('stvsptr', $data);
			} else {
				if ($grade || $comment != null) {
					$data = array(
						'Subjerct_id' => $sub,
						'Type' => $type,
						'Action_date' => $t_date,
						'Student_id' => $student_id,
						'Grade' => $grade,
						'Comment' => $comment,
						'Teacher_id' => $this->session->userdata('oliveMobileSystemUser'),
						'Create_at' => $create_at
					);
					$insert = $this->db->insert('stvsptr', $data);
				}
			}
		}
		if ($update != null) {
			return "Data Updated Successfully";
		} else if ($insert != null) {
			return "Data Inserted Successfully";
		}
	}

	// ------------------------- Ansari Kamran -------------------------------
	// ---------------------------- 13-09-23 ---------------------------------
	// ------------------------------ Start ----------------------------------
	function get_student_D($class, $div, $type = null)
	{
		if ($type == 'alert') {
			$sql = "select STVCLAS_code, STVCLAS_student_id, STVCLAS_rollno, STVCLAS_standard, STVCLAS_class, STVCLAS_userid, STVCLAS_acdm, 
			updated_flag, id, total_fees_paid_by_st, 
			SPRIDEN_student_id, SPRIDEN_student_name, SPRIDEN_father_name, SPRIDEN_surname, total_fees_to_be_paid, sum(total_fees_paid) total_fees_paid, 
			(total_fees_to_be_paid - sum(total_fees_paid)) remaining_fees_to_be_paid
			, stvpcnf_whatsapp from (SELECT
						stvclas.*,
						spriden.id,
						(select ifnull(sum(sprfees_amount),0) total_fees_paid_by_st from sprfees where sprfees_studentid = spriden.id and academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees_paid_by_st,
						spriden.SPRIDEN_student_id,
						spriden.SPRIDEN_student_name,
						spriden.SPRIDEN_father_name,
						spriden.SPRIDEN_surname,
						stvfstr.stvfstr_fees AS total_fees_to_be_paid,
						COALESCE(sprfees.sprfees_amount, 0) AS total_fees_paid,
						-- GREATEST(stvfstr.stvfstr_fees - COALESCE(sprfees.sprfees_amount, 0), 0) AS remaining_fees_to_be_paid,
						stvpcnf.stvpcnf_whatsapp  
					FROM
						spriden
					INNER JOIN stvclas ON stvclas.STVCLAS_student_id = spriden.id
					LEFT JOIN sprfees ON sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year IN (SELECT stvschool_acdm_year FROM stvschool)
					LEFT JOIN stvfstr ON stvfstr.stvfstr_std = stvclas.STVCLAS_standard AND stvfstr.stvfstr_acdm_year IN (SELECT stvschool_acdm_year FROM stvschool)
					LEFT JOIN stvpcnf ON stvpcnf.stvpcnf_student_id = spriden.id 
					WHERE
						stvclas.STVCLAS_standard = '$class'
						AND stvclas.STVCLAS_class = '$div'
						AND spriden.spriden_student_status = 'A'
						AND stvclas.STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool)
						AND (
							(sprfees.sprfees_amount IS NULL OR sprfees.sprfees_amount < stvfstr.stvfstr_fees)
							AND (COALESCE(sprfees.sprfees_amount, 0) >= 0 AND stvfstr.stvfstr_fees >= 0)
						) 
						AND (spriden.rte_status IS NULL OR spriden.rte_status = 'No')
					ORDER BY CAST(stvclas.STVCLAS_rollno AS SIGNED) ) temp group by id having remaining_fees_to_be_paid > 0";
		} else {
			$sql = "select spriden.*, stvclas.*, stvpcnf.stvpcnf_whatsapp 
			from spriden, stvclas , stvpcnf
			where stvclas.STVCLAS_student_id = spriden.id 
			and spriden.id = stvpcnf.stvpcnf_student_id and stvpcnf.stvpcnf_student_id = stvclas.STVCLAS_student_id
			and stvclas.STVCLAS_standard = '$class' and stvclas.STVCLAS_class = '$div' and spriden.spriden_student_status in ('A') 
			and stvclas.STVCLAS_acdm  in (select stvschool_acdm_year from stvschool) order by CAST(stvclas.STVCLAS_rollno AS SIGNED)";
		}
		return $this->db->query($sql)->result_array();
	}
	// ---------------------- End -------------------------

	// Ansari Kamran
	// date 23-09-23
	function syllabus($std)
	{
		$sql = "SELECT stvsubj_id,stvsubj_subject FROM `stvsubj` WHERE stvsubj_std = '$std' and stvsubj_acdm = (select stvschool_acdm_year from stvschool) and stvsubj_subject != 'ALL'";
		return $this->db->query($sql)->result_array();
	}

	function insert_syllabus()
	{
		$header_id = $this->input->post('hidden_header_id');
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$unit = $this->input->post('unit');
		$std = $this->input->post('std');
		$sub = $this->input->post('sub');
		$div = $this->input->post('division');
		$title = $this->input->post('title');
		$teaching_date = $this->input->post('teaching_date');
		$status = $this->input->post('status');
		$date = date('Y-m-d');
		if ($header_id == '') {
			$sql = "INSERT INTO stvslbs (title, unit, std, subject, `div`, teaching_date, status, teacher_id, created_by, created_at) VALUES ('$title','$unit','$std','$sub','$div','$teaching_date','$status','$teacher_id','$teacher_id','$date')";
		} else {
			$sql = "UPDATE stvslbs SET title = '$title', unit = '$unit', std = '$std', subject = '$sub', `div` = '$div', teaching_date = '$teaching_date', status = '$status', updated_by = '$teacher_id', updated_at = '$date' WHERE id = $header_id";
		}
		if ($this->db->query($sql)) {
			$data['status'] = 1;
			$data['message'] = 'Data Insert Successfully';
		} else {
			$data['status'] = 0;
			$data['message'] = 'Data Insert Failed';
		}
		return $data;
	}

	function syllabus_calender($std, $div, $startDate, $endDate)
	{
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$sql = "SELECT * FROM stvslbs where teaching_date BETWEEN '$startDate' AND '$endDate' and created_by ='$teacher_id' and std = '$std' and `div` = '$div'";
		return $this->db->query($sql)->result_array();
	}

	public function fetch_syllabus()
	{
		$teacher = $this->session->userdata('oliveMobileSystemUser');
		$sql = "SELECT id, title, unit, std,(SELECT stvsubj_subject FROM stvsubj where stvsubj_id = subject) as subject, `div`, teaching_date, status, teacher_id, created_by, created_at, updated_by, updated_at FROM stvslbs WHERE teacher_id = '$teacher'";
		return $this->db->query($sql)->result_array();
	}

	// Ansari Kamran 
	function fixed_formative_marks($value)
	{
		$invalid = ['', null, ' '];
		if (in_array($value, $invalid)) {
			return 0;
		}
		return $value;
	}

	function insert_evaluation()
	{
		$teacher_id = $this->session->userdata('oliveMobileSystemUser');
		$header_id = $this->input->post('hidden_header_id');
		$acdm = $this->input->post('acdm');
		$exam = $this->input->post('exam');
		$std = $this->input->post('std');
		$sub = $this->input->post('sub');
		$div = $this->input->post('division');
		$date = date('Y-m-d');
		$total_stud = $this->input->post('total_stud');
		$result = false;
		$this->db->query("delete FROM `sprsumeval` WHERE `sprsumeval_class`='$std' and `sprsumeval_div`='$div' and `sprsumeval_acdm`='$acdm ' and  `sprsumeval_subject_id`='$sub' and `sprsumeval_exam_id`='$exam' and `sprsumeval_created_by`='$teacher_id'");
		for ($i = 1; $i <= $total_stud; $i++) {
			$stud_id = $this->input->post("line_sid$i");
			$daily = $this->fixed_formative_marks($this->input->post("daily$i"));
			$oral_w = $this->fixed_formative_marks($this->input->post("oral_w$i"));
			$pract = $this->fixed_formative_marks($this->input->post("pract$i"));
			$activity = $this->fixed_formative_marks($this->input->post("activity$i"));
			$project = $this->fixed_formative_marks($this->input->post("project$i"));
			$test = $this->fixed_formative_marks($this->input->post("test$i"));
			$cw = $this->fixed_formative_marks($this->input->post("cw$i"));
			$other = $this->fixed_formative_marks($this->input->post("other$i"));
			$total = $this->fixed_formative_marks($this->input->post("total$i"));
			$oral = $this->fixed_formative_marks($this->input->post("oral$i"));
			$practical = $this->fixed_formative_marks($this->input->post("practical$i"));
			$writen = $this->fixed_formative_marks($this->input->post("writen$i"));
			$total_m = $this->fixed_formative_marks($this->input->post("total_m$i"));
			$ab = $this->fixed_formative_marks($this->input->post("ab$i"));
			$grade = $this->fixed_formative_marks($this->input->post("grade$i"));
			$sql = "INSERT INTO sprsumeval (sprsumeval_id, sprsumeval_stu_id, sprsumeval_class, sprsumeval_div, sprsumeval_acdm, sprsumeval_created_by, sprsumeval_created_on, sprsumeval_subject_id, sprsumeval_exam_id, daily_observation, fm_oral_work, fm_practical, fm_activity, fm_project, fm_test, fm_cw_hw, fm_other, fm_total, sm_oral, sm_practical, sm_written, sm_total, sm_a_b, sm_grade)
		 VALUES ('$header_id','$stud_id','$std','$div','$acdm','$teacher_id','$date','$sub','$exam','$daily','$oral_w','$pract','$activity','$project','$test','$cw','$other','$total','$oral','$practical','$writen','$total_m','$ab','$grade')";
			// echo "<br> $sql <br>";
			$result = $this->db->query($sql);
		}

		if ($result) {
			$data['status'] = 1;
			$data['message'] = 'Data Insert Successfully';
		} else {
			$data['status'] = 0;
			$data['message'] = 'Data Insert Failed: ' . $this->db->error();
		}
		return $data;
	}

	function fetch_sprsumeval_data($class, $exam, $div, $sub, $acdm)
	{
		$sql = "SELECT sp.id, sp.SPRIDEN_student_id,  sp.SPRIDEN_surname, sp.SPRIDEN_student_name, sp.SPRIDEN_father_name,
		c.STVCLAS_rollno,c.STVCLAS_student_id, sprsumeval.*
	FROM 
		spriden sp
	JOIN 
		stvclas c ON c.STVCLAS_student_id = sp.id
		LEFT JOIN 
		sprsumeval ON sprsumeval.sprsumeval_stu_id = sp.id AND sprsumeval.sprsumeval_class = '$class' 
		AND sprsumeval.sprsumeval_div = '$div' 
		AND sprsumeval.sprsumeval_acdm = '$acdm' 
		AND sprsumeval.sprsumeval_subject_id = '$sub' 
		AND sprsumeval.sprsumeval_exam_id = $exam
	WHERE 
		c.STVCLAS_standard = '$class' 
		AND c.STVCLAS_class = '$div' 
		AND sp.spriden_student_status IN ('A') 
		AND c.STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool)
	ORDER BY 
		CAST(c.STVCLAS_rollno AS SIGNED);";
		// die($sql);
		return $this->db->query($sql)->result_array();
	}

	// Generate Meetings
	function add_meeting($data)
	{
		$status = $this->db->insert('parents_meet', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function update_meeting($data, $id)
	{
		$this->db->where('prameet_id ', $id);
		$status = $this->db->update('parents_meet', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function checkExistmeeting($id)
	{
		$this->db->where('prameet_id ', $id);
		$data = $this->db->get('parents_meet')->row_array();
		if ($data == null) {
			return 0;
		} else {
			return count($data);
		}
	}

	function getAllmeeting()
	{
		$this->db->order_by('prameet_id', 'desc');
		return $data = $this->db->get('parents_meet')->result_array();
	}

	function getmeetingByID()
	{
		$this->db->where('prameet_id', $this->input->post('id'));
		return $this->db->get('parents_meet')->row_array();
	}

	// Prents Meeting for teacher
	function getParentsMeeting($class, $div, $meet)
	{

		$sql = "SELECT 
			s.id, s.SPRIDEN_surname, s.SPRIDEN_student_name ,s.SPRIDEN_father_name, c.STVCLAS_standard ,c.STVCLAS_class, m.			prameet_vanue, m.prameet_agenda, w.stvpcnf_whatsapp
			FROM
			spriden s
			LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id 
			LEFT JOIN parents_meet m ON m.prameet_class = c.STVCLAS_standard
			LEFT JOIN stvpcnf w ON w.stvpcnf_student_id = s.id
			WHERE
			s.SPRIDEN_user_type = 'st'
			AND s.spriden_student_status = 'A'
			AND c.STVCLAS_standard = '$class'
			AND STVCLAS_class = '$div'
			AND m.prameet_vanue= '$meet'";
		return $this->db->query($sql)->result_array();
	}

	public function getMeetingsByClass($class)
	{
		$sql = "
        SELECT 
            m.prameet_vanue, 
            c.STVCLAS_standard
        FROM 
            parents_meet m
        LEFT JOIN 
            stvclas c ON c.STVCLAS_standard = m.prameet_class
        WHERE 
            c.STVCLAS_standard = ?
			GROUP BY 
            c.STVCLAS_standard";
		$query = $this->db->query($sql, array($class));
		return $query->result_array();
	}

	function getParentsMeet()
	{
		return $classes = $this->db->get('parents_meet')->result_array();
	}

	// Aadhar info report (Ansari Kamran)
	// public function get_filtered_notes($class, $division)
	// {
	// 	$this->db->select('*');
	// 	$this->db->from('stu_par_aadhar_details');
	// 	if ($class) {
	// 		$this->db->where('class', $class);
	// 	}
	// 	if ($division) {
	// 		$this->db->where('division', $division);
	// 	}
	// 	return $this->db->get()->result_array();

	// Aadhar info report (Ansari Kamran)
	public function get_filtered_notes($class = '', $division = '')
	{
		$this->db->select('st.student_id,
        s.SPRIDEN_student_id,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        st.student_name,
        st.student_aadhar_number,
        st.student_dob,
        st.father_name,
        st.father_aadhar_number,
        st.father_dob,
        st.mother_name,
        st.mother_aadhar_number,
        st.mother_dob,
        st.fat_mob_no');
		$this->db->from('stu_par_aadhar_details st');
		$this->db->join('spriden s', 's.id = st.student_id', 'left');
		$this->db->join('stvclas c', 'c.STVCLAS_student_id = s.id', 'left');

		// Add conditions dynamically if the parameters are provided
		if (!empty($class)) {
			$this->db->where('c.STVCLAS_standard', $class);
		}
		if (!empty($division)) {
			$this->db->where('c.STVCLAS_class', $division);
		}
		$this->db->group_by('s.SPRIDEN_student_id');
		// Execute the query and return the results
		return $this->db->get()->result_array();
	}

	// generate notes for students
	function get_subject($std)
	{
		$academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$academic_year = $academic_year['stvschool_acdm_year'];
		$sql = "SELECT * 
				FROM stvsubj
				WHERE stvsubj_std = '$std'
				  AND stvsubj_acdm = '$academic_year';";
		return $this->db->query($sql)->result_array();
	}

	public function add_note()
	{
		// Single values
		$acdm = $this->input->post('cur_acdm');
		$class = $this->input->post('marks_class');
		$session = $this->input->post('div_filter_');
		// Arrays for multiple rows
		$subject = $this->input->post('subject');
		$doc_titles = $this->input->post('doc_title');
		$doc_types = $this->input->post('doc_type');
		$doc_files = $_FILES['doc_file']; // Files
		// Prepare array for batch insert
		$data = [];
		for ($i = 0; $i < count($doc_titles); $i++) {
			// File upload handling
			$file_name = null;
			if (!empty($doc_files['name'][$i])) {
				$_FILES['file']['name'] = $doc_files['name'][$i];
				$_FILES['file']['type'] = $doc_files['type'][$i];
				$_FILES['file']['tmp_name'] = $doc_files['tmp_name'][$i];
				$_FILES['file']['error'] = $doc_files['error'][$i];
				$_FILES['file']['size'] = $doc_files['size'][$i];
				$config['upload_path'] = './documents/';
				$config['allowed_types'] = 'pdf|doc|docx|jpg|png|txt';
				$config['max_size'] = 20480; // 20MB limit
				$config['file_name'] = time() . "_" . $doc_files['name'][$i];
				$this->load->library('upload', $config);
				if ($this->upload->do_upload('file')) {
					$upload_data = $this->upload->data();
					$file_name = $upload_data['file_name'];
				} else {
					return ['status' => 'error', 'message' => 'File upload failed'];
				}
			}
			// Add to batch insert array
			$data[] = [
				'cur_acdm' => $acdm,
				'std_filter' => $class,
				'div_filter' => $session,
				'subject' => $subject[$i],
				'doc_title' => $doc_titles[$i],
				'doc_type' => $doc_types[$i],
				'attachment' => $file_name,
				'is_active' => '1',
				'created_at' => date('Y-m-d H:i:s')
			];
		}
		if (!empty($data)) {
			$this->db->insert_batch('notes', $data);
			return ['status' => 'success', 'message' => 'Data inserted successfully'];
		} else {
			return ['status' => 'error', 'message' => 'No data to insert'];
		}
	}

	public function get_notes($acdm = '', $std = '', $div = '', $sub = '')
	{
		$this->db->select('*');
		$this->db->from('notes');
		if ($acdm) {
			$this->db->where('cur_acdm', $acdm);
		}
		if ($std) {
			$this->db->where('std_filter', $std);
		}
		if ($div) {
			$this->db->where('div_filter', $div);
		}
		if ($sub) {
			$this->db->where('subject', $sub);
		}
		return $this->db->get()->result_array();
	}

	function get_notes_by_id($curr_acdm)
	{
		$sql = "SELECT * FROM notes WHERE cur_acdm = '$curr_acdm'";
		return $this->db->query($sql)->result_array();
	}


	//  Code by Hassan starts here 

	/**
	 * Save complete profile with all related data
	 */
	public function save_complete_profile($user_id, $profile_id, $profile_data, $education, $skills, $skill_ids, $certs, $cert_ids, $awards)
	{
		$this->db->trans_start();

		try {
			// 1. Handle Profile
			if ($profile_id) {
				// Check if profile exists and belongs to user
				$existing = $this->db->get_where('tc_profiles', ['id' => $profile_id, 'user_id' => $user_id])->row();
				if ($existing) {
					$this->db->where('id', $profile_id)->update('tc_profiles', $profile_data);
				} else {
					// Profile ID doesn't match user, create new one
					$profile_data['user_id'] = $user_id;
					$this->db->insert('tc_profiles', $profile_data);
					$profile_id = $this->db->insert_id();
				}
			} else {
				$profile_data['user_id'] = $user_id;
				$this->db->insert('tc_profiles', $profile_data);
				$profile_id = $this->db->insert_id();
			}

			// Validate profile_id before proceeding
			if (!$profile_id) {
				throw new Exception('Failed to create/update profile');
			}

			// 2. Handle Education
			$this->handle_education($profile_id, $education);

			// 3. Handle Skills (Tags)
			$this->handle_skills($profile_id, $skills, $skill_ids);

			// 4. Handle Certifications (Tags)
			$this->handle_certs($profile_id, $certs, $cert_ids);

			// 5. Handle Awards
			$this->handle_awards($profile_id, $awards);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				return false;
			}

			return true;

		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', 'Error saving profile: ' . $e->getMessage());
			return false;
		}
	}

	/**
	 * Handle Education records
	 */
	private function handle_education($profile_id, $education)
	{
		// Get existing education IDs
		$existing_edu = $this->db->select('id')
			->from('tc_education')
			->where('profile_id', $profile_id)
			->get()->result_array();
		$existing_edu_ids = array_column($existing_edu, 'id');

		// Get submitted education IDs (filter out empty values)
		$submitted_edu_ids = [];
		if (!empty($education)) {
			foreach ($education as $edu) {
				if (!empty($edu['id'])) {
					$submitted_edu_ids[] = $edu['id'];
				}
			}
		}

		// Calculate IDs to delete
		$to_delete_edu = array_diff($existing_edu_ids, $submitted_edu_ids);

		// Delete removed education
		if (!empty($to_delete_edu)) {
			$this->db->where_in('id', $to_delete_edu)->delete('tc_education');
		}

		// Update or Insert education
		if (!empty($education)) {
			foreach ($education as $edu) {
				// Always set profile_id
				$edu['profile_id'] = $profile_id;

				if (!empty($edu['id'])) {
					// Update existing
					$update_data = [
						'degree_title' => $edu['degree_title'] ?? '',
						'institution' => $edu['institution'] ?? '',
						'passing_year' => $edu['passing_year'] ?? ''
					];
					$this->db->where('id', $edu['id'])->update('tc_education', $update_data);
				} else {
					// Insert new - only if at least one field has value
					if (!empty($edu['degree_title']) || !empty($edu['institution'])) {
						$insert_data = [
							'profile_id' => $profile_id,
							'degree_title' => $edu['degree_title'] ?? '',
							'institution' => $edu['institution'] ?? '',
							'passing_year' => $edu['passing_year'] ?? ''
						];
						$this->db->insert('tc_education', $insert_data);
					}
				}
			}
		}
	}

	/**
	 * Handle Skills (Tags)
	 */
	private function handle_skills($profile_id, $skills, $skill_ids)
	{
		// Get existing skill IDs
		$existing_skills = $this->db->select('id')
			->from('tc_tags')
			->where(['profile_id' => $profile_id, 'tag_type' => 'skill'])
			->get()->result_array();
		$existing_skill_ids = array_column($existing_skills, 'id');

		// Filter out empty IDs from submitted
		$valid_skill_ids = array_filter($skill_ids, function ($id) {
			return !empty($id);
		});

		// Calculate IDs to delete
		$to_delete_skills = array_diff($existing_skill_ids, $valid_skill_ids);

		if (!empty($to_delete_skills)) {
			$this->db->where_in('id', $to_delete_skills)->delete('tc_tags');
		}

		// Insert new skills
		if (!empty($skills)) {
			foreach ($skills as $key => $skill) {
				$skill = trim($skill);

				// Skip empty skills
				if (empty($skill)) {
					continue;
				}

				// If no existing ID, check if it's a new skill
				if (empty($skill_ids[$key])) {
					// Check if skill already exists for this profile
					$exists = $this->db->get_where('tc_tags', [
						'profile_id' => $profile_id,
						'tag_name' => $skill,
						'tag_type' => 'skill'
					])->num_rows();

					if ($exists == 0) {
						$this->db->insert('tc_tags', [
							'profile_id' => $profile_id,
							'tag_name' => $skill,
							'tag_type' => 'skill'
						]);
					}
				}
				// If skill has ID, it's already in DB, no action needed
			}
		}
	}

	/**
	 * Handle Certifications (Tags)
	 */
	private function handle_certs($profile_id, $certs, $cert_ids)
	{
		// Get existing cert IDs
		$existing_certs = $this->db->select('id')
			->from('tc_tags')
			->where(['profile_id' => $profile_id, 'tag_type' => 'cert'])
			->get()->result_array();
		$existing_cert_ids = array_column($existing_certs, 'id');

		// Filter out empty IDs from submitted
		$valid_cert_ids = array_filter($cert_ids, function ($id) {
			return !empty($id);
		});

		// Calculate IDs to delete
		$to_delete_certs = array_diff($existing_cert_ids, $valid_cert_ids);

		if (!empty($to_delete_certs)) {
			$this->db->where_in('id', $to_delete_certs)->delete('tc_tags');
		}

		// Insert new certs
		if (!empty($certs)) {
			foreach ($certs as $key => $cert) {
				$cert = trim($cert);

				// Skip empty certs
				if (empty($cert)) {
					continue;
				}

				// If no existing ID, check if it's a new cert
				if (empty($cert_ids[$key])) {
					// Check if cert already exists for this profile
					$exists = $this->db->get_where('tc_tags', [
						'profile_id' => $profile_id,
						'tag_name' => $cert,
						'tag_type' => 'cert'
					])->num_rows();

					if ($exists == 0) {
						$this->db->insert('tc_tags', [
							'profile_id' => $profile_id,
							'tag_name' => $cert,
							'tag_type' => 'cert'
						]);
					}
				}
			}
		}
	}

	/**
	 * Handle Awards
	 */
	private function handle_awards($profile_id, $awards)
	{
		// Get existing award IDs
		$existing_awards = $this->db->select('id')
			->from('tc_awards')
			->where('profile_id', $profile_id)
			->get()->result_array();
		$existing_award_ids = array_column($existing_awards, 'id');

		// Get submitted award IDs
		$submitted_award_ids = [];
		if (!empty($awards)) {
			foreach ($awards as $award) {
				if (!empty($award['id'])) {
					$submitted_award_ids[] = $award['id'];
				}
			}
		}

		$to_delete_awards = array_diff($existing_award_ids, $submitted_award_ids);

		if (!empty($to_delete_awards)) {
			$this->db->where_in('id', $to_delete_awards)->delete('tc_awards');
		}

		// Update or Insert awards
		if (!empty($awards)) {
			foreach ($awards as $award) {
				// Skip empty awards
				if (empty($award['award_name']) && empty($award['award_meta'])) {
					continue;
				}

				if (!empty($award['id'])) {
					// Update existing
					$update_data = [
						'award_name' => $award['award_name'] ?? '',
						'award_meta' => $award['award_meta'] ?? ''
					];
					$this->db->where('id', $award['id'])->update('tc_awards', $update_data);
				} else {
					// Insert new
					$insert_data = [
						'profile_id' => $profile_id,
						'award_name' => $award['award_name'] ?? '',
						'award_meta' => $award['award_meta'] ?? ''
					];
					$this->db->insert('tc_awards', $insert_data);
				}
			}
		}
	}

	/**
	 * Get basic profile info
	 */
	public function get_teacher_profile($user_id)
	{
		$query = $this->db->get_where('tc_profiles', ['user_id' => $user_id]);

		if ($query->num_rows() > 0) {
			return $query->row();
		} else {
			return (object) [
				'id' => null,
				'speciality' => '',
				'secondary_languages' => ''
			];
		}
	}

	/**
	 * Get teacher education
	 */
	public function get_teacher_education($user_id)
	{
		return $this->db->select('e.*')
			->from('tc_education e')
			->join('tc_profiles p', 'p.id = e.profile_id')
			->where('p.user_id', $user_id)
			->order_by('e.passing_year', 'DESC')
			->get()->result();
	}

	/**
	 * Get teacher skills
	 */
	public function get_teacher_skills($user_id)
	{
		return $this->db->select('t.*')
			->from('tc_tags t')
			->join('tc_profiles p', 'p.id = t.profile_id')
			->where(['p.user_id' => $user_id, 't.tag_type' => 'skill'])
			->order_by('t.tag_name', 'ASC')
			->get()->result();
	}

	/**
	 * Get teacher certifications
	 */
	public function get_teacher_certs($user_id)
	{
		return $this->db->select('t.*')
			->from('tc_tags t')
			->join('tc_profiles p', 'p.id = t.profile_id')
			->where(['p.user_id' => $user_id, 't.tag_type' => 'cert'])
			->order_by('t.tag_name', 'ASC')
			->get()->result();
	}

	/**
	 * Get teacher awards
	 */
	public function get_teacher_awards($user_id)
	{
		return $this->db->select('a.*')
			->from('tc_awards a')
			->join('tc_profiles p', 'p.id = a.profile_id')
			->where('p.user_id', $user_id)
			->order_by('a.id', 'DESC')
			->get()->result();
	}

	/**
	 * Delete education record - FIXED: Accept user_id as parameter
	 */
	public function delete_education($id, $user_id = null)
	{
		// If user_id not provided, try to get from session
		if ($user_id === null) {
			$user_id = $this->session->userdata('oliveMobileSystemUser');
		}

		if (!$user_id) {
			return false;
		}

		// Verify ownership before deletion
		$exists = $this->db->select('e.id')
			->from('tc_education e')
			->join('tc_profiles p', 'p.id = e.profile_id')
			->where('e.id', $id)
			->where('p.user_id', $user_id)
			->get()->row();

		if ($exists) {
			return $this->db->where('id', $id)->delete('tc_education');
		}

		return false;
	}

	/**
	 * Delete tag (skill/cert) - FIXED: Accept user_id as parameter
	 */
	public function delete_tag($id, $user_id = null)
	{
		// If user_id not provided, try to get from session
		if ($user_id === null) {
			$user_id = $this->session->userdata('oliveMobileSystemUser');
		}

		if (!$user_id) {
			return false;
		}

		// Verify ownership before deletion
		$exists = $this->db->select('t.id')
			->from('tc_tags t')
			->join('tc_profiles p', 'p.id = t.profile_id')
			->where('t.id', $id)
			->where('p.user_id', $user_id)
			->get()->row();

		if ($exists) {
			return $this->db->where('id', $id)->delete('tc_tags');
		}

		return false;
	}

	/**
	 * Delete award record - FIXED: Accept user_id as parameter
	 */
	public function delete_award($id, $user_id = null)
	{
		// If user_id not provided, try to get from session
		if ($user_id === null) {
			$user_id = $this->session->userdata('oliveMobileSystemUser');
		}

		if (!$user_id) {
			return false;
		}

		// Verify ownership before deletion
		$exists = $this->db->select('a.id')
			->from('tc_awards a')
			->join('tc_profiles p', 'p.id = a.profile_id')
			->where('a.id', $id)
			->where('p.user_id', $user_id)
			->get()->row();

		if ($exists) {
			return $this->db->where('id', $id)->delete('tc_awards');
		}

		return false;
	}
	//  Code by Hassan ends here 
}