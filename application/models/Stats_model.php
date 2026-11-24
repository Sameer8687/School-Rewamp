<?php

class Stats_model extends CI_model
{
	public function get_all_academic_years()
	{
		return $this->db->select('stvacdm.stvacdm_id, stvacdm.stvacdm_year')
			->from('stvacdm')
			->order_by('stvacdm_year', 'DESC')
			->get()
			->result_array();
	}

	//Get Total Count For Dashboard
	function get_total_count()
	{
		//Get Total count for student active
		$this->db->where('SPRIDEN_user_type', "st");
		$this->db->where('spriden_student_status ', "A");
		$student = $this->db->get('spriden')->result_array();
		if ($student == null) {
			$student_total_active = 0;
		} else {
			$student_total_active = count($student);
		}

		//Get Total count for student deactive
		$this->db->where('SPRIDEN_user_type', "st");
		$this->db->where('spriden_student_status', "I");
		$student = $this->db->get('spriden')->result_array();
		if ($student == null) {
			$student_total_deactive = 0;
		} else {
			$student_total_deactive = count($student);
		}

		//Get Total count for student deactive
		$this->db->where('SPRIDEN_user_type', "st");
		$student = $this->db->get('spriden')->result_array();
		if ($student == null) {
			$student_total = 0;
		} else {
			$student_total = count($student);
		}

		//Get Total count for Teacher
		$this->db->where('SPRIDEN_user_type', "tc");
		$this->db->where('spriden_student_status', "A");
		$teacher = $this->db->get('spriden')->result_array();
		if ($teacher == null) {
			$teacher_total = 0;
		} else {
			$teacher_total = count($teacher);
		}

		//Get Total count for Teacher
		$this->db->where('SPRIDEN_user_type', "tc");
		$this->db->where('spriden_student_status', "I");
		$teacher_i = $this->db->get('spriden')->result_array();
		if ($teacher_i == null) {
			$teacher_total_i = 0;
		} else {
			$teacher_total_i = count($teacher_i);
		}

		//Collect data into array and return
		$data = array(
			'student_total_active' => $student_total_active,
			'student_total_deactive' => $student_total_deactive,
			'student_total' => $student_total,
			'teacher_total' => $teacher_total,
			'teacher_total_i' => $teacher_total_i
		);
		return $data;
	}

	//Get app track
	function track()
	{
		$sql = "SELECT DISTINCT ap.*,concat (spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name
		FROM application_tracking ap,spriden
		where spriden.SPRIDEN_student_id=ap.student_id";
		// die($sql);
		return $this->db->query($sql)->result_array();
	}

	function track_rep($date, $user)
	{
		$sql = "SELECT DISTINCT ap.*,concat (spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name
		FROM application_tracking ap,spriden
		where spriden.SPRIDEN_student_id=ap.student_id and ap.action_date='$date' and ap.source='$user'";
		// die($sql);
		return $this->db->query($sql)->result_array();
	}

	//Get User Details
	function getUserDetails($userId)
	{
		$this->db->where('spruser_id', $userId);
		return $user = $this->db->get('spruser')->row_array();
	}

	function fetchDueDateRecords()
	{
		$date = date('Y-m-d');
		return $this->db->query("SELECT DISTINCT f.sprfees_studentid, sum(sprfees_amount) total_paid, sum(sprfees_exumption) total_exumpted,
		concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ',s.SPRIDEN_father_name) AS name,
		s.SPRIDEN_student_id,s.id,
		c.STVCLAS_standard,
		(SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = f.academic_year) AS class_fees,
		(SELECT COALESCE(stvpcnf_mobile, stvpcnf_mobile2) AS primary_mobile FROM stvpcnf WHERE stvpcnf_student_id = f.sprfees_studentid) AS mobile
		FROM sprfees f, spriden s, stvclas c
		WHERE f.sprfees_due_date = '$date'
		AND s.id = f.sprfees_studentid
		AND s.id = c.STVCLAS_student_id AND c.STVCLAS_student_id = f.sprfees_studentid
		AND c.STVCLAS_acdm = f.academic_year
		AND f.academic_year IN (SELECT stvschool_acdm_year FROM stvschool)
		GROUP BY f.sprfees_studentid
		ORDER BY f.sprfees_studentid DESC")->result_array();
	}

	//get All Form Names
	function getAllFormsNames()
	{
		$formName = $this->input->post('form_Name');
		$this->db->where('spruser_id ', $this->session->userdata('oliveSchoolDashboardUser'));
		$userTypeData = $this->db->get('spruser')->row_array();
		if ($userTypeData['spruser_usertype'] == "Admin") {
			$this->db->select('*');
			$this->db->from('stvfrms');
			$this->db->like('stvfrms_url_name', $formName);
			$data = $this->db->get();
			return $data->result_array();
		} else {
			$this->db->select('stvfrms.*, stvuacs.*');
			$this->db->from('stvfrms');
			$this->db->where('stvuacs.stvuacs_userid', $this->session->userdata('oliveSchoolDashboardUser'));
			$this->db->join('stvuacs', 'stvuacs.stvuacs_formurl = stvfrms.stvfrms_url_name');
			$this->db->like('stvfrms_url_name', $formName);
			$data = $this->db->get();
			return $data->result_array();
		}
	}

	//Check valid form name or not
	function getValidFormName($formName)
	{
		$this->db->where('stvfrms_url_name', $formName);
		$isValidUrl = $this->db->get('stvfrms')->row_array();
		if ($isValidUrl) {
			return $isValidUrl['stvfrms_url_name'];
		} else {
			return "invalid";
		}
	}

	//Get User Access
	function getUserAccessDetails($userId, $formName)
	{
		$this->db->where('stvuacs_userid', $userId);
		$this->db->where('stvuacs_formurl', $formName);
		$user = $this->db->get('stvuacs')->row_array();
		if ($user != null) {
			return $user;
		} else {
			return "noaccess";
		}
	}

	//Get Classes
	function getAllClasses()
	{
		return $this->db->get('stvfees')->result_array();
	}

	//Get Classes
	function getAllClasses_div()
	{
		$sql = "SELECT stvfees.*, spatesd.* FROM stvfees
			 	INNER JOIN spatesd
			 	ON stvfees.stvfees_class = SPATESD_std
 			 	GROUP BY SPATESD_std,SPATESD_div";
		return $this->db->query($sql)->result_array();
	}

	//Get All Student Model
	function get_all_fees_from_spofees()
	{
		$this->db->order_by('sprfees_standard', 'asc');
		return $this->db->get('spofees')->result_array();
	}

	//Get All Users
	function getAllUsers()
	{
		return $this->db->get('spruser')->result_array();
	}

	//Add Form Data Access
	function add_form_data($data)
	{
		$this->db->insert('stvfrmh', $data);
	}

	//Get All Form Access Data
	function getAllFormAccessData()
	{
		return $this->db->select('stvfrmh.*, spruser.*')
			->from('stvfrmh')
			->join('spruser', 'stvfrmh.stvfrmh_userid = spruser.spruser_id')
			->get()->result_array();
	}

	//function to get all users
	function getallForm_name()
	{
		return $this->db->get('stvfrms')->result_array();
	}

	//Function to get Last Login
	function last_login()
	{
		$userId = $this->session->userdata('oliveSchoolDashboardUser');
		$maxid = 0;
		$sql1 = "SELECT MAX(stvlgin_id) AS `maxid` FROM stvlgin WHERE stvlgin_id NOT IN (SELECT MAX(stvlgin_id) FROM stvlgin) AND stvlgin_userid='$userId' AND stvlgin_status='Logged In'";
		$lastID = $this->db->query($sql1)->row_array();
		if ($lastID['maxid'] == "") {
			$sql = "SELECT MAX(stvlgin_id) AS `maxid` FROM stvlgin WHERE stvlgin_userid ='$userId'";
			$recentID = $this->db->query($sql)->row_array();
			$this->db->where('stvlgin_id', $recentID['maxid']);
			return $recentLoginData = $this->db->get('stvlgin')->row_array();
		} else {
			$this->db->where('stvlgin_id', $lastID['maxid']);
			return $lastLoginData = $this->db->get('stvlgin')->row_array();
		}
	}

	function current_login()
	{
		$userId = $this->session->userdata('oliveSchoolDashboardUser');
		$sql = "SELECT MAX(stvlgin_id) AS `maxid` FROM stvlgin WHERE stvlgin_userid ='$userId'";
		$recentID = $this->db->query($sql)->row_array();
		$this->db->where('stvlgin_id', $recentID['maxid']);
		return $recentLoginData = $this->db->get('stvlgin')->row_array();
	}

	function getAllEventsForDispay()
	{
		$this->db->order_by('stvevnt_id', 'desc');
		return $this->db->get('stvevnt')->result_array();
	}

	//get academic Year
	function getAcademicYear()
	{
		$sql = "SELECT stvschool.* , stvacdm.* FROM stvschool, stvacdm WHERE stvschool.stvschool_acdm_year = stvacdm.stvacdm_id";
		$data = $this->db->query($sql)->row_array();
		if ($data != null) {
			return $data;
		} else {
			return 0;
		}
	}

	function get_all_hold_types()
	{
		return $this->db->get('stvhold')->result_array();
	}

	function get_all_non_class_students()
	{
		$sql = 'SELECT * FROM spriden WHERE id NOT IN (SELECT STVCLAS_student_id FROM stvclas) AND SPRIDEN_user_type = "st"';
		return $this->db->query($sql)->result_array();
	}

	function getcolumnName()
	{
		$sql = "select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='visiting'";
		return $this->db->query($sql)->result_array();
	}

	function get_all_std_count()
	{
		$sql = "SELECT STVCLAS_standard AS std,STVCLAS_class AS divi, COUNT(STVCLAS_class) AS total FROM stvclas GROUP BY STVCLAS_standard,STVCLAS_class";
		return $this->db->query($sql)->result_array();
	}

	//Report Data
	function getStatsForReport($teacher, $date)
	{
		$attnd = "SELECT spriden.SPRIDEN_student_id, concat (spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name, stvclas.STVCLAS_standard, stvclas.STVCLAS_class, spratnd.SPRATND_status FROM spriden, stvclas, spratnd WHERE spriden.id = stvclas.STVCLAS_student_id AND spriden.id = spratnd.SPRATND_student_id AND spratnd.SPRATND_teacher_id = '" . $teacher . "' and spratnd.SPRATND_date = '" . $date . "'";
		$attendance = $this->db->query($attnd)->result_array();
		if ($attendance == null) {
			$data['attendance_data'] = "0";
		} else {
			$data['attendance_data'] = $attendance;
		}
		$rmrk = "SELECT spriden.SPRIDEN_student_id, concat (spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name, stvclas.STVCLAS_standard, stvclas.STVCLAS_class, stvrmrk.* FROM spriden, stvclas, stvrmrk WHERE spriden.id = stvclas.STVCLAS_student_id AND spriden.id = stvrmrk.stvrmrk_student_id AND stvrmrk.stvrmrk_teacher_id = '" . $teacher . "'";
		$remarks = $this->db->query($rmrk)->result_array();
		if ($remarks == null) {
			$data['remarks'] = "0";
		} else {
			$data['remarks'] = $remarks;
		}
		$marks = "SELECT spriden.SPRIDEN_student_id, concat (spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name, sprmark.*, stvexam.stvexam_name, stvsubj.stvsubj_subject FROM spriden, sprmark,stvexam,stvsubj WHERE
		spriden.id = sprmark.sprmark_student_id AND sprmark.sprmark_exam_name = stvexam.stvexam_id AND sprmark.sprmark_subject = stvsubj.stvsubj_id AND sprmark.sprmark_teacher_id = '" . $teacher . "'";
		$marks_data = $this->db->query($marks)->result_array();
		if ($marks_data == null) {
			$data['marks_data'] = "0";
		} else {
			$data['marks_data'] = $marks_data;
		}
		return $data;
	}

	function getSelectedAcdmYear()
	{
		$data = $this->db->get('stvschool')->row_array();
		if ($data == null) {
			return 0;
		} else {
			return $data['stvschool_acdm_year'];
		}
	}

	function getAcademicDetails()
	{
		return $this->db->query("SELECT * FROM stvacdm WHERE stvacdm_id IN (SELECT stvschool_acdm_year FROM stvschool)")->row_array();
	}

	function getAcademicYearsForSet()
	{
		$this->db->where('status', "0");
		return $this->db->get('stvacdm')->result_array();
	}

	//Added 14-9-2026
	function getCurrentAcademicYear()
	{
		$today = date('Y-m-d');

		$this->db->where('status', "0");
		$this->db->where('stvacdm_start <=', $today);
		$this->db->where('stvacdm_end >=', $today);

		return $this->db->get('stvacdm')->row_array();
	}

	function update_acdm_year()
	{
		$data = array(
			'stvschool_acdm_year' => $this->input->post('year')
		);
		$this->db->where('stvschool_id', 1);
		$result = $this->db->update('stvschool', $data);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	//----------------------- Ansari Kamran --- 11-12-25--------------------------------------
	function get_fees_report_by_academic_year($year)
	{
		// Use parameter binding to prevent SQL injection
		$sql = "SELECT 
				    CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
				    IFNULL(fs.stvfstr_fees,0) AS total_fees,
				    IFNULL(SUM(f.sprfees_amount),0) AS total_paid,
				    (IFNULL(fs.stvfstr_fees,0) - IFNULL(SUM(f.sprfees_amount),0)) AS balance,
				    MAX(f.sprfees_due_date) AS due_date,
				    s.id,
				    s.SPRIDEN_student_id,
				    s.spriden_student_status,
				    u.spruser_name,
				    c.STVCLAS_standard,
				    c.STVCLAS_class,
				    GROUP_CONCAT(DISTINCT u.spruser_name) AS received_by
				FROM spriden s
				JOIN stvclas c ON c.STVCLAS_student_id = s.id
				LEFT JOIN stvfstr fs 
				       ON fs.stvfstr_std = c.STVCLAS_standard
				      AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
				LEFT JOIN sprfees f 
				       ON f.sprfees_studentid = s.id
				      AND f.academic_year = c.STVCLAS_acdm
				LEFT JOIN spruser u 
				       ON u.spruser_id = f.sprfees_recivedby
				WHERE c.STVCLAS_acdm = '$year'
				GROUP BY s.id";

		return $this->db->query($sql)->result_array();
	}

	//----------------------- Changes Made To Here --- 10-Aug-2021----------------------------------------

	public function daily_fees_report($from, $to, $mode = 'records', $acdm_year = '', $new_admission = '')
	{
		$running_year = (int) $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row()->stvschool_acdm_year; // Default value
		$cur_Year = '';
		$newFilter = '';

		if ($acdm_year == 'current') {
			$cur_Year = " AND f.academic_year IN ($running_year) ";
			if ($new_admission == 'on') {
				$newFilter = " AND s.admsn_acdm_year IN ($running_year) ";
			}
		} elseif ($acdm_year == 'all') {
			$cur_Year = " AND f.academic_year NOT IN ($running_year) ";
		}

		if ($mode == 'stats') {
			$feesBreakDown = [];

			// Current Academic Year
			$feesBreakDown['current_fees_breakdown'] = $this->db->query("
            SELECT
                SUM(f.sprfees_amount) AS amount,
                f.payment_mode
            FROM sprfees f
            WHERE f.sprfees_feesdate BETWEEN '$from' AND '$to'
            AND f.academic_year IN ($running_year)
            GROUP BY f.payment_mode
        ")->result_array();

			$feesBreakDown['current_total'] = array_sum(
				array_column($feesBreakDown['current_fees_breakdown'], 'amount')
			);

			// Previous Academic Years
			$feesBreakDown['previous_fees_breakdown'] = $this->db->query("
            SELECT
                SUM(f.sprfees_amount) AS amount,
                f.payment_mode
            FROM sprfees f
            WHERE f.sprfees_feesdate BETWEEN '$from' AND '$to'
            AND f.academic_year NOT IN ($running_year)
            GROUP BY f.payment_mode
        ")->result_array();

			$feesBreakDown['previous_total'] = array_sum(
				array_column($feesBreakDown['previous_fees_breakdown'], 'amount')
			);

			// Grand Total
			$feesBreakDown['grand_total'] =
				$feesBreakDown['current_total'] +
				$feesBreakDown['previous_total'];

			return $feesBreakDown;
		}

		if ($mode == 'collections') {
			$collections = [];
			$users = $this->db->query("
            SELECT DISTINCT f.sprfees_recivedby, u.spruser_name 
            FROM sprfees f 
            LEFT JOIN spruser u ON u.spruser_id = f.sprfees_recivedby 
            WHERE f.sprfees_feesdate BETWEEN '$from' AND '$to' $cur_Year
        ")->result_array();

			foreach ($users as $user) {
				$received_by = $user['sprfees_recivedby'];

				// Fix: Handle NULL/Empty receiver values safely
				if ($received_by === null || $received_by === '') {
					$userWhere = "AND (f.sprfees_recivedby IS NULL OR f.sprfees_recivedby = '')";
				} else {
					$userWhere = "AND f.sprfees_recivedby = '$received_by'";
				}

				$transactions['user'] = !empty($user['spruser_name']) ? $user['spruser_name'] : 'Unknown/System';
				$transactions['history'] = $this->db->query("
                SELECT f.sprfees_recivedby, UPPER(f.payment_mode) AS mode, SUM(f.sprfees_amount) AS total 
                FROM sprfees f
                WHERE f.sprfees_feesdate BETWEEN '$from' AND '$to' $cur_Year $userWhere
                GROUP BY f.sprfees_recivedby, f.payment_mode
                ORDER BY 1, 2
            ")->result_array();

				array_push($collections, $transactions);
			}
			return $collections;
		}

		$sql = "SELECT 
                s.id,
                s.SPRIDEN_student_id,
                CONCAT(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,
                u.spruser_name,
                f.*,
                IFNULL((
    SELECT SUM(f2.sprfees_amount)
    FROM sprfees f2
    WHERE f2.sprfees_studentid = f.sprfees_studentid
      AND f2.academic_year = f.academic_year
      AND (
            f2.sprfees_feesdate < f.sprfees_feesdate
            OR (
                f2.sprfees_feesdate = f.sprfees_feesdate
                AND f2.sprfees_timestamp < f.sprfees_timestamp
            )
          )
), 0) AS total_prev_fees,
                ac.stvacdm_year,
                f.academic_year
            FROM
                sprfees f
                JOIN spriden s ON s.id = f.sprfees_studentid
                LEFT JOIN spruser u ON f.sprfees_recivedby = u.spruser_id
                LEFT JOIN stvacdm ac ON ac.stvacdm_id = f.academic_year
            WHERE
                f.sprfees_feesdate BETWEEN '$from' AND '$to'
                $cur_Year $newFilter";

		return $this->db->query($sql)->result_array();
	}

	

	function getPassword($id)
	{
		$this->db->where('spruser_id', $id);
		return $this->db->get('spruser')->row_array();
	}

	function change_password($data, $id)
	{
		$this->db->where('spruser_id', $id);
		$status = $this->db->update('spruser', $data);
		if ($status) {
			return true;
		} else {
			return false;
		}
	}

	function student_total_active()
	{
		$sql = "SELECT count(*) active_student FROM spriden WHERE spriden_student_status='A' AND spriden_user_type = 'st' AND (rte_status = 'No' OR rte_status IS NULL)";
		return $this->db->query($sql)->row_array();
	}

	function inActive_student()
	{
		$sql = "SELECT count(*) as noshow_student FROM spriden WHERE spriden_student_status = 'n' AND SPRIDEN_user_type = 'st'";
		return $this->db->query($sql)->row_array();
	}

	function rte_student()
	{
		return $this->db->query("SELECT count(*) rte FROM spriden WHERE spriden_student_status='A' AND rte_status='Yes' AND SPRIDEN_user_type = 'st'")->row_Array();
	}

	function chart_data()
	{
		return $this->db->query("SELECT ifnull(count(*),0) TOTAL_STUDENTS, STVCLAS_standard FROM stvclas,spriden WHERE 
		stvclas_acdm=(SELECT stvschool_acdm_year FROM stvschool ) AND spriden_student_status='A' AND
		 SPRIDEN_user_type='st' AND spriden.id=STVCLAS_student_id GROUP BY  STVCLAS_standard")->result_array();
	}

	//Rte Student Details
	function getAllRte()
	{
		return $this->db->query("SELECT id, concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) name, SPRIDEN_admission_class FROM spriden WHERE rte_status = 'Yes' AND SPRIDEN_user_type = 'st'")->result_array();
	}

	function student_list_by_class($class, $div, $year = null)
	{
		$sql = "SELECT spriden.id, spriden.SPRIDEN_student_status, spriden.SPRIDEN_student_id, 
            concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name, 
            stvclas.STVCLAS_standard, stvclas.STVCLAS_class, spriden.rte_status
            FROM spriden, stvclas
            WHERE stvclas.STVCLAS_student_id = spriden.id AND spriden.rte_status = 'Yes'";

		if ($class != 'All') {
			$sql .= " AND stvclas.STVCLAS_standard = '$class' ";
			if ($div != '0')
				$sql .= " AND stvclas.STVCLAS_class = '$div'";
		}

		return $this->db->query($sql)->result_array();
	}

	public function fetchActivities($from, $to, $year)
	{
		// Teacher list
		$this->db->select('SPRIDEN_student_id AS teacher_id, 
                       CONCAT(SPRIDEN_surname, " ", SPRIDEN_student_name, " ", SPRIDEN_father_name) AS teacher_name');
		$this->db->from('spriden');
		$this->db->where('SPRIDEN_user_type', 'tc');
		$this->db->where('spriden_student_status', 'A');
		$this->db->where('SPRIDEN_student_id IS NOT NULL');
		$teachers = $this->db->get()->result_array();
		if (empty($teachers))
			return [];
		// Get all teacher IDs
		$teacher_ids = array_column($teachers, 'teacher_id');
		// Optimized join query (no loop)
		$this->db->select("
        t.user_id AS teacher_id,
        t.activity_date,
        t.activity_time,
        t.activity_sorce,
        t.action_date,
        CONCAT(c.SPATESD_std, ' - ', c.SPATESD_div) AS class,
        SUM(CASE WHEN s.spratnd_status = 'P' THEN 1 ELSE 0 END) AS present_count,
        SUM(CASE WHEN s.spratnd_status = 'A' THEN 1 ELSE 0 END) AS absent_count
    ");
		$this->db->from('teacher_activity t');
		$this->db->join('spatesd c', 't.user_id = c.SPATESD_teacher_id', 'left');
		$this->db->join('spratnd s', 's.spratnd_teacher_id = t.user_id AND s.spratnd_date = t.action_date AND s.spratnd_acdm_year = ' . $this->db->escape($year), 'left');
		$this->db->where_in('t.user_id', $teacher_ids);
		$this->db->where('c.SPATESD_academic_year', $year);
		$this->db->where('t.activity_date >=', $from);
		$this->db->where('t.activity_date <=', $to);
		$this->db->group_by(['t.user_id', 't.activity_date', 't.activity_time', 't.activity_sorce', 't.action_date', 'c.SPATESD_std', 'c.SPATESD_div']);
		$this->db->order_by('t.user_id, t.activity_date');
		$query = $this->db->get()->result_array();
		// Group results by teacher
		$result = [];
		foreach ($teachers as $t) {
			$result[$t['teacher_id']] = [
				'teacher_id' => $t['teacher_id'],
				'teacher_name' => $t['teacher_name'],
				'activities' => []
			];
		}
		foreach ($query as $row) {
			$row['attendance_counts'] = "Present {$row['present_count']}: Absent {$row['absent_count']}";
			$result[$row['teacher_id']]['activities'][] = $row;
		}
		// Remove teachers without activities
		return array_values(array_filter($result, function ($r) {
			return !empty($r['activities']);
		}));
	}

	public function get_academic_year_by_id($year_id)
	{
		if (!$year_id)
			return [];
		$this->db->where('stvacdm_id', $year_id);
		$res = $this->db->get('stvacdm')->row_array();
		if ($res) {
			return [
				'start_date' => $res['stvacdm_start'],
				'end_date' => $res['stvacdm_end']
			];
		}
		return [];
	}

	public function getStatus()
	{
		$sql = "SELECT DISTINCT spriden_student_status FROM spriden WHERE spriden_student_status is not null";
		return $this->db->query($sql)->result_array();
	}
}
