<?php
class Student_model extends CI_model
{
	function get_lession_details($student_id, $acdm)
	{
		$std_data = $this->db->query("SELECT STVCLAS_standard FROM stvclas WHERE STVCLAS_student_id = '$student_id'AND STVCLAS_acdm = $acdm")->row_array();
		$std = isset($std_data['STVCLAS_standard']) ? $std_data['STVCLAS_standard'] : '0';
		$year = date('Y');
		return $this->db->query("SELECT spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name, stvlmst.stvlmst_std, stvlmst.stvlmst_lesson, stvlmst.stvlmst_teacher_comment, stvsubj.stvsubj_subject, stvlmst.stvlmst_teached_at, stvlmst.stvlmst_id FROM spriden, stvlmst, stvsubj WHERE spriden.SPRIDEN_student_id = stvlmst.stvlmst_lesson_status_created_by AND stvlmst.stvlmst_sub = stvsubj.stvsubj_id AND stvlmst_std = '$std' AND YEAR(stvlmst.stvlmst_teached_at)='$year'")->result_array();
	}

	function getstudentDetails($id)
	{
		return $this->db->query("SELECT s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name, spriden_auth_token,
		(SELECT concat(STVCLAS_standard) FROM stvclas WHERE STVCLAS_student_id = s.id
        AND STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool)) AS class,
        (SELECT concat(STVCLAS_class) FROM stvclas WHERE STVCLAS_student_id = s.id
        AND STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool)) AS division
		FROM spriden s WHERE s.id = '$id'")->row_array();
	}

	function get_allsubjects($std)
	{
		$sql = "SELECT * FROM stvsubj WHERE stvsubj_std = '$std' and stvsubj_subject IN ('English','Science', 'Maths','English Grammer','Algebra','ScienceI','ScienceII','Geometry','Hindi','Marathi','History','Geography','Grammer','Islamic Studies','Computer','EVS 1','EVS 2','Learning')";
		$data = $this->db->query($sql);
		return $data->result_array();
	}

	function fetchMaxDue_date($id, $acdm)
	{
		return $this->db->query("SELECT max(sprfees_due_date) due_date FROM sprfees WHERE sprfees_studentid = '$id' AND academic_year = $acdm")->row_array();
	}

	function addDueDateAction($acdm_year)
	{
		$student_id = $this->input->post('student_id');
		$due_date = $this->input->post('due_date');
		$exist = $this->db->query("SELECT * FROM notification_tracker WHERE student_id = '$student_id' AND due_date = '$due_date'")->row_array();
		if ($exist == null) {
			// insert
			$data = array(
				'student_id' => $student_id,
				'source' => 'Student Profile',
				'action_date' => date('Y-m-d'),
				'due_date' => $due_date,
				'counter' => 1
			);
			if ($this->db->insert('notification_tracker', $data)) {
				echo 'success';
			} else {
				echo 'fail';
			}
		} else {
			$id = $exist['id'];
			$counter = (float) $exist['counter'];
			$data = array(
				'counter' => $counter + 1,
			);
			if ($this->db->where('id', $id)->update('notification_tracker', $data)) {
				echo 'success';
			} else {
				echo 'fail';
			}
		}
	}

	function fetchCounterValue($id, $acdm)
	{
		$data = $this->db->query("SELECT ifnull(counter,0) AS total FROM notification_tracker WHERE student_id = '$id'")->row_array();
		return $data == null ? 1 : $data['total'] + 1;
	}

	function fetchStudentDetails($id)
	{
		return $this->db->query("SELECT s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
		(SELECT concat(STVCLAS_standard, ' - ', STVCLAS_class) FROM stvclas WHERE STVCLAS_student_id = s.id AND STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool)) AS class,
		(SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_acdm_year IN (SELECT stvschool_acdm_year FROM stvschool) AND stvfstr_std IN (SELECT STVCLAS_standard FROM stvclas WHERE STVCLAS_student_id = s.id AND STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool))) AS total_fees,
		(SELECT sum(sprfees_amount) FROM sprfees WHERE sprfees_studentid = s.id AND academic_year IN (SELECT stvschool_acdm_year FROM stvschool)) AS fees_paid
		FROM spriden s WHERE s.id = $id")->row_array();
	}

	function fetchFeesDetails($id)
	{
		return $this->db->query("SELECT s.sprfees_amount,s.sprfees_feesdate,s.sprfees_timestamp,s.sprfees_notes,s.sprfees_due_date,s.sprfees_source,
		(SELECT spruser_name FROM spruser WHERE spruser_id = s.sprfees_recivedby) AS rec_by
		FROM sprfees s WHERE s.sprfees_studentid = $id AND s.academic_year IN (SELECT stvschool_acdm_year FROM stvschool)")->result_array();
	}

	// function get_all_fees_data_of_student($student_id)
	// {
	// 	return $this->db->query("SELECT *,
	// 			(class_fees - total_paid) AS balance,
	// 			((class_fees - total_paid) - total_exumpted) AS final_balance
	// 			FROM (SELECT acd.stvacdm_year,f.academic_year,c.STVCLAS_standard sprfees_standard,f.sprfees_studentid,sum(f.sprfees_amount) total_paid,
	// 			sum(f.sprfees_exumption) total_exumpted,
	// 			(SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = f.academic_year) class_fees,
	// 			(SELECT group_concat(sf.sprfees_standard,'->', sf.sprfees_amount,'->', sf.sprfees_feesdate,'->',sf.sprfees_exumption,'->',
	// 			IFNULL(u.spruser_name,'NA'), '->', sf.sprfees_notes, '->', sf.payment_mode) 
	// 			FROM sprfees sf
	// 			LEFT JOIN  spruser u ON u.spruser_id = sf.sprfees_recivedby
	// 			WHERE sf.academic_year = f.academic_year AND sf.sprfees_studentid = $student_id
	// 			) installments
	// 			FROM sprfees f, stvclas c,stvacdm acd
	// 			WHERE f.academic_year NOT IN (1,2,3,4)
	// 			AND f.sprfees_studentid = $student_id
	// 			AND acd.stvacdm_id = f.academic_year AND c.STVCLAS_acdm = acd.stvacdm_id
	// 			AND c.STVCLAS_student_id = f.sprfees_studentid AND c.STVCLAS_acdm = f.academic_year 
	// 			GROUP BY f.academic_year,f.sprfees_studentid
	// 			ORDER BY 2 DESC ) AS temp")->result_array();
	// }

	function get_all_fees_data_of_student($student_id)
	{
		return $this->db->query("
		SELECT *,
			(class_fees - total_paid) AS balance,
			((class_fees - total_paid) - total_exumpted) AS final_balance
		FROM
		(
			SELECT
				acd.stvacdm_year,
				c.STVCLAS_acdm AS academic_year,
				c.STVCLAS_standard AS sprfees_standard,
				c.STVCLAS_student_id AS sprfees_studentid,

				IFNULL(SUM(f.sprfees_amount),0) AS total_paid,
				IFNULL(SUM(f.sprfees_exumption),0) AS total_exumpted,

				(
					SELECT stvfstr_fees
					FROM stvfstr
					WHERE stvfstr_std = c.STVCLAS_standard
					AND stvfstr_acdm_year = c.STVCLAS_acdm
					LIMIT 1
				) AS class_fees,

				(
					SELECT GROUP_CONCAT(
						sf.sprfees_standard,'->',
						sf.sprfees_amount,'->',
						sf.sprfees_feesdate,'->',
						sf.sprfees_exumption,'->',
						IFNULL(u.spruser_name,'NA'),'->',
						sf.sprfees_notes,'->',
						sf.payment_mode
						ORDER BY sf.sprfees_feesdate
						SEPARATOR ','
					)
					FROM sprfees sf
					LEFT JOIN spruser u
						ON u.spruser_id = sf.sprfees_recivedby
					WHERE sf.academic_year = c.STVCLAS_acdm
					AND sf.sprfees_studentid = c.STVCLAS_student_id
				) AS installments

			FROM stvclas c

			INNER JOIN stvacdm acd
				ON acd.stvacdm_id = c.STVCLAS_acdm

			LEFT JOIN sprfees f
				ON f.sprfees_studentid = c.STVCLAS_student_id
				AND f.academic_year = c.STVCLAS_acdm

			WHERE c.STVCLAS_student_id = '$student_id'
			AND c.STVCLAS_acdm NOT IN (1,2,3,4)

			GROUP BY c.STVCLAS_acdm, c.STVCLAS_student_id

			ORDER BY c.STVCLAS_acdm DESC

		) temp
	")->result_array();
	}


	//fetch greater id 

	function get_MaxID()
	{
		$maxid = 0;
		$row = $this->db->query('SELECT MAX(SPRIDEN_student_id) AS `maxid` FROM spriden WHERE SPRIDEN_user_type="st"')->row();
		if ($row) {
			return $maxid = $row->maxid;
		}
	}

	function get_MaxID_By_Acdm($acdm_year)
	{
		$maxid = 0;
		$row = $this->db->query('SELECT MAX(SPRIDEN_student_id) AS `maxid` FROM spriden WHERE SPRIDEN_user_type="st" and SPRIDEN_student_id like "S' . $acdm_year . '%"')->row();
		if ($row) {
			return $maxid = $row->maxid;
		}
	}


	// function student_gr_contact($std, $div)
	// {
	// 	$sql = "SELECT s.SPRIDEN_student_id,s.spriden_gr_number,s.SPRIDEN_reg_number,s.SPRIDEN_aadhar_no, concat(s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name, ' ' ,s.SPRIDEN_surname) AS name,s.SPRIDEN_birthdate,s.SPRIDEN_mother_name,s.SPRIDEN_gender,c.STVCLAS_standard, c.STVCLAS_class,
	// 	(SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id) AS mobile FROM spriden s, stvclas c WHERE s.SPRIDEN_user_type = 'st' AND c.STVCLAS_student_id = s.id AND c.STVCLAS_standard = '$std' AND s.spriden_student_status = 'A' AND c.STVCLAS_acdm in (SELECT stvschool_acdm_year FROM stvschool) ";
	// 	if ($div != '') {
	// 		$sql .= " and c.STVCLAS_class = '$div' ";
	// 	}
	// 	return $this->db->query($sql)->result_array();
	// }

	function student_gr_contact($std, $div = '', $year = '')
	{
		$sql = "SELECT
                s.SPRIDEN_student_id,
                s.spriden_gr_number,
                s.SPRIDEN_reg_number,
                s.SPRIDEN_aadhar_no,
                CONCAT(
                    s.SPRIDEN_student_name,' ',
                    s.SPRIDEN_father_name,' ',
                    s.SPRIDEN_surname
                ) AS name,
                s.SPRIDEN_birthdate,
                s.SPRIDEN_mother_name,
                s.SPRIDEN_gender,
                c.STVCLAS_standard,
                c.STVCLAS_class,
                (
                    SELECT stvpcnf_mobile
                    FROM stvpcnf
                    WHERE stvpcnf_student_id = s.id
                    LIMIT 1
                ) AS mobile
            FROM spriden s
            INNER JOIN stvclas c
                ON c.STVCLAS_student_id = s.id
            WHERE
                s.SPRIDEN_user_type = 'st'
                AND s.spriden_student_status = 'A'
                AND c.STVCLAS_standard = '$std'";

		if ($div != '') {
			$sql .= " AND c.STVCLAS_class = '$div'";
		}

		if ($year != '') {
			$sql .= " AND c.STVCLAS_acdm = '$year'";
		}

		$sql .= " ORDER BY c.STVCLAS_rollno ASC";

		return $this->db->query($sql)->result_array();
	}

	//Function to check student record exist or not
	function check_if_exist($aahdhar_no)
	{
		$this->db->where('id', $aahdhar_no);
		$this->db->where('SPRIDEN_user_type', "st");
		$student = $this->db->get('spriden')->result_array();
		if ($student == null) {
			return 0;
		} else {
			return count($student);
		}
	}

	//Add Student Data
	function register_student($data)
	{
		$status = $this->db->insert('spriden', $data);
		$last_insert = $this->db->insert_id();
		if ($status) {
			// ------------- Save data into SPRFEES table
			$fees_data = array(
				'sprfees_studentid' => $last_insert,
				'sprfees_standard' => $this->input->post('admission_class'),
				'sprfees_amount' => 0,
				'sprfees_feesdate' => date('Y-m-d'),
				'sprfees_recivedby' => $this->session->userdata('oliveSchoolDashboardUser'),
				'sprfees_notes' => "",
				'sprfees_due_date' => date('Y-m-d')
			);
			$status = $this->db->insert('sprfees', $fees_data);
			return true;
		} else {
			return false;
		}
	}

	//Update Student Data
	function update_student($data, $student_id, $pre_id)
	{
		$this->db->where('id', $student_id);
		$status = $this->db->update('spriden', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	//Search Student Names in database
	function search_student_names($student_name)
	{
		$sql = 'SELECT spriden.*, stvclas.* FROM spriden
		 INNER JOIN stvclas ON stvclas.STVCLAS_student_id = spriden.id
		 WHERE concat(spriden.SPRIDEN_surname, " " , spriden.SPRIDEN_student_name, 
		 " " ,spriden.SPRIDEN_father_name) LIKE "%' . $student_name . '%" AND 
		 spriden.SPRIDEN_user_type = "st" ORDER BY stvclas.STVCLAS_standard';
		return $this->db->query($sql)->result_array();
	}

	function get_selected_student_data($student_id)
	{
		$this->db->where('id', $student_id);
		return $student_info = $this->db->get('spriden')->row_array();
	}

	function get_previous_transaction($student_id)
	{
		$this->db->where('visiting_student_id', $student_id);
		return $student_info = $this->db->get('visiting')->result_array();
	}

	//Search Student Names in for fees
	function search_from_spofees($student_name)
	{
		$this->db->select('*');
		$this->db->from('spofees');
		$this->db->like('SPRIDEN_surname', $student_name);
		$this->db->or_like('SPRIDEN_student_name', $student_name);
		$this->db->or_like('SPRIDEN_father_name', $student_name);
		$this->db->order_by('sprfees_standard', 'asc');
		$this->db->where('spriden_student_status = A');
		$student = $this->db->get();
		return $student->result_array();
	}

	function get_search_from_spofees($student_id)
	{
		$this->db->where('spIdInc', $student_id);
		return $this->db->get('spofees')->row_array();
	}

	function getSelectedStudentBooksData($student_id)
	{
		$this->db->where('STVBOOK_student_id', $student_id);
		return $this->db->get('stvbook')->row_array();
	}

	// Check user eist in Books & Result Table or not
	function check_books_data_available($student_id)
	{
		$this->db->where('STVBOOK_student_id', $student_id);
		$available = $this->db->get('stvbook')->row_array();
		if ($available == null) {
			return 0;
		} else {
			return count($available);
		}
	}

	//Add Books Data
	function add_books_and_result_data($data)
	{
		$status = $this->db->insert('stvbook', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	//Update Books Data
	function get_updater_name($updater_id)
	{
		$this->db->where('spruser_id', $updater_id);
		return $this->db->get('spruser')->row_array();
	}

	function update_books_and_result_data($data, $student_id)
	{
		$this->db->where('STVBOOK_student_id', $student_id);
		$status = $this->db->update('stvbook', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	//Get Books & Result Data
	function get_books_and_result_data_all()
	{
		$this->db->select('spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvbook.*');
		$this->db->from('spriden');
		$this->db->join('stvbook', 'stvbook.STVBOOK_student_id = spriden.SPRIDEN_student_id');
		$this->db->order_by('STVBOOK_standard', 'asc');
		$students = $this->db->get();
		return $students->result_array();
	}

	//Get Sprfees Data
	function get_fees_data_of_student($student_id, $acdm_year)
	{
		return $this->db->query("SELECT sprfees.*, stvacdm.stvacdm_year, spruser.spruser_name,
		(SELECT exumption FROM sprexumtion WHERE exu_studentid = sprfees.sprfees_studentid AND acdm_year  = $acdm_year) exumption
		FROM sprfees, stvacdm , spruser
		WHERE stvacdm.stvacdm_id = sprfees.academic_year AND sprfees.sprfees_studentid = " . $student_id . "
		AND sprfees.academic_year = $acdm_year
		AND sprfees.sprfees_recivedby = spruser.spruser_id
		ORDER BY stvacdm.stvacdm_year desc")->result_array();
	}

	//Add Fees Data
	function add_fees_data($data)
	{
		$status = $this->db->insert('sprfees', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function getexumption($student_id)
	{
		$sql = "SELECT exumption FROM sprexumtion WHERE exu_studentid = '$student_id'";
		return $this->db->query($sql)->row_array();
	}

	//Get All Students
	function get_all_student_details()
	{
		$this->db->where('SPRIDEN_user_type', "st");
		return $this->db->get('spriden')->result_array();
	}

	//Create New Class
	function addNewClass($std, $div, $students)
	{
		$acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$acdm_year = $acdm['stvschool_acdm_year'];
		for ($i = 0; $i < count($students); $i++) {
			$data = array(
				'STVCLAS_student_id' => $students[$i],
				'STVCLAS_standard' => $std,
				'STVCLAS_class' => $div,
				'STVCLAS_userid' => $this->session->userdata('oliveSchoolDashboardUser'),
				'STVCLAS_acdm' => $acdm_year
			);
			$status = $this->db->insert('stvclas', $data);
		}
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	//Get Data for class view
	function getDataForClass()
	{
		$academic_year = $this->input->post('acdm_year');
		$std = $this->input->post('class');
		$div = $this->input->post('divi');
		$this->db->select('spriden.*, stvclas.*');
		$this->db->from('spriden');
		$this->db->join('stvclas', 'stvclas.STVCLAS_student_id = spriden.id');
		$this->db->where('STVCLAS_standard', $std);
		$this->db->where('STVCLAS_acdm', $academic_year);
		$this->db->where('SPRIDEN_student_status', 'A');
		// Division filter only if selected
		if (!empty($div)) {
			$this->db->where('STVCLAS_class', $div);
		}
		$this->db->order_by('CAST(stvclas.STVCLAS_rollno AS UNSIGNED)', 'ASC');
		$students = $this->db->get();
		return $students->result_array();
	}

	//Fill Attendance
	function fillStudentsAttendance($date, $std, $div, $students, $status_of)
	{
		for ($i = 0; $i < count($students); $i++) {
			$data = array(
				'SPRATND_teacher_id' => $this->session->userdata('oliveSchoolDashboardUser'),
				'SPRATND_student_id' => $students[$i],
				'SPRATND_date' => $date,
				'SPRATND_std' => $std,
				'SPRATND_div' => $div,
				'SPRATND_status' => $status_of[$i]
			);
			$status = $this->db->insert('spratnd', $data);
		}
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	//Add Communication details into db
	function add_communication_dt($data)
	{
		$status = $this->db->insert('stvpcnf', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	// ===================================== STUDENT PROFILE DATA ============================
	function get_spriden_data($student_id)
	{
		$sql = "SELECT s.*,st.stvpcnf_whatsapp FROM spriden s,stvpcnf st WHERE s.id=st.stvpcnf_student_id AND s.id='$student_id'";
		return $this->db->query($sql)->row_array();
	}

	function get_sprfees_data($student_id, $academic_year)
	{

		$this->db->where('sprfees_studentid', $student_id);
		$this->db->where('academic_year', $academic_year);
		return $this->db->get('sprfees')->result_array();
	}

	function class_details($student_id, $acdm_year)
	{
		$this->db->where('STVCLAS_student_id', $student_id);
		$this->db->where('STVCLAS_acdm', $acdm_year);
		return $this->db->get('stvclas')->row_array();
	}

	function get_fees_details($student_id, $academic_year)
	{
		$std_data = $this->db->query("SELECT STVCLAS_standard FROM stvclas WHERE STVCLAS_student_id = $student_id AND STVCLAS_acdm = $academic_year")->row_array();
		$std = $std_data != null ? $std_data['STVCLAS_standard'] : 0;
		$sql = "SELECT f.sprfees_amount,f.sprfees_feesdate,f.sprfees_recivedby, s.id, u.spruser_name AS submited_to
		FROM sprfees f, spriden s, spruser u
		WHERE academic_year = $academic_year
		AND u.spruser_id = f.sprfees_recivedby
		AND f.sprfees_studentid = s.id AND s.id = '$student_id'";
		// die($sql);
		return $this->db->query($sql)->result_array();
	}
	function get_classfees_details($student_id, $academic_year)
	{
		$std_data = $this->db->query("SELECT STVCLAS_standard FROM stvclas WHERE STVCLAS_student_id = $student_id AND STVCLAS_acdm = $academic_year")->row_array();

		$std = $std_data != null ? $std_data['STVCLAS_standard'] : 0;
		$sql = "SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = '$std' AND stvfstr_acdm_year = $academic_year";
		// die($sql);
		return $this->db->query($sql)->row_array();
	}

	//Add Visiting Data
	function checkIfExistStudentData($id)
	{
		$this->db->where('visiting_id', $id);
		$data = $this->db->get('visiting')->row_array();
		if ($data == null) {
			return 0;
		} else {
			return count($data);
		}
	}

	function add_visiting_data($data)
	{
		$status = $this->db->insert('visiting', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function update_student_data($data, $id)
	{
		$this->db->where('visiting_id', $id);
		$status = $this->db->update('visiting', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function getAllVisitingData()
	{
		$sql = "SELECT visiting.*,spruser.*, spriden.*, stvclas.*, concat(spriden.SPRIDEN_surname, ' ',spriden.SPRIDEN_student_name, ' ',spriden.SPRIDEN_father_name) AS name FROM visiting, stvclas, spriden,spruser WHERE visiting.visiting_student_id = spriden.id AND visiting.visiting_added_by = spruser.spruser_id  AND visiting.visiting_student_fees_paid != 0 AND visiting.visiting_student_id = stvclas.STVCLAS_student_id";
		return $this->db->query($sql)->result_array();
	}

	function getStudentDataVisitingByID()
	{
		$this->db->where('visiting_student_id', $this->input->post('id'));
		return $this->db->get('visiting')->row_array();
	}

	function get_visiting_data($id)
	{
		$this->db->where('visiting_student_id', $id);
		return $this->db->get('visiting')->row_array();
	}

	function get_attendance_data($id, $acdm)
	{
		$this->db->where('SPRATND_student_id', $id);
		$this->db->where('MONTH(SPRATND_date)', date('m'));
		$this->db->where('YEAR(SPRATND_date)', date('Y'));
		return $this->db->get('spratnd')->result_array();
	}

	function get_remark_data($id)
	{
		$academic_year = $this->getCurrentAcademicYear();
		$this->db->where('stvrmrk_student_id', $id);
		$this->db->where("stvrmrk_remark != ''");
		$this->db->where('stvrmrk_academic_year', $academic_year);
		$this->db->order_by('stvrmrk_date', 'desc');
		return $this->db->get('stvrmrk')->result_array();
	}

	function get_remarks($std, $acdm)
	{
		return $this->db->query("SELECT r.stvrmrk_id, r.stvrmrk_teacher_id, r.stvrmrk_student_id, r.stvrmrk_std,r.stvrmrk_date,r.stvrmrk_remark,r.stvrmrk_reply,r.stvrmrk_date_of_reply,r.stvrmrk_status,r.stvrmrk_academic_year,
		(SELECT concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) FROM spriden s WHERE s.id = r.stvrmrk_student_id) AS student_name,
		(SELECT concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) FROM spriden s WHERE s.SPRIDEN_student_id = r.stvrmrk_teacher_id) AS teacher_name
		FROM stvrmrk r WHERE r.stvrmrk_std = '$std' AND r.stvrmrk_academic_year = $acdm")->result_array();
	}

	function get_stvclas_data($id, $academic_year)
	{
		$this->db->where('STVCLAS_acdm', $academic_year);
		$this->db->where('STVCLAS_student_id', $id);
		return $this->db->get('stvclas')->row_array();
	}

	function get_quiz_data($id)
	{
		$academic_year = $this->getCurrentAcademicYear();
		$quizDB = $this->load->database('QuizDB', TRUE);
		$quizDB->select('result.*, quiz.*');
		$quizDB->from('result');
		$quizDB->where('result.student_id', $id);
		$quizDB->where('quiz.academic_year', $academic_year);
		$quizDB->join('quiz', 'quiz.quiz_id = result.quiz_id');
		$quizDB->order_by("result.result_id", "desc");
		$data = $quizDB->get();
		return $data->result_array();
	}

	//functions for Attendance Details
	function getClassWiseAttendanceDetails()
	{
		$standard = $this->input->post('std');
		$division = $this->input->post('div');
		$sql = "SELECT spriden.id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, SPRATND_student_id,
				sum(CASE WHEN SPRATND_status='A' THEN 1 ELSE 0 END) AS Absent,
				sum(CASE WHEN SPRATND_status='P' THEN 1 ELSE 0 END) AS Present
				FROM spratnd, spriden WHERE SPRATND_std = '" . $standard . "' and SPRATND_div = '" . $division . "' and spratnd.SPRATND_student_id = spriden.id and MONTH(SPRATND_date) = MONTH(CURRENT_DATE())
				AND YEAR(SPRATND_date) = YEAR(CURRENT_DATE())
				GROUP BY SPRATND_student_id";
		return $this->db->query($sql)->result_array();
	}

	function getStudentNamesForAttendanceDetails()
	{
		$stad = $this->input->post('std');
		$divi = $this->input->post('div');
		$sql = "SELECT spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname,spriden.SPRIDEN_student_name,spriden.SPRIDEN_father_name, stvclas.* FROM spriden, stvclas WHERE stvclas.STVCLAS_standard = '" . $stad . "' and stvclas.STVCLAS_class = '" . $divi . "' and spriden.id = stvclas.STVCLAS_student_id";
		return $this->db->query($sql)->result_array();
	}

	function getStudentNamesForAttendanceByStad()
	{
		$stad = $this->input->post('std');
		$sql = "SELECT spriden.id, spriden.SPRIDEN_student_id, spriden.SPRIDEN_surname,spriden.SPRIDEN_student_name,spriden.SPRIDEN_father_name, stvclas.* FROM spriden, stvclas WHERE stvclas.STVCLAS_standard = '" . $stad . "' and spriden.id = stvclas.STVCLAS_student_id ORDER BY stvclas.STVCLAS_class";
		return $this->db->query($sql)->result_array();
	}

	function get_personal_details($student_id)
	{
		$sql = "SELECT spriden.id, spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name, stvclas.STVCLAS_standard , stvclas.STVCLAS_class FROM spriden, stvclas WHERE spriden.id = '" . $student_id . "' and spriden.id = stvclas.STVCLAS_student_id";
		return $this->db->query($sql)->row_array();
	}

	function display_all_std_att_by_day($get_date)
	{
		$sql = "SELECT SPRATND_std, sum(CASE WHEN SPRATND_status='A' THEN 1 ELSE 0 END) AS Absent,
				sum(CASE WHEN SPRATND_status='P' THEN 1 ELSE 0 END) AS Present FROM spratnd WHERE SPRATND_date = '" . $get_date . "' GROUP BY SPRATND_std";
		return $this->db->query($sql)->result_array();
	}

	function get_balance_data($studentID)
	{
		$this->db->where('spIdInc', $studentID);
		return $this->db->get('spofees')->row_array();
	}

	// function get_balance_info($id)
	// {
	// 	$sql = "SELECT class, acdm,s.stvacdm_year, paid, exumption, total, balance
	// FROM  (SELECT class,acdm, IFNULL(paid,0) paid, IFNULL(exumption,0) exumption, IFNULL(total,0) total,
	// IFNULL((total-(paid+exumption)),0) balance
	// FROM (
	// SELECT DISTINCT c.STVCLAS_standard as class,c.STVCLAS_acdm as acdm,
	// (SELECT SUM(sprfees_amount) total_paid FROM sprfees WHERE sprfees_studentid = '$id' AND academic_year = c.STVCLAS_acdm) paid,
	// (SELECT SUM(sprfees_exumption) total_paid FROM sprfees WHERE sprfees_studentid = '$id' AND academic_year = c.STVCLAS_acdm) exumption,
	// (SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = c.STVCLAS_acdm) total
	// FROM stvclas c WHERE STVCLAS_student_id = '$id' AND c.STVCLAS_acdm not in (select stvschool_acdm_year from stvschool)) as temp) AS TEMP LEFT JOIN stvacdm s on acdm = s.stvacdm_id
	// WHERE balance > 0";
	// 	return $this->db->query($sql)->result_array();
	// }

	function get_balance_info($id)
	{
		$sql = "SELECT 
				class,
				acdm,
				s.stvacdm_year,
				paid,
				exumption,
				total,
				balance
			FROM
			(
				SELECT
					c.STVCLAS_standard AS class,
					c.STVCLAS_acdm AS acdm,

					IFNULL(SUM(f.sprfees_amount),0) AS paid,
					IFNULL(SUM(f.sprfees_exumption),0) AS exumption,

					IFNULL(
						(
							SELECT stvfstr_fees
							FROM stvfstr
							WHERE stvfstr_std = c.STVCLAS_standard
							AND stvfstr_acdm_year = c.STVCLAS_acdm
							LIMIT 1
						),0
					) AS total,

					(
						IFNULL(
							(
								SELECT stvfstr_fees
								FROM stvfstr
								WHERE stvfstr_std = c.STVCLAS_standard
								AND stvfstr_acdm_year = c.STVCLAS_acdm
								LIMIT 1
							),0
						)
						-
						(
							IFNULL(SUM(f.sprfees_amount),0)
							+
							IFNULL(SUM(f.sprfees_exumption),0)
						)
					) AS balance

				FROM stvclas c

				LEFT JOIN sprfees f
					ON f.sprfees_studentid = c.STVCLAS_student_id
					AND f.academic_year = c.STVCLAS_acdm

				WHERE c.STVCLAS_student_id = '$id'
				AND c.STVCLAS_acdm NOT IN
				(
					SELECT stvschool_acdm_year
					FROM stvschool
				)

				GROUP BY
					c.STVCLAS_standard,
					c.STVCLAS_acdm
			) TEMP

			LEFT JOIN stvacdm s
				ON TEMP.acdm = s.stvacdm_id

			WHERE balance > 0

			ORDER BY acdm DESC";

		return $this->db->query($sql)->result_array();
	}

	function current_balance($studentID, $acdm)
	{

		$data = $this->db->query("select (select stvfstr_fees from stvfstr where stvfstr_acdm_year = $acdm and stvfstr_std in (select STVCLAS_standard from stvclas where STVCLAS_student_id = '$studentID' and STVCLAS_acdm = $acdm)) 
		- (select sum(sprfees_amount) total_paid from sprfees where sprfees_studentid = '$studentID' and  academic_year = $acdm) balance from dual")->row_array();
		if ($data == null) {
			return 0;
		} else {
			return $data['balance'];
		}
	}

	function get_all_events($student_id, $academic_year)
	{
		$this->db->where('STVCLAS_acdm', $academic_year);
		$this->db->where('STVCLAS_student_id', $student_id);
		$student = $this->db->get('stvclas')->row_array();
		$this->db->where('stvevnt_std', isset($student['STVCLAS_standard']) ? $student['STVCLAS_standard'] : '0');
		$this->db->where('stvevnt_div', isset($student['STVCLAS_class']) ? $student['STVCLAS_class'] : '0');
		$this->db->where('stvevnt_academic_year', $academic_year);
		return $this->db->get('stvevnt')->result_array();
	}

	//============== HOLD Data ============================
	function add_student_hold_data($data)
	{
		$status = $this->db->insert('sprhold', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function get_all_hold_data()
	{
		$sql = 'SELECT sprhold.*, spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, " ", spriden.SPRIDEN_student_name, " ", spriden.SPRIDEN_father_name) as name, stvhold.stvhold_type from spriden,stvhold,sprhold WHERE sprhold.sprhold_hold_id = stvhold.stvhold_id and sprhold.sprhold_student_id = spriden.id';
		return $this->db->query($sql)->result_array();
	}

	function get_studenthold_data($id)
	{
		$sql = "SELECT sprhold.*, stvhold.* FROM sprhold, stvhold WHERE sprhold.sprhold_student_id = " . $id . " and sprhold.sprhold_hold_id = stvhold.stvhold_id";
		return $this->db->query($sql)->row_array();
	}

	function filterVisitingData($col_name, $input_data)
	{
		$sql = "SELECT visiting.*,spruser.*, spriden.*, stvclas.*, concat(spriden.SPRIDEN_surname, ' ',spriden.SPRIDEN_student_name, ' ',spriden.SPRIDEN_father_name) AS name from visiting, stvclas, spriden,spruser WHERE visiting.visiting_student_id = spriden.id AND visiting.visiting_added_by = spruser.spruser_id AND visiting.visiting_student_fees_paid != 0 AND visiting.visiting_student_id = stvclas.STVCLAS_student_id AND visiting." . $col_name . " LIKE '%" . $input_data . "%' ORDER BY stvclas.STVCLAS_standard,stvclas.STVCLAS_class";
		return $this->db->query($sql)->result_array();
	}

	function updateStudentClass()
	{
		$id = $this->input->post('student_id');
		$std = $this->input->post('std');
		$divi = $this->input->post('divi');
		$year = $this->input->post('year');
		$sql = "UPDATE stvclas SET STVCLAS_standard = '" . $std . "', STVCLAS_class = '" . $divi . "' WHERE STVCLAS_student_id = " . $id . " AND STVCLAS_acdm = " . $year . "";
		$status = $this->db->query($sql);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	// function updateStudentClass()
	// {
	// 	$id = $this->input->post('student_id');
	// 	$std = $this->input->post('std');
	// 	$divi = $this->input->post('divi');
	// 	$sql = "UPDATE stvclas SET STVCLAS_standard = '" . $std . "', STVCLAS_class = '" . $divi . "' WHERE STVCLAS_student_id = " . $id . "";
	// 	$status = $this->db->query($sql);
	// 	if ($status) {
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

	// Visiting Data for Update
	function getVisitingDataForUpdate()
	{
		$id = $this->input->post('id');
		$row_id = $this->input->post('row_id');
		$sql = "select spriden.id, spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name, stvclas.STVCLAS_standard from spriden, visiting, stvclas WHERE visiting.visiting_student_id = spriden.id and visiting.visiting_student_id = " . $id . " and stvclas.STVCLAS_student_id = visiting.visiting_student_id";
		$data['info'] = $this->db->query($sql)->row_array();
		$sql1 = "SELECT * FROM `visiting` WHERE visiting_student_id = " . $id . "";
		$data['visiting_rows'] = $this->db->query($sql1)->result_array();
		$sql2 = "SELECT * FROM `visiting` WHERE visiting_id = " . $row_id . "";
		$data['visiting_data'] = $this->db->query($sql2)->row_array();
		return $data;
	}

	function deleteVisitingDataByID()
	{
		$id = $this->input->post('id');
		$this->db->where('visiting_id', $id);
		$status = $this->db->delete('visiting');
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	///========================get current academic year
	function getCurrentAcademicYear()
	{
		$acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		return $acdm['stvschool_acdm_year'];
	}

	// -------------   NEW ADMISSION FORM --------------------------------------------------
	//Add Student Data
	function register_student1($data, $acdm_year_data, $div, $cls)
	{
		$status = $this->db->insert('spriden', $data);
		$last_insert = $this->db->insert_id();
		if ($status) {
			// ------------- Save data into SPRFEES table
			if ($this->input->post('back_entry') == "new") {
				$text_to_fees = $this->input->post('admission_note');
			} else {
				$text_to_fees = 'BE';
			}
			// ------------- Save data into SPRFEES table
			$com_data = array(
				'stvpcnf_student_id' => $last_insert,
				'stvpcnf_parent_typ' => "Father",
				'stvpcnf_mobile' => $this->input->post('parents_mobile'),
				'stvpcnf_whatsapp' => $this->input->post('whatsapp_number'),
				'stvpncf_added_by' => $this->session->userdata('oliveSchoolDashboardUser'),
				'stvpncf_date_added' => date('Y-m-d')
			);
			$status = $this->db->insert('stvpcnf', $com_data);
			$class_data = array(
				'STVCLAS_student_id' => $last_insert,
				'STVCLAS_rollno' => 0,
				'STVCLAS_standard' => $cls,
				'STVCLAS_class' => $div,
				'STVCLAS_userid' => $this->session->userdata('oliveSchoolDashboardUser'),
				'STVCLAS_acdm' => $this->input->post('acdm_year')
			);
			$status = $this->db->insert('stvclas', $class_data);
			$this->session->set_flashdata('message', "Student Registered Successfully !!!");
			$this->session->set_flashdata('alert_class', "alert-success");
			redirect(base_url() . 'student/admission_form/' . $last_insert);
		} else {
			$this->session->set_flashdata('message', "Failed to Registred Student !!!");
			$this->session->set_flashdata('alert_class', "alert-danger");
			redirect(base_url() . 'admission-form');
		}
	}

	function getContactDetails($id)
	{
		$this->db->where('stvpcnf_student_id', $id);
		return $this->db->get('stvpcnf')->row_array();
	}
	//------------------------------------- FEES FORM ------------------------
	//====================29-12-2021 from ==================

	function findStudentNameForFees($name, $academic_year, $include_flag)
	{
		if ($include_flag == 'Yes') {
			$status_condition = "spriden.spriden_student_status IN ('A','I')";
		} else {
			$status_condition = "spriden.spriden_student_status = 'A'";
		}
		$sql = "SELECT stvclas.*, 
            concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name , 
			spriden.spriden_student_status
            FROM spriden, stvclas 
            WHERE stvclas.STVCLAS_student_id = spriden.id 
            AND $status_condition
            AND stvclas.STVCLAS_acdm = $academic_year
            AND concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) LIKE '%" . $name . "%'";
		return $this->db->query($sql)->result_array();
	}

	//========================29-12-2021 to =======================
	function student_details_to_display_for_fees($id, $acdm_year)
	{
		$sql = "SELECT stvclas.STVCLAS_standard, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name , spriden.id ,
		spriden.SPRIDEN_student_id,spriden.rte_status,stvclas.STVCLAS_acdm
		FROM spriden, stvclas WHERE stvclas.STVCLAS_student_id = spriden.id 
		AND stvclas.STVCLAS_acdm = $acdm_year
		AND spriden.id = " . $id . "";
		return $this->db->query($sql)->row_array();
	}

	function student_current_fees_structure($std, $year)
	{
		return $this->db->query("SELECT * FROM stvfstr WHERE stvfstr_std = '" . $std . "' and stvfstr_acdm_year = '" . $year . "'")->row_array();
	}

	function student_this_year_balance($year, $id)
	{
		return $this->db->query("SELECT sum(sprfees_amount) AS total FROM sprfees WHERE academic_year = " . $year . " and sprfees_studentid = " . $id . "")->row_array();
	}

	// Changes made from here -------------------------------- 11-Aug-2021----------------
	function recordsForFeesDetails($student_id)
	{
		$data['fees_records'] = $this->db->query("SELECT sprfees.*,stvacdm.stvacdm_year FROM sprfees, stvacdm WHERE sprfees_studentid = " . $student_id . " AND sprfees.academic_year = stvacdm.stvacdm_id order by sprfees.academic_year")->result_array();
		$data['stu_details'] = $this->db->query("SELECT spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) name ,
		stvclas.STVCLAS_standard, stvclas.STVCLAS_class FROM spriden, stvclas WHERE spriden.id = " . $student_id . " AND spriden.id = stvclas.STVCLAS_student_id")->row_array();
		return $data;
	}

	// Changes made to here -------------------------------- 11-Aug-2021------------------
	function student_list_by_class()
	{
		$class = $this->input->post('class');
		$div = $this->input->post('div');
		if ($class != '0' && $div != '0') {
			return $this->db->query("SELECT spriden.id, spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name, stvclas.STVCLAS_standard, stvclas.STVCLAS_class, spriden.rte_status FROM spriden,stvclas WHERE stvclas.STVCLAS_student_id = spriden.id
			AND stvclas.STVCLAS_standard = '$class' AND stvclas.STVCLAS_class = '$div'")->result_array();
		} else if ($class != '0' && $div == '0') {
			return $this->db->query("SELECT spriden.id, spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name, stvclas.STVCLAS_standard, stvclas.STVCLAS_class, spriden.rte_status FROM spriden,stvclas WHERE stvclas.STVCLAS_student_id = spriden.id
			AND stvclas.STVCLAS_standard = '$class'")->result_array();
		} else {
			return null;
		}
	}

	function add_rte()
	{
		$total_students = $this->input->post('total_students');
		$count = 0;
		for ($i = 1; $i <= $total_students; $i++) {
			$id = $this->input->post('student_id_hidden' . $i);
			$data = array(
				'rte_status' => $this->input->post('status' . $i)
			);
			$this->db->where('id', $id);
			$status = $this->db->update('spriden', $data);
			if ($status) {
				$count++;
			}
		}
		if ($count == 0) {
			return "Failed to update RTE Status";
		} else {
			return "RTE Status updated of " . $count . ' Students';
		}
	}

	// ------------------------ Re Admission Insert -------------------------------------
	function insertReAdmission()
	{
		$selectedStudentID = $this->input->post('student_list_search');
		$selectedAcdm = $this->input->post('acdm_year');
		$id = $this->session->userdata('oliveSchoolDashboardUser');
		$date = date('Y-m-d');
		$date_time = date('d-M-y HH:MM:SS');
		$stvradm = array(
			'stvradm_student_id' => $this->input->post('student_list_search'),
			'stvradm_class' => $this->input->post('admission_class'),
			'stvradm_fess' => $this->input->post('fees_paid'),
			'stvradm_academic_year' => $this->input->post('acdm_year'),
			'stvradm_date' => $date,
			'stvradm_date_time' => $date_time,
			'stvradm_created_by' => $id,
		);
		$status = $this->db->insert('stvradm', $stvradm);
		$zero_based = ['', ' ', null, 0];
		$exumpted_amount = in_array($this->input->post('pre_fees_balance'), $zero_based) ? 0 : "-" . $this->input->post('pre_fees_balance');

		$existInCurrentAcademic = $this->db->query("SELECT * FROM stvclas WHERE STVCLAS_student_id = '$selectedStudentID' AND STVCLAS_acdm = $selectedAcdm")->row_array();

		$selectedClass = $this->input->post('admission_class');
		$selectedUserID = $this->session->userdata('oliveSchoolDashboardUser');
		if (empty($existInCurrentAcademic)) {
			// insert student record
			$this->db->query("insert into stvclas (STVCLAS_student_id, STVCLAS_rollno, STVCLAS_standard, STVCLAS_class, STVCLAS_userid, STVCLAS_acdm, updated_flag)
			values ($selectedStudentID, 0, '$selectedClass', '*', $selectedUserID, $selectedAcdm, null)");
		}
		$spriden_data = array(
			'SPRIDEN_surname' => $this->input->post('surname'),
			'SPRIDEN_student_name' => $this->input->post('student_name'),
			'SPRIDEN_father_name' => $this->input->post('father_name'),
			'SPRIDEN_mother_name' => $this->input->post('mother_name'),
			'SPRIDEN_religion' => $this->input->post('religion'),
			'SPRIDEN_gender' => $this->input->post('gender'),
			'SPRIDEN_nationality' => $this->input->post('nationality'),
			'SPRIDEN_mother_toungue' => $this->input->post('mother_tongue'),
			'SPRIDEN_birth_place' => $this->input->post('birth_place'),
			'SPRIDEN_aadhar_no' => $this->input->post('aadhar_number'),
			'SPRIDEN_birthdate' => $this->input->post('date_of_birth'),
			'SPRIDEN_main_address' => $this->input->post('main_address'),
			'cast' => $this->input->post('caste'),
			'sub_cast' => $this->input->post('sub_caste'),
			'taluka' => $this->input->post('taluka'),
			'district' => $this->input->post('district'),
			'state' => $this->input->post('state'),
			'rte_status' => $this->input->post('rte_student'),
			'SPRIDEN_last_updated' => date('Y-m-d'),
			'spriden_student_status' => "A",
			'SPRIDEN_updated_by' => $this->session->userdata('oliveSchoolDashboardUser')
		);
		$this->db->where('id', $this->input->post('student_list_search'));
		$this->db->update('spriden', $spriden_data);
		$this->db->where('stvpcnf_student_id', $this->input->post('student_list_search'));
		$exist = $this->db->get('stvpcnf')->row_array();
		if ($exist == null) {
			// Insert PCNF
			$contact_details = array(
				'stvpcnf_student_id' => $this->input->post('student_list_search'),
				'stvpcnf_parent_typ' => "Father",
				'stvpcnf_mobile' => $this->input->post('parents_mobile'),
				'stvpcnf_whatsapp' => $this->input->post('whatsapp_number'),
			);
			$this->db->insert('stvpcnf', $contact_details);
		} else {
			//Update PCNF
			$contact_details = array(
				'stvpcnf_parent_typ' => "Father",
				'stvpcnf_mobile' => $this->input->post('parents_mobile'),
				'stvpcnf_whatsapp' => $this->input->post('whatsapp_number'),
			);
			$this->db->where('stvpcnf_student_id', $this->input->post('student_list_search'));
			$this->db->update('stvpcnf', $contact_details);
		}
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function fees_details_prev($student_id)
	{
		return $this->db->query("SELECT *,(class_fees - total_paid) AS balance,((class_fees - total_paid) - total_exumpted) AS final_balance FROM (SELECT acd.stvacdm_year,f.academic_year,c.STVCLAS_standard sprfees_standard,f.sprfees_studentid,SUM(f.sprfees_amount) total_paid, SUM(f.sprfees_exumption) total_exumpted,(SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = f.academic_year) class_fees FROM sprfees f, stvclas c,stvacdm acd WHERE f.academic_year NOT IN (1,2,3,4) AND f.sprfees_studentid = $student_id AND acd.stvacdm_id = f.academic_year AND c.STVCLAS_acdm = acd.stvacdm_id AND c.STVCLAS_student_id = f.sprfees_studentid AND c.STVCLAS_acdm = f.academic_year GROUP BY f.academic_year,f.sprfees_studentid ORDER BY 2 DESC ) AS temp")->result_array();
	}

	function student_profile_last_fees($id)
	{
		return $this->db->query("SELECT *,
       (class_fees - total_paid) AS balance,
       ((class_fees - total_paid) - total_exumpted) AS final_balance
FROM
(
    SELECT
        acd.stvacdm_year,
        f.academic_year,
        c.STVCLAS_standard AS sprfees_standard,
        f.sprfees_studentid,
        SUM(f.sprfees_amount) AS total_paid,
        SUM(f.sprfees_exumption) AS total_exumpted,
        (
            SELECT stvfstr_fees
            FROM stvfstr
            WHERE stvfstr_std = c.STVCLAS_standard
            AND stvfstr_acdm_year = f.academic_year
        ) AS class_fees
    FROM sprfees f
    JOIN stvclas c
        ON c.STVCLAS_student_id = f.sprfees_studentid
       AND c.STVCLAS_acdm = f.academic_year
    JOIN stvacdm acd
        ON acd.stvacdm_id = f.academic_year
    WHERE f.academic_year = (SELECT pre_academic_year FROM stvschool)
    AND f.sprfees_studentid = '$id'
    GROUP BY f.academic_year, f.sprfees_studentid
) AS temp")->row_array();
	}

	// function student_profile_last_fees($id)
	// {
	// 	return $this->db->query("SELECT *,(class_fees - total_paid) AS balance,((class_fees - total_paid) - total_exumpted) AS final_balanceFROM (SELECT acd.stvacdm_year,f.academic_year,c.STVCLAS_standard sprfees_standard,f.sprfees_studentid,SUM(f.sprfees_amount) total_paid,SUM(f.sprfees_exumption) total_exumpted,(SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = f.academic_year) class_fees FROM sprfees f, stvclas c,stvacdm acd WHERE f.academic_year = (SELECT pre_academic_year FROM stvschool) AND f.sprfees_studentid = '$id' AND acd.stvacdm_id = f.academic_year AND c.STVCLAS_acdm = acd.stvacdm_id AND c.STVCLAS_student_id = f.sprfees_studentid AND c.STVCLAS_acdm = f.academic_year GROUP BY f.academic_year,f.sprfees_studentid ORDER BY 2 DESC ) AS temp")->row_array();
	// }

	// ------------------------ Student Details -----------------------------------------
	// ------------------------ Tailor Details -----------------------------------------

	function tailor_details_api($std)
	{
		return $this->db->query("SELECT s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) AS name, c.STVCLAS_standard, c.STVCLAS_class,(SELECT stvstlr_tailor FROM stvstlr WHERE stvstlr_sid = s.id) AS tailor_name,(SELECT stvstlr_isrec FROM stvstlr WHERE stvstlr_sid = s.id) AS stvstlr_isrec FROM spriden s, stvclas c WHERE s.SPRIDEN_user_type = 'st' AND c.STVCLAS_student_id = s.id AND c.STVCLAS_standard = '$std' ")->result_array();
	}

	function add_tailor_dt()
	{
		$total_rows = $this->input->post('max_rows');
		for ($i = 1; $i <= $total_rows; $i++) {
			$sid = $this->input->post('sid' . $i);
			$data = $this->db->query("SELECT * FROM stvstlr WHERE stvstlr_sid = $sid")->row_array();
			if ($data == null) {
				// insert record
				$data_sub = array(
					'stvstlr_class' => $this->input->post('class' . $i),
					'stvstlr_div' => $this->input->post('divi' . $i),
					'stvstlr_sid' => $this->input->post('sid' . $i),
					'stvstlr_tailor' => $this->input->post('tailor' . $i),
					'stvstlr_created_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvstlr_created_on' => date('Y-m-d'),
					'stvstlr_isrec' => $this->input->post('is_rec' . $i),
					'stvstlr_date_stamp_on' => date('d-M-Y h:i:s'),
					'stvstlr_isrec_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvstlr_isrec_on' => date('d-M-Y h:i:s')
				);
				$this->db->insert('stvstlr', $data_sub);
			} else {
				// update record
				$data_sub = array(
					'stvstlr_class' => $this->input->post('class' . $i),
					'stvstlr_div' => $this->input->post('divi' . $i),
					'stvstlr_tailor' => $this->input->post('tailor' . $i),
					'stvstlr_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvstlr_updated_at' => date('d-M-Y h:i:s'),
					'stvstlr_isrec' => $this->input->post('is_rec' . $i),
					'stvstlr_isrec_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvstlr_isrec_updated_on' => date('d-M-Y h:i:s')
				);
				$this->db->where('stvstlr_sid', $sid);
				$this->db->update('stvstlr', $data_sub);
			}
		}
		echo "success";
	}

	function all_gr_numbers()
	{
		return $this->db->query("SELECT s.id,s.spriden_gr_number,s.SPRIDEN_reg_number, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name) AS name, s.SPRIDEN_mother_name, s.SPRIDEN_birthdate, c.STVCLAS_standard, c.STVCLAS_class,s.spriden_student_status FROM spriden s, stvclas c WHERE s.SPRIDEN_user_type = 'st' AND c.STVCLAS_student_id = s.id")->result_array();
	}

	function student_comments_api($std, $mode, $acdm_year)
	{
		if ($mode == "books") {
			return $this->db->query("SELECT s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) AS name,
			c.STVCLAS_standard, c.STVCLAS_class,
			(SELECT stvstbk_comments FROM stvstbk where stvstbk_student_id = s.id AND stvstbk_academic_year = $acdm_year) AS comments,(SELECT stvstbk_date FROM stvstbk where stvstbk_student_id = s.id AND stvstbk_academic_year = $acdm_year) AS cdate,(SELECT stvstbk_amount FROM stvstbk where stvstbk_student_id = s.id AND stvstbk_academic_year = $acdm_year) AS camount,(SELECT sum(stvstbk_amount) FROM stvstbk where stvstbk_academic_year = $acdm_year AND stvstbk_class = '$std' group by stvstbk_class) AS total_amount,(SELECT sum(stvstbk_amount) FROM stvstbk where stvstbk_academic_year = $acdm_year) AS all_amount FROM spriden s, stvclas c where s.SPRIDEN_user_type = 'st'AND c.STVCLAS_student_id = s.id AND c.STVCLAS_standard = '$std'")->result_array();
		} else {
			return $this->db->query("SELECT s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) AS name,c.STVCLAS_standard, c.STVCLAS_class,(SELECT stvscmnt_comment FROM stvscmnt where stvscmnt_student_id = s.id) AS comments,(SELECT stvscmnt_cdate FROM stvscmnt where stvscmnt_student_id = s.id) AS cdate FROM spriden s, stvclas c s.SPRIDEN_user_type = 'st' AND c.STVCLAS_student_id = s.id AND c.STVCLAS_standard = '$std'")->result_array();
		}
	}



	function add_stu_cmnt_dt()
	{
		$total_rows = $this->input->post('max_rows');
		for ($i = 1; $i <= $total_rows; $i++) {
			$sid = $this->input->post('sid' . $i);
			$data = $this->db->query("SELECT * FROM stvscmnt WHERE stvscmnt_student_id = $sid")->row_array();
			if ($data == null) {
				// insert record
				$data_sub = array(
					'stvscmnt_student_id' => $this->input->post('sid' . $i),
					'stvscmnt_comment' => $this->input->post('comments' . $i),
					'stvscmnt_cdate' => $this->input->post('cdate' . $i),
					'stvscmnt_created_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvscmnt_created_on' => date('d-M-Y h:i:s')
				);
				$this->db->insert('stvscmnt', $data_sub);
			} else {
				// update record
				$data_sub = array(
					'stvscmnt_student_id' => $this->input->post('sid' . $i),
					'stvscmnt_comment' => $this->input->post('comments' . $i),
					'stvscmnt_cdate' => $this->input->post('cdate' . $i),
					'stvscmnt_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvscmnt_updated_on' => date('d-M-Y h:i:s')
				);
				$this->db->where('stvscmnt_student_id', $sid);
				$this->db->update('stvscmnt', $data_sub);
			}
		}
		echo "success";
	}

	function add_stu_books_data()
	{
		$total_rows = $this->input->post('max_rows');
		$result_array = array();
		for ($i = 1; $i <= $total_rows; $i++) {
			$sid = $this->input->post('sid' . $i);
			$data = $this->db->query("SELECT * FROM stvstbk WHERE stvstbk_student_id = '$sid'")->row_array();
			if ($data == null) {
				// insert record
				$data_sub = array(
					'stvstbk_student_id' => $sid,
					'stvstbk_comments' => $this->input->post('comments' . $i),
					'stvstbk_date' => $this->input->post('cdate' . $i),
					'stvstbk_amount' => $this->input->post('amount' . $i),
					'stvstbk_academic_year' => $this->input->post('acdm_year'),
					'stvstbk_class' => $this->input->post('std'),
					'stvstbk_created_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvstbk_created_at' => date('d-M-Y h:i:s')
				);
				$this->db->insert('stvstbk', $data_sub);
			} else {
				// update record
				$data_sub = array(
					'stvstbk_comments' => $this->input->post('comments' . $i),
					'stvstbk_date' => $this->input->post('cdate' . $i),
					'stvstbk_amount' => $this->input->post('amount' . $i),
					'stvstbk_academic_year' => $this->input->post('acdm_year'),
					'stvstbk_class' => $this->input->post('std'),
					'stvstbk_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
					'stvstbk_uopdated_at' => date('d-M-Y h:i:s')
				);
				$this->db->where('stvstbk_student_id', $sid);
				$this->db->update('stvstbk', $data_sub);
			}
		}
		echo "success";
	}

	function student_fees_card_status($student_id)
	{
		$data = $this->db->query("SELECT * FROM stvfcrd WHERE stvfcrd_student_id = $student_id")->row_array();
		if ($data == null) {
			return "No";
		} else {
			if ($data['stvfcrd_status'] != null && $data['stvfcrd_status'] != '') {
				return $data['stvfcrd_status'];
			} else {
				return "No";
			}
		}
	}

	function updateCardIssueStatus($student_id, $status)
	{
		$data = $this->db->query("SELECT * FROM stvfcrd WHERE stvfcrd_student_id = $student_id")->row_array();
		if ($data == null) {
			$stu = array(
				'stvfcrd_student_id' => $student_id,
				'stvfcrd_status' => $status,
				'stvfcrd_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
				'stvfcrd_updated_at' => date('Y-m-d')
			);
			$status = $this->db->insert('stvfcrd', $stu);
			echo $status ? "success" : "fail";
		} else {
			$stu = array(
				'stvfcrd_status' => $status,
				'stvfcrd_updated_by' => $this->session->userdata('oliveSchoolDashboardUser'),
				'stvfcrd_updated_at' => date('Y-m-d')
			);
			$this->db->where('stvfcrd_student_id', $student_id);
			$status = $this->db->update('stvfcrd', $stu);
			echo $status ? "success" : "fail";
		}
	}

	// Student Icard System
	function student_icard_by_class($class, $div)
	{
		$academic_year = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$academic_year = $academic_year['stvschool_acdm_year'];
		$sql = "SELECT spriden.id, spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name, stvclas.STVCLAS_standard, stvclas.STVCLAS_class FROM spriden,stvclas WHERE stvclas.STVCLAS_student_id = spriden.id AND stvclas.STVCLAS_acdm = $academic_year";
		if ($class != '0')
			$sql .= " and stvclas.STVCLAS_standard = '$class' ";
		if ($div != '0')
			$sql .= " and stvclas.STVCLAS_class = '$div'";
		return $this->db->query($sql)->result_array();
	}

	function student_icard_by_class_dt($id)
	{
		$academic_year = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$academic_year = $academic_year['stvschool_acdm_year'];
		$sql = "SELECT spriden.id, spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name, stvclas.STVCLAS_standard, stvclas.STVCLAS_class FROM spriden,stvclas WHERE stvclas.STVCLAS_student_id = spriden.id AND spriden.id = $id AND stvclas.STVCLAS_acdm = $academic_year";
		return $this->db->query($sql)->row_array();
	}

	function add_image($filePathStr, $student_id)
	{
		$check = $this->db->query("SELECT * FROM stvicard WHERE stvicard_student_id = $student_id")->row_array();
		if ($check == null) {
			$data = array(
				'stvicard_student_id' => $this->input->post('stud_id'),
				'stvicard_stu_img' => $filePathStr != '' ? $filePathStr[0] : null,
				'created_at' => date('Y-m-d'),
			);
			$status = $this->db->insert('stvicard', $data);
		} else {
			$data = array(
				'stvicard_student_id' => $this->input->post('stud_id'),
				'stvicard_stu_img' => $filePathStr != '' ? $filePathStr[0] : null,
				'updated_at' => date('Y-m-d'),
			);
			$this->db->where('stvicard_student_id', $student_id);
			$status = $this->db->update('stvicard', $data);
		}
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function stud_image($class, $div)
	{
		$academic_year = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$academic_year = $academic_year['stvschool_acdm_year'];

		$sql = "SELECT spriden.id, spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name) name, concat(SPRIDEN_surname, ' ',SPRIDEN_father_name) father_name, stvpcnf_mobile, stvclas.STVCLAS_standard, stvclas.STVCLAS_class, stvicard_stu_img FROM spriden,stvclas,stvicard,stvpcnf WHERE stvclas.STVCLAS_student_id = spriden.id AND stvpcnf_student_id = spriden.id AND stvicard_student_id = spriden.id AND stvclas.STVCLAS_acdm = $academic_year ";
		if ($class != '0' && $div != '0') {
			$sql .= " and stvclas.STVCLAS_standard = '$class' ";
			$sql .= " and stvclas.STVCLAS_class = '$div'";
		}
		return $this->db->query($sql)->result_array();
	}

	function select_card($class, $div, $list = array(), $choice = '')
	{
		$sql = "SELECT spriden.id, spriden.SPRIDEN_student_id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name) name, concat(SPRIDEN_surname, ' ',SPRIDEN_father_name) father_name, stvpcnf_mobile, stvclas.STVCLAS_standard, stvclas.STVCLAS_class, stvicard_stu_img FROM spriden,stvclas,stvicard,stvpcnf WHERE stvclas.STVCLAS_student_id = spriden.id AND stvpcnf_student_id = spriden.id AND stvicard_student_id = spriden.id";
		if ($class != '0' && $div != '0') {
			$sql .= " and stvclas.STVCLAS_standard = '$class' ";
			$sql .= " and stvclas.STVCLAS_class = '$div'";
		}
		if ($choice == 'selected' && $list != null) {
			$sql .= " and stvicard_student_id in (";
			for ($i = 0; $i < count($list); $i++) {
				$sql .= $list[$i] . " ";
				if ($i < count($list) - 1) {
					$sql .= ", ";
				}
			}
			$sql .= ")";
		}
		return $this->db->query($sql)->result_array();
	}

	function getStudAttendance($std, $div, $date)
	{
		$sql = "SELECT 
		    s.id, 
		    s.SPRIDEN_student_id, CONCAT(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,s.SPRIDEN_father_name,s.SPRIDEN_surname,c.STVCLAS_standard, c.STVCLAS_class, cn.stvpcnf_whatsapp,at.SPRATND_status,at.SPRATND_date FROM spriden s JOIN stvclas c ON c.STVCLAS_student_id = s.id LEFT JOIN stvpcnf cn ON cn.stvpcnf_student_id = s.id LEFT JOIN spratnd at ON at.SPRATND_student_id = s.id AND at.SPRATND_date = '$date' WHERE s.SPRIDEN_student_status = 'A' AND s.SPRIDEN_user_type = 'st' AND c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div';
		";
		return $this->db->query($sql)->result_array();
	}

	function getFeesMassage($std, $div, $date)
	{
		$sql = "SELECT s.id,s.SPRIDEN_student_id,CONCAT(SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name, s.SPRIDEN_father_name,s.SPRIDEN_surname,c.STVCLAS_standard,c.STVCLAS_class,s.rte_status,IFNULL(SUM(f.sprfees_exumption), 0) AS total_exemption,cn.stvpcnf_whatsapp,IFNULL(fs.stvfstr_fees, 0) AS total_fees, IFNULL(SUM(f.sprfees_amount), 0) AS paid_amount, (IFNULL(SUM(f.sprfees_amount), 0) + IFNULL(SUM(f.sprfees_exumption), 0)) AS total_paid, (IFNULL(fs.stvfstr_fees, 0) - (IFNULL(SUM(f.sprfees_amount), 0) + IFNULL(SUM(f.sprfees_exumption), 0))) AS balance, (SELECT GROUP_CONCAT(sf.sprfees_amount, '->', sf.sprfees_feesdate, '->', sf.sprfees_exumption, '->', IFNULL(u.spruser_name, 'NA')) FROM sprfees sf LEFT JOIN spruser u ON u.spruser_id = sf.sprfees_recivedby WHERE sf.academic_year = c.STVCLAS_acdm AND sf.sprfees_studentid = s.id) AS installments FROM spriden s JOIN stvclas c ON c.STVCLAS_student_id = s.id LEFT JOIN stvpcnf cn ON cn.stvpcnf_student_id = s.id LEFT JOIN sprfees f ON f.sprfees_studentid = s.id AND f.academic_year = c.STVCLAS_acdm LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm WHERE s.spriden_student_status = 'A' AND (s.rte_status = 'No' OR s.rte_status IS NULL) AND SPRIDEN_user_type = 'st' AND c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div' AND c.STVCLAS_acdm = '$date' GROUP BY s.id, s.SPRIDEN_student_id, name, s.SPRIDEN_father_name, s.SPRIDEN_surname, c.STVCLAS_standard, c.STVCLAS_class, s.rte_status, cn. stvpcnf_whatsapp, fs.stvfstr_fees";
		return $this->db->query($sql)->result_array();
	}

	function getFeesDetails($id)
	{
		$sql = "SELECT s.id, s.SPRIDEN_student_id, s.SPRIDEN_surname, s.SPRIDEN_student_name, s.SPRIDEN_father_name, c.STVCLAS_standard, c.STVCLAS_class, cn.stvpcnf_whatsapp, SUM(f.sprfees_amount) AS total_paid, f.academic_year, a.stvacdm_year, fs.stvfstr_fees, (fs.stvfstr_fees - SUM(f.sprfees_amount)) AS balance, GROUP_CONCAT(CONCAT(f.sprfees_amount, '->', f.sprfees_feesdate, '->', IFNULL(f.sprfees_exumption, 0), '->', IFNULL(u.spruser_name, 'NA')) ORDER BY f.sprfees_feesdate ASC) AS installments FROM stvclas c JOIN spriden s ON s.id = c.STVCLAS_student_id JOIN sprfees f ON f.sprfees_studentid = s.id AND f.academic_year = c.STVCLAS_acdm JOIN stvacdm a ON a.stvacdm_id = c.STVCLAS_acdm JOIN stvfstr fs ON fs.stvfstr_acdm_year = c.STVCLAS_acdm AND fs.stvfstr_std = c.STVCLAS_standard LEFT JOIN stvpcnf cn ON cn.stvpcnf_student_id = s.id LEFT JOIN spruser u ON u.spruser_id = f.sprfees_recivedby WHERE s.id = '$id' GROUP BY s.id, s.SPRIDEN_student_id, c.STVCLAS_standard, c.STVCLAS_class, cn.stvpcnf_whatsapp, f.academic_year, a.stvacdm_year, fs.stvfstr_fees HAVING (fs.stvfstr_fees - SUM(f.sprfees_amount)) > 0 ORDER BY f.academic_year";
		return $this->db->query($sql)->result_array();
	}

	// student aadhar info
	public function getSelectedAcademicYear()
	{
		$data = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		return $data['stvschool_acdm_year'] ?? 0;
	}

	public function check_student_exists($student_id, $mode = 'isexist')
	{
		$this->db->where('student_id', $student_id);
		$query = $this->db->get('stu_par_aadhar_details');
		if ($mode == 'data')
			return $query->row_array();
		return $query->num_rows() > 0;
	}

	// Update student details
	public function update_student_details($student_id, $data)
	{
		$this->db->where('student_id', $student_id);
		return $this->db->update('stu_par_aadhar_details', $data);
	}

	// Insert new student details
	public function insert_student_details($data)
	{
		if ($this->db->insert('stu_par_aadhar_details', $data)) {
			return 'success';
		}
	}

	public function fetch_student_aadhar_details($student_id)
	{
		$this->db->where('student_id', $student_id);
		$query = $this->db->get('stu_par_aadhar_details');
		return $query->row_array();
	}

	function spriden_data($student_id, $token)
	{
		$sql = "SELECT s.*,st.stvpcnf_whatsapp FROM spriden s,stvpcnf st WHERE s.id=st.stvpcnf_student_id AND s.id='$student_id' AND s.spriden_auth_token = '$token'";
		return $this->db->query($sql)->row_array();
	}

	public function get_student_fee_history($standard, $class, $year)
	{
		$sql = "WITH current_students AS (SELECT s.id FROM spriden s JOIN stvclas c ON c.STVCLAS_student_id = s.id
        WHERE s.SPRIDEN_user_type = 'st' AND c.STVCLAS_acdm = ? AND c.STVCLAS_standard = ? AND c.STVCLAS_class = ?)SELECT s.id,CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,s.spriden_student_status AS status,s.rte_status,c.STVCLAS_acdm AS academic_year_id,acdm.stvacdm_year AS academic_year,c.STVCLAS_standard AS standard,c.STVCLAS_class AS class,fstr.stvfstr_fees,IFNULL(SUM(fs.sprfees_amount), 0) AS total_paid,IFNULL(SUM(fs.sprfees_exumption), 0) AS exempted,fstr.stvfstr_fees - (IFNULL(SUM(fs.sprfees_amount), 0) + IFNULL(SUM(fs.sprfees_exumption), 0)) AS balance_amount FROM spriden s JOIN stvclas c ON c.STVCLAS_student_id = s.id LEFT JOIN sprfees fs ON fs.sprfees_studentid = s.id AND fs.academic_year = c.STVCLAS_acdm LEFT JOIN stvfstr fstr ON fstr.stvfstr_std = c.STVCLAS_standard AND fstr.stvfstr_acdm_year = c.STVCLAS_acdm LEFT JOIN stvacdm acdm ON acdm.stvacdm_id = c.STVCLAS_acdm WHERE s.SPRIDEN_user_type = 'st' AND s.id IN (SELECT id FROM current_students) AND c.STVCLAS_acdm <= ? GROUP BY s.id, name, s.spriden_student_status, s.rte_status, c.STVCLAS_acdm, acdm.stvacdm_year, c.STVCLAS_standard, c.STVCLAS_class, fstr.stvfstr_fees ORDER BY s.id, c.STVCLAS_acdm DESC ";
		return $this->db->query($sql, [$year, $standard, $class, $year])->result_array();
	}

	// load current students
	public function getcurstudents($year, $class, $div = '')
	{
		$this->db
			->select('spriden.*, stvclas.*')
			->from('spriden')
			->join('stvclas', 'stvclas.STVCLAS_student_id = spriden.id')
			->where('STVCLAS_standard', $class)
			->where('STVCLAS_acdm', $year)
			->where('SPRIDEN_student_status', 'A');

		if (!empty($div)) {
			$this->db->where('STVCLAS_class', $div);
		}

		$this->db->order_by('CAST(STVCLAS_rollno AS UNSIGNED)', '', FALSE);

		return $this->db->get()->result_array();
	}

	// promote student
	public function promoteStudents($students, $rollnos, $acdm, $std, $div)
	{
		$already_exist = [];
		$promoted = 0;
		$skipped = 0;

		foreach ($students as $key => $student) {

			// Check if student is already promoted in this Academic Year
			$existing = $this->db
				->select('STVCLAS_standard, STVCLAS_class')
				->where('STVCLAS_student_id', $student)
				->where('STVCLAS_acdm', $acdm)
				->get('stvclas')
				->row_array();

			if (!empty($existing)) {

				$already_exist[] = [
					'student_id' => $student,
					'class' => $existing['STVCLAS_standard'],
					'div' => $existing['STVCLAS_class']
				];

				$skipped++;
				continue;
			}

			$data = [
				'STVCLAS_student_id' => $student,
				'STVCLAS_rollno' => $rollnos[$key],
				'STVCLAS_standard' => $std,
				'STVCLAS_class' => $div,
				'STVCLAS_userid' => $this->session->userdata('oliveSchoolDashboardUser'),
				'STVCLAS_acdm' => $acdm
			];

			if ($this->db->insert('stvclas', $data)) {
				$promoted++;
			}
		}

		return [
			'status' => true,
			'promoted' => $promoted,
			'skipped' => $skipped,
			'students' => $already_exist
		];
	}
}