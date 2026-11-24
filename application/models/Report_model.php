<?php
class Report_model extends CI_Model
{

	function getStudentRecords($std, $status)
	{
		if ($status != NULL) {
			$sql = "SELECT spriden.id, spriden.SPRIDEN_student_status, spriden.SPRIDEN_student_id,
					concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) name ,
					concat (stvclas.STVCLAS_standard, '-', stvclas.STVCLAS_class) class_name,
					stvclas.STVCLAS_standard,
					(SELECT sum(sprfees.sprfees_amount) FROM sprfees WHERE sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool) 
					AND sprfees.sprfees_studentid = spriden.id) fees_paid
					FROM spriden, stvclas WHERE spriden.id = stvclas.STVCLAS_student_id
					AND stvclas.STVCLAS_standard = '$std' AND spriden.SPRIDEN_user_type = 'st' 
					AND (spriden.rte_status IS null OR spriden.rte_status = 'no') 
					AND spriden.SPRIDEN_student_status = '$status'";
		} else {
			$sql = "SELECT spriden.id, spriden.SPRIDEN_student_status, spriden.SPRIDEN_student_id,
					concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) name ,
					concat (stvclas.STVCLAS_standard, '-', stvclas.STVCLAS_class) class_name,
					stvclas.STVCLAS_standard,
					(SELECT sum(sprfees.sprfees_amount) FROM sprfees WHERE sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool) 
					AND sprfees.sprfees_studentid = spriden.id) fees_paid
					FROM spriden, stvclas WHERE spriden.id = stvclas.STVCLAS_student_id
					AND stvclas.STVCLAS_standard = '$std' AND spriden.SPRIDEN_user_type = 'st' 
					AND (spriden.rte_status IS null OR spriden.rte_status = 'no') 
					AND spriden.SPRIDEN_student_status = '$status'";
		}
		return $this->db->query($sql)->result_array();
	}

	function student_stats_report_22Mar($due_date, $type = 0)
	{
		$sql = "SELECT  DISTINCT spriden.id,spriden.rte_status,spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name, (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = spriden.id) AS phone, (SELECT stvfstr.stvfstr_fees FROM stvfstr WHERE stvfstr.stvfstr_std=stvclas.STVCLAS_standard AND stvfstr.stvfstr_acdm_year=stvclas.STVCLAS_acdm) total_fees, (SELECT sum(sprfees.sprfees_amount) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees_paid, (SELECT (total_fees)-(total_fees_paid) FROM dual) AS balancia, (SELECT MAX(sprfees.sprfees_feesdate) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) last_paid, (SELECT MAX(sprfees.sprfees_due_date) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) due_date, stvclas.STVCLAS_standard,stvclas.STVCLAS_class (SELECT note FROM sprremark WHERE sprremark.remark_id IN (SELECT max(sprremark.remark_id) FROM sprremark) AND sprremark.id=spriden.id) note, (SELECT MAX(due_date) FROM sprremark WHERE sprremark.id = spriden.id) next_date, (SELECT sum(sprfees.sprfees_exumption) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) AS exumtion, IFNULL((SELECT (balancia-exumtion) FROM dual),0) bharna, (SELECT COUNT(spratnd.SPRATND_status) FROM spratnd WHERE spratnd.SPRATND_status = 'P' AND spratnd.SPRATND_student_id = spriden.id) attendence_p, (SELECT stvpcnf.stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id) whatsapp FROM spriden, stvclas where spriden.SPRIDEN_user_type = 'st' AND stvclas.stvclas_acdm IN (SELECT max(stvclas_acdm) FROM stvclas ) AND stvclas.STVCLAS_student_id = spriden.id AND  spriden.SPRIDEN_student_status = 'A' ";

		if ($due_date != '' && $due_date != '-')
			$sql .= "and sprfees.sprfees_due_date = '$due_date' ";
		if ($type != 1)
			$sql .= " and (spriden.rte_status IS null or spriden.rte_status = 'No') ";
		$sql .= " order by attendence_p desc,
			total_fees_paid";
		return $this->db->query($sql)->result_array();
	}

	function student_stats_report_byazz($due_date, $type = 'no', $class = 'ALL', $division = '0', $acdm = null)
	{
		if ($acdm == null) {
			$academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
			$acdm = $academic_year['stvschool_acdm_year'];
		}
		$this->db->select("
        spriden.id,
        spriden.rte_status,
        spriden.SPRIDEN_student_id,
        CONCAT(spriden.SPRIDEN_surname,' ',spriden.SPRIDEN_student_name,' ',spriden.SPRIDEN_father_name) as name,
        spriden.SPRIDEN_student_status,
        stvclas.STVCLAS_standard,
        stvclas.STVCLAS_class,
        stvpcnf.stvpcnf_mobile as phone,
        stvpcnf.stvpcnf_whatsapp as whatsapp,
        stvfstr.stvfstr_fees as total_fees_of_class,
        SUM(sprfees.sprfees_amount) as total_fees_paid,
        SUM(sprfees.sprfees_exumption) as exumtion,
        MAX(sprfees.sprfees_feesdate) as last_paid,
        MAX(sprfees.sprfees_due_date) as due_date,
        MAX(sprremark.note) as note,
        MAX(sprremark.due_date) as next_date,
    ");
		$this->db->from('spriden');
		$this->db->join('stvclas', 'stvclas.STVCLAS_student_id = spriden.id AND stvclas.STVCLAS_acdm = ' . $acdm);
		$this->db->join('stvpcnf', 'stvpcnf.stvpcnf_student_id = spriden.id', 'left');
		$this->db->join('stvfstr', 'stvfstr.stvfstr_std = stvclas.STVCLAS_standard AND stvfstr.stvfstr_acdm_year = ' . $acdm, 'left');
		$this->db->join('sprfees', 'sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = ' . $acdm, 'left');
		$this->db->join('sprremark', 'sprremark.id = spriden.id', 'left');
		$this->db->where('spriden.SPRIDEN_user_type', 'st');
		$this->db->where('spriden.SPRIDEN_student_status', 'A');
		if ($type != 'yes')
			$this->db->where("(spriden.rte_status IS NULL OR spriden.rte_status = 'No')");
		if ($type == 'yes')
			$this->db->where('spriden.rte_status', 'Yes');
		if ($class != 'ALL')
			$this->db->where('stvclas.STVCLAS_standard', $class);
		if ($division != '0')
			$this->db->where('stvclas.STVCLAS_class', $division);
		$this->db->group_by('spriden.id');
		$this->db->order_by('stvclas.STVCLAS_standard');
		$this->db->order_by('stvclas.STVCLAS_class');
		return $this->db->get()->result_array();
	}

	function student_stats_report($due_date, $type = 'no', $class = 'ALL', $division = '0', $acdm = null)
	{
		if ($acdm == null) {
			$academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
			$acdm = $academic_year['stvschool_acdm_year'];
		}
		$this->db->select("
        spriden.id,
        spriden.rte_status,
        spriden.SPRIDEN_gender,
        spriden.SPRIDEN_student_id,
        CONCAT(spriden.SPRIDEN_surname,' ',spriden.SPRIDEN_student_name,' ',spriden.SPRIDEN_father_name) as name,
        spriden.SPRIDEN_student_status,
        stvclas.STVCLAS_standard,
        stvclas.STVCLAS_class,
        stvpcnf.stvpcnf_mobile as phone,
        stvpcnf.stvpcnf_whatsapp as whatsapp,
        stvfstr.stvfstr_fees as total_fees_of_class,
        COALESCE(fees.total_paid, 0) as total_fees_paid,
        COALESCE(fees.total_exumtion, 0) as exumtion,
        MAX(sprfees.sprfees_feesdate) as last_paid,
        MAX(sprfees.sprfees_due_date) as due_date,
        MAX(sprremark.note) as note,
        MAX(sprremark.due_date) as next_date
    ");
		$this->db->from('spriden');
		$this->db->join('stvclas', 'stvclas.STVCLAS_student_id = spriden.id AND stvclas.STVCLAS_acdm = ' . $acdm);
		$this->db->join('stvpcnf', 'stvpcnf.stvpcnf_student_id = spriden.id', 'left');
		$this->db->join('stvfstr', 'stvfstr.stvfstr_std = stvclas.STVCLAS_standard AND stvfstr.stvfstr_acdm_year = ' . $acdm, 'left');
		// ✅ ONE SUBQUERY FOR BOTH SUMS
		$this->db->join("(
        SELECT 
            sprfees_studentid,
            SUM(sprfees_amount) as total_paid,
            SUM(sprfees_exumption) as total_exumtion
        FROM sprfees
        WHERE academic_year = {$acdm}
        GROUP BY sprfees_studentid
    ) as fees", 'fees.sprfees_studentid = spriden.id', 'left');
		// existing joins (unchanged)
		$this->db->join('sprfees', 'sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = ' . $acdm, 'left');
		$this->db->join('sprremark', 'sprremark.id = spriden.id', 'left');
		$this->db->where('spriden.SPRIDEN_user_type', 'st');
		$this->db->where('spriden.SPRIDEN_student_status', 'A');
		if ($type != 'yes')
			$this->db->where("(spriden.rte_status IS NULL OR spriden.rte_status = 'No')");
		if ($type == 'yes')
			$this->db->where('spriden.rte_status', 'Yes');
		if ($class != 'ALL')
			$this->db->where('stvclas.STVCLAS_standard', $class);
		if ($division != '0')
			$this->db->where('stvclas.STVCLAS_class', $division);
		$this->db->group_by('spriden.id');
		$this->db->order_by('stvclas.STVCLAS_standard');
		$this->db->order_by('stvclas.STVCLAS_class');
		return $this->db->get()->result_array();
	}

	function student_report($id)
	{
		$sql = "SELECT  DISTINCT spriden.id,spriden.rte_status,spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name, (select sum(sprfees.sprfees_amount) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees,	(SELECT MAX(sprfees.sprfees_feesdate) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) last_paid,	(SELECT MAX(sprfees.sprfees_due_date) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) due_date, stvclas.STVCLAS_standard,stvclas.STVCLAS_class, (SELECT note FROM sprremark WHERE sprremark.remark_id IN (SELECT max(sprremark.remark_id) FROM sprremark WHERE sprremark.id=spriden.id) AND sprremark.id=spriden.id) note, (SELECT COUNT(spratnd.SPRATND_status) FROM spratnd WHERE spratnd.SPRATND_status = 'P' AND spratnd.SPRATND_student_id = spriden.id) attendence_p FROM spriden, stvclas, sprfees WHERE spriden.SPRIDEN_user_type = 'st' AND stvclas.STVCLAS_student_id = spriden.id AND spriden.SPRIDEN_student_status = 'A' AND sprfees.sprfees_studentid = spriden.id ORDER BY  attendence_p desc, total_fees";
		return $this->db->query($sql)->result_array();
	}

	function student_report_to_remark($id)
	{
		return $this->db->query("SELECT spriden.id,spriden.rte_status,spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name, (SELECT stvfstr.stvfstr_fees FROM stvfstr WHERE stvfstr.stvfstr_std=stvclas.STVCLAS_standard AND stvfstr.stvfstr_acdm_year=stvclas.STVCLAS_acdm) total_fees,(SELECT sum(sprfees.sprfees_amount) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees_paid,(SELECT (total_fees)-(total_fees_paid) FROM dual) AS balancia,(SELECT MAX(sprfees.sprfees_due_date) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) due_date, (SELECT stvpcnf.stvpcnf_mobile FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id) mobile, (SELECT stvpcnf.stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id) whatsapp, IFNULL((SELECT exumption FROM sprexumtion WHERE exu_studentid = spriden.id ),0) AS exumtion, IFNULL((SELECT (balancia-exumtion) FROM dual),0) bharna, stvclas.STVCLAS_standard,stvclas.STVCLAS_class,concat (stvclas.STVCLAS_standard, ' ' , stvclas.STVCLAS_class ) standard,(SELECT note AS date FROM sprremark WHERE sprremark.remark_id IN (SELECT max(sprremark.remark_id) FROM sprremark WHERE sprremark.id=spriden.id) AND sprremark.id=spriden.id) note, (SELECT MAX(due_date) AS date FROM sprremark WHERE sprremark.id = spriden.id) next_date, (SELECT COUNT(spratnd.SPRATND_status) FROM spratnd WHERE spratnd.SPRATND_status = 'P' AND spratnd.SPRATND_student_id = spriden.id) attendence_p FROM spriden, stvclas WHERE spriden.SPRIDEN_user_type = 'st' AND stvclas.STVCLAS_student_id = spriden.id AND spriden.id = '$id'")->row_array();
	}

	function student_stats_report_lc($due_date)
	{
		if ($due_date != "-") {
			$sql = "SELECT  distinct spriden.id,spriden.rte_status,spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name,
			(SELECT stvclas_standard FROM stvclas WHERE stvclas_student_id=spriden.id and STVCLAS_acdm=(select max(STVCLAS_acdm) FROM stvclas WHERE stvclas_student_id=spriden.id))current_std,
			(select stvpcnf_mobile from stvpcnf where stvpcnf_student_id = spriden.id) as phone,

			(select sum(sprfees.sprfees_amount) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees,

			(select MAX(sprfees.sprfees_feesdate) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) last_paid,

			(select MAX(sprfees.sprfees_due_date) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) due_date,

			--stvclas.STVCLAS_standard,stvclas.STVCLAS_class, 
			
			(SELECT MAX(note)  FROM sprremark WHERE sprremark.id = spriden.id) note,

			(select exumption from sprexumtion where exu_studentid = spriden.id ) as exumtion,

			(SELECT MAX(due_date) FROM sprremark WHERE sprremark.id = spriden.id) next_date,

			(select COUNT(spratnd.SPRATND_status) from spratnd WHERE spratnd.SPRATND_status = 'P' and spratnd.SPRATND_student_id = spriden.id) attendence_p

			from spriden, stvclas, sprfees

			where spriden.SPRIDEN_user_type = 'st' 

			and stvclas.STVCLAS_student_id = spriden.id

        	and sprfees.sprfees_due_date = '$due_date' and

        	sprfees.sprfees_studentid = spriden.id and spriden.SPRIDEN_student_status = 'A'

			order by attendence_p desc,

			total_fees";
		} else {

			$sql = "SELECT  distinct spriden.id,spriden.rte_status,spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name,

			(SELECT stvclas_standard FROM stvclas WHERE stvclas_student_id=spriden.id and STVCLAS_acdm=(select max(STVCLAS_acdm) FROM stvclas WHERE stvclas_student_id=spriden.id ))current_std,

			(select stvpcnf_mobile from stvpcnf where stvpcnf_student_id = spriden.id) as phone,

			(select sum(sprfees.sprfees_amount) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees,

			(select MAX(sprfees.sprfees_feesdate) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) last_paid,

			(select MAX(sprfees.sprfees_due_date) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) due_date,

			--stvclas.STVCLAS_standard,stvclas.STVCLAS_class,
			 (SELECT MAX(note) FROM sprremark WHERE sprremark.id = spriden.id) note,

			(SELECT MAX(due_date) FROM sprremark WHERE sprremark.id = spriden.id) next_date,

			(select exumption from sprexumtion where exu_studentid = spriden.id ) as exumtion,

			(select COUNT(spratnd.SPRATND_status) from spratnd WHERE spratnd.SPRATND_status = 'P' and spratnd.SPRATND_student_id = spriden.id) attendence_p

			from spriden, stvclas

			where spriden.SPRIDEN_user_type = 'st' 

			and stvclas.STVCLAS_student_id = spriden.id and  spriden.SPRIDEN_student_status = 'A' 
			-- and stvclas.STVCLAS_acdm = (select stvschool_acdm_year from stvschool)

			order by attendence_p desc,

			total_fees";
		}



		return $this->db->query($sql)->result_array();
	}

	// function student_stats_report_lc_updated($due_date, $acdm = '6')
	// {


	// 	if ($due_date != "-") {

	// 		$sql = "SELECT  distinct spriden.id,spriden.rte_status,spriden.SPRIDEN_student_id, concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name,
	// 		(SELECT stvclas_standard FROM stvclas WHERE stvclas_student_id=spriden.id and STVCLAS_acdm=(select max(STVCLAS_acdm) FROM stvclas WHERE stvclas_student_id=spriden.id))current_std,

	// 		(select stvpcnf_mobile from stvpcnf where stvpcnf_student_id = spriden.id) as phone,

	// 		(select sum(sprfees.sprfees_amount) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees,

	// 		(select MAX(sprfees.sprfees_feesdate) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) last_paid,

	// 		(select MAX(sprfees.sprfees_due_date) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) due_date,

	// 		--stvclas.STVCLAS_standard,stvclas.STVCLAS_class, 

	// 		(SELECT MAX(note)  FROM sprremark WHERE sprremark.id = spriden.id) note,

	// 		(select exumption from sprexumtion where exu_studentid = spriden.id ) as exumtion,

	// 		(SELECT MAX(due_date) FROM sprremark WHERE sprremark.id = spriden.id) next_date,

	// 		(select COUNT(spratnd.SPRATND_status) from spratnd WHERE spratnd.SPRATND_status = 'P' and spratnd.SPRATND_student_id = spriden.id) attendence_p

	// 		from spriden, stvclas, sprfees

	// 		where spriden.SPRIDEN_user_type = 'st' 

	// 		and stvclas.STVCLAS_student_id = spriden.id

	//     	and sprfees.sprfees_due_date = '$due_date' and

	//     	sprfees.sprfees_studentid = spriden.id and spriden.SPRIDEN_student_status = 'A'

	// 		order by attendence_p desc,

	// 		total_fees";
	// 	} else {

	// 		$sql = "WITH current_std_cte AS (
	// 			SELECT 
	// 				stvclas_student_id,
	// 				MAX(STVCLAS_acdm) AS max_acdm
	// 			FROM stvclas
	// 			GROUP BY stvclas_student_id
	// 		),
	// 		total_fees_cte AS (
	// 			SELECT 
	// 				sprfees_studentid,
	// 				SUM(sprfees_amount) AS total_fees,
	// 				MAX(sprfees_feesdate) AS last_paid,
	// 				MAX(sprfees_due_date) AS due_date
	// 			FROM sprfees
	// 			WHERE sprfees.academic_year = $acdm -- Use parameter here
	// 			GROUP BY sprfees_studentid
	// 		),
	// 		latest_remark_cte AS (
	// 			SELECT 
	// 				id,
	// 				MAX(note) AS note,
	// 				MAX(due_date) AS next_date
	// 			FROM sprremark
	// 			GROUP BY id
	// 		),
	// 		attendance_cte AS (
	// 			SELECT 
	// 				SPRATND_student_id,
	// 				COUNT(SPRATND_status) AS attendence_p
	// 			FROM spratnd 
	// 			WHERE SPRATND_status = 'P'
	// 			GROUP BY SPRATND_student_id
	// 		)
	// 		SELECT DISTINCT 
	// 			spriden.id,
	// 			spriden.rte_status,
	// 			spriden.SPRIDEN_student_id, 
	// 			CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name,
	// 			stvclas.STVCLAS_standard ,
	// 			stvclas.STVCLAS_class ,
	// 			stvpcnf.stvpcnf_mobile AS phone,
	// 			tf.total_fees,
	// 			tf.last_paid,
	// 			tf.due_date,
	// 			lr.note,
	// 			lr.next_date,
	// 			sprexumtion.exumption AS exumtion,
	// 			att.attendence_p
	// 		FROM 
	// 			spriden
	// 		LEFT JOIN 
	// 			stvclas ON stvclas.STVCLAS_student_id = spriden.id AND stvclas.STVCLAS_acdm = $acdm
	// 		LEFT JOIN 
	// 			current_std_cte csc ON csc.stvclas_student_id = spriden.id AND stvclas.STVCLAS_acdm = csc.max_acdm
	// 		LEFT JOIN 
	// 			stvpcnf ON stvpcnf.stvpcnf_student_id = spriden.id
	// 		LEFT JOIN 
	// 			total_fees_cte tf ON tf.sprfees_studentid = spriden.id
	// 		LEFT JOIN 
	// 			latest_remark_cte lr ON lr.id = spriden.id
	// 		LEFT JOIN 
	// 			sprexumtion ON sprexumtion.exu_studentid = spriden.id
	// 		LEFT JOIN 
	// 			attendance_cte att ON att.SPRATND_student_id = spriden.id
	// 		WHERE 
	// 			spriden.SPRIDEN_user_type = 'st' 
	// 			AND spriden.SPRIDEN_student_status = 'A'
	// 		ORDER BY 
	// 			att.attendence_p DESC,
	// 			tf.total_fees;";
	// 	}



	// 	return $this->db->query($sql)->result_array();
	// }

	// ===================== Changes by Aasif for New LC on 25-05-24 ===========================

	function student_report_to_remark_lc_jc($id, $acdm)
	{

		$sql = "SELECT DISTINCT 
    spriden.id AS id,
    (SELECT stvclas_standard FROM stvclas 
     WHERE stvclas_student_id = '$id' 
     AND STVCLAS_acdm = (SELECT MAX(STVCLAS_acdm) FROM stvclas WHERE stvclas_student_id = '$id')
     LIMIT 1) AS current_std,
    spriden.rte_status,
	spriden.SPRIDEN_pan_no,
    spriden.SPRIDEN_student_id,
    spriden.SPRIDEN_aadhar_no,
    spriden.SPRIDEN_surname,
    spriden.SPRIDEN_student_name,
    spriden.SPRIDEN_father_name,
    spriden.SPRIDEN_mother_name,
    spriden.SPRIDEN_mother_toungue,
    spriden.spriden_religion,
    spriden.cast,
    spriden.sub_cast,
    spriden.spriden_birth_place,
    spriden.taluka,
    spriden.district,
    spriden.state,
    spriden.spriden_birthdate,
    spriden.spriden_last_school,
    spriden.spriden_date_created,
    spriden.spriden_gr_number,
    spriden.spriden_college_gr,
    stvclas.stvclas_standard,
    spriden.spriden_admission_class,
    spriden.SPRIDEN_reg_number,
    spriden.birthdate_words,
    spriden.progress,
    spriden.conduct,
    spriden.leaving_reason,
    spriden.remark,
    spriden.leaving_date,
    spriden.since_when,
    spriden.lc_date,
    -- Yahan MAX() lagane se error nahi aata
    (SELECT MAX(IFNULL(lc_no_jc, 0)) FROM lc_status WHERE student_id = spriden.id) AS lc_num,
    -- Yahan LIMIT 1 lagana zaroori tha
    (SELECT date_issued_on FROM lc_status WHERE student_id = spriden.id ORDER BY id DESC LIMIT 1) AS date_issued_on,
    (SELECT lc_status FROM lc_status WHERE student_id = spriden.id ORDER BY id DESC LIMIT 1) AS lc_status,
    stvclas.STVCLAS_standard,
    stvclas.STVCLAS_class
FROM 
    spriden, 
    stvclas 
WHERE 
    spriden.SPRIDEN_user_type = 'st' 
    AND stvclas.STVCLAS_student_id = spriden.id 
    AND spriden.id = '$id' 
    AND stvclas.STVCLAS_acdm = $acdm";
		return $this->db->query($sql)->row_array();
	}

	function generate_lc_no($student_std)
	{
		// die('student_std is ' . $student_std);
		if ($student_std == 'XI' || $student_std == 'XII') {
			$sql = "select ifnull(max(lc_no_jc) + 1,1)lc_no from lc_status";
		} else {
			$sql = "select ifnull(max(lc_no) + 1,1)lc_no from lc_status";
		}
		// die($sql);
		return $this->db->query($sql)->row_array();
	}


	function lc_data()
	{
		$this->db->trans_start();

		$student_id = $this->input->post('student_id');
		$std = $this->input->post('standard');
		$stud_type = $this->input->post('stud_type');
		$acdm = $this->input->post('acdm');
		$lc_status = 'Issued';
		$date_issued_on = date('Y-m-d', strtotime($this->input->post('date_issued')));

		// Validate required fields
		if (empty($student_id) || empty($date_issued_on)) {
			return 'missing_data';
		}

		// Check if LC already exists for this student
		$existing_lc = $this->db->query(
			"SELECT * FROM lc_status WHERE student_id = ?",
			[$student_id]
		)->row_array();

		if ($existing_lc) {
			return 'lc_exists';
		}

		// Generate next LC Number
		if ($stud_type == 'jc') {

			$last_lc = $this->db->select('lc_no_jc')
				->from('lc_status')
				->where('lc_no_jc IS NOT NULL', null, false)
				->where('lc_no_jc !=', '')
				->order_by('CAST(lc_no_jc AS UNSIGNED)', 'DESC', false)
				->limit(1)
				->get()
				->row();

			$lc_no = ($last_lc && !empty($last_lc->lc_no_jc))
				? ($last_lc->lc_no_jc + 1)
				: 1;
		} else {

			$last_lc = $this->db->select('lc_no')
				->from('lc_status')
				->where('lc_no IS NOT NULL', null, false)
				->where('lc_no !=', '')
				->order_by('CAST(lc_no AS UNSIGNED)', 'DESC', false)
				->limit(1)
				->get()
				->row();

			$lc_no = ($last_lc && !empty($last_lc->lc_no))
				? ($last_lc->lc_no + 1)
				: 1;
		}

		// Prepare insert data
		$insert_data = [
			'student_id' => $student_id,
			'lc_status' => $lc_status,
			'date_issued_on' => $date_issued_on,
			'academic_year' => $acdm
		];

		if ($stud_type == 'jc') {
			$insert_data['lc_no_jc'] = $lc_no;
		} else {
			$insert_data['lc_no'] = $lc_no;
		}

		// Insert LC Record
		$insert_success = $this->db->insert('lc_status', $insert_data);

		if ($insert_success) {

			// Update Student Status
			$update_success = $this->db
				->where('id', $student_id)
				->update('spriden', [
					'spriden_student_status' => 'I'
				]);

			if ($update_success) {

				$this->db->trans_complete();

				if ($this->db->trans_status() === FALSE) {
					return 'false';
				}

				return [
					'status' => 'success',
					'lc_no' => $lc_no
				];
			}
		}

		$this->db->trans_rollback();
		return 'false';
	}


	// function generate_lc_no()
	// {
	// 	return $this->db->query("select ifnull(max(lc_no) + 1,1)lc_no from lc_status")->row_array();
	// }

	// function lc_data()
	// {
	// 	$student_id = $this->input->post('student_id');
	// 	$lc_no = $this->input->post('lc_number');
	// 	$lc_status = 'Issued';
	// 	$date_issued_on = $this->input->post('date_issued');
	// 	$data = $this->db->query("select * from lc_status where student_id = $student_id")->row_array();

	// 	$sql = "insert into lc_status (student_id,lc_no,lc_status,date_issued_on) 
	// 				values ($student_id,$lc_no,'$lc_status','$date_issued_on')";
	// 	if ($this->db->query($sql)) {
	// 		echo 'success';
	// 	} else {
	// 		echo 'false';
	// 	}
	// }
	// =====================================Changes End ==================================================

	function student_report_to_remark_lc($id, $acdm)
	{

		$sql = "SELECT spriden.id as id,
    (SELECT stvclas_standard FROM stvclas WHERE stvclas_student_id='$id' and STVCLAS_acdm=(select max(STVCLAS_acdm) FROM stvclas WHERE stvclas_student_id='$id') LIMIT 1)current_std,
    spriden.rte_status,SPRIDEN_pan_no,spriden.SPRIDEN_student_id,spriden.spriden_aadhar_no ,spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name,spriden.SPRIDEN_mother_name,spriden.SPRIDEN_mother_toungue,spriden.spriden_religion,spriden.cast,spriden.sub_cast,spriden.spriden_birth_place,spriden.taluka,spriden.district,spriden.state,spriden.spriden_birthdate,spriden.spriden_last_school,spriden.spriden_date_created,spriden.spriden_gr_number,stvclas.stvclas_standard,spriden.spriden_admission_class,spriden.SPRIDEN_reg_number,spriden.SPRIDEN_aadhar_no, spriden.SPRIDEN_date_created,spriden.SPRIDEN_birth_place,spriden.taluka,spriden.district,spriden.birthdate_words,spriden.progress,spriden.conduct,spriden.leaving_reason,spriden.remarks,spriden.leaving_date,spriden.since_when,spriden.lc_date,spriden.remark,
    -- Added LIMIT 1 here
    (select ifnull(lc_no,0) from lc_status where student_id = spriden.id LIMIT 1) lc_num,
    (select date_issued_on from lc_status where student_id = spriden.id LIMIT 1) date_issued_on,
    -- Added LIMIT 1 to stvschool
    (select sum(sprfees.sprfees_amount) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool LIMIT 1)) total_fees, 
    (select MAX(sprfees.sprfees_feesdate) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool LIMIT 1)) last_paid, 
    (select MAX(sprfees.sprfees_due_date) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool LIMIT 1)) due_date, 
    -- Added MAX to contact info
    (SELECT MAX(stvpcnf.stvpcnf_mobile) FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id) mobile,  
    (SELECT MAX(stvpcnf.stvpcnf_whatsapp) FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id) whatsapp,
    stvclas.STVCLAS_standard,stvclas.STVCLAS_class,
    (SELECT MAX(note) FROM sprremark WHERE sprremark.id = spriden.id) note, 
    (SELECT MAX(due_date) FROM sprremark WHERE sprremark.id = spriden.id) next_date, 
    (select COUNT(spratnd.SPRATND_status) from spratnd WHERE spratnd.SPRATND_status = 'P' and spratnd.SPRATND_student_id = spriden.id) attendence_p 
from spriden, stvclas 
where spriden.SPRIDEN_user_type = 'st' 
and stvclas.STVCLAS_student_id = spriden.id  
and spriden.id = '$id' 
and stvclas.STVCLAS_acdm = $acdm";

		return $this->db->query($sql)->row_array();
	}

	function fetchRecordsForLC($acdm_year, $class)
	{
		// 	$sql = "SELECT DISTINCT 
		// s.id, 
		// s.SPRIDEN_student_id, 
		// CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
		// s.spriden_student_status,
		// c.STVCLAS_class, 
		// c.STVCLAS_acdm, 
		// c.STVCLAS_standard,
		// cnf.stvpcnf_mobile, 
		// cnf.stvpcnf_whatsapp,
		// (SELECT lc_status FROM lc_status WHERE student_id = s.id LIMIT 1) AS lc_status,
		// (SELECT lc_no FROM lc_status WHERE student_id = s.id LIMIT 1) AS lc_no,
		// (SELECT lc_no_jc FROM lc_status WHERE student_id = s.id LIMIT 1) AS lc_no_jc,
		// IFNULL(lc.status, 'draft') AS lc_request_status, 
		// lc.token
		// FROM spriden s
		// JOIN stvclas c ON s.id = c.STVCLAS_student_id
		// LEFT JOIN stvpcnf cnf ON cnf.stvpcnf_student_id = s.id AND cnf.stvpcnf_parent_typ = 'Father'
		// LEFT JOIN lc_requests lc ON s.id = lc.student_id AND lc.academic_year = c.STVCLAS_acdm
		// WHERE c.STVCLAS_standard = '$class' AND c.STVCLAS_acdm = $acdm_year";
		$sql = "SELECT DISTINCT
    						s.id,
    						s.SPRIDEN_student_id,
    						CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
    						s.spriden_student_status,
    						c.STVCLAS_class,
    						c.STVCLAS_acdm,
    						c.STVCLAS_standard,
    						cnf.stvpcnf_mobile,
    						cnf.stvpcnf_whatsapp,
    						lcs.lc_status,
    						lcs.lc_no,
    						lcs.lc_no_jc,
    						IFNULL(lc.status, 'draft') AS lc_request_status,
    						lc.token
					FROM spriden s
					JOIN stvclas c ON s.id = c.STVCLAS_student_id
					JOIN lc_status lcs ON s.id = lcs.student_id 
					LEFT JOIN stvpcnf cnf ON cnf.stvpcnf_student_id = s.id AND cnf.stvpcnf_parent_typ = 'Father'
					LEFT JOIN lc_requests lc ON s.id = lc.student_id AND lc.academic_year = c.STVCLAS_acdm
					WHERE c.STVCLAS_standard = '$class' 
					  AND c.STVCLAS_acdm = $acdm_year
					  AND lcs.lc_status = 'Issued'";

		return $this->db->query($sql)->result_array();
	}

	function student_report_to_remark_bfc($id, $acdm = 6)
	{

		return $this->db->query("SELECT spriden.id as id,spriden.rte_status,spriden.SPRIDEN_student_id,spriden.spriden_aadhar_no ,spriden.SPRIDEN_surname, spriden.SPRIDEN_student_name, spriden.SPRIDEN_father_name,spriden.SPRIDEN_mother_name,spriden.SPRIDEN_mother_toungue,spriden.spriden_religion,spriden.cast,spriden.sub_cast,spriden.spriden_birth_place,spriden.taluka,spriden.district,spriden.state,spriden.spriden_birthdate,spriden.spriden_last_school,spriden.spriden_date_created,spriden.spriden_gr_number,stvclas.stvclas_standard,stvclas.stvclas_class,spriden.spriden_admission_class,spriden.SPRIDEN_reg_number,spriden.SPRIDEN_aadhar_no, spriden.SPRIDEN_date_created,
		(SELECT stvacdm_year FROM stvacdm WHERE stvacdm.stvacdm_id=stvclas.stvclas_acdm)acdm_year,
		(select ifnull(lc_no,0) lc_num from lc_status where student_id = spriden.id) lc_num,
		(select date_issued_on from lc_status where student_id = spriden.id) date_issued_on,
		(select sum(sprfees.sprfees_amount) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) total_fees, 

		(select MAX(sprfees.sprfees_feesdate) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) last_paid, 

		(select MAX(sprfees.sprfees_due_date) from sprfees where sprfees.sprfees_studentid = spriden.id and sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool)) due_date, (SELECT stvpcnf.stvpcnf_mobile FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id) mobile,  (SELECT stvpcnf.stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id) whatsapp,

		stvclas.STVCLAS_standard,stvclas.STVCLAS_class,

		(SELECT MAX(note) as date FROM sprremark WHERE sprremark.id = spriden.id) note, 

		(SELECT MAX(due_date) as date FROM sprremark WHERE sprremark.id = spriden.id) next_date, 

		(select COUNT(spratnd.SPRATND_status) from spratnd WHERE spratnd.SPRATND_status = 'P' and spratnd.SPRATND_student_id = spriden.id) attendence_p 

		from spriden, stvclas where spriden.SPRIDEN_user_type = 'st' and stvclas.STVCLAS_student_id = spriden.id  and  spriden.id = '$id' and stvclas.STVCLAS_acdm = $acdm")->row_array();
	}


	function add_report()
	{

		$id = $this->input->post('header_id');

		$sql = "SELECT stvpcnf_mobile from stvpcnf where stvpcnf_student_id = '$id'";

		$exist = $this->db->query($sql)->row_array();

		$data = array(

			'id' => $this->input->post('header_id'),

			'student_name' => $this->input->post('student_name'),

			'due_date' => $this->input->post('due_date'),

			'note' => $this->input->post('note')

		);

		$status = $this->db->insert('sprremark', $data);

		if ($exist == null) {

			// Insert PCNF

			$stvpcnf_data = array(

				'stvpcnf_student_id' => $id,

				'stvpcnf_parent_typ' => "Father",

				'stvpcnf_mobile' => $this->input->post('stvpcnf_mobile'),

				'stvpcnf_whatsapp' => $this->input->post('stvpcnf_whatsapp'),

				'stvpncf_date_added' => date("Y-m-d"),



			);

			$this->db->insert('stvpcnf', $stvpcnf_data);

			$this->session->set_flashdata('msg', "Details Added Successfully");
		} else {

			//Update PCNF

			$stvpcnf_data = array(

				'stvpcnf_mobile' => $this->input->post('stvpcnf_mobile'),

				'stvpcnf_whatsapp' => $this->input->post('stvpcnf_whatsapp'),

				'stvpncf_date_added' => date("Y-m-d"),



			);

			$this->db->where('stvpcnf_student_id', $id);

			$this->db->update('stvpcnf', $stvpcnf_data);
		}



		if ($status) {

			return true;
		} else {

			return false;
		}
	}

	function getDate()
	{

		$sql = "SELECT MAX(due_date) FROM sprremark,spriden WHERE sprremark.id = spriden.id";

		return $this->db->query($sql)->row_array();
	}

	function getReport($class, $amount, $divi, $acdm)
	{
		// Validate and sanitize inputs
		$class = $this->db->escape_str($class);
		$amount = floatval($amount);
		$divi = $this->db->escape_str($divi);
		$acdm = intval($acdm);

		$sql = "SELECT 
                id, 
                SPRIDEN_student_id, 
                name, 
                class, 
                phone, 
                whatsapp, 
                fees_paid, 
                exumption, 
                total_fees, 
                due_date, 
                note, 
                STVCLAS_standard, 
                STVCLAS_class,
                (total_fees - (COALESCE(fees_paid, 0) + COALESCE(exumption, 0))) as balance_fees
            FROM (
                SELECT 
                    s.id, 
                    s.SPRIDEN_student_id, 
                    CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
                    c.STVCLAS_class,
                    CONCAT(c.STVCLAS_standard, '-', c.STVCLAS_class) as class,
                    c.STVCLAS_standard,
                    (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) as phone,
                    (SELECT stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) as whatsapp,
                    (SELECT COALESCE(SUM(sprfees_amount), 0) FROM sprfees WHERE sprfees_studentid = s.id AND academic_year = ?) as fees_paid,
                    (SELECT COALESCE(SUM(sprfees_exumption), 0) FROM sprfees WHERE sprfees_studentid = s.id AND academic_year = ?) as exumption,
                    (SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_acdm_year = ? AND stvfstr_std = c.STVCLAS_standard LIMIT 1) as total_fees,
                    (SELECT MAX(sprfees_due_date) FROM sprfees WHERE sprfees_studentid = s.id AND academic_year = ?) as due_date, 
                    (SELECT MAX(sprremark.note) FROM sprremark WHERE sprremark.id = s.id) as note
                FROM 
                    spriden s, 
                    stvclas c
                WHERE 
                    s.id = c.STVCLAS_student_id 
                    AND s.SPRIDEN_student_status = 'A' 
                    AND s.SPRIDEN_user_type = 'st'  
                    AND c.STVCLAS_acdm = ?
                    AND (s.rte_status = 'no' OR s.rte_status IS NULL)
            ) as temp 
            WHERE 
                COALESCE(fees_paid, 0) <= ? 
                AND STVCLAS_standard = ?";

		$params = [$acdm, $acdm, $acdm, $acdm, $acdm, $amount, $class];

		if ($divi != '0') {
			$sql .= " AND STVCLAS_class = ?";
			$params[] = $divi;
		}

		return $this->db->query($sql, $params)->result_array();
	}

	function getTotalReport()
	{

		return $this->db->query("SELECT distinct

		case when cl.STVCLAS_standard = 'Nursery' then 1

			 when cl.STVCLAS_standard = 'Jr.Kg.' then 2

			 when cl.STVCLAS_standard = 'Sr.Kg.' then 3

			 when cl.STVCLAS_standard = 'I' then 4

			 when cl.STVCLAS_standard = 'II' then 5

			 when cl.STVCLAS_standard = 'III' then 6

			 when cl.STVCLAS_standard = 'IV' then 7

			 when cl.STVCLAS_standard = 'V' then 8

			 when cl.STVCLAS_standard = 'VI' then 9

			 when cl.STVCLAS_standard = 'VII' then 10

			 when cl.STVCLAS_standard = 'VIII' then 11

			 when cl.STVCLAS_standard = 'IX' then 12

			 when cl.STVCLAS_standard = 'X' then 13

			  end SEGMENT,

		cl.STVCLAS_standard,cl.STVCLAS_class, sp.spriden_student_status, count(*) total from spriden sp, stvclas cl where `SPRIDEN_user_type` = 'st'

		and sp.id = cl.STVCLAS_student_id and spriden_student_status != 'null'

		group by STVCLAS_standard,cl.STVCLAS_class, spriden_student_status,SEGMENT

		order by SEGMENT,cl.STVCLAS_class")->result_array();
	}

	function getStandard()
	{

		return $this->db->query("SELECT distinct cl.STVCLAS_standard from spriden sp, stvclas cl where `SPRIDEN_user_type` = 'st'

		and sp.id = cl.STVCLAS_student_id

		group by STVCLAS_standard, spriden_student_status")->result_array();
	}



	function admission_report($class, $type)
	{

		$acdm_data = $this->db->query("select stvacdm_year from stvacdm where stvacdm_id in (select stvschool_acdm_year from stvschool)")->row_array();

		$acdm_year_seq = $acdm_data != null ? substr($acdm_data['stvacdm_year'], 0, 4) : 0000;

		// fetch report

		$sql = "select s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) as name,

		c.STVCLAS_standard, c.STVCLAS_class, s.SPRIDEN_date_created,

        (select ifnull(sum(sprfees_amount),0) total from sprfees where sprfees_studentid = s.id) fees_paid

		from spriden s, stvclas c

		where s.SPRIDEN_user_type = 'st'

		and c.STVCLAS_student_id = s.id

        and s.SPRIDEN_student_id like 'S" . $acdm_year_seq . "%' ";

		if ($type != 'page') {

			if ($type == 'section') {

				switch ($class) {

					case '1':

						$sql .= " and c.STVCLAS_standard in ('Nursery','Jr.Kg.','Sr.Kg.') ";

						break;

					case '2':

						$sql .= " and c.STVCLAS_standard in ('I','II','III','IV') ";

						break;

					case '3':

						$sql .= " and c.STVCLAS_standard in ('V','VI','VII','VIII','IX', 'X') ";

						break;

					case '4':

						$sql .= " and c.STVCLAS_standard in ('XI','XII') ";

						break;
				}
			} else {

				$sql .= " and c.STVCLAS_standard = '$class'";
			}
		}

		$sql .= " order by s.id";

		return $this->db->query($sql)->result_array();
	}

	function readmission_report($acdm = 999999, $type = 'readmission')
	{
		$academic_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$academic_year = $acdm == 999999 ? $academic_year['stvschool_acdm_year'] : $acdm;
		if ($type == 'readmission') {
			$sql = "select s.id, s.SPRIDEN_student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) as name,c.stvradm_class, c.stvradm_fess, c.stvradm_date

		from spriden s, stvradm c

		where c.stvradm_student_id = s.id ";



			if ($acdm == 999999) {

				$sql .= " and c.stvradm_academic_year in (select stvschool_acdm_year from stvschool)";
			} else {

				$sql .= " and c.stvradm_academic_year = $acdm";
			}
		} else {
			$sql = " select s.id,concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name, c.STVCLAS_standard as stvradm_class, s.SPRIDEN_student_id,
			(select distinct stvpcnf_mobile from stvpcnf where stvpcnf_student_id = s.id) phone
			from spriden s, stvclas c
			where c.STVCLAS_student_id = s.id 
			and s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A'
			and c.STVCLAS_acdm = $academic_year
			and s.id not in (select distinct stvradm_student_id from stvradm where stvradm_academic_year = $academic_year)
			and substr(s.SPRIDEN_student_id,2,4) != (select substr(stvacdm_year, 1,4) start_digit from stvacdm where stvacdm_id = $academic_year)
			order by c.STVCLAS_standard";
		}

		return $this->db->query($sql)->result_array();
	}

	// function all_admission_report()
	// {
	// 	return $this->db->query("SELECT 
	// 		    s.id AS id,
	// 			s.SPRIDEN_gender,
	// 		    s.SPRIDEN_student_id AS student_id,
	// 		    CONCAT(s.SPRIDEN_surname,
	// 		            ' ',
	// 		            s.SPRIDEN_student_name,
	// 		            ' ',
	// 		            s.SPRIDEN_father_name) AS name,
	// 		    c.stvradm_class AS class,
	// 		    (SELECT 
	// 		            IFNULL(SUM(sprfees_amount), 0) total
	// 		        FROM
	// 		            sprfees
	// 		        WHERE
	// 		            sprfees_studentid = s.id
	// 		                AND academic_year IN (SELECT 
	// 		                    stvschool_acdm_year
	// 		                FROM
	// 		                    stvschool)) fees,
	// 		    (SELECT 
	// 		            stvfstr_fees
	// 		        FROM
	// 		            stvfstr
	// 		        WHERE
	// 		            stvfstr_std = c.stvradm_class
	// 		                AND stvfstr_acdm_year IN (SELECT 
	// 		                    stvschool_acdm_year
	// 		                FROM
	// 		                    stvschool)) total_fees_of_class,
	// 		    (SELECT (total_fees_of_class) - (fees) FROM DUAL) AS balancia,
	// 		    IFNULL((SELECT 
	// 		                    IFNULL(SUM(sprfees.sprfees_exumption), 0)
	// 		                FROM
	// 		                    sprfees
	// 		                WHERE
	// 		                    sprfees.sprfees_studentid = s.id
	// 		                        AND sprfees.academic_year IN (SELECT 
	// 		                            stvschool_acdm_year
	// 		                        FROM
	// 		                            stvschool)),
	// 		            0) AS exumtion,
	// 		    IFNULL((SELECT (balancia - exumtion) FROM DUAL),
	// 		            0) bharna,
	// 		    cn.stvpcnf_mobile AS mobile,
	// 		    cn.stvpcnf_whatsapp AS whatsapp,
	// 		    c.stvradm_date AS adm_date,
	// 		    'Re-Admission' AS type
	// 		FROM
	// 		    spriden s
	// 		INNER JOIN stvradm c 
	// 		    ON c.stvradm_student_id = s.id
	// 		LEFT JOIN stvpcnf cn 
	// 		    ON cn.stvpcnf_student_id = s.id
	// 		WHERE
	// 		    c.stvradm_academic_year IN (SELECT 
	// 		            stvschool_acdm_year
	// 		        FROM
	// 		            stvschool) 

	// 		UNION 

	// 		SELECT 
	// 		    s.id AS id,
	// 		    s.SPRIDEN_student_id AS student_id,
	// 		    CONCAT(s.SPRIDEN_surname,
	// 		            ' ',
	// 		            s.SPRIDEN_student_name,
	// 		            ' ',
	// 		            s.SPRIDEN_father_name) AS name,
	// 		    c.STVCLAS_standard AS class,
	// 		    (SELECT 
	// 		            IFNULL(SUM(sprfees_amount), 0) total
	// 		        FROM
	// 		            sprfees
	// 		        WHERE
	// 		            sprfees_studentid = s.id) fees,
	// 		    (SELECT 
	// 		            stvfstr_fees
	// 		        FROM
	// 		            stvfstr
	// 		        WHERE
	// 		            stvfstr_std = c.STVCLAS_standard
	// 		                AND stvfstr_acdm_year IN (SELECT 
	// 		                    stvschool_acdm_year
	// 		                FROM
	// 		                    stvschool)) total_fees_of_class,
	// 		    (SELECT (total_fees_of_class) - (fees) FROM DUAL) AS balancia,
	// 		    (SELECT 
	// 		            IFNULL(SUM(sprfees.sprfees_exumption), 0)
	// 		        FROM
	// 		            sprfees
	// 		        WHERE
	// 		            sprfees.sprfees_studentid = s.id
	// 		                AND sprfees.academic_year IN (SELECT 
	// 		                    stvschool_acdm_year
	// 		                FROM
	// 		                    stvschool)) AS exumtion,
	// 		    IFNULL((SELECT (IFNULL(balancia, 0) - IFNULL(exumtion, 0)) FROM DUAL),
	// 		            0) bharna,
	// 		    cn.stvpcnf_mobile AS mobile,
	// 		    cn.stvpcnf_whatsapp AS whatsapp,
	// 		    s.SPRIDEN_date_created AS adm_date,
	// 		    'New Admission' AS type
	// 		FROM
	// 		    spriden s
	// 		INNER JOIN stvclas c 
	// 		    ON c.STVCLAS_student_id = s.id
	// 		LEFT JOIN stvpcnf cn 
	// 		    ON cn.stvpcnf_student_id = s.id
	// 		WHERE
	// 		    s.SPRIDEN_user_type = 'st'
	// 		    AND s.SPRIDEN_student_id LIKE CONCAT('S',
	// 		            (SELECT 
	// 		                    SUBSTR(stvacdm_year, 1, 4)
	// 		                FROM
	// 		                    stvacdm
	// 		                WHERE
	// 		                    stvacdm_id IN (SELECT 
	// 		                            stvschool_acdm_year
	// 		                        FROM
	// 		                            stvschool)),
	// 		            '%')
	// 		ORDER BY adm_date DESC;")->result_array();
	// }
	function all_admission_report()
	{
		return $this->db->query("SELECT 
            s.id AS id,
            s.SPRIDEN_gender,
            s.SPRIDEN_student_id AS student_id,
            CONCAT(s.SPRIDEN_surname,
                    ' ',
                    s.SPRIDEN_student_name,
                    ' ',
                    s.SPRIDEN_father_name) AS name,
            c.stvradm_class AS class,
            (SELECT 
                    IFNULL(SUM(sprfees_amount), 0) total
                FROM
                    sprfees
                WHERE
                    sprfees_studentid = s.id
                        AND academic_year IN (SELECT 
                            stvschool_acdm_year
                        FROM
                            stvschool)) fees,
            (SELECT 
                    stvfstr_fees
                FROM
                    stvfstr
                WHERE
                    stvfstr_std = c.stvradm_class
                        AND stvfstr_acdm_year IN (SELECT 
                            stvschool_acdm_year
                        FROM
                            stvschool)) total_fees_of_class,
            (SELECT (total_fees_of_class) - (fees) FROM DUAL) AS balancia,
            IFNULL((SELECT 
                            IFNULL(SUM(sprfees.sprfees_exumption), 0)
                        FROM
                            sprfees
                        WHERE
                            sprfees.sprfees_studentid = s.id
                                AND sprfees.academic_year IN (SELECT 
                                    stvschool_acdm_year
                                FROM
                                    stvschool)),
                    0) AS exumtion,
            IFNULL((SELECT (balancia - exumtion) FROM DUAL),
                    0) bharna,
            cn.stvpcnf_mobile AS mobile,
            cn.stvpcnf_whatsapp AS whatsapp,
            c.stvradm_date AS adm_date,
            'Re-Admission' AS type
        FROM
            spriden s
        INNER JOIN stvradm c 
            ON c.stvradm_student_id = s.id
        LEFT JOIN stvpcnf cn 
            ON cn.stvpcnf_student_id = s.id
        WHERE
            c.stvradm_academic_year IN (SELECT 
                    stvschool_acdm_year
                FROM
                    stvschool) 

        UNION 

        SELECT 
            s.id AS id,
            s.SPRIDEN_gender, -- Added this column to fix the matching error
            s.SPRIDEN_student_id AS student_id,
            CONCAT(s.SPRIDEN_surname,
                    ' ',
                    s.SPRIDEN_student_name,
                    ' ',
                    s.SPRIDEN_father_name) AS name,
            c.STVCLAS_standard AS class,
            (SELECT 
                    IFNULL(SUM(sprfees_amount), 0) total
                FROM
                    sprfees
                WHERE
                    sprfees_studentid = s.id) fees,
            (SELECT 
                    stvfstr_fees
                FROM
                    stvfstr
                WHERE
                    stvfstr_std = c.STVCLAS_standard
                        AND stvfstr_acdm_year IN (SELECT 
                            stvschool_acdm_year
                        FROM
                            stvschool)) total_fees_of_class,
            (SELECT (total_fees_of_class) - (fees) FROM DUAL) AS balancia,
            (SELECT 
                    IFNULL(SUM(sprfees.sprfees_exumption), 0)
                FROM
                    sprfees
                WHERE
                    sprfees.sprfees_studentid = s.id
                        AND sprfees.academic_year IN (SELECT 
                            stvschool_acdm_year
                        FROM
                            stvschool)) AS exumtion,
            IFNULL((SELECT (IFNULL(balancia, 0) - IFNULL(exumtion, 0)) FROM DUAL),
                    0) bharna,
            cn.stvpcnf_mobile AS mobile,
            cn.stvpcnf_whatsapp AS whatsapp,
            s.SPRIDEN_date_created AS adm_date,
            'New Admission' AS type
        FROM
            spriden s
        INNER JOIN stvclas c 
            ON c.STVCLAS_student_id = s.id
        LEFT JOIN stvpcnf cn 
            ON cn.stvpcnf_student_id = s.id
        WHERE
            s.SPRIDEN_user_type = 'st'
            AND s.SPRIDEN_student_id LIKE CONCAT('S',
                    (SELECT 
                            SUBSTR(stvacdm_year, 1, 4)
                        FROM
                            stvacdm
                        WHERE
                            stvacdm_id IN (SELECT 
                                stvschool_acdm_year
                            FROM
                                stvschool)),
                    '%')
        ORDER BY adm_date DESC;")->result_array();
	}

	function admission_summary($from, $to)
	{

		$academic_year_data = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();

		$academic_year = $academic_year_data['stvschool_acdm_year'];

		$data_academic_year = $this->db->query("select * from stvacdm where stvacdm_id = $academic_year")->row_array();

		$date = $this->db->query("select min(stvradm_date) from_date, max(stvradm_date) to_date  from stvradm WHERE stvradm_academic_year = '$academic_year'")->row_array();

		$date1 = $this->db->query("select min(SPRIDEN_date_created) from_date, max(SPRIDEN_date_created) to_date from spriden where SPRIDEN_date_created != '0000-00-00' and SPRIDEN_user_type = 'st' and SPRIDEN_student_id like concat('S', (select substr(stvacdm_year,1,4) from stvacdm where stvacdm_id in (select stvschool_acdm_year from stvschool)), '%')")->row_array();

		if ($from == 0) {

			$spriden_from = $date1['from_date'];

			$acdm_from = $date['from_date'];
		} else {

			$spriden_from = $from;

			$acdm_from = $from;
		}

		if ($to == 0) {

			$spriden_to = $date1['to_date'];

			$acdm_to = $date['to_date'];
		} else {

			$spriden_to = $to;

			$acdm_to = $to;
		}

		$sql = "select class sprfees_standard,sum(fees) sprfees_amount, count(fees) sprfees_studentid from (select s.id as id, s.SPRIDEN_student_id as student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) as name,c.stvradm_class as class, c.stvradm_fess as fees, c.stvradm_date as adm_date, 'Re-Admission' as type

		from spriden s, stvradm c

		where c.stvradm_student_id = s.id 

		and c.stvradm_date BETWEEN '$acdm_from' and '$acdm_to' 

		and c.stvradm_academic_year in (select stvschool_acdm_year from stvschool)

        union 

        select s.id as id, s.SPRIDEN_student_id as student_id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ', s.SPRIDEN_father_name) as name,

		c.STVCLAS_standard as class,(select ifnull(sum(sprfees_amount),0) total from sprfees where sprfees_studentid = s.id) fees, s.SPRIDEN_date_created as adm_date, 'New Admission' as type

		from spriden s, stvclas c

		where s.SPRIDEN_user_type = 'st'

		and c.STVCLAS_student_id = s.id

        and s.SPRIDEN_date_created BETWEEN '$spriden_from' and '$spriden_to'                                       

        and s.SPRIDEN_student_id like concat('S', (select substr(stvacdm_year,1,4) from stvacdm where stvacdm_id in (select stvschool_acdm_year from stvschool)), '%') order by adm_date desc) table_stu where class is not null and class != '' group by class order by 1";

		// die($sql);

		return $this->db->query($sql)->result_array();
	}

	function fetchInstallment($class, $div)
	{

		$sql = "SELECT distinct spriden.id,
		concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name,
		stvclas.STVCLAS_acdm,
		concat(stvclas.STVCLAS_standard, ' - ', stvclas.STVCLAS_class) class_name,
		stvclas.STVCLAS_standard,
		(select GROUP_CONCAT(sprfees.sprfees_amount, '->', sprfees.sprfees_feesdate) from sprfees where sprfees.sprfees_studentid=spriden.id and sprfees.sprfees_studentid=STVCLAS_student_id and sprfees.sprfees_standard=stvclas.STVCLAS_standard) installments,
		(select sum(sprfees.sprfees_amount) from sprfees where sprfees.sprfees_studentid=spriden.id and sprfees.sprfees_studentid=STVCLAS_student_id and sprfees.sprfees_standard=stvclas.STVCLAS_standard) total_fees_paid,
		ifnull(spriden.rte_status,'No') rte_status
		from spriden,stvclas
		where stvclas.STVCLAS_student_id = spriden.id and 
		spriden.spriden_student_status='A' and 
		spriden.spriden_user_type='st' ";

		if ($class != '0') {

			$sql .= " and stvclas.STVCLAS_standard = '$class'  ";
		}

		if ($div != '0') {

			$sql .= " and stvclas.STVCLAS_class = '$div' ";
		}

		$sql .= " group by spriden.id order by spriden.id ";



		return $this->db->query($sql)->result_array();
	}

	// Updated 2025 Oct 10 - Ansari Kamran 
	public function get_all_academic_years()
	{
		return $this->db->select('stvacdm.stvacdm_id, stvacdm.stvacdm_year')
			->from('stvacdm')
			->order_by('stvacdm_year', 'DESC')
			->get()
			->result_array();
	}

	function feesAlertNotification($year, $class, $div, $amount)
	{
		// Updated 2024 Sep 26 - Ansari Kamran 
		// Updated 2025 Oct 10 - Ansari Kamran 
		$sql = "
		SELECT 
			s.id,
			s.SPRIDEN_student_id,
			CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
			s.rte_status,
			c.STVCLAS_rollno,
			c.STVCLAS_standard,
			c.STVCLAS_class,
			p.stvpcnf_whatsapp,
			IFNULL(SUM(f.sprfees_amount), 0) AS total_paid,
			IFNULL(SUM(CASE WHEN f.sprfees_exumption > 0 THEN f.sprfees_exumption ELSE 0 END), 0) AS total_exemption,
			fstr.stvfstr_fees AS total_fees,
			(IFNULL(fstr.stvfstr_fees, 0) - IFNULL(SUM(f.sprfees_amount), 0)) AS balance
		FROM
			stvclas c
		JOIN
			spriden s ON s.id = c.STVCLAS_student_id
		LEFT JOIN
			sprfees f ON f.sprfees_studentid = c.STVCLAS_student_id
			AND f.academic_year = c.STVCLAS_acdm
		LEFT JOIN
			stvfstr fstr ON fstr.stvfstr_acdm_year = c.STVCLAS_acdm
			AND fstr.stvfstr_std = c.STVCLAS_standard
		LEFT JOIN 
			stvpcnf p ON s.id = p.stvpcnf_student_id
		WHERE
			c.STVCLAS_standard = '$class'
			AND c.STVCLAS_class = '$div'
			AND c.STVCLAS_acdm = '$year'
			AND s.spriden_student_status = 'A'
		GROUP BY 
			s.SPRIDEN_student_id";

		if (strpos($amount, '-') !== false) {
			list($min_amount, $max_amount) = explode('-', $amount);
			$sql .= " HAVING balance BETWEEN $min_amount AND $max_amount";
		} else {
			$sql .= " HAVING balance = '$amount'";
		}

		return $this->db->query($sql)->result_array();
	}

	function datewise_fees($from, $to, $class, $type)
	{
		if ($type == 0) {
			if ($class == 0) {
				$records = [];
				$academics = $this->db->query("select distinct ac.stvacdm_year,f.academic_year from sprfees f LEFT JOIN stvacdm ac ON ac.stvacdm_id = f.academic_year where f.sprfees_feesdate BETWEEN '$from' and '$to' ORDER BY 1, 2")->result_array();


				foreach ($academics as $acdms) {
					if ($acdms['stvacdm_year'] != '' && $acdms['academic_year'] != '') {
						$record['acdm_id'] = $acdms['academic_year'];
						$record['acdm_year'] = $acdms['stvacdm_year'];
						$record['data'] = $this->db->query("select f.sprfees_feesdate, sum(f.sprfees_amount) sum from sprfees f LEFT JOIN stvacdm ac ON ac.stvacdm_id = f.academic_year where f.sprfees_feesdate BETWEEN '$from' and '$to' and f.academic_year = $acdms[academic_year] group by f.academic_year, f.sprfees_feesdate ORDER BY 1")->result_array();
						array_push($records, $record);
					}
				}
				return $records;
			} else {
				$sql = "select sprfees_feesdate, sum(sprfees_amount) sum,sprfees_standard from sprfees where sprfees_feesdate BETWEEN '$from' and '$to' group by sprfees_feesdate,sprfees_standard";
			}
		} else {
			if ($class == 0) {
				$sql = "select * from (select CAST(SUBSTRING(sprfees_feesdate, 6, 2) as UNSIGNED) sprfees_feesdate, CAST(SUBSTRING(sprfees_feesdate, 3, 2) as UNSIGNED)sprfees_month, sum(sprfees_amount) sum from sprfees where sprfees_feesdate BETWEEN '$from' and '$to' group by CAST(SUBSTRING(sprfees_feesdate, 6, 2)as UNSIGNED) order by SUBSTRING(sprfees_feesdate, 6, 2)) as temp order by sprfees_month asc;";
			} else {
				$sql = "select * from (select CAST(SUBSTRING(sprfees_feesdate, 6, 2) as UNSIGNED) sprfees_feesdate, CAST(SUBSTRING(sprfees_feesdate, 3, 2) as UNSIGNED)sprfees_month, sum(sprfees_amount) sum from sprfees where sprfees_feesdate BETWEEN '$from' and '$to' group by CAST(SUBSTRING(sprfees_feesdate, 6, 2)as UNSIGNED) order by SUBSTRING(sprfees_feesdate, 6, 2)) as temp order by sprfees_month asc,sprfees_standard";
			}
		}


		return $this->db->query($sql)->result_array();
	}

	function exemption_fees($from, $to)
	{
		$acdm_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
		$acdm_year = $acdm_year['stvschool_acdm_year'];
		$data['last_installment'] = $this->db->query("select max(total_installament) max_installment from (SELECT s.id,  (select count(*) from sprfees where sprfees_studentid = s.id and sprfees_feesdate between '$from' and '$to') total_installament FROM spriden s, stvclas c  where s.id = c.STVCLAS_student_id and s.id in (select distinct sprfees_studentid from sprfees where sprfees_feesdate between '$from' and '$to')) as temp")->row_array();
		$data['last_installment'] = $data['last_installment'] != null ? $data['last_installment']['max_installment'] : 0;
		$sql = "SELECT * FROM (SELECT s.id,s.spriden_student_id, concat(s.spriden_surname, ' ', s.spriden_student_name, ' ', s.spriden_father_name) name,
		c.STVCLAS_standard,
		(select ifnull(sum(sprfees_amount),0) from sprfees where sprfees_studentid = s.id and sprfees_feesdate between '$from' and '$to') total_fees_paid,
		(select count(*) from sprfees where sprfees_studentid = s.id and sprfees_feesdate between '$from' and '$to') total_installament,
		(select group_concat(sprfees_amount,':',sprfees_pk, ':', sprfees_feesdate ) from sprfees where sprfees_studentid = s.id and sprfees_feesdate between '$from' and '$to' order by sprfees_pk) installment
		 FROM spriden s, stvclas c 
		 where s.id = c.STVCLAS_student_id
		 and s.id in (select distinct sprfees_studentid from sprfees where sprfees_feesdate between '$from' and '$to')) as temp WHERE total_fees_paid > 0 
		 order by STVCLAS_standard, id";
		$data['records'] = $this->db->query($sql)->result_array();
		return $data;
	}


	function update_exemption_fees()
	{
		$total_students = $this->input->post('total_students');
		$total_installments = $this->input->post('total_installments');

		for ($i = 1; $i <= $total_students; $i++) {
			$selected = $this->input->post('checkbox' . $i);
			$student_id = $this->input->post('student-id' . $i);
			if ($selected == 'on') {
				for ($j = 0; $j < $total_installments; $j++) {
					$line_id = $this->input->post("line_id$i-$j");
					if ($line_id != '') {
						$fees_data = $this->db->query("SELECT * FROM sprfees where sprfees_pk  = $line_id and sprfees_studentid = $student_id")->row_array();
						$fees_amount = $fees_data != null ? $fees_data['sprfees_amount'] : 0;
						$value = $this->input->post("box$i-$j");
						$exemption = $value - $fees_amount;

						$sql = "update sprfees set sprfees_amount = $value where sprfees_pk = $line_id and sprfees_studentid = $student_id";
						$this->db->query($sql);
					}
				}
			}
		}

		echo 'success';
	}

	function fetchreceipt($from, $to)
	{
		$sql = "select f.sprfees_pk,s.id, concat(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name , ' ',s.SPRIDEN_father_name)as name,
			f.sprfees_standard,f.sprfees_amount, f.sprfees_feesdate
			from spriden s, sprfees f
			where s.id = f.sprfees_studentid
			and f.sprfees_feesdate BETWEEN '$from' and '$to'
			and f.sprfees_amount != 0
			order by f.sprfees_pk ";
		return $this->db->query($sql)->result_array();
	}

	// ============================================================

	function balanc_report($vls)
	{
		$sql = "select ID,SPRIDEN_student_id, paid_fees as total_paid, exumption as total_exumpted, name, class as STVCLAS_standard, fs.stvfstr_fees as class_fees,
		(fs.stvfstr_fees - paid_fees) balance,
		((fs.stvfstr_fees - exumption) - paid_fees) bal
		from (select f.sprfees_studentid as ID , sum(f.sprfees_amount) paid_fees,sum(f.sprfees_exumption) exumption,
		concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name, c.STVCLAS_standard class, s.SPRIDEN_student_id
		from sprfees f, spriden s, stvclas c
		where f.academic_year = (select pre_academic_year from stvschool)
		and f.sprfees_studentid = s.id
		and (s.rte_status is null or s.rte_status = 'no')
		and c.STVCLAS_student_id = s.id and c.STVCLAS_student_id = f.sprfees_studentid
		and c.STVCLAS_acdm = f.academic_year
		group by f.sprfees_studentid) as temp, stvfstr fs where stvfstr_acdm_year = (select pre_academic_year from stvschool) and fs.stvfstr_std = class
		and ((fs.stvfstr_fees - exumption) - paid_fees) > 0";
		if ($vls == 'Readmission')
			$sql .= " and ID in (select stvradm_student_id from stvradm where stvradm_academic_year = (select stvschool_acdm_year from stvschool))";

		return $this->db->query($sql)->result_array();
	}

	// ============================================================
	// ============================================================
	// Developer : Ansari Kamran
	// Date : 12-09-23
	// ------------------------------- Start ---------------------------------------
	function application_data($from_date, $to_date, $type)
	{
		if ($type == 'Teacher')
			$type = 'tc';
		if ($type == 'Student')
			$type = 'st';

		$sql = "SELECT at.user_type,at.user_id, DATE_FORMAT(at.login_date, '%d %b %Y') login_date, at.login_time, at.last_login, at.login_count,
	concat (s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,s.SPRIDEN_student_id
	from app_tracking at, spriden s
	where 
	(
	case when at.user_type = 'tc' then at.user_id = s.SPRIDEN_student_id
		 when at.user_type = 'st' then at.user_id = s.id
	end
	)
	and at.user_type = '$type' and at.login_date between '$from_date' and '$to_date' order by at.login_date, at.login_time";
		return $this->db->query($sql)->result_array();
	}

	function teacher_activity($from_date, $to_date)
	{
		$sql = "SELECT DATE_FORMAT(at.activity_date, '%d %b %Y') activity_date, at.activity_time, at.activity_sorce, at.action_date,
	concat (s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,s.SPRIDEN_student_id
	from teacher_activity at, spriden s
	where at.user_id = s.SPRIDEN_student_id and s.spriden_student_status = 'A'
	and at.activity_date between '$from_date' and '$to_date' order by at.activity_date, at.activity_time";
		// die($sql);
		return $this->db->query($sql)->result_array();
	}
	// ---------------------------- End --------------------------------------------


	function get_teachers()
	{
		$sql = "select spriden_student_id id, concat(SPRIDEN_student_name, ' ' , SPRIDEN_father_name, ' ' , SPRIDEN_surname) NAME from spriden where 
		SPRIDEN_user_type='tc' and spriden_student_status='A' order by 1";
		return $this->db->query($sql)->result_array();
	}

	function get_source()
	{
		$sql = "SELECT DISTINCT CONCAT(UCASE(LEFT(activity_sorce, 1)), LOWER(SUBSTRING(activity_sorce, 2))) AS source
		FROM teacher_activity";
		return $this->db->query($sql)->result_array();
	}

	function get_attend($teach_id, $from, $to)
	{
		$sql = "SELECT 
		    t.id,
		    t.user_id,
		    t.stud_id,
		    t.activity_date,
		    t.activity_time,
		    t.activity_sorce,
		    t.action_date,
		    CASE
		        WHEN t.activity_sorce = 'attendance' THEN CONCAT(c.SPATESD_std, ' - ', c.SPATESD_div)
		        ELSE NULL
		    END AS class_and_division,
		    CASE
		        WHEN t.activity_sorce = 'attendance'
		        THEN (
		            SELECT COUNT(*)
		            FROM spratnd s
		            WHERE s.spratnd_teacher_id = t.user_id
		              AND s.spratnd_date = t.action_date
		              AND s.spratnd_std = c.SPATESD_std
		              AND s.spratnd_div = c.SPATESD_div
		              AND s.spratnd_status = 'P'
		        )
		        ELSE NULL
		    END AS Present,
		    CASE
		        WHEN t.activity_sorce = 'attendance'
		        THEN (
		            SELECT COUNT(*)
		            FROM spratnd s
		            WHERE s.spratnd_teacher_id = t.user_id
		              AND s.spratnd_date = t.action_date
		              AND s.spratnd_std = c.SPATESD_std
		              AND s.spratnd_div = c.SPATESD_div
		              AND s.spratnd_status = 'A'
		        )
		        ELSE NULL
		    END AS Absent
		FROM
		    teacher_activity t
		LEFT JOIN
		    spatesd c ON t.user_id = c.SPATESD_teacher_id
		WHERE
		    t.activity_sorce = 'attendance'
		    AND t.user_id = '$teach_id'
		    AND t.activity_date BETWEEN '$from' AND '$to'";

		return $this->db->query($sql)->result_array();
	}

	function get_fees_alert($teach_id, $from, $to)
	{
		$sql = "SELECT
				ta.*,
				(
					SELECT GROUP_CONCAT(CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name, ' (', sc.STVCLAS_standard, '-', sc.STVCLAS_class, ')') SEPARATOR ', ')
					FROM spriden s
					LEFT JOIN stvclas sc ON s.id = sc.STVCLAS_student_id AND sc.STVCLAS_acdm = 6
					WHERE FIND_IN_SET(s.id, ta.stud_id) > 0
				) AS student_names
			FROM teacher_activity ta
			WHERE ta.activity_date between '$from' and '$to'
			AND ta.user_id = '$teach_id'
			AND ta.activity_sorce = 'fees_alert'";
		// die($sql);
		return $this->db->query($sql)->result_array();
	}

	function get_remark($teach_id, $from, $to)
	{
		$sql = "SELECT
		ta.*,
		(
			SELECT GROUP_CONCAT(CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name, 
			' (', sc.STVCLAS_standard, '-', sc.STVCLAS_class, ')') SEPARATOR ', ')
			FROM spriden s
			LEFT JOIN stvclas sc ON s.id = sc.STVCLAS_student_id AND sc.STVCLAS_acdm = 6
			WHERE FIND_IN_SET(s.id, ta.stud_id) > 0
		) AS student_names,
		(
			SELECT GROUP_CONCAT(stvrmrk_remark SEPARATOR ', ')
			FROM stvrmrk
			WHERE stvrmrk_teacher_id = ta.user_id
			and stvrmrk_date=ta.activity_date
			AND FIND_IN_SET(stvrmrk_student_id, ta.stud_id) > 0
		) AS remarks,
		(
        SELECT GROUP_CONCAT(stvrmrk_reply SEPARATOR ', ')
        FROM stvrmrk
        WHERE stvrmrk_teacher_id = ta.user_id
        AND FIND_IN_SET(stvrmrk_student_id, ta.stud_id) > 0
    ) AS replies,
    (
        SELECT GROUP_CONCAT(stvrmrk_date_of_reply SEPARATOR ', ')
        FROM stvrmrk
        WHERE stvrmrk_teacher_id = ta.user_id
        AND FIND_IN_SET(stvrmrk_student_id, ta.stud_id) > 0
    ) AS reply_dates
		FROM teacher_activity ta
		WHERE ta.activity_date between '$from' and '$to'
			AND ta.user_id = '$teach_id'
		AND ta.activity_sorce = 'remark'";

		return $this->db->query($sql)->result_array();
	}

	function get_event($teach_id, $from, $to)
	{
		$sql = "select * from stvevnt st,spatesd sp where st.stvevnt_std=sp.SPATESD_std and st.stvevnt_div=sp.SPATESD_div 
		and SPATESD_teacher_id= '$teach_id' and stvevnt_start_date between '$from' and '$to' ";
		return $this->db->query($sql)->result_array();
	}



	// Ansari Kamran
	// date 23-09-23
	function fetch_data()
	{
		$teacher = $this->input->get('teacher');
		$std = $this->input->get('std');
		$sub = $this->input->get('sub');
		$div = $this->input->get('div');

		if ($teacher != '') {
			$sql = "SELECT id, title, unit, std,(SELECT stvsubj_subject FROM stvsubj where stvsubj_id = subject) as subject, `div`, teaching_date, status, teacher_id, created_by, created_at, updated_by, updated_at FROM stvslbs WHERE teacher_id = '$teacher'";
		}
		if ($teacher && $std != '') {
			$sql = "SELECT id, title, unit, std,(SELECT stvsubj_subject FROM stvsubj where stvsubj_id = subject) as subject, `div`, teaching_date, status, teacher_id, created_by, created_at, updated_by, updated_at FROM stvslbs WHERE teacher_id = '$teacher' and std = '$std'";
		}
		if ($teacher && $std && $sub != '') {
			$sql = "SELECT id, title, unit, std,(SELECT stvsubj_subject FROM stvsubj where stvsubj_id = subject) as subject, `div`, teaching_date, status, teacher_id, created_by, created_at, updated_by, updated_at FROM stvslbs WHERE teacher_id = '$teacher' and std = '$std' and subject = '$sub'";
		}
		if ($teacher && $std && $sub && $div != '') {
			$sql = "SELECT id, title, unit, std,(SELECT stvsubj_subject FROM stvsubj where stvsubj_id = subject) as subject, `div`, teaching_date, status, teacher_id, created_by, created_at, updated_by, updated_at FROM stvslbs WHERE teacher_id = '$teacher' and std = '$std' and subject = '$sub' and `div` = '$div' ";
		}

		return $this->db->query($sql)->result_array();
	}

	function syllabus($std)
	{

		$sql = "SELECT stvsubj_id,stvsubj_subject FROM `stvsubj` WHERE stvsubj_std = '$std' and stvsubj_acdm = (select stvschool_acdm_year from stvschool) and stvsubj_subject != 'ALL'";

		return $this->db->query($sql)->result_array();
	}

	function syllabus_calender($std, $div, $teacher_id, $startDate, $endDate)
	{

		// Get the current date as a timestamp


		// $sql = $this->db->query("SELECT * FROM stvslbs where teaching_date BETWEEN '2023-09-25' AND '2023-09-30' and created_by ='E20220003' and std = '' and `div` = ''");
		$sql = "SELECT * FROM stvslbs where teaching_date BETWEEN '$startDate' AND '$endDate' and created_by ='$teacher_id' and std = '$std' and `div` = '$div'";
		return $this->db->query($sql)->result_array();
	}

	// function aadhar_report($class, $div, $year = 0)
	// {

	// 	$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
	// 	$year = $q['stvschool_acdm_year'];
	// 	$sql = "SELECT s.id, s.SPRIDEN_student_id, CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS student_name, a.*
	// 	FROM spriden s
	// 	LEFT JOIN aadhar_details a ON s.id = a.user_id
	// 	WHERE s.id IN (
	// 		SELECT DISTINCT c.STVCLAS_student_id
	// 		FROM stvclas c
	// 		JOIN spriden sp ON sp.id = c.STVCLAS_student_id
	// 		WHERE  c.STVCLAS_standard = '$class'
	//               AND c.STVCLAS_class = '$div'
	// 			  AND c.STVCLAS_acdm = '$year'
	// 		AND sp.SPRIDEN_user_type = 'st'
	// 		AND sp.SPRIDEN_student_status = 'A'
	// 	)
	// 	";
	// 	// die($sql);
	// 	$data = $this->db->query($sql)->result_array();
	// 	return $data;
	// }

	function aadhar_report($class, $div)
	{
		// Academic Year fetch kar rahe hain
		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

		// Check karein agar year exist karta hai, nahi toh default 0
		$acdm_year = isset($q['stvschool_acdm_year']) ? $q['stvschool_acdm_year'] : 0;

		// Optimized SQL Query
		// 1. CONCAT_WS: Name fields mein agar NULL hai toh bhi naam dikhega.
		// 2. JOIN stvclas: Subquery hata kar direct join use kiya hai for better performance.
		// 3. DISTINCT: Agar kisi student ke do entries hain toh repeat na ho.
		$sql = "SELECT DISTINCT 
                s.id, 
                s.SPRIDEN_student_id, 
                CONCAT_WS(' ', s.SPRIDEN_surname, s.SPRIDEN_student_name, s.SPRIDEN_father_name) AS student_name, 
                a.*
            FROM spriden s
            INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
            LEFT JOIN aadhar_details a ON s.id = a.user_id
            WHERE c.STVCLAS_standard = ? 
              AND c.STVCLAS_class = ? 
              AND c.STVCLAS_acdm = ? 
              AND s.SPRIDEN_user_type = 'st' 
              AND s.SPRIDEN_student_status = 'A'";

		// Parameters pass kar rahe hain (Security maintained)
		$data = $this->db->query($sql, array($class, $div, $acdm_year))->result_array();

		return $data;
	}

	function total_fees()
	{

		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$year = $q['stvschool_acdm_year'];

		$sql = "SELECT
		(SELECT SUM(sprfees_amount) FROM sprfees WHERE academic_year = $year) AS total_fees_paid,
		(SELECT SUM(sprfees_exumption) FROM sprfees WHERE academic_year = $year) AS total_fees_exemption,
		SUM(temp.stvfstr_fees) AS total_fees
	FROM
		(SELECT DISTINCT s.id, c.STVCLAS_standard, fs.stvfstr_fees
		FROM spriden s
		JOIN stvclas c ON s.SPRIDEN_user_type = 'st' AND s.spriden_student_status = 'A' 
					  AND c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = $year
		JOIN stvfstr fs ON fs.stvfstr_acdm_year = c.STVCLAS_acdm AND fs.stvfstr_std = c.STVCLAS_standard
		) AS temp;";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}

	// 08-11-23
	function total_stud()
	{

		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$year = $q['stvschool_acdm_year'];

		$sql = "SELECT 
		(select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='A' and st.stvclas_acdm='$year' and s.id=stvclas_student_id) AS TOTAL_STUDENT,
		(select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='A' and s.rte_status='Yes' and st.stvclas_acdm='$year' and s.id=stvclas_student_id) AS RTE,
		(select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='I' and st.stvclas_acdm='$year' and s.id=stvclas_student_id
	  ) AS INACTIVE";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}


	// 08-11-23
	function fetch_class()
	{

		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$year = $q['stvschool_acdm_year'];

		$sql = "SELECT temp.STVCLAS_standard AS standard, COUNT(temp.id) AS no_of_students, SUM(temp.stvfstr_fees) AS total_fees
        FROM
        (
            SELECT s.id, CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,
            c.STVCLAS_standard, f.stvfstr_fees, f.stvfstr_std
            FROM spriden s, stvclas c, stvfstr f
            WHERE s.SPRIDEN_user_type = 'st' AND s.spriden_student_status = 'A'
            AND s.id = c.STVCLAS_student_id AND c.STVCLAS_acdm = '$year'
            AND c.STVCLAS_standard = f.stvfstr_std AND f.stvfstr_acdm_year = c.STVCLAS_acdm
        ) AS temp
        GROUP BY temp.STVCLAS_standard
        ORDER BY standard ASC;";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}

	// 08-11-23
	function fetch_rte()
	{

		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$year = $q['stvschool_acdm_year'];

		$sql = "SELECT distinct SPRIDEN_student_id AS student_id,
		CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS student_name,
		c.STVCLAS_standard AS standard
		FROM spriden s, stvclas c where s.id = c.STVCLAS_student_id
		and s.spriden_student_status = 'A'
		  AND s.SPRIDEN_user_type = 'st'
		  AND s.rte_status = 'Yes'
		  AND c.STVCLAS_acdm= $year;";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}


	function fetch_active()
	{

		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$year = $q['stvschool_acdm_year'];

		$sql = " SELECT DISTINCT SPRIDEN_student_id,
		CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,
		c.STVCLAS_standard
			FROM spriden s,sprfees sp,stvclas c
			WHERE s.id = c.STVCLAS_student_id
		   and  s.SPRIDEN_user_type = 'st'
			AND s.spriden_student_status = 'A'
			AND c.STVCLAS_acdm = '6'
			AND sp.academic_year = '6'
			GROUP BY s.id, c.STVCLAS_standard
			ORDER BY c.STVCLAS_standard;";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}

	function fetch_inactive()
	{

		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$year = $q['stvschool_acdm_year'];

		$sql = "Select s.SPRIDEN_student_id,CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,  st.STVCLAS_standard from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='I' and st.stvclas_acdm='$year' and s.id=stvclas_student_id";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}



	function student_dataChart($year, $std)
	{
		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

		$year = $q['stvschool_acdm_year'];

		$sql = "SELECT DISTINCT SPRIDEN_student_id,    
    
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,
        c.STVCLAS_standard,
        f.stvfstr_fees,
        SUM(sp.sprfees_amount) AS paid_fees
        FROM spriden s
        INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
        INNER JOIN stvfstr f ON c.STVCLAS_standard = f.stvfstr_std AND f.stvfstr_acdm_year = c.STVCLAS_acdm
        INNER JOIN sprfees sp ON sp.sprfees_studentid = s.id AND sp.sprfees_standard = c.STVCLAS_standard
        WHERE s.SPRIDEN_user_type = 'st'
        AND s.spriden_student_status = 'A'
        AND c.STVCLAS_acdm = '$year'
        AND sp.academic_year = '$year'
        AND c.STVCLAS_standard = '$std'
        GROUP BY s.id, c.STVCLAS_standard
        ORDER BY c.STVCLAS_standard";

		// die($sql);
		return $this->db->query($sql)->result_array();
	}


	function stud_fetch($student_id, $acdm_year)
	{

		$sql = "SELECT sprfees.*, stvacdm.stvacdm_year, spruser.spruser_name,

		(select exumption from sprexumtion where exu_studentid = sprfees.sprfees_studentid and acdm_year  = '$acdm_year') exumption

		from sprfees, stvacdm , spruser

		where stvacdm.stvacdm_id = sprfees.academic_year and sprfees.sprfees_studentid = '$student_id'

		and sprfees.sprfees_recivedby = spruser.spruser_id

		order by stvacdm.stvacdm_year desc";


		return $this->db->query($sql)->result_array();
	}
	function class_fetch()
	{
		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

		$year = $q['stvschool_acdm_year'];
		$sql = "select distinct count(c.STVCLAS_student_id) total_active_students, st.stvfstr_std,st.stvfstr_fees,
		(count(c.STVCLAS_student_id) * st.stvfstr_fees) total_fees,
  
		(select sum(f.sprfees_amount) fees_paid from sprfees f, spriden s
		where f.academic_year = '$year'
		and f.sprfees_studentid = s.id
		and s.spriden_student_status = 'A'
		and s.SPRIDEN_user_type = 'st'
	and f.sprfees_standard = c.STVCLAS_standard) fees_paid
		from stvclas c, spriden s, stvfstr st
		where c.STVCLAS_acdm = '$year'
		and c.STVCLAS_student_id = s.id and s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A'
		and c.STVCLAS_standard = st.stvfstr_std and st.stvfstr_acdm_year = '$year' and st.stvfstr_acdm_year = c.STVCLAS_acdm
  group by st.stvfstr_std,st.stvfstr_fees
		order by 1 desc;";


		return $this->db->query($sql)->result_array();
	}
	function date()
	{
		$sql = "select stvacdm_start, stvacdm_end,stvacdm_year from stvacdm where stvacdm_id=(select stvschool_acdm_year from stvschool)";
		$sql = $this->db->query($sql)->row_array();
		return $sql;
	}


	function total_exemption()
	{
		$sql = "SELECT SUM(stvfstr_fees) AS total_fees, SUM(exumption) AS total_exemption
	  FROM sprexumtion, stvfstr f";
		return $this->db->query($sql)->result_array();
	}

	function monthfilter($year)
	{

		$req = $this->input->post('request');
		$acdm_data = $this->db->query("select stvacdm_start,stvacdm_end from stvacdm where stvacdm_id in (select stvschool_acdm_year from stvschool)")->row_array();
		$acdm_start = $acdm_data['stvacdm_start'];
		$acdm_end = $acdm_data['stvacdm_end'];

		$range = $this->db->query("SELECT 
          DATE_FORMAT(DATE_ADD('$acdm_start', INTERVAL (input - 1) MONTH), '%Y-%m') AS Month
          FROM
              (SELECT 1 AS input
              UNION ALL SELECT 2
              UNION ALL SELECT 3
              UNION ALL SELECT 4
              UNION ALL SELECT 5
              UNION ALL SELECT 6
              UNION ALL SELECT 7
              UNION ALL SELECT 8
              UNION ALL SELECT 9
              UNION ALL SELECT 10
              UNION ALL SELECT 11
              UNION ALL SELECT 12) AS months
          WHERE
              DATE_ADD('$acdm_start', INTERVAL (input - 1) MONTH) <= '$acdm_end'
              LIMIT $req");

		if ($range != null) {
			$start_date = date('Y-m-01');
			$end_date = date('Y-m-t');
			if ($req == 1) {
				$data = $range->row_array();
				$date_to = $data['Month'];
				$start_date = $date_to . '-01';
				$end_date = date('Y-m-t', strtotime($start_date));
			} else {
				$data = $range->result_array();
				$start_date = $data[0]['Month'] . '-01';
				$end_element = $data[count($data) - 1]['Month'] . '-01';
				$end_date = date('Y-m-t', strtotime($end_element));
			}
			return $this->db->query("select total_students, total_paid, STVCLAS_standard, (stvfstr_fees * total_students) class_fees from (select count(temp.id) total_students, sum(temp.total_paid) total_paid,temp.STVCLAS_standard, f.stvfstr_fees
      from (select s.id, c.STVCLAS_standard,sum(sprfees_amount) total_paid
      from spriden s, stvclas c, sprfees sf
      where s.spriden_student_status = 'A' and s.SPRIDEN_user_type = 'st' and (s.rte_status is null or s.rte_status = 'No')
      and c.STVCLAS_student_id = s.id and c.STVCLAS_acdm in (select stvschool_acdm_year from stvschool)
      and sf.sprfees_studentid = s.id 
      and sf.sprfees_studentid = c.STVCLAS_student_id
      and sf.sprfees_feesdate between '$start_date' AND '$end_date'
      group by s.id) as temp, stvfstr f
      where f.stvfstr_std = temp.STVCLAS_standard and f.stvfstr_acdm_year in (select stvschool_acdm_year from stvschool)
      group by temp.STVCLAS_standard) as temp")->result_array();
		}
	}

	function leaving($id)
	{
		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

		$year = $q['stvschool_acdm_year'];
		$sql = "SELECT spr.id, spr.SPRIDEN_student_id, concat(spr.SPRIDEN_surname, ' ', spr.SPRIDEN_student_name, ' ', spr.SPRIDEN_father_name) as name,
			spr.leaving_date, spr.leaving_reason,spr.lc_date, YEAR(spr.leaving_date) leaving_year,spr.student_next_school,spr.remarks
			FROM spriden spr
			WHERE spr.SPRIDEN_student_id = '$id'";
		return $this->db->query($sql)->result_array();
	}


	function paid_chart($year)
	{
		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

		$year = $q['stvschool_acdm_year'];

		$sql = "SELECT
        paid_fees.year,
        temp.total_students,
        temp.total_fees,
        IFNULL(paid_fees.sprfees_status, 0) AS fees_paid
      FROM
        (SELECT
          COUNT(temp.id) AS total_students,
          SUM(temp.stvfstr_fees) AS total_fees
        FROM
          (SELECT
            s.id,
            CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,
            c.STVCLAS_standard,
            f.stvfstr_fees,
            f.stvfstr_std
          FROM
            spriden s
            INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
            INNER JOIN stvfstr f ON c.STVCLAS_standard = f.stvfstr_std
          WHERE
            s.SPRIDEN_user_type = 'st'
            AND s.spriden_student_status = 'A'
            AND c.STVCLAS_acdm = $year
            AND f.stvfstr_acdm_year = c.STVCLAS_acdm) AS temp) AS temp
      LEFT JOIN
        (SELECT
          YEAR(sprfees_feesdate) AS year,
          SUM(sprfees_amount) AS sprfees_status
        FROM
          sprfees
        WHERE
          YEAR(sprfees_feesdate) = YEAR(CURRENT_DATE)
        GROUP BY
          YEAR(sprfees_feesdate)
        ORDER BY
          YEAR(sprfees_feesdate)) AS paid_fees
      ON
        1 = 1;
      ";
		$sqls = $this->db->query($sql)->result_array();
		return $sqls;
	}

	function filter($from, $to)
	{
		$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();

		$year = $q['stvschool_acdm_year'];

		$sql = "select distinct count(c.STVCLAS_student_id) total_active_students, st.stvfstr_std,st.stvfstr_fees,
      (count(c.STVCLAS_student_id) * st.stvfstr_fees) total_fees,

      (select sum(f.sprfees_amount) fees_paid from sprfees f, spriden s
      where f.academic_year = '$year'
      and f.sprfees_studentid = s.id
      and s.spriden_student_status = 'A'
      and s.SPRIDEN_user_type = 'st'
      and YEAR(f.sprfees_feesdate) = '2023'
          and  f.sprfees_feesdate between '$from' and '$to'
  and f.sprfees_standard = c.STVCLAS_standard) fees_paid
      from stvclas c, spriden s, stvfstr st
      where c.STVCLAS_acdm = '$year'
      and c.STVCLAS_student_id = s.id and s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A'
      and c.STVCLAS_standard = st.stvfstr_std and st.stvfstr_acdm_year = '$year' and st.stvfstr_acdm_year = c.STVCLAS_acdm

      group by st.stvfstr_std,st.stvfstr_fees
      order by 1 desc;
      ";
		$sqls = $this->db->query($sql)->result_array();
		return $sqls;
	}


	function total_cls_stud($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$year = $q['stvschool_acdm_year'];
		}
		$sql = "SELECT TEMP.total_students, TEMP.STVCLAS_standard,TEMP.STVCLAS_class,TEMP.STVCLAS_acdm,f.stvfstr_fees,
		(TEMP.total_students * f.stvfstr_fees) total_fees_of_students
		FROM (SELECT count(*) total_students, c.STVCLAS_standard,c.STVCLAS_acdm,c.STVCLAS_class
								FROM stvclas c
								JOIN spriden sp ON sp.id = c.STVCLAS_student_id
								WHERE c.STVCLAS_standard = '$std'
								  AND c.STVCLAS_class = '$div'
								  AND c.STVCLAS_acdm = '$year'
								  AND sp.SPRIDEN_user_type = 'st'
								  AND sp.SPRIDEN_student_status = 'A') TEMP,stvfstr f where f.stvfstr_acdm_year = TEMP.STVCLAS_acdm and f.stvfstr_std = TEMP.STVCLAS_standard";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}


	// Code Updates By Mr. Azz
	function getSelectedAcademicYear($year = null)
	{
		if ($year == null) {
			$sql = "select * from stvacdm where stvacdm_id in (select stvschool_acdm_year from stvschool)";
		} else {
			$sql = "select * from stvacdm where stvacdm_id  = $year";
		}
		return $this->db->query($sql)->row_array();
	}


	function fetch_paid($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$year = $q['stvschool_acdm_year'];
		}
		$sql = "SELECT sum(sprfees_amount) total_fees_paid FROM sprfees where academic_year = 6 and sprfees_studentid in (
			SELECT DISTINCT c.STVCLAS_student_id
									FROM stvclas c
									JOIN spriden sp ON sp.id = c.STVCLAS_student_id
									WHERE 
									c.STVCLAS_standard = '$std'
									  AND c.STVCLAS_class = '$div'
									  AND c.STVCLAS_acdm = '$year'
									  AND sp.SPRIDEN_user_type = 'st'
									  AND sp.SPRIDEN_student_status = 'A')";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}
	function fetch_exemption($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$year = $q['stvschool_acdm_year'];
		}
		$sql = "SELECT sum(case when sprfees_exumption < 0 then 0 when sprfees_exumption = '-0' then 0 else sprfees_exumption end) sprfees_exumption FROM sprfees where academic_year = '$year' and sprfees_studentid in (
			SELECT DISTINCT c.STVCLAS_student_id
									FROM stvclas c
									JOIN spriden sp ON sp.id = c.STVCLAS_student_id
									WHERE c.STVCLAS_standard = '$std'
									  AND c.STVCLAS_class = '$div'
									  AND c.STVCLAS_acdm = '$year'
									  AND sp.SPRIDEN_user_type = 'st'
									  AND sp.SPRIDEN_student_status = 'A')";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}


	function fetch_balance($std, $div, $from_date, $to_date, $year = null, $type = 'new')
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year,pre_academic_year FROM stvschool")->row_array();
			if ($type == 'new')
				$year = $q['stvschool_acdm_year'];
			else
				$year = $q['pre_academic_year'];
		}
		$sql = "SELECT 
		(SELECT SUM(sprfees_amount) 
		 FROM sprfees 
		 WHERE academic_year = $year 
		 AND sprfees_studentid IN (
			 SELECT DISTINCT c.STVCLAS_student_id
			 FROM stvclas c
			 JOIN spriden sp ON sp.id = c.STVCLAS_student_id
			 WHERE c.STVCLAS_standard = '$std'
			 AND c.STVCLAS_class = '$div'
			 AND c.STVCLAS_acdm = '$year'
			 AND sp.SPRIDEN_user_type = 'st'
			 AND sp.SPRIDEN_student_status = 'A'
		 )
		) AS total_fees_paid,
		TEMP.total_students,
		TEMP.STVCLAS_standard,
		TEMP.STVCLAS_class,
		TEMP.STVCLAS_acdm,
		f.stvfstr_fees,
		(TEMP.total_students * f.stvfstr_fees) AS total_fees_of_students
	FROM 
		(SELECT 
			COUNT(*) AS total_students, 
			c.STVCLAS_standard, 
			c.STVCLAS_acdm, 
			c.STVCLAS_class
		FROM 
			stvclas c
		JOIN 
			spriden sp ON sp.id = c.STVCLAS_student_id
		WHERE 
			c.STVCLAS_standard = '$std'
			AND c.STVCLAS_class = '$div'
			AND c.STVCLAS_acdm = '$year'
			AND sp.SPRIDEN_user_type = 'st'
			AND sp.SPRIDEN_student_status = 'A'
		GROUP BY 
			c.STVCLAS_standard, 
			c.STVCLAS_acdm, 
			c.STVCLAS_class
		) AS TEMP
	JOIN 
		stvfstr f 
	ON 
		f.stvfstr_acdm_year = TEMP.STVCLAS_acdm 
		AND f.stvfstr_std = TEMP.STVCLAS_standard";

		$data = $this->db->query($sql)->row_array();
		return $data;
	}
	function fetch_trte($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$year = $q['stvschool_acdm_year'];
		}
		$sql = "SELECT (select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='A' 
		and s.rte_status='Yes' and st.stvclas_acdm='$year' and s.id=stvclas_student_id and st.STVCLAS_standard ='$std' and st.STVCLAS_class = '$div') AS RTE;";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}
	function fetch_IInactive($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$year = $q['stvschool_acdm_year'];
		}
		$sql = "SELECT 
		(select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and 
        s.spriden_student_status='I' and st.stvclas_acdm='$year' and s.id=stvclas_student_id and st.STVCLAS_standard ='$std' and st.STVCLAS_class = '$div'
	  ) AS INACTIVE";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}


	function fetch_table_data($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$year = $q['stvschool_acdm_year'];
		}

		$sql = "SELECT distinct spriden.id,
		concat(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name,
		stvclas.STVCLAS_acdm,
		concat(stvclas.STVCLAS_standard, ' - ', stvclas.STVCLAS_class) class_name,
		stvclas.STVCLAS_standard,
		(select GROUP_CONCAT(sprfees.sprfees_amount, '->', sprfees.sprfees_feesdate) from sprfees where sprfees.sprfees_studentid=spriden.id 
        and sprfees.sprfees_studentid=STVCLAS_student_id and sprfees.sprfees_standard=stvclas.STVCLAS_standard  and sprfees.sprfees_feesdate between 
        '$from_date' and '$to_date') installments,
		(select sum(sprfees.sprfees_amount) from sprfees where sprfees.sprfees_studentid=spriden.id and sprfees.sprfees_studentid=STVCLAS_student_id 
        and sprfees.sprfees_standard=stvclas.STVCLAS_standard and sprfees.sprfees_feesdate between '$from_date' and '$to_date') total_fees_paid,
        (select (case when sum(case when sprfees.sprfees_exumption < 0 then sprfees.sprfees_exumption else 0 end) < 0 
        then (sum(case when sprfees.sprfees_exumption < 0 then sprfees.sprfees_exumption else 0 end) * -1) 
        else sum(case when sprfees.sprfees_exumption < 0 then sprfees.sprfees_exumption else 0 end) end) total_last 
		from sprfees where sprfees.sprfees_studentid=spriden.id and sprfees.sprfees_studentid=STVCLAS_student_id 
        and sprfees.sprfees_standard=stvclas.STVCLAS_standard and sprfees.academic_year = $year) total_last_balance,
		ifnull(spriden.rte_status,'No') rte_status
		from spriden,stvclas
		where stvclas.STVCLAS_student_id = spriden.id and 
		spriden.spriden_student_status='A' and 
		spriden.rte_status != 'Yes' and 
		spriden.spriden_user_type='st'
		and stvclas.STVCLAS_class = '$div' and STVCLAS_standard = '$std' and stvclas.STVCLAS_acdm = '$year'";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}


	function fatch_prev($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT pre_academic_year FROM stvschool;")->row_array();
			$year = $q['pre_academic_year'];
		}
		$sql = "SELECT 
		(SELECT SUM(sprfees_amount) 
		 FROM sprfees 
		 WHERE academic_year = $year
		 AND sprfees_studentid IN (
			 SELECT DISTINCT c.STVCLAS_student_id
			 FROM stvclas c
			 JOIN spriden sp ON sp.id = c.STVCLAS_student_id
			 WHERE c.STVCLAS_standard = '$std'
			 AND c.STVCLAS_class = '$div'
			 AND c.STVCLAS_acdm = '$year'
			 AND sp.SPRIDEN_user_type = 'st'
			 AND sp.SPRIDEN_student_status = 'A'
		 )
		) AS total_fees_paid,
		TEMP.total_students,
		TEMP.STVCLAS_standard,
		TEMP.STVCLAS_class,
		TEMP.STVCLAS_acdm,
		f.stvfstr_fees,
		(TEMP.total_students * f.stvfstr_fees) AS total_fees_of_students
	FROM 
		(SELECT 
			COUNT(*) AS total_students, 
			c.STVCLAS_standard, 
			c.STVCLAS_acdm, 
			c.STVCLAS_class
		FROM 
			stvclas c
		JOIN 
			spriden sp ON sp.id = c.STVCLAS_student_id
		WHERE 
			c.STVCLAS_standard = '$std'
			AND c.STVCLAS_class = '$div'
			AND c.STVCLAS_acdm = '$year'
			AND sp.SPRIDEN_user_type = 'st'
			AND sp.SPRIDEN_student_status = 'A'
		GROUP BY 
			c.STVCLAS_standard, 
			c.STVCLAS_acdm, 
			c.STVCLAS_class
		) AS TEMP
	JOIN 
		stvfstr f 
	ON 
		f.stvfstr_acdm_year = TEMP.STVCLAS_acdm 
		AND f.stvfstr_std = TEMP.STVCLAS_standard";
		$data = $this->db->query($sql)->row_array();
		return $data;
	}
	function fatch_rte_modal($std, $div, $from_date, $to_date, $year = null)
	{
		if ($year == null) {
			$q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
			$year = $q['stvschool_acdm_year'];
		}
		$sql = "SELECT sp.id,sp.SPRIDEN_student_id, concat(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) as name,
		sp.rte_status,c.STVCLAS_standard, c.STVCLAS_class
		FROM spriden sp
		INNER JOIN stvclas c ON c.STVCLAS_student_id = sp.id AND c.STVCLAS_acdm = '$year' AND c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div'
		WHERE sp.SPRIDEN_user_type = 'st' AND sp.spriden_student_status = 'A' AND sp.rte_status = 'Yes'
		ORDER BY c.STVCLAS_standard, c.STVCLAS_class";
		return $this->db->query($sql)->result_array();
	}

	function student_stats_report_lc_updated($acdm)
	{
		$sql = "WITH current_std_cte AS ( SELECT stvclas_student_id, MAX(STVCLAS_acdm) 
			AS max_acdm FROM stvclas GROUP BY stvclas_student_id ), total_fees_cte AS 
			( SELECT sprfees_studentid, SUM(sprfees_amount) AS total_fees, MAX(sprfees_feesdate) 
			AS last_paid, MAX(sprfees_due_date) AS due_date FROM sprfees WHERE sprfees.academic_year = $acdm GROUP BY sprfees_studentid ),
			 latest_remark_cte AS ( SELECT id, MAX(note) AS note, MAX(due_date) AS next_date FROM sprremark GROUP BY id ), 
			 attendance_cte AS ( SELECT SPRATND_student_id, COUNT(SPRATND_status) AS attendence_p FROM spratnd WHERE SPRATND_status = 'P' GROUP BY SPRATND_student_id ) 
			SELECT DISTINCT spriden.id, spriden.rte_status, spriden.SPRIDEN_student_id,stvclas.STVCLAS_acdm,
			 CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) 
			 AS name, stvclas.STVCLAS_standard , stvclas.STVCLAS_class , stvpcnf.stvpcnf_mobile 
			 AS phone, tf.total_fees, tf.last_paid, tf.due_date, lr.note, lr.next_date, sprexumtion.exumption 
			 AS exumtion, att.attendence_p FROM spriden LEFT JOIN stvclas ON stvclas.STVCLAS_student_id = spriden.id 
			 AND stvclas.STVCLAS_acdm = $acdm
			 LEFT JOIN current_std_cte csc ON csc.stvclas_student_id = spriden.id AND stvclas.STVCLAS_acdm = csc.max_acdm 
			 LEFT JOIN stvpcnf ON stvpcnf.stvpcnf_student_id = spriden.id 
			 LEFT JOIN total_fees_cte tf ON tf.sprfees_studentid = spriden.id 
			 LEFT JOIN latest_remark_cte lr ON lr.id = spriden.id 
			 LEFT JOIN sprexumtion ON sprexumtion.exu_studentid = spriden.id 
			 LEFT JOIN attendance_cte att ON att.SPRATND_student_id = spriden.id 
			 WHERE spriden.SPRIDEN_user_type = 'st' AND spriden.SPRIDEN_student_status = 'A' ORDER BY att.attendence_p DESC, tf.total_fees";

		return $this->db->query($sql)->result_array();
	}

	function inactive_student($to_date, $from_date)
	{
		$sql = "select * from spriden where spriden_student_status='I' and spriden_status_end_date between '$to_date' and '$from_date'";
		return $this->db->query($sql)->result_array();
	}

	//Kamran
	public function request_lc($stud_id, $stud_class, $status, $acdm_year)
	{
		$student_id = $stud_id;
		$class = $stud_class;
		$academic_year = $acdm_year;
		$statuss = $status;
		$token = hash("sha256", time());
		$requested_by = $this->session->userdata('oliveSchoolDashboardUser');
		$requested_at = date('Y-m-d');
		$approved_by = $this->session->userdata('oliveSchoolDashboardUser');
		$approved_at = date('Y-m-d');

		// Check if the record already exists
		$this->db->where('student_id', $student_id);
		$exist = $this->db->get('lc_requests')->row();

		if ($exist) {
			// Update the record if it exists
			$data = array(
				'approved_by' => $approved_by,
				'approved_at' => $approved_at,
				'status' => $statuss,
			);

			$this->db->where('student_id', $student_id);
			return $this->db->update('lc_requests', $data);
		} else {
			// Insert a new record if it doesn't exist
			$data = array(
				'student_id' => $student_id,
				'class' => $class,
				'academic_year' => $academic_year,
				'status' => $statuss,
				'token' => $token,
				'requested_by' => $requested_by,
				'requested_at' => $requested_at
			);

			return $this->db->insert('lc_requests', $data);
		}
	}

	public function lc_isshu($to_date = '', $from_date = '')
	{
		$sql = "SELECT lr.id, lr.student_id, CONCAT(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) AS Name, lr.class, lr.academic_year, lr.status, us.spruser_name AS requested_by, lr.requested_at 
            FROM lc_requests lr 
            JOIN spriden sp ON lr.student_id = sp.id 
            JOIN spruser us ON us.spruser_id = lr.requested_by";

		// Sirf tab date filter lagao jab dono dates available hon
		if (!empty($to_date) && !empty($from_date)) {
			$sql .= " WHERE lr.requested_at BETWEEN '$to_date' AND '$from_date'";
		}

		return $this->db->query($sql)->result_array();
	}


	// function lc_isshu($to_date, $from_date)
	// {
	// 	$sql = "SELECT lr.id,lr.student_id, CONCAT(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) AS Name, lr.class, lr.academic_year, lr.status, us.spruser_name AS requested_by, lr.requested_at FROM lc_requests lr JOIN spriden sp ON lr.student_id = sp.id JOIN spruser us ON us.spruser_id = lr.requested_by WHERE lr.requested_at BETWEEN '$to_date' AND '$from_date'";

	// 	return $this->db->query($sql)->result_array();
	// }
	// Updated 2024 July 24
	// function fetchTeacherActivities($date, $teacher = null)
	// {
	// 	$acdm = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();
	// 	$acdm = $acdm['stvschool_acdm_year'];
	// 	$activities = [];
	// 	$sql = "select distinct t.user_id,s.id,s.SPRIDEN_gender, concat(s.SPRIDEN_Student_name ,' ', s.SPRIDEN_surname) as teacher_name,
	// 	concat(d.SPATESD_std , ' - ', d.SPATESD_div) as class
	// 	from teacher_activity t
	// 	LEFT JOIN spriden s ON s.SPRIDEN_student_id = t.user_id 
	// 	LEFT JOIN spatesd d ON d.SPATESD_teacher_id = t.user_id AND d.SPATESD_academic_year = $acdm";
	// 	$conditions = [];
	// 	if ($date != '')
	// 		$conditions[] = " t.activity_date = '$date'";
	// 	if ($teacher != '')
	// 		$conditions[] = " t.user_id = '$teacher'";
	// 	$sql .= " WHERE " . implode(" AND ", $conditions);
	// 	// die($sql);
	// 	$teachers = $this->db->query($sql)->result_array();
	// 	foreach ($teachers as $teacher) {
	// 		$activity = $teacher;
	// 		$activity['date'] = $date;
	// 		$teacher_id = $teacher['user_id'];
	// 		$daily_activities = $this->db->query("SELECT * FROM (
	// 			-- TOTAL ATTENDANCE TAKEN BY TEACHER 
	// 			(SELECT count(*) total_attendance FROM teacher_activity WHERE user_id = '$teacher_id' 
	// 			AND activity_date = '$date' AND activity_sorce = 'attendance') AS  attendance,
	// 			-- REMARK COUNT --
	// 			(SELECT 
	// 				SUM(LENGTH(stud_id) - LENGTH(REPLACE(stud_id, ',', '')) + 1) AS total_remark
	// 			FROM 
	// 				teacher_activity
	// 			WHERE 
	// 				user_id = '$teacher_id'
	// 				AND activity_date = '$date'
	// 				AND activity_sorce = 'remark') AS remark,
	// 			-- ABSENT SMS --
	// 			(SELECT 
	// 				SUM(LENGTH(stud_id) - LENGTH(REPLACE(stud_id, ',', '')) + 1) AS total_absent_sms
	// 			FROM 
	// 				teacher_activity
	// 			WHERE 
	// 				user_id = '$teacher_id'
	// 				AND activity_date = '$date'
	// 				AND activity_sorce = 'absent-sms') AS absents,
	// 			-- QUIZ -- 
	// 			(SELECT count(*) total_quiz FROM teacher_activity WHERE user_id = '$teacher_id' AND activity_date = '$date' AND activity_sorce = 'quiz') AS quiz,
	// 			-- MARKS --
	// 			(SELECT count(*) total_marks FROM teacher_activity WHERE user_id = '$teacher_id' AND activity_date = '$date' AND activity_sorce = 'mark') AS marks
	// 			)")->row_array();

	// 		$activities[] = array_merge($activity, $daily_activities);
	// 	}
	// 	return $activities;
	// }

	// function fetchAttendanceActivityStats($teacher_id, $activity_date)
	// {
	// 	return $this->db->query("SELECT 
	// 		ta.user_id,
	// 		CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS teacher_name,
	// 		ta.action_date,s.SPRATND_std,s.SPRATND_div,
	// 		SUM(CASE WHEN s.SPRATND_status = 'P' THEN 1 ELSE 0 END) AS total_present,
	// 		SUM(CASE WHEN s.SPRATND_status = 'A' THEN 1 ELSE 0 END) AS total_absent
	// 	FROM teacher_activity ta
	// 	LEFT JOIN spratnd s ON ta.user_id = s.SPRATND_teacher_id AND ta.action_date = s.SPRATND_date
	// 	LEFT JOIN spriden sp ON ta.user_id = sp.SPRIDEN_student_id
	// 	WHERE ta.activity_date = '$activity_date' AND ta.activity_sorce = 'attendance' AND ta.user_id = '$teacher_id'
	// 	GROUP BY 
	// 		ta.user_id,ta.action_date,s.SPRATND_std,s.SPRATND_div,teacher_name")->result_array();
	// }
	// function fetchAttendanceDetails($teacher_id, $date, $std, $div, $status)
	// {
	// 	$sql = "SELECT at.*, CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS student_name
	// 	FROM spratnd at
	// 	LEFT JOIN spriden sp ON sp.id = at.SPRATND_student_id WHERE SPRATND_teacher_id = '$teacher_id' AND SPRATND_date = '$date' AND SPRATND_std = '$std' AND SPRATND_div = '$div'";
	// 	if ($status != 'all')
	// 		$sql .= " AND SPRATND_status = '$status'";
	// 	$sql .= " ORDER BY 1 DESC";
	// 	return $this->db->query($sql)->result_array();
	// }
	// function fetchRemarkActivityDetails($teacher_id, $activity_date)
	// {
	// 	$students_id = $this->db->query("select stud_id from teacher_activity WHERE user_id = '$teacher_id' AND activity_date = '$activity_date' AND activity_sorce = 'remark'")->row_array();
	// 	$students = $students_id['stud_id'];
	// 	$sql = "SELECT rm.*, CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS student_name, sp.SPRIDEN_student_id
	// 	FROM stvrmrk rm
	// 	LEFT JOIN spriden sp ON sp.id = rm.stvrmrk_student_id
	// 	WHERE stvrmrk_date = '$activity_date' AND stvrmrk_teacher_id = '$teacher_id' ";
	// 	if ($students != '')
	// 		$sql .= " AND stvrmrk_student_id IN ($students)";
	// 	return $this->db->query($sql)->result_array();
	// }
	// function fetchAbsentSMSActivityDetails($teacher_id, $activity_date)
	// {
	// 	$students_id = $this->db->query("select stud_id from teacher_activity WHERE user_id = '$teacher_id' AND activity_date = '$activity_date' AND activity_sorce = 'absent-sms'")->row_array();
	// 	$students = $students_id['stud_id'];
	// 	$sql = "SELECT sp.id,CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS student_name, sp.SPRIDEN_student_id,c.STVCLAS_standard, c.STVCLAS_class
	// 	FROM spriden sp	LEFT JOIN stvclas c ON sp.id = c.STVCLAS_student_id AND c.STVCLAS_acdm = (select stvschool_acdm_year from stvschool)";
	// 	if ($students != '')
	// 		$sql .= " WHERE sp.id IN ($students)";
	// 	return $this->db->query($sql)->result_array();
	// }
	// function fetchQuizActivityDetails($teacher_id, $activity_date)
	// {
	// 	return $this->db->query("SELECT t.user_id, t.activity_date, t.class, t.attribute,
	// 	q.quiz_name, s.stvsubj_subject, concat(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) as teacher_name
	// 	FROM teacher_activity t 
	// 	LEFT JOIN quiz q ON q.subject = t.attribute AND q.created_by = t.user_id AND q.created_at = t.activity_date AND q.class = t.class
	// 	LEFT JOIN stvsubj s ON s.stvsubj_id = t.attribute
	// 	LEFT JOIN spriden sp ON sp.SPRIDEN_student_id = t.user_id
	// 	WHERE activity_sorce = 'quiz' AND activity_date = '$activity_date' AND user_id = '$teacher_id'")->result_array();
	// }
	// function fetchMarkActivityDetails($teacher_id, $activity_date)
	// {
	// 	$activity = $this->db->query("SELECT * FROM teacher_activity WHERE activity_date = '$activity_date' AND activity_sorce = 'mark' AND user_id = '$teacher_id'")->row_array();
	// 	$class = '';
	// 	$div = '';
	// 	$subject = '';
	// 	if (isset($activity['class'])) {
	// 		$class_array = explode("-", $activity['class']);
	// 		if (isset($class_array[0]))
	// 			$class = $class_array[0];
	// 		if (isset($class_array[1]))
	// 			$div = $class_array[1];
	// 	}
	// 	if (isset($activity['attribute'])) {
	// 		$attr_array = explode("-", $activity['attribute']);
	// 		if (isset($attr_array[1]))
	// 			$subject = $attr_array[1];
	// 	}
	// 	return $this->db->query("SELECT m.sprmark_teacher_id, m.sprmark_class, m.sprmark_div,m.sprmark_total_marks,m.sprmark_mark,
	// 	e.stvexam_name, concat(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) as student_name,
	// 	sb.stvsubj_subject,m.sprmark_subject
	// 	FROM sprmark m
	// 	LEFT JOIN stvexam e ON e.stvexam_id = m.sprmark_exam_name
	// 	LEFT JOIN spriden sp ON sp.id = m.sprmark_student_id
	// 	LEFT JOIN stvsubj sb ON sb.stvsubj_id = m.sprmark_subject
	// 	WHERE m.sprmark_teacher_id = '$teacher_id' AND m.sprmark_class = '$class' AND m.sprmark_div = '$div' AND m.sprmark_subject = $subject AND m.sprmark_date = '$activity_date'
	// 	ORDER BY 1 DESC")->result_array();
	// }
	// function fetchActiveTeacherList()
	// {
	// 	return $this->db->query("SELECT sp.SPRIDEN_student_id,sp.SPRIDEN_gender, concat(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) as teacher_name 
	// 	FROM spriden sp
	// 	WHERE sp.SPRIDEN_user_type = 'tc' AND sp.spriden_student_status = 'A'")->result_array();
	// }

	// Updated 2024 July 24
	function fetchTeacherActivities($date, $to_date, $teacher = null)
	{
		$acdm = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
		$acdm = $acdm['stvschool_acdm_year'];

		// Get all active teachers first
		$all_teachers = $this->fetchActiveTeacherList();
		$activities = [];

		foreach ($all_teachers as $teacher_data) {
			$teacher_id = $teacher_data['SPRIDEN_student_id'];

			// Only process if it's "all teachers" or the specific teacher requested
			if ($teacher === null || $teacher === '' || $teacher === $teacher_id) {
				$activity = [
					'user_id' => $teacher_id,
					'SPRIDEN_gender' => $teacher_data['SPRIDEN_gender'],
					'teacher_name' => $teacher_data['teacher_name'],
					'date' => $date,
					'toDateValue' => $to_date,
					'total_attendance' => 0,
					'total_remark' => 0,
					'total_absent_sms' => 0,
					'total_quiz' => 0,
					'total_marks' => 0
				];

				// Get class info if exists
				$class_info = $this->db->query("
					SELECT CONCAT(SPATESD_std, ' - ', SPATESD_div) as class 
					FROM spatesd 
					WHERE SPATESD_teacher_id = '$teacher_id' 
					AND SPATESD_academic_year = $acdm
					LIMIT 1
				")->row_array();

				$activity['class'] = $class_info['class'] ?? '';

				// Get activity counts
				$date_condition = ($to_date == '' || $to_date == null) ?
					"action_date = '$date'" :
					"action_date BETWEEN '$date' AND '$to_date'";

				$counts = $this->db->query("
					SELECT 
						(SELECT COUNT(*) FROM teacher_activity 
						 WHERE user_id = '$teacher_id' AND $date_condition 
						 AND activity_sorce = 'attendance') AS total_attendance,
						
						(SELECT IFNULL(SUM(LENGTH(stud_id) - LENGTH(REPLACE(stud_id, ',', '')) + 1), 0)
						 FROM teacher_activity 
						 WHERE user_id = '$teacher_id' AND $date_condition 
						 AND activity_sorce = 'remark') AS total_remark,
						
						(SELECT IFNULL(SUM(LENGTH(stud_id) - LENGTH(REPLACE(stud_id, ',', '')) + 1), 0)
						 FROM teacher_activity 
						 WHERE user_id = '$teacher_id' AND $date_condition 
						 AND activity_sorce = 'absent-sms') AS total_absent_sms,
						
						(SELECT COUNT(*) FROM teacher_activity 
						 WHERE user_id = '$teacher_id' AND $date_condition 
						 AND activity_sorce = 'quiz') AS total_quiz,
						
						(SELECT COUNT(*) FROM teacher_activity 
						 WHERE user_id = '$teacher_id' AND $date_condition 
						 AND activity_sorce = 'mark') AS total_marks
				")->row_array();

				// Merge counts with activity data
				$activities[] = array_merge($activity, $counts);
			}
		}

		return $activities;
	}

	function fetchAttendanceActivityStats($teacher_id, $activity_date, $to_date)
	{
		$sql = "SELECT 
			ta.user_id,
			CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS teacher_name,
			ta.action_date,s.SPRATND_std,s.SPRATND_div,
			SUM(CASE WHEN s.SPRATND_status = 'P' THEN 1 ELSE 0 END) AS total_present,
			SUM(CASE WHEN s.SPRATND_status = 'A' THEN 1 ELSE 0 END) AS total_absent
		FROM teacher_activity ta
		LEFT JOIN spratnd s ON ta.user_id = s.SPRATND_teacher_id AND ta.action_date = s.SPRATND_date
		LEFT JOIN spriden sp ON ta.user_id = sp.SPRIDEN_student_id
		WHERE ";
		//Here
		if ($to_date == '' || $to_date == null)
			$sql .= "ta.action_date = '$activity_date'";
		else
			$sql .= "ta.action_date between '$activity_date' and '$to_date'";
		$sql .= "AND ta.activity_sorce = 'attendance' AND ta.user_id = '$teacher_id'
		GROUP BY 
			ta.user_id,ta.action_date,s.SPRATND_std,s.SPRATND_div,teacher_name";
		// die($sql);
		return $this->db->query($sql)->result_array();
	}
	function fetchAttendanceDetails($teacher_id, $date, $std, $div, $status)
	{
		$sql = "SELECT at.*, CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS student_name
		FROM spratnd at
		LEFT JOIN spriden sp ON sp.id = at.SPRATND_student_id WHERE SPRATND_teacher_id = '$teacher_id' AND SPRATND_date = '$date' AND SPRATND_std = '$std' AND SPRATND_div = '$div'";
		if ($status != 'all')
			$sql .= " AND SPRATND_status = '$status'";
		$sql .= " ORDER BY 1 DESC";
		return $this->db->query($sql)->result_array();
	}
	function fetchRemarkActivityDetails($teacher_id, $activity_date, $to_date)
	{
		$students_id_query = "SELECT stud_id FROM teacher_activity WHERE user_id = '$teacher_id' AND ";
		if ($to_date == '' || $to_date == null) {
			$students_id_query .= "action_date = '$activity_date' AND activity_sorce = 'remark'";
		} else {
			$students_id_query .= "action_date BETWEEN '$activity_date' AND '$to_date' AND activity_sorce = 'remark'";
		}

		$result = $this->db->query($students_id_query)->result_array();

		// Assuming only one row is returned, access the 'stud_id' from the first row of the result
		if (!empty($result)) {
			$students = $result[0]['stud_id'];
		} else {
			$students = null; // or handle the case where no rows are returned
		}

		$sql = "SELECT rm.*, CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS student_name, sp.SPRIDEN_student_id
		FROM stvrmrk rm
		LEFT JOIN spriden sp ON sp.id = rm.stvrmrk_student_id
		WHERE ";
		if ($to_date == '' || $to_date == null)
			$sql .= "stvrmrk_date = '$activity_date' AND stvrmrk_teacher_id = '$teacher_id' ";
		else
			$sql .= "stvrmrk_date between '$activity_date' and '$to_date' AND stvrmrk_teacher_id = '$teacher_id'";
		if ($students != '')
			$sql .= " AND stvrmrk_student_id IN ($students)";
		return $this->db->query($sql)->result_array();
	}
	function fetchAbsentSMSActivityDetails($teacher_id, $activity_date, $to_date)
	{

		$students_id = "SELECT stud_id FROM teacher_activity WHERE user_id = '$teacher_id' AND ";

		if ($to_date == '' || $to_date == null) {
			$students_id .= "action_date = '$activity_date' AND activity_sorce = 'absent-sms'";
		} else {
			$students_id .= "action_date BETWEEN '$activity_date' AND '$to_date' AND activity_sorce = 'absent-sms'";
		}

		$result = $this->db->query($students_id)->row_array();

		// Assuming only one row is returned, access the 'stud_id' from the first row of the result
		if (!empty($result)) {
			$students = $result['stud_id'];
		} else {
			$students = null; // or handle the case where no rows are returned
		}

		// $students = $students_id['stud_id'];

		$sql = "SELECT sp.id,CONCAT(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) AS student_name, sp.SPRIDEN_student_id,c.STVCLAS_standard, c.STVCLAS_class
		FROM spriden sp	LEFT JOIN stvclas c ON sp.id = c.STVCLAS_student_id AND c.STVCLAS_acdm = (select stvschool_acdm_year from stvschool)";
		if ($students != '')
			$sql .= " WHERE sp.id IN ($students)";
		// print_r($sql);
		// die;
		return $this->db->query($sql)->result_array();
	}
	function fetchQuizActivityDetails($teacher_id, $activity_date, $to_date)
	{
		$sql = "SELECT distinct t.user_id, t.action_date, t.class, t.attribute,
		q.quiz_name, s.stvsubj_subject, concat(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) as teacher_name
		FROM teacher_activity t 
		LEFT JOIN quiz q ON q.subject = t.attribute AND q.created_by = t.user_id AND q.created_at = t.action_date AND q.class = t.class
		LEFT JOIN stvsubj s ON s.stvsubj_id = t.attribute
		LEFT JOIN spriden sp ON sp.SPRIDEN_student_id = t.user_id
		WHERE activity_sorce = 'quiz' ";
		if ($to_date == '' || $to_date == null)
			$sql .= "AND t.action_date = '$activity_date' ";
		else
			$sql .= "AND t.action_date between '$activity_date' and '$to_date' ";
		$sql .= "AND user_id = '$teacher_id'";
		return $this->db->query($sql)->result_array();
	}
	function fetchMarkActivityDetails($teacher_id, $activity_date, $to_date)
	{
		$activity = "SELECT * FROM teacher_activity WHERE ";
		if ($to_date == '' || $to_date == null)
			$activity .= "action_date = '$activity_date' ";
		else
			$activity .= "action_date between '$activity_date' and '$to_date' ";
		$activity .= "AND activity_sorce = 'mark' AND user_id = '$teacher_id'";



		$activity = $this->db->query($activity)->row_array();
		$class = '';
		$div = '';
		$subject = '';
		if (isset($activity['class'])) {
			$class_array = explode("-", $activity['class']);
			if (isset($class_array[0]))
				$class = $class_array[0];
			if (isset($class_array[1]))
				$div = $class_array[1];
		}
		if (isset($activity['attribute'])) {
			$attr_array = explode("-", $activity['attribute']);
			if (isset($attr_array[1]))
				$subject = $attr_array[1];
		}
		$sql = "SELECT m.sprmark_teacher_id, m.sprmark_class, m.sprmark_div,m.sprmark_total_marks,m.sprmark_mark,
		e.stvexam_name, concat(sp.SPRIDEN_student_name, ' ',sp.SPRIDEN_surname) as student_name,
		sb.stvsubj_subject,m.sprmark_subject
		FROM sprmark m
		LEFT JOIN stvexam e ON e.stvexam_id = m.sprmark_exam_name
		LEFT JOIN spriden sp ON sp.id = m.sprmark_student_id
		LEFT JOIN stvsubj sb ON sb.stvsubj_id = m.sprmark_subject
		WHERE m.sprmark_teacher_id = '$teacher_id'  AND ";
		if ($to_date == '' || $to_date == null)
			$sql .= "m.sprmark_date = '$activity_date' ORDER BY 1 DESC";
		else
			$sql .= "m.sprmark_date between '$activity_date' and '$to_date' ORDER BY 1 DESC";


		return $this->db->query($sql)->result_array();
	}
	function fetchActiveTeacherList()
	{
		return $this->db->query("SELECT sp.SPRIDEN_student_id,
       sp.SPRIDEN_gender,
       CONCAT(
           UPPER(LEFT(sp.SPRIDEN_student_name, 1)), 
           LOWER(SUBSTRING(sp.SPRIDEN_student_name, 2)), 
           ' ', 
           UPPER(LEFT(sp.SPRIDEN_surname, 1)), 
           LOWER(SUBSTRING(sp.SPRIDEN_surname, 2))
       ) AS teacher_name
			FROM spriden sp
			WHERE sp.SPRIDEN_user_type = 'tc' 
			AND sp.spriden_student_status = 'A' AND sp.SPRIDEN_student_id is not null
			")->result_array();
	}

	// -------------------------------------------------------------------------------

	public function get_lc_report($year = '', $std = '')
	{
		$sql = "SELECT
                acdm.stvacdm_year,
                acdm.stvacdm_id,
                sp.id,
                sp.spriden_student_status,
                sp.SPRIDEN_student_id,
                CONCAT(
                    sp.SPRIDEN_surname,' ',
                    sp.SPRIDEN_student_name,' ',
                    sp.SPRIDEN_father_name
                ) AS name,
                cls.STVCLAS_standard,
                cls.STVCLAS_class,
                cls.STVCLAS_acdm,
                pc.stvpcnf_mobile,
                pc.stvpcnf_whatsapp
            FROM spriden sp

            LEFT JOIN stvclas cls
                ON cls.STVCLAS_student_id = sp.id

            LEFT JOIN stvacdm acdm
                ON acdm.stvacdm_id = cls.STVCLAS_acdm

            LEFT JOIN stvpcnf pc
                ON pc.stvpcnf_student_id = sp.id

            WHERE 1=1 ";

		if ($year != '') {
			$sql .= " AND cls.STVCLAS_acdm = '" . $this->db->escape_str($year) . "'";
		}

		if ($std != '') {
			$sql .= " AND cls.STVCLAS_standard = '" . $this->db->escape_str($std) . "'";
		}

		$sql .= " ORDER BY sp.id";

		return $this->db->query($sql)->result_array();
	}

	// public function get_lc_report()
	// {
	// 	$sql = "SELECT 
	// 				lc.id AS lc_id,
	// 				lc.lc_no,
	// 				lc.lc_status,
	// 				lc.date_issued_on,
	// 				lc.lc_no_jc,
	// 				acdm.stvacdm_year,
	// 				acdm.stvacdm_id,
	// 				sp.id,
	// 				sp.spriden_student_status,
	// 				sp.SPRIDEN_student_id,
	// 				CONCAT(sp.SPRIDEN_surname, ' ', sp.SPRIDEN_student_name, ' ', sp.SPRIDEN_father_name) AS name,
	// 				cls.STVCLAS_standard,
	// 				cls.STVCLAS_class,
	// 				cls.STVCLAS_acdm,
	//                 pc.stvpcnf_mobile,
	//                 pc.stvpcnf_whatsapp,
	//                 re.status AS lc_request_status,
	//                 re.token
	// 			FROM lc_status lc
	// 			LEFT JOIN stvacdm acdm 
	// 			    ON CONVERT(acdm.stvacdm_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			     = CONVERT(lc.academic_year USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			LEFT JOIN spriden sp 
	// 			    ON CONVERT(sp.id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			     = CONVERT(lc.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			LEFT JOIN stvpcnf pc 
	// 			    ON CONVERT(pc.stvpcnf_student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			     = CONVERT(lc.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			LEFT JOIN lc_requests re 
	// 			    ON CONVERT(re.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			     = CONVERT(lc.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			LEFT JOIN stvclas cls 
	// 			    ON CONVERT(cls.STVCLAS_acdm USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			     = CONVERT(lc.academic_year USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			   AND CONVERT(cls.STVCLAS_student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			     = CONVERT(lc.student_id USING utf8mb4) COLLATE utf8mb4_unicode_ci
	// 			     ORDER BY lc_no";

	// 	$query = $this->db->query($sql);
	// 	return $query->result_array();
	// }


	function student_get_lc_jc($id, $acdm)
	{
		$sql = "SELECT DISTINCT 
    spriden.id AS id,
    (SELECT stvclas_standard FROM stvclas 
     WHERE stvclas_student_id = '$id' 
     AND STVCLAS_acdm = (SELECT MAX(STVCLAS_acdm) FROM stvclas WHERE stvclas_student_id = '$id')
     LIMIT 1) AS current_std,
    spriden.rte_status,
    spriden.SPRIDEN_student_id,
    spriden.spriden_aadhar_no,
    CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name,
    spriden.SPRIDEN_mother_name,
    spriden.SPRIDEN_mother_toungue,
    spriden.spriden_religion,
    spriden.cast,
    spriden.sub_cast,
    spriden.spriden_birth_place,
    spriden.taluka,
    spriden.district,
    spriden.state,
    spriden.spriden_birthdate,
    spriden.spriden_last_school,
    spriden.spriden_date_created,
    spriden.spriden_gr_number,
    spriden.spriden_college_gr,
    stvclas.stvclas_standard,
    spriden.spriden_admission_class,
    spriden.SPRIDEN_reg_number,
    spriden.birthdate_words,
    spriden.progress,
    spriden.conduct,
    spriden.leaving_reason,
    spriden.remarks,
    spriden.leaving_date,
    spriden.since_when,
    spriden.lc_date,
    -- Yahan MAX() lagane se error nahi aata
    (SELECT MAX(IFNULL(lc_no_jc, 0)) FROM lc_status WHERE student_id = spriden.id) AS lc_num,
    -- Yahan LIMIT 1 lagana zaroori tha
    (SELECT date_issued_on FROM lc_status WHERE student_id = spriden.id ORDER BY id DESC LIMIT 1) AS date_issued_on,
    (SELECT lc_status FROM lc_status WHERE student_id = spriden.id ORDER BY id DESC LIMIT 1) AS lc_status,
    stvclas.STVCLAS_standard,
    stvclas.STVCLAS_class
FROM 
    spriden, 
    stvclas 
WHERE 
    spriden.SPRIDEN_user_type = 'st' 
    AND stvclas.STVCLAS_student_id = spriden.id 
    AND spriden.id = '$id' 
    AND stvclas.STVCLAS_acdm = $acdm";
		return $this->db->query($sql)->row_array();
	}


	function student_get_lc($id, $acdm)
	{
		$sql = "SELECT spriden.id as id,
    (SELECT stvclas_standard FROM stvclas WHERE stvclas_student_id='$id' AND STVCLAS_acdm=(SELECT MAX(STVCLAS_acdm) FROM stvclas WHERE stvclas_student_id='$id') LIMIT 1) as current_std,
    spriden.rte_status, spriden.SPRIDEN_student_id, spriden.spriden_aadhar_no, CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) as name, spriden.SPRIDEN_mother_name, spriden.SPRIDEN_mother_toungue, spriden.spriden_religion, spriden.cast, spriden.sub_cast, spriden.spriden_birth_place, spriden.taluka, spriden.district, spriden.state, spriden.spriden_birthdate, spriden.spriden_last_school, spriden.spriden_date_created, spriden.spriden_gr_number, stvclas.stvclas_standard, spriden.spriden_admission_class, spriden.SPRIDEN_reg_number, spriden.SPRIDEN_aadhar_no, spriden.SPRIDEN_date_created, spriden.SPRIDEN_birth_place, spriden.taluka, spriden.district, spriden.birthdate_words, spriden.progress, spriden.conduct, spriden.leaving_reason, spriden.remarks, spriden.leaving_date, spriden.since_when, spriden.lc_date,
    
    /* Yahan MAX lagaya hai taaki 1 hi row aaye */
    (SELECT MAX(ifnull(lc_no,0)) FROM lc_status WHERE student_id = spriden.id) as lc_num,
    (SELECT MAX(date_issued_on) FROM lc_status WHERE student_id = spriden.id) as date_issued_on,
    
    /* Yahan LIMIT 1 lagaya hai taaki agar school table mein multi-rows ho toh error na aaye */
    (SELECT SUM(sprfees.sprfees_amount) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool LIMIT 1)) as total_fees, 
    (SELECT MAX(sprfees.sprfees_feesdate) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool LIMIT 1)) as last_paid, 
    (SELECT MAX(sprfees.sprfees_due_date) FROM sprfees WHERE sprfees.sprfees_studentid = spriden.id AND sprfees.academic_year = (SELECT stvschool_acdm_year FROM stvschool LIMIT 1)) as due_date, 
    
    /* Yahan bhi LIMIT 1 safe side ke liye */
    (SELECT stvpcnf.stvpcnf_mobile FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id LIMIT 1) as mobile, 
    (SELECT stvpcnf.stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf.stvpcnf_student_id = spriden.id LIMIT 1) as whatsapp,

    stvclas.STVCLAS_standard, stvclas.STVCLAS_class,
    (SELECT MAX(note) FROM sprremark WHERE sprremark.id = spriden.id) as note, 
    (SELECT MAX(due_date) FROM sprremark WHERE sprremark.id = spriden.id) as next_date, 
    (SELECT COUNT(spratnd.SPRATND_status) FROM spratnd WHERE spratnd.SPRATND_status = 'P' AND spratnd.SPRATND_student_id = spriden.id) as attendence_p 

FROM spriden, stvclas 
WHERE spriden.SPRIDEN_user_type = 'st' 
AND stvclas.STVCLAS_student_id = spriden.id  
AND spriden.id = '$id' 
AND stvclas.STVCLAS_acdm = $acdm";
		return $this->db->query($sql)->row_array();
	}

	public function get_monthly_fees($from_date, $to_date)
	{
		$sql = " SELECT 
    			DATE_FORMAT(sprfees_feesdate, '%d-%b-%Y') AS fees_date,   -- e.g. 01-Sep-2025
    			SUM(CASE WHEN payment_mode = 'cash' THEN sprfees_amount ELSE 0 END) AS cash_collection,
    			SUM(CASE WHEN payment_mode = 'online' THEN sprfees_amount ELSE 0 END) AS online_collection,
    			SUM(sprfees_amount) AS total_collection
			FROM sprfees
			WHERE sprfees_feesdate BETWEEN '$from_date' AND '$to_date'
			GROUP BY sprfees_feesdate
			ORDER BY sprfees_feesdate;";
		return $this->db->query($sql)->result_array();
	}

	function examAlertNotification($year, $class, $div)
	{
		$sql = "
    SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
        s.rte_status,
        c.STVCLAS_rollno,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        p.stvpcnf_whatsapp,

        IFNULL(SUM(f.sprfees_amount), 0) AS total_paid,

        IFNULL(SUM(
            CASE 
                WHEN f.sprfees_exumption > 0 THEN f.sprfees_exumption 
                ELSE 0 
            END
        ), 0) AS total_exemption,

        IFNULL(fstr.stvfstr_fees, 0) AS total_fees,

        (
            IFNULL(fstr.stvfstr_fees, 0) 
            - IFNULL(SUM(f.sprfees_amount), 0) 
            - IFNULL(SUM(
                CASE 
                    WHEN f.sprfees_exumption > 0 THEN f.sprfees_exumption 
                    ELSE 0 
                END
            ), 0)
        ) AS balance

    FROM stvclas c
    JOIN spriden s ON s.id = c.STVCLAS_student_id

    LEFT JOIN sprfees f 
        ON f.sprfees_studentid = c.STVCLAS_student_id
        AND f.academic_year = c.STVCLAS_acdm

    LEFT JOIN stvfstr fstr 
        ON fstr.stvfstr_acdm_year = c.STVCLAS_acdm
        AND fstr.stvfstr_std = c.STVCLAS_standard

    LEFT JOIN stvpcnf p 
        ON s.id = p.stvpcnf_student_id

    WHERE
        c.STVCLAS_standard = '$class'
        AND c.STVCLAS_class = '$div'
        AND c.STVCLAS_acdm = '$year'
        AND s.spriden_student_status = 'A'

    GROUP BY s.id
    ";

		return $this->db->query($sql)->result_array();
	}

	public function reques_for_lc($stud_id, $stud_class, $status, $acdm_year, $requested_by)
	{

		// Pehle check karenge ki kya is student ki is academic year mein request pehle se hai?
		$this->db->where('student_id', $stud_id);
		$this->db->where('academic_year', $acdm_year);
		$query = $this->db->get('lc_requests');

		$current_date = date('Y-m-d H:i:s');

		if ($query->num_rows() > 0) {
			// Agar record pehle se exist karta hai, toh usko UPDATE karenge
			$data = array(
				'class' => $stud_class,
				'status' => $status,
				'requested_by' => $requested_by,
				'requested_at' => $current_date
			);

			$this->db->where('student_id', $stud_id);
			$this->db->where('academic_year', $acdm_year);
			return $this->db->update('lc_requests', $data);
		} else {
			// Agar record nahi hai, toh naya INSERT karenge
			$token = hash("sha256", time()); // Unique token generate kar rahe hain

			$data = array(
				'student_id' => $stud_id,
				'class' => $stud_class,
				'academic_year' => $acdm_year,
				'token' => $token,
				'status' => $status,
				'requested_by' => $requested_by,
				'requested_at' => $current_date
			);

			return $this->db->insert('lc_requests', $data);
		}
	}

	public function manage_lc_request($stud_id, $stud_class, $status, $acdm_year, $requested_by)
	{
		// Pehle check karenge ki record exist karta hai ya nahi
		$this->db->where('student_id', $stud_id);
		$this->db->where('academic_year', $acdm_year);
		$query = $this->db->get('lc_requests');

		$current_date = date('Y-m-d H:i:s');
		$simple_date = date('Y-m-d');

		if ($query->num_rows() > 0) {
			// --- RECORD UPDATE ---
			$data = array(
				'class' => $stud_class,
				'status' => $status
			);

			// Agar status 'requested' ya 'rejected' hai, toh requested details update karo
			if ($status == 'requested' || $status == 'rejected') {
				$data['requested_by'] = $requested_by;
				$data['requested_at'] = $current_date;
			}
			// Agar status 'approved' hai, toh approved details update karo
			elseif ($status == 'approved') {
				$data['approved_by'] = $requested_by;
				$data['approved_at'] = $simple_date;
			}

			$this->db->where('student_id', $stud_id);
			$this->db->where('academic_year', $acdm_year);
			return $this->db->update('lc_requests', $data);
		} else {
			// --- NEW RECORD INSERT ---
			$data = array(
				'student_id' => $stud_id,
				'class' => $stud_class,
				'academic_year' => $acdm_year,
				'token' => hash("sha256", time()),
				'status' => $status,
				'requested_by' => $requested_by,
				'requested_at' => $current_date
			);

			// Agar direct 'approved' status aaye toh insert mein bhi ye add karo
			if ($status == 'approved') {
				$data['approved_by'] = $requested_by;
				$data['approved_at'] = $simple_date;
			}

			return $this->db->insert('lc_requests', $data);
		}
	}

	// function attendanceData($acdm_year, $class, $div, $month, $year)
	// {
	// 	$monthCondition = "";
	// 	$yearCondition = "";

	// 	if (!empty($month)) {
	// 		$monthCondition = "AND MONTH(sp.SPRATND_date) = '$month'";
	// 	}



	// 	$sql = "SELECT 
	//             s.id,
	//             CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,

	//             COALESCE(SUM(CASE WHEN sp.SPRATND_status = 'P' THEN 1 ELSE 0 END),0) AS total_present,

	//             COALESCE(SUM(CASE WHEN sp.SPRATND_status = 'A' THEN 1 ELSE 0 END),0) AS total_absent,

	//             COUNT(CASE WHEN sp.SPRATND_status IN ('P','A') THEN 1 END) AS total_days

	//         FROM spriden s

	//         LEFT JOIN stvclas c 
	//             ON c.STVCLAS_student_id = s.id

	//         LEFT JOIN spratnd sp 
	//             ON sp.SPRATND_student_id = s.id
	//             AND sp.SPRATND_acdm_year = '$acdm_year'
	//             $monthCondition


	//         WHERE
	//             c.STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool)
	//             AND s.SPRIDEN_user_type = 'st'
	//             AND s.SPRIDEN_student_status = 'A'
	//             AND c.STVCLAS_standard = '$class'
	//             AND c.STVCLAS_class = '$div'

	//         GROUP BY s.id
	//         ORDER BY name ASC";

	// 	return $this->db->query($sql)->result_array();
	// }

	function attendanceData($acdm_year, $class, $div)
	{
		$sql = "SELECT 
        s.id,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,

        /* JUN */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=6 THEN 1 END) AS jun_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=6 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS jun_present,

        /* JUL */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=7 THEN 1 END) AS jul_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=7 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS jul_present,

        /* AUG */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=8 THEN 1 END) AS aug_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=8 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS aug_present,

        /* SEP */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=9 THEN 1 END) AS sep_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=9 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS sep_present,

        /* OCT */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=10 THEN 1 END) AS oct_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=10 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS oct_present,

        /* NOV */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=11 THEN 1 END) AS nov_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=11 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS nov_present,

        /* DEC */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=12 THEN 1 END) AS dec_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=12 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS dec_present,

        /* JAN */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=1 THEN 1 END) AS jan_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=1 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS jan_present,

        /* FEB */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=2 THEN 1 END) AS feb_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=2 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS feb_present,

        /* MAR */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=3 THEN 1 END) AS mar_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=3 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS mar_present,

        /* APR */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=4 THEN 1 END) AS apr_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=4 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS apr_present,

        /* MAY */
        COUNT(CASE WHEN MONTH(sp.SPRATND_date)=5 THEN 1 END) AS may_working,
        SUM(CASE WHEN MONTH(sp.SPRATND_date)=5 AND sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS may_present,

        /* TOTAL */
        COUNT(sp.SPRATND_status) AS total_working,
        SUM(CASE WHEN sp.SPRATND_status='P' THEN 1 ELSE 0 END) AS total_present

    FROM spriden s

    LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id

    LEFT JOIN spratnd sp 
        ON sp.SPRATND_student_id = s.id
        AND sp.SPRATND_acdm_year = '$acdm_year'

    WHERE
        c.STVCLAS_acdm IN (SELECT stvschool_acdm_year FROM stvschool)
        AND s.SPRIDEN_user_type = 'st'
        AND s.SPRIDEN_student_status = 'A'
        AND c.STVCLAS_standard = '$class'
        AND c.STVCLAS_class = '$div'

    GROUP BY s.id
    ORDER BY name ASC";

		return $this->db->query($sql)->result_array();
	}


	public function get_current_year_fees()
	{

		$sql = "SELECT
			    s.id,
			    s.SPRIDEN_student_id,
			    CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
			    u.spruser_name,
			    f.payment_mode,
			    f.sprfees_amount,
			    f.sprfees_feesdate,
			    ac.stvacdm_year,
			    f.academic_year
			FROM sprfees f
			INNER JOIN spriden s 
			    ON s.id = f.sprfees_studentid
			INNER JOIN spruser u 
			    ON u.spruser_id = f.sprfees_recivedby
			LEFT JOIN stvacdm ac 
			    ON ac.stvacdm_id = f.academic_year
			WHERE f.academic_year = (SELECT stvschool_acdm_year FROM stvschool LIMIT 1)
			ORDER BY f.sprfees_pk DESC;
			    ";

		return $this->db->query($sql)->result_array();
	}

	public function convert_online($from, $to, $year = '')
	{
		$running_year = (int) $this->db
			->query("SELECT stvschool_acdm_year FROM stvschool")
			->row()
			->stvschool_acdm_year;

		$this->db->set('convert_value', 'online');
		$this->db->where('payment_mode', 'cash');
		$this->db->where('DATE(sprfees_feesdate) >=', $from);
		$this->db->where('DATE(sprfees_feesdate) <=', $to);

		// Apply current academic year filter
		if ($year == 'current') {
			$this->db->where('academic_year', $running_year);
		}

		$this->db->update('sprfees');

		return [
			'status' => true,
			'message' => 'Data converted successfully'
		];
	}

	public function get_fees_balance_report($type)
	{
		if ($type == "all") {
			$sql = "WITH paid_fees AS
    (
        SELECT
            sprfees.sprfees_studentid,
            sprfees.academic_year,

            SUM(
                COALESCE(sprfees.sprfees_amount, 0)
            ) AS total_paid,

            SUM(
                CASE
                    WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
                    THEN sprfees.sprfees_exumption
                    ELSE 0
                END
            ) AS total_exumpted,

            SUM(
                COALESCE(sprfees.sprfees_amount, 0)
            ) +
            SUM(
                CASE
                    WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
                    THEN sprfees.sprfees_exumption
                    ELSE 0
                END
            ) AS total_adjusted_paid

        FROM sprfees

        WHERE sprfees.academic_year BETWEEN 5 AND 8

        GROUP BY
            sprfees.sprfees_studentid,
            sprfees.academic_year
    ),

    student_class AS
    (
        SELECT
            stvclas.STVCLAS_student_id,
            stvclas.STVCLAS_acdm,
            stvclas.STVCLAS_standard,
            MAX(stvclas.STVCLAS_class) AS STVCLAS_class

        FROM stvclas

        WHERE stvclas.STVCLAS_acdm BETWEEN 5 AND 8

        GROUP BY
            stvclas.STVCLAS_student_id,
            stvclas.STVCLAS_acdm,
            stvclas.STVCLAS_standard
    ),

    father_contact AS
    (
        SELECT
            stvpcnf.stvpcnf_student_id,

            COALESCE(
                MAX(
                    NULLIF(
                        TRIM(stvpcnf.stvpcnf_whatsapp),
                        ''
                    )
                ),
                MAX(
                    NULLIF(
                        TRIM(stvpcnf.stvpcnf_mobile),
                        ''
                    )
                ),
                ''
            ) AS mobile_number

        FROM stvpcnf

        WHERE LOWER(
            TRIM(stvpcnf.stvpcnf_parent_typ)
        ) = 'father'

        GROUP BY
            stvpcnf.stvpcnf_student_id
    ),

    pending_fee_details AS
    (
        SELECT
            spriden.id,

            spriden.SPRIDEN_student_id,

            CONCAT(
                spriden.SPRIDEN_surname,
                ' ',
                spriden.SPRIDEN_student_name,
                ' ',
                spriden.SPRIDEN_father_name
            ) AS name,

            stvacdm.stvacdm_id,
            stvacdm.stvacdm_year,

            student_class.STVCLAS_standard,
            student_class.STVCLAS_class,

            COALESCE(
                stvfstr.stvfstr_fees,
                0
            ) AS total_fee,

            COALESCE(
                paid_fees.total_paid,
                0
            ) AS total_paid,

            COALESCE(
                paid_fees.total_exumpted,
                0
            ) AS total_exumpted,

            COALESCE(
                paid_fees.total_adjusted_paid,
                0
            ) AS total_adjusted_paid,

            COALESCE(
                stvfstr.stvfstr_fees,
                0
            ) -
            COALESCE(
                paid_fees.total_adjusted_paid,
                0
            ) AS pending_fee

        FROM spriden

        INNER JOIN student_class
            ON student_class.STVCLAS_student_id = spriden.id

        INNER JOIN stvacdm
            ON stvacdm.stvacdm_id =
               student_class.STVCLAS_acdm

        INNER JOIN stvfstr
            ON stvfstr.stvfstr_std =
               student_class.STVCLAS_standard
           AND stvfstr.stvfstr_acdm_year =
               student_class.STVCLAS_acdm

        LEFT JOIN paid_fees
            ON paid_fees.sprfees_studentid = spriden.id
           AND paid_fees.academic_year =
               student_class.STVCLAS_acdm

        WHERE spriden.SPRIDEN_student_status = 'A'

          AND spriden.SPRIDEN_user_type = 'st'

          AND
          (
              LOWER(TRIM(spriden.rte_status)) = 'no'
              OR spriden.rte_status IS NULL
              OR TRIM(spriden.rte_status) = ''
          )

          AND stvacdm.stvacdm_id BETWEEN 5 AND 8

          AND
          (
              UPPER(
                  COALESCE(@P_STUDENT_ID, 'ALL')
              ) = 'ALL'

              OR spriden.SPRIDEN_student_id =
                 @P_STUDENT_ID
          )
    ),

    student_pending AS
    (
        SELECT
            pending_fee_details.id,

            pending_fee_details.SPRIDEN_student_id,

            pending_fee_details.name,

            COALESCE(
                father_contact.mobile_number,
                ''
            ) AS MobileNumber,

            MAX(
                CASE
                    WHEN pending_fee_details.stvacdm_id = 5
                         AND pending_fee_details.pending_fee > 0
                    THEN ROUND(
                        pending_fee_details.pending_fee,
                        0
                    )
                    ELSE 0
                END
            ) AS Pending_5,

            MAX(
                CASE
                    WHEN pending_fee_details.stvacdm_id = 6
                         AND pending_fee_details.pending_fee > 0
                    THEN ROUND(
                        pending_fee_details.pending_fee,
                        0
                    )
                    ELSE 0
                END
            ) AS Pending_6,

            MAX(
                CASE
                    WHEN pending_fee_details.stvacdm_id = 7
                         AND pending_fee_details.pending_fee > 0
                    THEN ROUND(
                        pending_fee_details.pending_fee,
                        0
                    )
                    ELSE 0
                END
            ) AS Pending_7,

            MAX(
                CASE
                    WHEN pending_fee_details.stvacdm_id = 8
                         AND pending_fee_details.pending_fee > 0
                    THEN ROUND(
                        pending_fee_details.pending_fee,
                        0
                    )
                    ELSE 0
                END
            ) AS Pending_8

        FROM pending_fee_details

        LEFT JOIN father_contact
            ON father_contact.stvpcnf_student_id =
               pending_fee_details.id

        GROUP BY
            pending_fee_details.id,
            pending_fee_details.SPRIDEN_student_id,
            pending_fee_details.name,
            father_contact.mobile_number
    )

    SELECT
        SPRIDEN_student_id,
        name,
        MobileNumber,
        Pending_5,
        Pending_6,
        Pending_7,
        Pending_8

    FROM student_pending

    WHERE
        Pending_5 > 0
        OR Pending_6 > 0
        OR Pending_7 > 0
        OR Pending_8 > 0

    ORDER BY
        SPRIDEN_student_id";
		} elseif ($type == "last")
			// last  balance 
			$sql = "WITH paid_fees AS
		(SELECT sprfees.sprfees_studentid,
		        sprfees.academic_year,
				SUM(COALESCE(sprfees.sprfees_amount, 0)) AS total_paid,
				SUM(CASE
		                WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
		                THEN sprfees.sprfees_exumption
		                ELSE 0
		            END
		        ) AS total_exumpted,
		        SUM(COALESCE(sprfees.sprfees_amount, 0))+
		        SUM(CASE
		                WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
		                THEN sprfees.sprfees_exumption
		                ELSE 0
		            END
		        ) AS total_adjusted_paid
		    FROM sprfees
		    WHERE sprfees.academic_year = 8
		    GROUP BY
		        sprfees.sprfees_studentid,
		        sprfees.academic_year
		),
		student_class AS
		(SELECT stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_acdm,
		        stvclas.STVCLAS_standard,
		        MAX(stvclas.STVCLAS_class) AS STVCLAS_class
		    FROM stvclas
		    WHERE stvclas.STVCLAS_acdm = 8
		    GROUP BY
		        stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_acdm,
		        stvclas.STVCLAS_standard),
		pending_fee_details AS
		(SELECT spriden.id,
        	spriden.SPRIDEN_student_id,
            CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name,
        	stvacdm.stvacdm_id,
        	stvacdm.stvacdm_year,
        	student_class.STVCLAS_standard,
        	student_class.STVCLAS_class,
        	COALESCE(stvfstr.stvfstr_fees, 0) AS total_fee,
        	COALESCE(paid_fees.total_paid, 0) AS total_paid,
        	COALESCE(paid_fees.total_exumpted, 0) AS total_exumpted,
        	COALESCE(paid_fees.total_adjusted_paid, 0) AS total_adjusted_paid,
        	COALESCE(stvfstr.stvfstr_fees, 0) -
        	COALESCE(paid_fees.total_adjusted_paid, 0) AS pending_fee
    	FROM spriden
    	INNER JOIN student_class
        	ON student_class.STVCLAS_student_id = spriden.id
    	INNER JOIN stvacdm
        	ON stvacdm.stvacdm_id = student_class.STVCLAS_acdm
    	INNER JOIN stvfstr
        	ON stvfstr.stvfstr_std = student_class.STVCLAS_standard
       	AND stvfstr.stvfstr_acdm_year = student_class.STVCLAS_acdm
    	LEFT JOIN paid_fees
        	ON paid_fees.sprfees_studentid = spriden.id
       	AND paid_fees.academic_year = student_class.STVCLAS_acdm
    	WHERE spriden.SPRIDEN_student_status = 'A'
    		AND spriden.SPRIDEN_user_type = 'st'
    		AND (LOWER(TRIM(spriden.rte_status)) = 'no'
    	    OR spriden.rte_status IS NULL
    	    OR TRIM(spriden.rte_status) = '')
    	AND stvacdm.stvacdm_id = 8
    	AND (UPPER(COALESCE(@P_STUDENT_ID, 'ALL')) = 'ALL'
    	    OR spriden.SPRIDEN_student_id = @P_STUDENT_ID
    	))
		SELECT
		    SPRIDEN_student_id,
		    name,
		    stvacdm_year AS Academic_year,
		    total_fee AS Total_fee,
		    total_paid AS Total_paid,
		    total_exumpted AS Total_exumpted,
		    total_adjusted_paid AS Total_paid_icluding_exumption,
		    pending_fee AS Pending_fees
		FROM pending_fee_details
		WHERE pending_fee > 0
		ORDER BY SPRIDEN_student_id;";
		elseif ($type == "paid")
			// last paid current zero
			$sql = "WITH paid_fees AS
		(SELECT sprfees.sprfees_studentid,
		        sprfees.academic_year,
		        SUM(COALESCE(sprfees.sprfees_amount, 0)) AS total_paid,
		        SUM(CASE
		                WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
		                THEN sprfees.sprfees_exumption
		                ELSE 0
		            END
		        ) AS total_exumpted,
		        SUM(COALESCE(sprfees.sprfees_amount, 0)) +
		        SUM(CASE
		                WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
		                THEN sprfees.sprfees_exumption
		                ELSE 0
		            END
		        ) AS total_adjusted_paid
		    FROM sprfees
		    WHERE sprfees.academic_year IN (8, 9)
		    GROUP BY
		        sprfees.sprfees_studentid,
		        sprfees.academic_year
		),
		student_class_8 AS
		(SELECT stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard,
		        MAX(stvclas.STVCLAS_class) AS STVCLAS_class
		    FROM stvclas
		    WHERE stvclas.STVCLAS_acdm = 8
		    GROUP BY
		        stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard
		),
		student_class_9 AS
		(SELECT stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard,
		        MAX(stvclas.STVCLAS_class) AS STVCLAS_class
		    FROM stvclas
		    WHERE stvclas.STVCLAS_acdm = 9
		    GROUP BY
		        stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard
		),
		last_year_status AS
		(SELECT student_class_8.STVCLAS_student_id,
		        COALESCE(stvfstr.stvfstr_fees, 0) AS total_fee_8,
		        COALESCE(paid_fees.total_adjusted_paid, 0) AS total_adjusted_paid_8,
		        COALESCE(stvfstr.stvfstr_fees, 0) -
		        COALESCE(paid_fees.total_adjusted_paid, 0) AS pending_fee_8
		    FROM student_class_8
		    INNER JOIN stvfstr ON stvfstr.stvfstr_std = student_class_8.STVCLAS_standard
		       AND stvfstr.stvfstr_acdm_year = 8
		    LEFT JOIN paid_fees ON paid_fees.sprfees_studentid = student_class_8.STVCLAS_student_id
		       AND paid_fees.academic_year = 8
		)
		SELECT
		    spriden.SPRIDEN_student_id AS `Student ID`,
		    CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name,
		    student_class_9.STVCLAS_standard AS `Class`,
		    student_class_9.STVCLAS_class AS `Division`,
			COALESCE(current_year_payment.total_paid, 0) AS TotalPaid
		FROM spriden
		INNER JOIN student_class_9 ON student_class_9.STVCLAS_student_id = spriden.id
		INNER JOIN last_year_status ON last_year_status.STVCLAS_student_id = spriden.id
		LEFT JOIN paid_fees AS current_year_payment ON current_year_payment.sprfees_studentid = spriden.id
		   AND current_year_payment.academic_year = 9
		WHERE spriden.SPRIDEN_student_status = 'A'
		  AND spriden.SPRIDEN_user_type = 'st'
		  AND (LOWER(TRIM(spriden.rte_status)) = 'no'
		      OR spriden.rte_status IS NULL
		      OR TRIM(spriden.rte_status) = ''
		  )
		  /* Academic year 8 was fully cleared */
		  AND last_year_status.pending_fee_8 <= 0
		  /* No fee amount was paid in academic year 9 */
		  AND COALESCE(current_year_payment.total_paid, 0) = 0
		ORDER BY
		    student_class_9.STVCLAS_standard,
		    student_class_9.STVCLAS_class,
		    name";
		elseif ($type == "zero")
			//current zero
			$sql = "WITH current_year_paid_fees AS
(
    SELECT
        sprfees.sprfees_studentid,
        SUM(COALESCE(sprfees.sprfees_amount, 0)) AS total_paid
    FROM sprfees
    WHERE sprfees.academic_year = 9
    GROUP BY sprfees.sprfees_studentid
),

student_class_9 AS
(
    SELECT
        stvclas.STVCLAS_student_id,
        stvclas.STVCLAS_standard,
        MAX(stvclas.STVCLAS_class) AS STVCLAS_class
    FROM stvclas
    WHERE stvclas.STVCLAS_acdm = 9
    GROUP BY
        stvclas.STVCLAS_student_id,
        stvclas.STVCLAS_standard
),

father_contact AS
(
    SELECT
        stvpcnf.stvpcnf_student_id,

        COALESCE(
            MAX(NULLIF(TRIM(stvpcnf.stvpcnf_whatsapp), '')),
            MAX(NULLIF(TRIM(stvpcnf.stvpcnf_mobile), '')),
            ''
        ) AS MobileNumber

    FROM stvpcnf

    WHERE LOWER(TRIM(stvpcnf.stvpcnf_parent_typ)) = 'father'

    GROUP BY stvpcnf.stvpcnf_student_id
)

SELECT
    spriden.SPRIDEN_student_id AS StudentID,

    CONCAT(
        spriden.SPRIDEN_surname,
        ' ',
        spriden.SPRIDEN_student_name,
        ' ',
        spriden.SPRIDEN_father_name
    ) AS name,

    student_class_9.STVCLAS_standard AS Class,
    student_class_9.STVCLAS_class AS Division,

    COALESCE(
        father_contact.MobileNumber,
        ''
    ) AS MobileNumber,

    COALESCE(
        current_year_paid_fees.total_paid,
        0
    ) AS TotalPaid

FROM spriden

INNER JOIN student_class_9
    ON student_class_9.STVCLAS_student_id = spriden.id

LEFT JOIN current_year_paid_fees
    ON current_year_paid_fees.sprfees_studentid = spriden.id

LEFT JOIN father_contact
    ON father_contact.stvpcnf_student_id = spriden.id

WHERE spriden.SPRIDEN_student_status = 'A'

  AND spriden.SPRIDEN_user_type = 'st'

  AND
  (
      LOWER(TRIM(spriden.rte_status)) = 'no'
      OR spriden.rte_status IS NULL
      OR TRIM(spriden.rte_status) = ''
  )

  AND COALESCE(current_year_paid_fees.total_paid, 0) = 0

ORDER BY
    student_class_9.STVCLAS_standard,
    student_class_9.STVCLAS_class,
    name";
		elseif ($type == "balanced")
			// last balance current zero
			$sql = "WITH paid_fees AS
		(SELECT
		        sprfees.sprfees_studentid,
		        sprfees.academic_year,
		         SUM(COALESCE(sprfees.sprfees_amount, 0)) AS total_paid,
		         SUM(CASE
		                WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
		                THEN sprfees.sprfees_exumption
		                ELSE 0
		            END
		        ) AS total_exumpted,
		         SUM(COALESCE(sprfees.sprfees_amount, 0)) +
		        SUM(CASE
		                WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
		                THEN sprfees.sprfees_exumption
		                ELSE 0
		            END
		        ) AS total_adjusted_paid
		    FROM sprfees
		    WHERE sprfees.academic_year IN (8, 9)
		    GROUP BY
		        sprfees.sprfees_studentid,
		        sprfees.academic_year),
		student_class_8 AS
		(SELECT
		        stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard,
		        MAX(stvclas.STVCLAS_class) AS STVCLAS_class
		    FROM stvclas
		    WHERE stvclas.STVCLAS_acdm = 8
		    GROUP BY
		        stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard),
		student_class_9 AS
		(SELECT
		        stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard,
		        MAX(stvclas.STVCLAS_class) AS STVCLAS_class
		    FROM stvclas
		    WHERE stvclas.STVCLAS_acdm = 9
		    GROUP BY
		        stvclas.STVCLAS_student_id,
		        stvclas.STVCLAS_standard),
		last_year_status AS
		(SELECT
		        student_class_8.STVCLAS_student_id,
				COALESCE(stvfstr.stvfstr_fees, 0) AS total_fee_8,
		        COALESCE(paid_fees.total_adjusted_paid, 0)
		            AS total_adjusted_paid_8,
		        COALESCE(stvfstr.stvfstr_fees, 0) -
		        COALESCE(paid_fees.total_adjusted_paid, 0)
		            AS pending_fee_8
		     FROM student_class_8
		     INNER JOIN stvfstr
		        ON stvfstr.stvfstr_std =
		           student_class_8.STVCLAS_standard
		       AND stvfstr.stvfstr_acdm_year = 8
		     LEFT JOIN paid_fees
		        ON paid_fees.sprfees_studentid =
		           student_class_8.STVCLAS_student_id
		       AND paid_fees.academic_year = 8)
		SELECT
		    spriden.SPRIDEN_student_id AS StudentID,
		    CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS name,
		    student_class_9.STVCLAS_standard AS Class,
		    student_class_9.STVCLAS_class AS Division,
		    last_year_status.total_fee_8 AS LastYearFee,
		    last_year_status.total_adjusted_paid_8 AS LastYearPaid,
		    last_year_status.pending_fee_8 AS LastYearBalance
		FROM spriden
		INNER JOIN student_class_9
		    ON student_class_9.STVCLAS_student_id = spriden.id
		INNER JOIN last_year_status
		    ON last_year_status.STVCLAS_student_id = spriden.id
		LEFT JOIN paid_fees AS current_year_payment
		    ON current_year_payment.sprfees_studentid = spriden.id
		   AND current_year_payment.academic_year = 9
		WHERE spriden.SPRIDEN_student_status = 'A'
		  AND spriden.SPRIDEN_user_type = 'st'
		  AND (LOWER(TRIM(spriden.rte_status)) = 'no'
		      OR spriden.rte_status IS NULL
		      OR TRIM(spriden.rte_status) = '')
		/* Academic year 8 was fully cleared */
		  AND last_year_status.pending_fee_8 >= 0
		/* No fee amount was paid in academic year 9 */
		  AND COALESCE(current_year_payment.total_paid, 0) = 0
		ORDER BY
		    student_class_9.STVCLAS_standard,
		    student_class_9.STVCLAS_class,
		    name";
		else
			return [];
		return $this->db->query($sql)->result_array();
	}

	public function Fees_comment()
	{
		$sql = "WITH paid_fees AS
			(SELECT sprfees.sprfees_studentid,
			        sprfees.academic_year,
			        SUM(COALESCE(sprfees.sprfees_amount, 0)) AS total_paid,
			        SUM(CASE WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
			                THEN sprfees.sprfees_exumption
			                ELSE 0
			            END) AS total_exempted,
			        SUM(COALESCE(sprfees.sprfees_amount, 0)) +
			        SUM(CASE WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
			                THEN sprfees.sprfees_exumption
			                ELSE 0
			            END) AS total_adjusted_paid
			    FROM sprfees
			    WHERE sprfees.academic_year = 9
			    GROUP BY sprfees.sprfees_studentid,
			        sprfees.academic_year),
			latest_fee_payment_ranked AS
			(SELECT sprfees.sprfees_studentid,
			        sprfees.sprfees_feesdate,
			        sprfees.sprfees_notes,
			        sprfees.sprfees_amount,
			        sprfees.academic_year,
			 ROW_NUMBER() OVER
			        (PARTITION BY sprfees.sprfees_studentid
			            ORDER BY sprfees.sprfees_feesdate DESC,
			                sprfees.sprfees_pk DESC) AS row_num
			    FROM sprfees
			    WHERE COALESCE(sprfees.sprfees_amount, 0) > 0
			      AND sprfees.sprfees_feesdate IS NOT NULL),
			latest_fee_payment AS
			(SELECT
			        latest_fee_payment_ranked.sprfees_studentid,
			        latest_fee_payment_ranked.sprfees_feesdate AS last_paid_date,
			        latest_fee_payment_ranked.sprfees_notes AS last_payment_notes,
			        latest_fee_payment_ranked.sprfees_amount AS last_paid_amount,
			        latest_fee_payment_ranked.academic_year AS last_payment_academic_year_id
			    FROM latest_fee_payment_ranked
			    WHERE latest_fee_payment_ranked.row_num = 1),
				student_class AS (SELECT stvclas.STVCLAS_student_id,
			        stvclas.STVCLAS_acdm,
			        stvclas.STVCLAS_standard,
			        MAX(stvclas.STVCLAS_class) AS STVCLAS_class
			    FROM stvclas
			    WHERE stvclas.STVCLAS_acdm = 9
			    GROUP BY stvclas.STVCLAS_student_id,
			        stvclas.STVCLAS_acdm,
			        stvclas.STVCLAS_standard),
			father_contact AS
			(SELECT stvpcnf.stvpcnf_student_id,
			        MAX(NULLIF(TRIM(stvpcnf.stvpcnf_mobile), '')) AS father_mobile_number,
			        MAX(NULLIF(TRIM(stvpcnf.stvpcnf_whatsapp), '')) AS father_whatsapp_number
			    FROM stvpcnf
			    WHERE LOWER(TRIM(stvpcnf.stvpcnf_parent_typ)) = 'father'
			    GROUP BY stvpcnf.stvpcnf_student_id)
			SELECT spriden.SPRIDEN_student_id AS student_id,
			    CONCAT(spriden.SPRIDEN_surname, ' ', spriden.SPRIDEN_student_name, ' ', spriden.SPRIDEN_father_name) AS student_name,
			    student_class.STVCLAS_standard AS class_name,
			    student_class.STVCLAS_class AS division,
			    stvacdm.stvacdm_year AS academic_year,
			    father_contact.father_mobile_number AS father_mobile_number,
			    father_contact.father_whatsapp_number AS father_whatsapp_number,
			    COALESCE(stvfstr.stvfstr_fees,0) AS total_fee,
			    COALESCE(paid_fees.total_paid,0) AS total_paid,
			    COALESCE(paid_fees.total_exempted,0) AS total_exempted,
			    COALESCE(paid_fees.total_adjusted_paid,0) AS total_paid_including_exemption,
			    COALESCE(stvfstr.stvfstr_fees,0)-
			    COALESCE(paid_fees.total_adjusted_paid,0) AS balance_fee,
			    latest_fee_payment.last_paid_date AS last_paid_date,
			    latest_fee_payment.last_paid_amount AS last_paid_amount,
			    latest_fee_payment.last_payment_notes AS last_payment_notes,
			    last_payment_acdm.stvacdm_year AS last_payment_academic_year
			FROM spriden
			INNER JOIN student_class
			    ON student_class.STVCLAS_student_id = spriden.id
			INNER JOIN stvacdm
			    ON stvacdm.stvacdm_id = student_class.STVCLAS_acdm
			INNER JOIN stvfstr ON stvfstr.stvfstr_std = student_class.STVCLAS_standard
			   AND stvfstr.stvfstr_acdm_year = student_class.STVCLAS_acdm
			LEFT JOIN paid_fees ON paid_fees.sprfees_studentid = spriden.id
			   AND paid_fees.academic_year = student_class.STVCLAS_acdm
			LEFT JOIN latest_fee_payment ON latest_fee_payment.sprfees_studentid = spriden.id
			LEFT JOIN stvacdm AS last_payment_acdm ON last_payment_acdm.stvacdm_id = latest_fee_payment.last_payment_academic_year_id
			LEFT JOIN father_contact ON father_contact.stvpcnf_student_id = spriden.id
			WHERE spriden.SPRIDEN_student_status = 'A'
			  AND spriden.SPRIDEN_user_type = 'st'
			  AND (LOWER(TRIM(spriden.rte_status)) = 'no'
			      OR spriden.rte_status IS NULL
			      OR TRIM(spriden.rte_status) = '')
			  AND student_class.STVCLAS_acdm = 9
			  AND COALESCE(TRIM(student_class.STVCLAS_class),'') <> '*'
			  AND (UPPER(COALESCE(@P_STUDENT_ID, 'ALL')) = 'ALL'
			      OR spriden.SPRIDEN_student_id = @P_STUDENT_ID)
			ORDER BY student_class.STVCLAS_standard,
			    student_class.STVCLAS_class,
			    student_name";

		return $this->db->query($sql)->result_array();
	}

	public function get_fees_balance_academic_years()
	{
		return $this->db
			->select('
            stvacdm_id AS academic_year_id,
            stvacdm_year AS academic_year_name
        ')
			->from('stvacdm')
			->where_in('stvacdm_id', [5, 6, 7, 8])
			->order_by('stvacdm_id', 'ASC')
			->get()
			->result_array();
	}
	// zoheb ---------
	private function all_previous_fees_base_sql()
	{
		return "
        WITH paid_fees AS
        (
            SELECT
                sprfees.sprfees_studentid,
                sprfees.academic_year,

                SUM(
                    COALESCE(
                        sprfees.sprfees_amount,
                        0
                    )
                ) AS total_paid,

                SUM(
                    CASE
                        WHEN COALESCE(
                            sprfees.sprfees_exumption,
                            0
                        ) > 0
                        THEN sprfees.sprfees_exumption
                        ELSE 0
                    END
                ) AS total_exumpted,

                SUM(
                    COALESCE(
                        sprfees.sprfees_amount,
                        0
                    )
                )
                +
                SUM(
                    CASE
                        WHEN COALESCE(
                            sprfees.sprfees_exumption,
                            0
                        ) > 0
                        THEN sprfees.sprfees_exumption
                        ELSE 0
                    END
                ) AS total_adjusted_paid

            FROM sprfees

            WHERE sprfees.academic_year
                BETWEEN 5 AND 8

            GROUP BY
                sprfees.sprfees_studentid,
                sprfees.academic_year
        ),

        student_class AS
        (
            SELECT
                stvclas.STVCLAS_student_id,
                stvclas.STVCLAS_acdm,
                stvclas.STVCLAS_standard,

                MAX(
                    stvclas.STVCLAS_class
                ) AS STVCLAS_class

            FROM stvclas

            WHERE stvclas.STVCLAS_acdm
                BETWEEN 5 AND 8

            GROUP BY
                stvclas.STVCLAS_student_id,
                stvclas.STVCLAS_acdm,
                stvclas.STVCLAS_standard
        ),

        father_contact AS
        (
            SELECT
                stvpcnf.stvpcnf_student_id,

                COALESCE(
                    MAX(
                        NULLIF(
                            TRIM(
                                stvpcnf.stvpcnf_whatsapp
                            ),
                            ''
                        )
                    ),
                    MAX(
                        NULLIF(
                            TRIM(
                                stvpcnf.stvpcnf_mobile
                            ),
                            ''
                        )
                    ),
                    ''
                ) AS MobileNumber

            FROM stvpcnf

            WHERE LOWER(
                TRIM(
                    stvpcnf.stvpcnf_parent_typ
                )
            ) = 'father'

            GROUP BY
                stvpcnf.stvpcnf_student_id
        ),

        pending_fee_details AS
        (
            SELECT
                spriden.id,

                spriden.SPRIDEN_student_id,

             TRIM(
 	   CONCAT_WS(
        ' ',
        NULLIF(
            TRIM(spriden.SPRIDEN_surname),
            ''
        ),
        NULLIF(
            TRIM(spriden.SPRIDEN_student_name),
            ''
        ),
        NULLIF(
            TRIM(spriden.SPRIDEN_father_name),
            ''
        )
   	 )
		) AS name,

                stvacdm.stvacdm_id,
                stvacdm.stvacdm_year,

                student_class.STVCLAS_standard,
                student_class.STVCLAS_class,

                COALESCE(
                    stvfstr.stvfstr_fees,
                    0
                ) AS total_fee,

                COALESCE(
                    paid_fees.total_adjusted_paid,
                    0
                ) AS total_paid,

                COALESCE(
                    stvfstr.stvfstr_fees,
                    0
                )
                -
                COALESCE(
                    paid_fees.total_adjusted_paid,
                    0
                ) AS pending_fee

            FROM spriden

            INNER JOIN student_class
                ON student_class.STVCLAS_student_id =
                    spriden.id

            INNER JOIN stvacdm
                ON stvacdm.stvacdm_id =
                    student_class.STVCLAS_acdm

            INNER JOIN stvfstr
                ON stvfstr.stvfstr_std =
                    student_class.STVCLAS_standard

               AND stvfstr.stvfstr_acdm_year =
                    student_class.STVCLAS_acdm

            LEFT JOIN paid_fees
                ON paid_fees.sprfees_studentid =
                    spriden.id

               AND paid_fees.academic_year =
                    student_class.STVCLAS_acdm

            WHERE spriden.SPRIDEN_student_status = 'A'

              AND spriden.SPRIDEN_user_type = 'st'

              AND
              (
                    LOWER(
                        TRIM(
                            spriden.rte_status
                        )
                    ) = 'no'

                    OR spriden.rte_status IS NULL

                    OR TRIM(
                        spriden.rte_status
                    ) = ''
              )

              AND stvacdm.stvacdm_id
                    BETWEEN 5 AND 8
        ),

        student_pending AS
        (
            SELECT
                details.id,

                details.SPRIDEN_student_id,

                details.name,

                COALESCE(
                    father_contact.MobileNumber,
                    ''
                ) AS MobileNumber,

                MAX(
                    CASE
                        WHEN details.stvacdm_id = 5
                         AND details.pending_fee > 0
                        THEN ROUND(
                            details.pending_fee,
                            0
                        )
                        ELSE 0
                    END
                ) AS Pending_5,

                MAX(
                    CASE
                        WHEN details.stvacdm_id = 6
                         AND details.pending_fee > 0
                        THEN ROUND(
                            details.pending_fee,
                            0
                        )
                        ELSE 0
                    END
                ) AS Pending_6,

                MAX(
                    CASE
                        WHEN details.stvacdm_id = 7
                         AND details.pending_fee > 0
                        THEN ROUND(
                            details.pending_fee,
                            0
                        )
                        ELSE 0
                    END
                ) AS Pending_7,

                MAX(
                    CASE
                        WHEN details.stvacdm_id = 8
                         AND details.pending_fee > 0
                        THEN ROUND(
                            details.pending_fee,
                            0
                        )
                        ELSE 0
                    END
                ) AS Pending_8

            FROM pending_fee_details details

            LEFT JOIN father_contact
                ON father_contact.stvpcnf_student_id =
                    details.id

            GROUP BY
                details.id,
                details.SPRIDEN_student_id,
                details.name,
                father_contact.MobileNumber
        )

        SELECT
            student_pending.SPRIDEN_student_id,
            student_pending.name,
            student_pending.MobileNumber,
            student_pending.Pending_5,
            student_pending.Pending_6,
            student_pending.Pending_7,
            student_pending.Pending_8,

           (
		    student_pending.Pending_5
		    +
		    student_pending.Pending_6
		    +
		    student_pending.Pending_7
		    +
		    student_pending.Pending_8
		) AS TotalPending,

		(
		    CASE
		        WHEN student_pending.Pending_5 > 0 THEN 1
		        ELSE 0
		    END
		    +
		    CASE
		        WHEN student_pending.Pending_6 > 0 THEN 1
		        ELSE 0
		    END
		    +
		    CASE
		        WHEN student_pending.Pending_7 > 0 THEN 1
		        ELSE 0
		    END
		    +
		    CASE
		        WHEN student_pending.Pending_8 > 0 THEN 1
		        ELSE 0
		    END
		) AS PendingYearCount

		        FROM student_pending

		        WHERE
		            student_pending.Pending_5 > 0
		            OR student_pending.Pending_6 > 0
		            OR student_pending.Pending_7 > 0
		            OR student_pending.Pending_8 > 0
		   	 	";
	}
	public function get_all_previous_fees_report(array $params = [])
	{
		$baseSql = $this->all_previous_fees_base_sql();

		$academicYears = array_values(array_filter(
			$this->get_fees_balance_academic_years(),
			static function ($year) {
				$yearId = (int) ($year['academic_year_id'] ?? 0);

				return $yearId >= 5 && $yearId <= 8;
			}
		));

		$start = max(0, (int) ($params['start'] ?? 0));
		$length = (int) ($params['length'] ?? 20);

		if (!in_array($length, [20, 50, 100], true)) {
			$length = 20;
		}

		$search = trim((string) ($params['search'] ?? ''));

		$conditions = [];
		$binds = [];

		if ($search !== '') {
			$searchValue = $search . '%';
			$mobileDigits = preg_replace('/[^0-9]/', '', $search);

			$searchParts = [
				'report.SPRIDEN_student_id LIKE ?',
				'report.name LIKE ?',
			];

			$binds[] = $searchValue;
			$binds[] = $searchValue;

			if ($mobileDigits !== '') {
				$searchParts[] = 'report.MobileNumber LIKE ?';
				$binds[] = $mobileDigits . '%';
			}

			$conditions[] = '(' . implode(' OR ', $searchParts) . ')';
		}

		$whereSql = $conditions
			? ' WHERE ' . implode(' AND ', $conditions)
			: '';

		/*
		 * One database query returns:
		 * - Current page records
		 * - Filtered record count
		 * - All academic-year totals
		 * - Complete outstanding total
		 */
		$dataSql = "
        SELECT
            filtered_report.SPRIDEN_student_id,
            filtered_report.name,
            filtered_report.MobileNumber,
            filtered_report.Pending_5,
            filtered_report.Pending_6,
            filtered_report.Pending_7,
            filtered_report.Pending_8,
            filtered_report.TotalPending,
            filtered_report.PendingYearCount,

            COUNT(*) OVER () AS report_students,

            SUM(filtered_report.Pending_5) OVER ()
                AS report_pending_5,

            SUM(filtered_report.Pending_6) OVER ()
                AS report_pending_6,

            SUM(filtered_report.Pending_7) OVER ()
                AS report_pending_7,

            SUM(filtered_report.Pending_8) OVER ()
                AS report_pending_8,

            SUM(filtered_report.TotalPending) OVER ()
                AS report_total_pending

        FROM
        (
            SELECT
                report.SPRIDEN_student_id,
                report.name,
                report.MobileNumber,
                report.Pending_5,
                report.Pending_6,
                report.Pending_7,
                report.Pending_8,
                report.TotalPending,
                report.PendingYearCount

            FROM ({$baseSql}) report

            {$whereSql}
        ) filtered_report

        ORDER BY
            filtered_report.PendingYearCount DESC,
            filtered_report.TotalPending DESC,
            filtered_report.SPRIDEN_student_id ASC,
            filtered_report.name ASC

        LIMIT ?, ?
  	  ";

		$dataBinds = $binds;
		$dataBinds[] = $start;
		$dataBinds[] = $length;

		$rows = $this->db
			->query($dataSql, $dataBinds)
			->result_array();

		/*
		 * Window totals are identical in every returned row,
		 * so read them only from the first record.
		 */
		$firstRow = $rows[0] ?? [];

		$recordsFiltered = (int) ($firstRow['report_students'] ?? 0);
		$totalPending = (float) ($firstRow['report_total_pending'] ?? 0);

		$serial = $start + 1;

		foreach ($rows as &$row) {
			$row['serial'] = $serial++;

			$row['Pending_5'] = (float) ($row['Pending_5'] ?? 0);
			$row['Pending_6'] = (float) ($row['Pending_6'] ?? 0);
			$row['Pending_7'] = (float) ($row['Pending_7'] ?? 0);
			$row['Pending_8'] = (float) ($row['Pending_8'] ?? 0);
			$row['TotalPending'] = (float) ($row['TotalPending'] ?? 0);
			$row['PendingYearCount'] = (int) ($row['PendingYearCount'] ?? 0);

			/*
			 * Remove internal summary columns before sending rows
			 * to the view.
			 */
			unset(
				$row['report_students'],
				$row['report_pending_5'],
				$row['report_pending_6'],
				$row['report_pending_7'],
				$row['report_pending_8'],
				$row['report_total_pending']
			);
		}

		unset($row);

		return [
			'recordsTotal' => $recordsFiltered,
			'recordsFiltered' => $recordsFiltered,
			'data' => $rows,
			'academicYears' => $academicYears,

			'summary' => [
				'students' => $recordsFiltered,
				'academicYears' => count($academicYears),
				'totalPending' => $totalPending,

				'averagePending' => $recordsFiltered > 0
					? $totalPending / $recordsFiltered
					: 0,

				'yearTotals' => [
					5 => (float) ($firstRow['report_pending_5'] ?? 0),
					6 => (float) ($firstRow['report_pending_6'] ?? 0),
					7 => (float) ($firstRow['report_pending_7'] ?? 0),
					8 => (float) ($firstRow['report_pending_8'] ?? 0),
				],
			],
		];
	}

	private function last_year_fees_base_sql()
	{
		return "
        WITH paid_fees AS
        (
            SELECT
                sprfees.sprfees_studentid,
                sprfees.academic_year,
                SUM(COALESCE(sprfees.sprfees_amount, 0)) AS total_paid,
                SUM(
                    CASE
                        WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
                        THEN sprfees.sprfees_exumption
                        ELSE 0
                    END
                ) AS total_exumpted,
                SUM(COALESCE(sprfees.sprfees_amount, 0)) +
                SUM(
                    CASE
                        WHEN COALESCE(sprfees.sprfees_exumption, 0) > 0
                        THEN sprfees.sprfees_exumption
                        ELSE 0
                    END
                ) AS total_adjusted_paid
            FROM sprfees
            WHERE sprfees.academic_year = 8
            GROUP BY
                sprfees.sprfees_studentid,
                sprfees.academic_year
        ),

        student_class AS
        (
            SELECT
                stvclas.STVCLAS_student_id,
                stvclas.STVCLAS_acdm,
                stvclas.STVCLAS_standard,
                MAX(stvclas.STVCLAS_class) AS STVCLAS_class
            FROM stvclas
            WHERE stvclas.STVCLAS_acdm = 8
            GROUP BY
                stvclas.STVCLAS_student_id,
                stvclas.STVCLAS_acdm,
                stvclas.STVCLAS_standard
        ),

        father_contact AS
        (
            SELECT
                stvpcnf.stvpcnf_student_id,
                COALESCE(
                    MAX(NULLIF(TRIM(stvpcnf.stvpcnf_whatsapp), '')),
                    MAX(NULLIF(TRIM(stvpcnf.stvpcnf_mobile), '')),
                    ''
                ) AS MobileNumber
            FROM stvpcnf
            INNER JOIN student_class
                ON student_class.STVCLAS_student_id =
                   stvpcnf.stvpcnf_student_id
            WHERE LOWER(TRIM(stvpcnf.stvpcnf_parent_typ)) = 'father'
            GROUP BY
                stvpcnf.stvpcnf_student_id
        ),

        pending_fee_details AS
        (
            SELECT
                spriden.id,
                spriden.SPRIDEN_student_id,
                TRIM(
                    CONCAT_WS(
                        ' ',
                        NULLIF(TRIM(spriden.SPRIDEN_surname), ''),
                        NULLIF(TRIM(spriden.SPRIDEN_student_name), ''),
                        NULLIF(TRIM(spriden.SPRIDEN_father_name), '')
                    )
                ) AS name,
                stvacdm.stvacdm_id,
                stvacdm.stvacdm_year,
                student_class.STVCLAS_standard,
                student_class.STVCLAS_class,
                COALESCE(stvfstr.stvfstr_fees, 0) AS total_fee,
                COALESCE(paid_fees.total_paid, 0) AS total_paid,
                COALESCE(paid_fees.total_exumpted, 0) AS total_exumpted,
                COALESCE(paid_fees.total_adjusted_paid, 0)
                    AS total_adjusted_paid,
                GREATEST(
                    COALESCE(stvfstr.stvfstr_fees, 0) -
                    COALESCE(paid_fees.total_adjusted_paid, 0),
                    0
                ) AS pending_fee,
                COALESCE(father_contact.MobileNumber, '')
                    AS MobileNumber
            FROM spriden
            INNER JOIN student_class
                ON student_class.STVCLAS_student_id = spriden.id
            INNER JOIN stvacdm
                ON stvacdm.stvacdm_id = student_class.STVCLAS_acdm
            INNER JOIN stvfstr
                ON stvfstr.stvfstr_std =
                   student_class.STVCLAS_standard
               AND stvfstr.stvfstr_acdm_year =
                   student_class.STVCLAS_acdm
            LEFT JOIN paid_fees
                ON paid_fees.sprfees_studentid = spriden.id
               AND paid_fees.academic_year =
                   student_class.STVCLAS_acdm
            LEFT JOIN father_contact
                ON father_contact.stvpcnf_student_id = spriden.id
            WHERE spriden.SPRIDEN_student_status = 'A'
              AND spriden.SPRIDEN_user_type = 'st'
              AND (
                    LOWER(TRIM(spriden.rte_status)) = 'no'
                    OR spriden.rte_status IS NULL
                    OR TRIM(spriden.rte_status) = ''
                  )
              AND stvacdm.stvacdm_id = 8
              AND COALESCE(stvfstr.stvfstr_fees, 0) > 0
        )

        SELECT
            SPRIDEN_student_id,
            name,
            stvacdm_year AS Academic_year,
            total_fee AS Total_fee,
            total_paid AS Total_paid,
            total_exumpted AS Total_exumpted,
            total_adjusted_paid AS Total_paid_icluding_exumption,
            pending_fee AS Pending_fees,
            MobileNumber
        FROM pending_fee_details
        WHERE pending_fee > 0
    ";
	}


	public function get_last_year_fees_report(array $params = [])
	{
		$baseSql = $this->last_year_fees_base_sql();

		$start = max(0, (int) ($params['start'] ?? 0));
		$length = (int) ($params['length'] ?? 20);

		if (!in_array($length, [20, 50, 100], true)) {
			$length = 20;
		}

		$search = trim((string) ($params['search'] ?? ''));

		$conditions = [];
		$binds = [];

		if ($search !== '') {
			$searchValue = $search . '%';
			$mobileDigits = preg_replace('/[^0-9]/', '', $search);

			$searchParts = [
				'report.SPRIDEN_student_id LIKE ?',
				'report.name LIKE ?',
			];

			$binds[] = $searchValue;
			$binds[] = $searchValue;

			if ($mobileDigits !== '') {
				$searchParts[] = 'report.MobileNumber LIKE ?';
				$binds[] = $mobileDigits . '%';
			}

			$conditions[] = '(' . implode(' OR ', $searchParts) . ')';
		}

		$whereSql = $conditions
			? ' WHERE ' . implode(' AND ', $conditions)
			: '';

		$dataSql = "
        SELECT
            report.*,
            COUNT(*) OVER() AS _full_count,
            SUM(report.Total_fee) OVER() AS _full_total_fee,
            SUM(report.Pending_fees) OVER() AS _full_total_pending,
            SUM(report.Total_paid) OVER() AS _full_total_paid,
            SUM(report.Total_exumpted) OVER() AS _full_total_exempted,
            SUM(report.Total_paid_icluding_exumption) OVER()
                AS _full_total_adjusted_paid
        FROM ({$baseSql}) report
        {$whereSql}
        ORDER BY
            report.SPRIDEN_student_id ASC
        LIMIT ?, ?
    	";

		$dataBinds = $binds;
		$dataBinds[] = $start;
		$dataBinds[] = $length;

		$rows = $this->db
			->query($dataSql, $dataBinds)
			->result_array();

		$recordsFiltered = 0;
		$totalFee = 0.0;
		$totalPending = 0.0;
		$totalPaid = 0.0;
		$totalExempted = 0.0;
		$totalAdjustedPaid = 0.0;

		if (!empty($rows)) {
			$first = $rows[0];

			$recordsFiltered = (int) ($first['_full_count'] ?? 0);
			$totalFee = (float) ($first['_full_total_fee'] ?? 0);
			$totalPending = (float) ($first['_full_total_pending'] ?? 0);
			$totalPaid = (float) ($first['_full_total_paid'] ?? 0);
			$totalExempted = (float) ($first['_full_total_exempted'] ?? 0);
			$totalAdjustedPaid = (float) (
				$first['_full_total_adjusted_paid'] ?? 0
			);

			foreach ($rows as &$row) {
				unset(
					$row['_full_count'],
					$row['_full_total_fee'],
					$row['_full_total_pending'],
					$row['_full_total_paid'],
					$row['_full_total_exempted'],
					$row['_full_total_adjusted_paid']
				);
			}

			unset($row);
		} else {
			$summarySql = "
            SELECT
                COUNT(*) AS total_students,
                COALESCE(SUM(report.Total_fee), 0) AS total_fee,
                COALESCE(SUM(report.Pending_fees), 0) AS total_pending,
                COALESCE(SUM(report.Total_paid), 0) AS total_paid,
                COALESCE(SUM(report.Total_exumpted), 0)
                    AS total_exempted,
                COALESCE(
                    SUM(report.Total_paid_icluding_exumption),
                    0
                ) AS total_adjusted_paid
            FROM ({$baseSql}) report
            {$whereSql}
        ";

			$summaryRow = $this->db
				->query($summarySql, $binds)
				->row_array();

			$recordsFiltered = (int) (
				$summaryRow['total_students'] ?? 0
			);
			$totalFee = (float) ($summaryRow['total_fee'] ?? 0);
			$totalPending = (float) (
				$summaryRow['total_pending'] ?? 0
			);
			$totalPaid = (float) ($summaryRow['total_paid'] ?? 0);
			$totalExempted = (float) (
				$summaryRow['total_exempted'] ?? 0
			);
			$totalAdjustedPaid = (float) (
				$summaryRow['total_adjusted_paid'] ?? 0
			);
		}

		$serial = $start + 1;

		foreach ($rows as &$row) {
			$row['serial'] = $serial++;
			$row['Total_fee'] = (float) ($row['Total_fee'] ?? 0);
			$row['Total_paid'] = (float) ($row['Total_paid'] ?? 0);
			$row['Total_exumpted'] = (float) (
				$row['Total_exumpted'] ?? 0
			);
			$row['Total_paid_icluding_exumption'] = (float) (
				$row['Total_paid_icluding_exumption'] ?? 0
			);
			$row['Pending_fees'] = (float) (
				$row['Pending_fees'] ?? 0
			);
		}

		unset($row);

		return [
			'recordsTotal' => $recordsFiltered,
			'recordsFiltered' => $recordsFiltered,
			'data' => $rows,
			'summary' => [
				'students' => $recordsFiltered,
				'totalFee' => $totalFee,
				'totalPending' => $totalPending,
				'totalPaid' => $totalPaid,
				'totalExempted' => $totalExempted,
				'totalAdjustedPaid' => $totalAdjustedPaid,
				'averagePending' => $recordsFiltered > 0
					? $totalPending / $recordsFiltered
					: 0,
			],
		];
	}

	private function current_zero_paid_base_sql()
	{
		return "
        WITH current_year_paid_fees AS
        (
            SELECT
                sprfees.sprfees_studentid,
                SUM(
                    COALESCE(
                        sprfees.sprfees_amount,
                        0
                    )
                ) AS total_paid
            FROM sprfees
            WHERE sprfees.academic_year = 9
            GROUP BY
                sprfees.sprfees_studentid
        ),

        student_class_9 AS
        (
            SELECT
                stvclas.STVCLAS_student_id,
                stvclas.STVCLAS_acdm,
                stvclas.STVCLAS_standard,
                MAX(stvclas.STVCLAS_class)
                    AS STVCLAS_class
            FROM stvclas
            WHERE stvclas.STVCLAS_acdm = 9
            GROUP BY
                stvclas.STVCLAS_student_id,
                stvclas.STVCLAS_acdm,
                stvclas.STVCLAS_standard
        ),

        father_contact AS
        (
            SELECT
                stvpcnf.stvpcnf_student_id,

                COALESCE(
                    MAX(
                        NULLIF(
                            TRIM(stvpcnf.stvpcnf_whatsapp),
                            ''
                        )
                    ),
                    MAX(
                        NULLIF(
                            TRIM(stvpcnf.stvpcnf_mobile),
                            ''
                        )
                    ),
                    ''
                ) AS MobileNumber

            FROM stvpcnf

            WHERE LOWER(
                TRIM(stvpcnf.stvpcnf_parent_typ)
            ) = 'father'

            GROUP BY
                stvpcnf.stvpcnf_student_id
        )

        SELECT
            spriden.SPRIDEN_student_id
                AS StudentID,

            TRIM(
                CONCAT_WS(
                    ' ',
                    NULLIF(
                        TRIM(spriden.SPRIDEN_surname),
                        ''
                    ),
                    NULLIF(
                        TRIM(spriden.SPRIDEN_student_name),
                        ''
                    ),
                    NULLIF(
                        TRIM(spriden.SPRIDEN_father_name),
                        ''
                    )
                )
            ) AS name,

            student_class_9.STVCLAS_standard
                AS Class,

            student_class_9.STVCLAS_class
                AS Division,

            COALESCE(
                father_contact.MobileNumber,
                ''
            ) AS MobileNumber,

            COALESCE(
                current_year_paid_fees.total_paid,
                0
            ) AS TotalPaid,

            COALESCE(
                stvfstr.stvfstr_fees,
                0
            ) AS TotalFee,

            GREATEST(
                COALESCE(
                    stvfstr.stvfstr_fees,
                    0
                ) -
                COALESCE(
                    current_year_paid_fees.total_paid,
                    0
                ),
                0
            ) AS PendingFee

        FROM spriden

        INNER JOIN student_class_9
            ON student_class_9.STVCLAS_student_id =
               spriden.id

        INNER JOIN stvacdm
            ON stvacdm.stvacdm_id =
               student_class_9.STVCLAS_acdm

        INNER JOIN stvfstr
            ON stvfstr.stvfstr_std =
               student_class_9.STVCLAS_standard

           AND stvfstr.stvfstr_acdm_year =
               student_class_9.STVCLAS_acdm

        LEFT JOIN current_year_paid_fees
            ON current_year_paid_fees.sprfees_studentid =
               spriden.id

        LEFT JOIN father_contact
            ON father_contact.stvpcnf_student_id =
               spriden.id

        WHERE
            spriden.SPRIDEN_student_status = 'A'

            AND spriden.SPRIDEN_user_type = 'st'

            AND
            (
                LOWER(
                    TRIM(spriden.rte_status)
                ) = 'no'

                OR spriden.rte_status IS NULL

                OR TRIM(
                    spriden.rte_status
                ) = ''
            )

            /*
             * Only students with no payment in academic year 9.
             */
            AND COALESCE(
                current_year_paid_fees.total_paid,
                0
            ) = 0

            /*
             * Exclude students whose configured fee is zero.
             */
            AND COALESCE(
                stvfstr.stvfstr_fees,
                0
            ) > 0
    	";
	}
	public function get_current_zero_paid_report(array $params = [])
	{
		$baseSql = $this->current_zero_paid_base_sql();

		$start = max(
			0,
			(int) ($params['start'] ?? 0)
		);

		$length = (int) ($params['length'] ?? 20);

		if (!in_array($length, [20, 50, 100], true)) {
			$length = 20;
		}

		$search = trim(
			(string) ($params['search'] ?? '')
		);

		$conditions = [];
		$binds = [];

		/*
		 * Search by student ID, name or mobile.
		 */
		if ($search !== '') {
			$searchValue = $search . '%';

			$mobileDigits = preg_replace(
				'/[^0-9]/',
				'',
				$search
			);

			$searchParts = [
				'report.StudentID LIKE ?',
				'report.name LIKE ?',
			];

			$binds[] = $searchValue;
			$binds[] = $searchValue;

			if ($mobileDigits !== '') {
				$searchParts[] =
					'report.MobileNumber LIKE ?';

				$binds[] = $mobileDigits . '%';
			}

			$conditions[] =
				'(' . implode(' OR ', $searchParts) . ')';
		}

		$whereSql = !empty($conditions)
			? ' WHERE ' . implode(' AND ', $conditions)
			: '';

		/*
		 * Single query returns:
		 * - Paginated rows
		 * - Filtered student count
		 * - Total expected fee
		 * - Total paid fee
		 * - Total pending fee
		 */
		$dataSql = "
        SELECT
            report.StudentID,
            report.name,
            report.Class,
            report.Division,
            report.MobileNumber,
            report.TotalPaid,
            report.TotalFee,
            report.PendingFee,

            COUNT(*) OVER ()
                AS _full_count,

            SUM(report.TotalPaid) OVER ()
                AS _full_total_paid,

            SUM(report.TotalFee) OVER ()
                AS _full_total_fee,

            SUM(report.PendingFee) OVER ()
                AS _full_total_pending

        FROM ({$baseSql}) report

        {$whereSql}

        ORDER BY
            report.Class ASC,
            report.Division ASC,
            report.name ASC,
            report.StudentID ASC

        LIMIT ?, ?
     ";

		$dataBinds = $binds;
		$dataBinds[] = $start;
		$dataBinds[] = $length;

		$rows = $this->db
			->query($dataSql, $dataBinds)
			->result_array();

		/*
		 * Always initialize summary variables.
		 */
		$recordsFiltered = 0;
		$totalPaid = 0.0;
		$totalFee = 0.0;
		$totalPending = 0.0;

		if (!empty($rows)) {
			$firstRow = $rows[0];

			$recordsFiltered = (int) (
				$firstRow['_full_count'] ?? 0
			);

			$totalPaid = (float) (
				$firstRow['_full_total_paid'] ?? 0
			);

			$totalFee = (float) (
				$firstRow['_full_total_fee'] ?? 0
			);

			$totalPending = (float) (
				$firstRow['_full_total_pending'] ?? 0
			);

			foreach ($rows as &$row) {
				unset(
					$row['_full_count'],
					$row['_full_total_paid'],
					$row['_full_total_fee'],
					$row['_full_total_pending']
				);
			}

			unset($row);
		} else {
			/*
			 * Required when the user requests a page beyond the
			 * available result pages.
			 */
			$summarySql = "
            SELECT
                COUNT(*) AS total_students,

                COALESCE(
                    SUM(report.TotalPaid),
                    0
                ) AS total_paid,

                COALESCE(
                    SUM(report.TotalFee),
                    0
                ) AS total_fee,

                COALESCE(
                    SUM(report.PendingFee),
                    0
                ) AS total_pending

            FROM ({$baseSql}) report

            {$whereSql}
        ";

			$summaryRow = $this->db
				->query($summarySql, $binds)
				->row_array();

			$recordsFiltered = (int) (
				$summaryRow['total_students'] ?? 0
			);

			$totalPaid = (float) (
				$summaryRow['total_paid'] ?? 0
			);

			$totalFee = (float) (
				$summaryRow['total_fee'] ?? 0
			);

			$totalPending = (float) (
				$summaryRow['total_pending'] ?? 0
			);
		}

		/*
		 * Format row values and create serial numbers.
		 */
		$serial = $start + 1;

		foreach ($rows as &$row) {
			$row['serial'] = $serial++;

			$row['TotalFee'] = (float) (
				$row['TotalFee'] ?? 0
			);

			$row['TotalPaid'] = (float) (
				$row['TotalPaid'] ?? 0
			);

			$row['PendingFee'] = (float) (
				$row['PendingFee'] ?? 0
			);
		}

		unset($row);

		return [
			'recordsTotal' => $recordsFiltered,

			'recordsFiltered' => $recordsFiltered,

			'data' => $rows,

			'summary' => [
				'students' => $recordsFiltered,

				'totalPending' => $totalPending,

				'totalFee' => $totalFee,

				'totalPaid' => $totalPaid,

				'averagePending' => $recordsFiltered > 0
					? $totalPending / $recordsFiltered
					: 0,
			],
		];
	}
	private function last_year_balance_base_sql()
	{
		return "
        WITH paid_fees AS
        (
            SELECT
                sprfees.sprfees_studentid,
                sprfees.academic_year,

                SUM(
                    COALESCE(
                        sprfees.sprfees_amount,
                        0
                    )
                ) AS total_paid,

                SUM(
                    COALESCE(
                        sprfees.sprfees_amount,
                        0
                    )
                )
                +
                SUM(
                    CASE
                        WHEN COALESCE(
                            sprfees.sprfees_exumption,
                            0
                        ) > 0
                        THEN sprfees.sprfees_exumption
                        ELSE 0
                    END
                ) AS total_adjusted_paid

            FROM sprfees

            WHERE sprfees.academic_year IN (8, 9)

            GROUP BY
                sprfees.sprfees_studentid,
                sprfees.academic_year
        ),

        student_class_8 AS
        (
            SELECT
                STVCLAS_student_id,
                STVCLAS_standard,

                MAX(
                    STVCLAS_class
                ) AS STVCLAS_class

            FROM stvclas

            WHERE STVCLAS_acdm = 8

            GROUP BY
                STVCLAS_student_id,
                STVCLAS_standard
        ),

        student_class_9 AS
        (
            SELECT
                STVCLAS_student_id,
                STVCLAS_standard,

                MAX(
                    STVCLAS_class
                ) AS STVCLAS_class

            FROM stvclas

            WHERE STVCLAS_acdm = 9

            GROUP BY
                STVCLAS_student_id,
                STVCLAS_standard
        ),

        father_contact AS
        (
            SELECT
                stvpcnf.stvpcnf_student_id,

                COALESCE(
                    MAX(
                        NULLIF(
                            TRIM(
                                stvpcnf.stvpcnf_whatsapp
                            ),
                            ''
                        )
                    ),
                    MAX(
                        NULLIF(
                            TRIM(
                                stvpcnf.stvpcnf_mobile
                            ),
                            ''
                        )
                    ),
                    ''
                ) AS MobileNumber

            FROM stvpcnf

            WHERE LOWER(
                TRIM(
                    stvpcnf.stvpcnf_parent_typ
                )
            ) = 'father'

            GROUP BY
                stvpcnf.stvpcnf_student_id
        ),

        last_year_status AS
        (
            SELECT
                class_8.STVCLAS_student_id,

                COALESCE(
                    fee_structure.stvfstr_fees,
                    0
                ) AS total_fee_8,

                COALESCE(
                    payment.total_adjusted_paid,
                    0
                ) AS total_paid_8,

                COALESCE(
                    fee_structure.stvfstr_fees,
                    0
                )
                -
                COALESCE(
                    payment.total_adjusted_paid,
                    0
                ) AS balance_8

            FROM student_class_8 class_8

            INNER JOIN stvfstr fee_structure
                ON fee_structure.stvfstr_std =
                    class_8.STVCLAS_standard

               AND fee_structure.stvfstr_acdm_year = 8

            LEFT JOIN paid_fees payment
                ON payment.sprfees_studentid =
                    class_8.STVCLAS_student_id

               AND payment.academic_year = 8
        )

        SELECT
            spriden.SPRIDEN_student_id AS StudentID,

            CONCAT(
                spriden.SPRIDEN_surname,
                ' ',
                spriden.SPRIDEN_student_name,
                ' ',
                spriden.SPRIDEN_father_name
            ) AS name,

            class_9.STVCLAS_standard AS Class,

            class_9.STVCLAS_class AS Division,

            last_year.total_fee_8 AS LastYearFee,

            last_year.total_paid_8 AS LastYearPaid,

            last_year.balance_8 AS LastYearBalance,

            COALESCE(
                father_contact.MobileNumber,
                ''
            ) AS MobileNumber

        FROM spriden

        INNER JOIN student_class_9 class_9
            ON class_9.STVCLAS_student_id =
                spriden.id

        INNER JOIN last_year_status last_year
            ON last_year.STVCLAS_student_id =
                spriden.id

        LEFT JOIN paid_fees current_payment
            ON current_payment.sprfees_studentid =
                spriden.id

           AND current_payment.academic_year = 9

        LEFT JOIN father_contact
            ON father_contact.stvpcnf_student_id =
                spriden.id

        WHERE spriden.SPRIDEN_student_status = 'A'

          AND spriden.SPRIDEN_user_type = 'st'

          AND
          (
                LOWER(
                    TRIM(spriden.rte_status)
                ) = 'no'

                OR spriden.rte_status IS NULL

                OR TRIM(spriden.rte_status) = ''
          )

          AND last_year.balance_8 >= 0

          AND COALESCE(
                current_payment.total_paid,
                0
          ) = 0
   	 ";
	}
	public function get_last_year_balance_report(
		array $params = []
	) {
		$baseSql = $this->last_year_balance_base_sql();

		/*
		 * First page load: only return dropdown values.
		 */
		if (!empty($params['filters_only'])) {
			$filterSql = "
            SELECT
                report.Class,
                report.Division

            FROM (
                {$baseSql}
            ) report

            WHERE report.Class IS NOT NULL
              AND TRIM(report.Class) <> ''

            GROUP BY
                report.Class,
                report.Division

            ORDER BY
                report.Class,
                report.Division
        ";

			$filterRows = $this->db
				->query($filterSql)
				->result_array();

			$classes = [];
			$divisions = [];

			foreach ($filterRows as $row) {
				$class = trim(
					(string) ($row['Class'] ?? '')
				);

				$division = trim(
					(string) ($row['Division'] ?? '')
				);

				if ($class !== '') {
					$classes[$class] = $class;
				}

				if ($division !== '') {
					$divisions[$division] = $division;
				}
			}

			natcasesort($classes);
			natcasesort($divisions);

			return [
				'classes' => array_values($classes),
				'divisions' => array_values($divisions)
			];
		}

		$start = max(
			0,
			(int) ($params['start'] ?? 0)
		);

		$length = (int) ($params['length'] ?? 25);

		if ($length < 1 || $length > 100) {
			$length = 25;
		}

		$search = trim(
			(string) ($params['search'] ?? '')
		);

		$class = trim(
			(string) ($params['class'] ?? '')
		);

		$division = trim(
			(string) ($params['division'] ?? '')
		);

		$conditions = [];
		$binds = [];

		if ($search !== '') {
			$conditions[] = "
            (
                CAST(report.StudentID AS CHAR) LIKE ?
                OR LOWER(report.name) LIKE LOWER(?)
                OR report.MobileNumber LIKE ?
            )
        ";

			$searchValue = '%' . $search . '%';

			$binds[] = $searchValue;
			$binds[] = $searchValue;
			$binds[] = $searchValue;
		}

		if ($class !== '') {
			$conditions[] = "report.Class = ?";
			$binds[] = $class;
		}

		if ($division !== '') {
			$conditions[] = "report.Division = ?";
			$binds[] = $division;
		}

		$whereSql = !empty($conditions)
			? ' WHERE ' . implode(' AND ', $conditions)
			: '';

		$columnMap = [
			0 => 'report.StudentID',
			1 => 'report.StudentID',
			2 => 'report.name',
			3 => 'report.Class',
			4 => 'report.Division',
			5 => 'report.LastYearFee',
			6 => 'report.LastYearPaid',
			7 => 'report.LastYearBalance'
		];

		$orderIndex = (int) (
			$params['order_column'] ?? 3
		);

		$orderColumn = $columnMap[$orderIndex]
			?? 'report.Class';

		$orderDirection = strtolower(
			(string) (
				$params['order_direction'] ?? 'asc'
			)
		) === 'desc'
			? 'DESC'
			: 'ASC';

		/*
		 * One summary query returns:
		 * - filtered count
		 * - total fees
		 * - paid fees
		 * - balance
		 */
		$summarySql = "
        SELECT
            COUNT(*) AS records_filtered,

            COALESCE(
                SUM(report.LastYearFee),
                0
            ) AS total_fee,

            COALESCE(
                SUM(report.LastYearPaid),
                0
            ) AS total_paid,

            COALESCE(
                SUM(report.LastYearBalance),
                0
            ) AS total_balance

        FROM (
            {$baseSql}
        ) report

        {$whereSql}
   	 ";

		$summary = $this->db
			->query($summarySql, $binds)
			->row_array();

		/*
		 * When no filters are selected, filtered count is
		 * also the total count. This avoids another full query.
		 */
		$hasFilters =
			$search !== '' ||
			$class !== '' ||
			$division !== '';

		if ($hasFilters) {
			$totalSql = "
            SELECT COUNT(*) AS total_rows

            FROM (
                {$baseSql}
            ) report
        ";

			$totalRow = $this->db
				->query($totalSql)
				->row_array();

			$recordsTotal = (int) (
				$totalRow['total_rows'] ?? 0
			);
		} else {
			$recordsTotal = (int) (
				$summary['records_filtered'] ?? 0
			);
		}

		$dataSql = "
        SELECT
            report.StudentID,
            report.name,
            report.Class,
            report.Division,
            report.LastYearFee,
            report.LastYearPaid,
            report.LastYearBalance,
            report.MobileNumber

        FROM (
            {$baseSql}
        ) report

        {$whereSql}

        ORDER BY
            {$orderColumn} {$orderDirection},
            report.Division ASC,
            report.name ASC

        LIMIT {$start}, {$length}
   	 ";

		$rows = $this->db
			->query($dataSql, $binds)
			->result_array();

		$serial = $start + 1;

		foreach ($rows as &$row) {
			$row['serial'] = $serial++;

			$row['LastYearFee'] =
				(float) $row['LastYearFee'];

			$row['LastYearPaid'] =
				(float) $row['LastYearPaid'];

			$row['LastYearBalance'] =
				(float) $row['LastYearBalance'];
		}

		unset($row);

		return [
			'recordsTotal' => $recordsTotal,

			'recordsFiltered' => (int) (
				$summary['records_filtered'] ?? 0
			),

			'data' => $rows,

			'summary' => [
				'students' => (int) (
					$summary['records_filtered'] ?? 0
				),

				'totalFee' => (float) (
					$summary['total_fee'] ?? 0
				),

				'totalPaid' => (float) (
					$summary['total_paid'] ?? 0
				),

				'totalBalance' => (float) (
					$summary['total_balance'] ?? 0
				)
			]
		];
	}

	// 	private function all_previous_fees_base_sql()
	// 	{
	// 		return "
	//         WITH paid_fees AS
	//         (
	//             SELECT
	//                 sprfees.sprfees_studentid,
	//                 sprfees.academic_year,

	//                 SUM(
	//                     COALESCE(
	//                         sprfees.sprfees_amount,
	//                         0
	//                     )
	//                 ) AS total_paid,

	//                 SUM(
	//                     CASE
	//                         WHEN COALESCE(
	//                             sprfees.sprfees_exumption,
	//                             0
	//                         ) > 0
	//                         THEN sprfees.sprfees_exumption
	//                         ELSE 0
	//                     END
	//                 ) AS total_exumpted,

	//                 SUM(
	//                     COALESCE(
	//                         sprfees.sprfees_amount,
	//                         0
	//                     )
	//                 )
	//                 +
	//                 SUM(
	//                     CASE
	//                         WHEN COALESCE(
	//                             sprfees.sprfees_exumption,
	//                             0
	//                         ) > 0
	//                         THEN sprfees.sprfees_exumption
	//                         ELSE 0
	//                     END
	//                 ) AS total_adjusted_paid

	//             FROM sprfees

	//             WHERE sprfees.academic_year
	//                 BETWEEN 5 AND 8

	//             GROUP BY
	//                 sprfees.sprfees_studentid,
	//                 sprfees.academic_year
	//         ),

	//         student_class AS
	//         (
	//             SELECT
	//                 stvclas.STVCLAS_student_id,
	//                 stvclas.STVCLAS_acdm,
	//                 stvclas.STVCLAS_standard,

	//                 MAX(
	//                     stvclas.STVCLAS_class
	//                 ) AS STVCLAS_class

	//             FROM stvclas

	//             WHERE stvclas.STVCLAS_acdm
	//                 BETWEEN 5 AND 8

	//             GROUP BY
	//                 stvclas.STVCLAS_student_id,
	//                 stvclas.STVCLAS_acdm,
	//                 stvclas.STVCLAS_standard
	//         ),

	//         father_contact AS
	//         (
	//             SELECT
	//                 stvpcnf.stvpcnf_student_id,

	//                 COALESCE(
	//                     MAX(
	//                         NULLIF(
	//                             TRIM(
	//                                 stvpcnf.stvpcnf_whatsapp
	//                             ),
	//                             ''
	//                         )
	//                     ),
	//                     MAX(
	//                         NULLIF(
	//                             TRIM(
	//                                 stvpcnf.stvpcnf_mobile
	//                             ),
	//                             ''
	//                         )
	//                     ),
	//                     ''
	//                 ) AS MobileNumber

	//             FROM stvpcnf

	//             WHERE LOWER(
	//                 TRIM(
	//                     stvpcnf.stvpcnf_parent_typ
	//                 )
	//             ) = 'father'

	//             GROUP BY
	//                 stvpcnf.stvpcnf_student_id
	//         ),

	//         pending_fee_details AS
	//         (
	//             SELECT
	//                 spriden.id,

	//                 spriden.SPRIDEN_student_id,

	//              TRIM(
	//     CONCAT_WS(
	//         ' ',
	//         NULLIF(
	//             TRIM(spriden.SPRIDEN_surname),
	//             ''
	//         ),
	//         NULLIF(
	//             TRIM(spriden.SPRIDEN_student_name),
	//             ''
	//         ),
	//         NULLIF(
	//             TRIM(spriden.SPRIDEN_father_name),
	//             ''
	//         )
	//     )
	// ) AS name,

	//                 stvacdm.stvacdm_id,
	//                 stvacdm.stvacdm_year,

	//                 student_class.STVCLAS_standard,
	//                 student_class.STVCLAS_class,

	//                 COALESCE(
	//                     stvfstr.stvfstr_fees,
	//                     0
	//                 ) AS total_fee,

	//                 COALESCE(
	//                     paid_fees.total_adjusted_paid,
	//                     0
	//                 ) AS total_paid,

	//                 COALESCE(
	//                     stvfstr.stvfstr_fees,
	//                     0
	//                 )
	//                 -
	//                 COALESCE(
	//                     paid_fees.total_adjusted_paid,
	//                     0
	//                 ) AS pending_fee

	//             FROM spriden

	//             INNER JOIN student_class
	//                 ON student_class.STVCLAS_student_id =
	//                     spriden.id

	//             INNER JOIN stvacdm
	//                 ON stvacdm.stvacdm_id =
	//                     student_class.STVCLAS_acdm

	//             INNER JOIN stvfstr
	//                 ON stvfstr.stvfstr_std =
	//                     student_class.STVCLAS_standard

	//                AND stvfstr.stvfstr_acdm_year =
	//                     student_class.STVCLAS_acdm

	//             LEFT JOIN paid_fees
	//                 ON paid_fees.sprfees_studentid =
	//                     spriden.id

	//                AND paid_fees.academic_year =
	//                     student_class.STVCLAS_acdm

	//             WHERE spriden.SPRIDEN_student_status = 'A'

	//               AND spriden.SPRIDEN_user_type = 'st'

	//               AND
	//               (
	//                     LOWER(
	//                         TRIM(
	//                             spriden.rte_status
	//                         )
	//                     ) = 'no'

	//                     OR spriden.rte_status IS NULL

	//                     OR TRIM(
	//                         spriden.rte_status
	//                     ) = ''
	//               )

	//               AND stvacdm.stvacdm_id
	//                     BETWEEN 5 AND 8
	//         ),

	//         student_pending AS
	//         (
	//             SELECT
	//                 details.id,

	//                 details.SPRIDEN_student_id,

	//                 details.name,

	//                 COALESCE(
	//                     father_contact.MobileNumber,
	//                     ''
	//                 ) AS MobileNumber,

	//                 MAX(
	//                     CASE
	//                         WHEN details.stvacdm_id = 5
	//                          AND details.pending_fee > 0
	//                         THEN ROUND(
	//                             details.pending_fee,
	//                             0
	//                         )
	//                         ELSE 0
	//                     END
	//                 ) AS Pending_5,

	//                 MAX(
	//                     CASE
	//                         WHEN details.stvacdm_id = 6
	//                          AND details.pending_fee > 0
	//                         THEN ROUND(
	//                             details.pending_fee,
	//                             0
	//                         )
	//                         ELSE 0
	//                     END
	//                 ) AS Pending_6,

	//                 MAX(
	//                     CASE
	//                         WHEN details.stvacdm_id = 7
	//                          AND details.pending_fee > 0
	//                         THEN ROUND(
	//                             details.pending_fee,
	//                             0
	//                         )
	//                         ELSE 0
	//                     END
	//                 ) AS Pending_7,

	//                 MAX(
	//                     CASE
	//                         WHEN details.stvacdm_id = 8
	//                          AND details.pending_fee > 0
	//                         THEN ROUND(
	//                             details.pending_fee,
	//                             0
	//                         )
	//                         ELSE 0
	//                     END
	//                 ) AS Pending_8

	//             FROM pending_fee_details details

	//             LEFT JOIN father_contact
	//                 ON father_contact.stvpcnf_student_id =
	//                     details.id

	//             GROUP BY
	//                 details.id,
	//                 details.SPRIDEN_student_id,
	//                 details.name,
	//                 father_contact.MobileNumber
	//         )

	//         SELECT
	//             student_pending.SPRIDEN_student_id,
	//             student_pending.name,
	//             student_pending.MobileNumber,
	//             student_pending.Pending_5,
	//             student_pending.Pending_6,
	//             student_pending.Pending_7,
	//             student_pending.Pending_8,

	//             (
	//                 student_pending.Pending_5
	//                 +
	//                 student_pending.Pending_6
	//                 +
	//                 student_pending.Pending_7
	//                 +
	//                 student_pending.Pending_8
	//             ) AS TotalPending

	//         FROM student_pending

	//         WHERE
	//             student_pending.Pending_5 > 0
	//             OR student_pending.Pending_6 > 0
	//             OR student_pending.Pending_7 > 0
	//             OR student_pending.Pending_8 > 0
	//    	 	";
	// 	}
	// 	public function get_all_previous_fees_report(array $params = [])
	// 	{
	// 		$baseSql = $this->all_previous_fees_base_sql();

	// 		$academicYears = array_values(array_filter(
	// 			$this->get_fees_balance_academic_years(),
	// 			static function ($year) {
	// 				$yearId = (int) ($year['academic_year_id'] ?? 0);

	// 				return $yearId >= 5 && $yearId <= 8;
	// 			}
	// 		));

	// 		$start = max(0, (int) ($params['start'] ?? 0));
	// 		$length = (int) ($params['length'] ?? 20);

	// 		if (!in_array($length, [20, 50, 100], true)) {
	// 			$length = 20;
	// 		}

	// 		$search = trim((string) ($params['search'] ?? ''));

	// 		$conditions = [];
	// 		$binds = [];

	// 		if ($search !== '') {
	// 			/*
	// 			 * Prefix search is faster than LIKE '%value%'.
	// 			 */
	// 			$searchValue = $search . '%';
	// 			$mobileDigits = preg_replace('/[^0-9]/', '', $search);

	// 			$searchParts = [
	// 				'report.SPRIDEN_student_id LIKE ?',
	// 				'report.name LIKE ?',
	// 			];

	// 			$binds[] = $searchValue;
	// 			$binds[] = $searchValue;

	// 			if ($mobileDigits !== '') {
	// 				$searchParts[] = 'report.MobileNumber LIKE ?';
	// 				$binds[] = $mobileDigits . '%';
	// 			}

	// 			$conditions[] = '(' . implode(' OR ', $searchParts) . ')';
	// 		}

	// 		$whereSql = $conditions
	// 			? ' WHERE ' . implode(' AND ', $conditions)
	// 			: '';

	// 		/*
	// 		 * This query returns count and totals together.
	// 		 * No separate total-count query is required.
	// 		 */
	// 		$summarySql = "
	//         SELECT
	//             COUNT(*) AS students,
	//             COALESCE(SUM(report.Pending_5), 0) AS pending_5,
	//             COALESCE(SUM(report.Pending_6), 0) AS pending_6,
	//             COALESCE(SUM(report.Pending_7), 0) AS pending_7,
	//             COALESCE(SUM(report.Pending_8), 0) AS pending_8,
	//             COALESCE(SUM(report.TotalPending), 0) AS total_pending

	//         FROM ({$baseSql}) report

	//         {$whereSql}
	//     ";

	// 		$summary = $this->db
	// 			->query($summarySql, $binds)
	// 			->row_array();

	// 		$recordsFiltered = (int) ($summary['students'] ?? 0);

	// 		/*
	// 		 * Use bound values for LIMIT instead of directly inserting variables.
	// 		 */
	// 		$dataSql = "
	//         SELECT
	//             report.SPRIDEN_student_id,
	//             report.name,
	//             report.MobileNumber,
	//             report.Pending_5,
	//             report.Pending_6,
	//             report.Pending_7,
	//             report.Pending_8,
	//             report.TotalPending

	//         FROM ({$baseSql}) report

	//         {$whereSql}

	//         ORDER BY
	//             report.SPRIDEN_student_id ASC,
	//             report.name ASC

	//         LIMIT ?, ?
	//     ";

	// 		$dataBinds = $binds;
	// 		$dataBinds[] = $start;
	// 		$dataBinds[] = $length;

	// 		$rows = $this->db
	// 			->query($dataSql, $dataBinds)
	// 			->result_array();

	// 		$serial = $start + 1;

	// 		foreach ($rows as &$row) {
	// 			$row['serial'] = $serial++;

	// 			$row['Pending_5'] = (float) ($row['Pending_5'] ?? 0);
	// 			$row['Pending_6'] = (float) ($row['Pending_6'] ?? 0);
	// 			$row['Pending_7'] = (float) ($row['Pending_7'] ?? 0);
	// 			$row['Pending_8'] = (float) ($row['Pending_8'] ?? 0);
	// 			$row['TotalPending'] = (float) ($row['TotalPending'] ?? 0);
	// 		}

	// 		unset($row);

	// 		$totalPending = (float) ($summary['total_pending'] ?? 0);

	// 		return [
	// 			'recordsTotal' => $recordsFiltered,
	// 			'recordsFiltered' => $recordsFiltered,
	// 			'data' => $rows,
	// 			'academicYears' => $academicYears,

	// 			'summary' => [
	// 				'students' => $recordsFiltered,
	// 				'academicYears' => count($academicYears),
	// 				'totalPending' => $totalPending,

	// 				'averagePending' => $recordsFiltered > 0
	// 					? $totalPending / $recordsFiltered
	// 					: 0,

	// 				'yearTotals' => [
	// 					5 => (float) ($summary['pending_5'] ?? 0),
	// 					6 => (float) ($summary['pending_6'] ?? 0),
	// 					7 => (float) ($summary['pending_7'] ?? 0),
	// 					8 => (float) ($summary['pending_8'] ?? 0),
	// 				],
	// 			],
	// 		];
	// 	}


	public function export_all_previous_fees_report(array $params = [])
	{
		$baseSql = $this->all_previous_fees_base_sql();

		$search = trim((string) ($params['search'] ?? ''));

		$conditions = [];
		$binds = [];

		/*
		 * SEARCH
		 * --------------------------------------------------
		 * Student ID
		 * Student Name
		 * Mobile Number
		 */
		if ($search !== '') {

			$searchValue = $search . '%';

			$mobileDigits = preg_replace(
				'/[^0-9]/',
				'',
				$search
			);

			$searchParts = [
				'report.SPRIDEN_student_id LIKE ?',
				'report.name LIKE ?',
			];

			$binds[] = $searchValue;
			$binds[] = $searchValue;

			if ($mobileDigits !== '') {

				$searchParts[] =
					'report.MobileNumber LIKE ?';

				$binds[] =
					$mobileDigits . '%';
			}

			$conditions[] =
				'(' . implode(' OR ', $searchParts) . ')';
		}

		$whereSql = $conditions
			? ' WHERE ' . implode(' AND ', $conditions)
			: '';

		/*
		 * GET ALL FILTERED RECORDS
		 *
		 * IMPORTANT:
		 * No LIMIT / OFFSET here.
		 * Excel should contain complete filtered data.
		 */
		$sql = "
        SELECT
            report.SPRIDEN_student_id,
            report.name,
            report.MobileNumber,

            report.Pending_5,
            report.Pending_6,
            report.Pending_7,
            report.Pending_8,

            report.TotalPending,
            report.PendingYearCount

        FROM
        (
            {$baseSql}
        ) report

        {$whereSql}

        ORDER BY
            report.PendingYearCount DESC,
            report.TotalPending DESC,
            report.SPRIDEN_student_id ASC,
            report.name ASC
    ";

		$query = $this->db->query(
			$sql,
			$binds
		);

		return $query->result_array();
	}

	public function export_last_year_fees_report(array $params = [])
	{
		$baseSql = $this->last_year_fees_base_sql();

		$search = trim((string) ($params['search'] ?? ''));

		$conditions = [];
		$binds = [];

		/*
		 * --------------------------------------------------
		 * SEARCH FILTER
		 * Student ID / Name / Mobile
		 * --------------------------------------------------
		 */
		if ($search !== '') {

			$searchValue = $search . '%';

			$mobileDigits = preg_replace(
				'/[^0-9]/',
				'',
				$search
			);

			$searchParts = [
				'report.SPRIDEN_student_id LIKE ?',
				'report.name LIKE ?',
			];

			$binds[] = $searchValue;
			$binds[] = $searchValue;

			if ($mobileDigits !== '') {

				$searchParts[] =
					'report.MobileNumber LIKE ?';

				$binds[] =
					$mobileDigits . '%';
			}

			$conditions[] =
				'(' . implode(' OR ', $searchParts) . ')';
		}

		$whereSql = $conditions
			? ' WHERE ' . implode(' AND ', $conditions)
			: '';

		/*
		 * --------------------------------------------------
		 * GET ALL FILTERED DATA
		 *
		 * IMPORTANT:
		 * No LIMIT / OFFSET
		 * because Excel should contain
		 * complete filtered report.
		 * --------------------------------------------------
		 */
		$sql = "
        SELECT
            report.SPRIDEN_student_id,
            report.name,
            report.MobileNumber,
            report.Academic_year,
            report.Total_fee,
            report.Total_paid,
            report.Total_exumpted,
            report.Total_paid_icluding_exumption,
            report.Pending_fees

        FROM
        (
            {$baseSql}
        ) report

        {$whereSql}

        ORDER BY
            report.SPRIDEN_student_id ASC
    ";

		$query = $this->db->query(
			$sql,
			$binds
		);

		return $query->result_array();
	}


	public function export_current_zero_paid_report(array $params = [])
	{
		$baseSql = $this->current_zero_paid_base_sql();

		$search = trim(
			(string) ($params['search'] ?? '')
		);

		$conditions = [];
		$binds = [];

		/*
		 * --------------------------------------------------
		 * SEARCH BY:
		 * Student ID
		 * Student Name
		 * Mobile
		 * --------------------------------------------------
		 */
		if ($search !== '') {

			$searchValue = $search . '%';

			$mobileDigits = preg_replace(
				'/[^0-9]/',
				'',
				$search
			);

			$searchParts = [
				'report.StudentID LIKE ?',
				'report.name LIKE ?',
			];

			$binds[] = $searchValue;
			$binds[] = $searchValue;

			if ($mobileDigits !== '') {

				$searchParts[] =
					'report.MobileNumber LIKE ?';

				$binds[] =
					$mobileDigits . '%';
			}

			$conditions[] =
				'(' . implode(' OR ', $searchParts) . ')';
		}

		$whereSql = !empty($conditions)
			? ' WHERE ' . implode(' AND ', $conditions)
			: '';

		/*
		 * --------------------------------------------------
		 * GET ALL FILTERED RECORDS
		 *
		 * IMPORTANT:
		 * No LIMIT / OFFSET.
		 *
		 * Excel should contain all matching students,
		 * not only the current page.
		 * --------------------------------------------------
		 */
		$sql = "
        SELECT
            report.StudentID,
            report.name,
            report.Class,
            report.Division,
            report.MobileNumber,
            report.TotalPaid,
            report.TotalFee,
            report.PendingFee

        FROM ({$baseSql}) report

        {$whereSql}

        ORDER BY
            report.Class ASC,
            report.Division ASC,
            report.name ASC,
            report.StudentID ASC
    ";

		$query = $this->db->query(
			$sql,
			$binds
		);

		return $query->result_array();
	}
}
