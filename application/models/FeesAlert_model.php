<?php



class FeesAlert_model extends CI_Model
{

	public function get_all_academic_years()
	{
		return $this->db->select('stvacdm.stvacdm_id, stvacdm.stvacdm_year')
			->from('stvacdm')
			->order_by('stvacdm_year', 'DESC')
			->get()
			->result_array();
	}

	function getAllClasses()
	{
		return $this->db->get('stvfees')->result_array();
	}

	function get_fees_report_for_alert($year)
	{

		return $this->db->query("Select concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name)as name,

		sum(sprfees.sprfees_amount)as total_paid,

		max(sprfees.sprfees_due_date)as due_date,

		10000 - sum(sprfees.sprfees_amount)as balance,

		spriden.id,

		spriden.SPRIDEN_student_id,

		stvclas.STVCLAS_standard,

		stvclas.STVCLAS_class,

        stvpcnf.stvpcnf_whatsapp Whatsapp

		from sprfees, stvclas,spriden, stvpcnf where spriden.id = stvclas.STVCLAS_student_id

		and spriden.id = sprfees.sprfees_studentid and sprfees.academic_year = " . $year . " and sprfees.sprfees_amount != 0 

        and spriden.id = stvpcnf.stvpcnf_student_id

		group by 

		name,

		spriden.id,

		spriden.SPRIDEN_student_id,

		stvclas.STVCLAS_standard ,

		stvclas.STVCLAS_class

        

    union 

    

    Select concat(SPRIDEN_surname, ' ', SPRIDEN_student_name, ' ', SPRIDEN_father_name)as name,

		sum(sprfees.sprfees_amount)as total_paid,

		max(sprfees.sprfees_due_date)as due_date,

		10000 - sum(sprfees.sprfees_amount)as balance,

		spriden.id,

		spriden.SPRIDEN_student_id,

		stvclas.STVCLAS_standard,

		stvclas.STVCLAS_class,'' whatsapp

		from sprfees, stvclas,spriden where spriden.id = stvclas.STVCLAS_student_id

		and spriden.id = sprfees.sprfees_studentid and sprfees.academic_year = " . $year . " and sprfees.sprfees_amount != 0 

        and spriden.id not in (select distinct stvpcnf_student_id from stvpcnf)

		group by 

		name,

		spriden.id,

		spriden.SPRIDEN_student_id,

		stvclas.STVCLAS_standard ,

		stvclas.STVCLAS_class;")->result_array();

	}



	function messageTemplates()
	{

		return $this->db->query("select * from stvmsgt order by 1 desc")->result_array();

	}



	function message_template($id)
	{

		return $this->db->query("select * from stvmsgt where stvmsgt_id = " . $id . "")->row_array();

	}

	public function admission_confirmation($year, $class, $div)
	{
		$sql = "SELECT
    			s.id,
    			s.SPRIDEN_student_id,
    			s.SPRIDEN_student_name,
    			s.SPRIDEN_father_name,
    			s.SPRIDEN_surname,
    			c.STVCLAS_rollno,
    			c.STVCLAS_standard,
    			c.STVCLAS_class,
    			c.STVCLAS_acdm,
    		IFNULL(f.stvfstr_fees, 0) AS total_fees_to_be_paid,
    		IFNULL(p.total_paid, 0) AS total_fees_paid_by_st,
    		(IFNULL(f.stvfstr_fees, 0) - IFNULL(p.total_paid, 0)) AS remaining_fees_to_be_paid,
    			pc.stvpcnf_whatsapp,
    		(
        		SELECT GROUP_CONCAT(
            		CONCAT(
                		c2.STVCLAS_acdm,
                		' -> ',
                		IFNULL(a.stvacdm_year, ''),
                		' -> ',
                		IFNULL(p2.total_paid, 0),
                		' -> ',
                		IFNULL(f2.total_fees, 0),
                		' -> ',
                		(IFNULL(f2.total_fees, 0) - IFNULL(p2.total_paid, 0))
            		)
            	ORDER BY CAST(c2.STVCLAS_acdm AS UNSIGNED) DESC
            	SEPARATOR ', '
        	)
        	FROM stvclas c2
        	LEFT JOIN stvacdm a
            ON a.stvacdm_id = c2.STVCLAS_acdm
        	LEFT JOIN
        	(
        	    SELECT
        	        stvfstr_std,
        	        stvfstr_acdm_year,
        	        SUM(IFNULL(stvfstr_fees, 0)) AS total_fees
        	    FROM stvfstr
        	    WHERE stvfstr_acdm_year NOT IN ('1', '2', '3', '4')
        	    GROUP BY
        	        stvfstr_std,
        	        stvfstr_acdm_year
        	) f2
        	    ON f2.stvfstr_std = c2.STVCLAS_standard
        	    AND f2.stvfstr_acdm_year = c2.STVCLAS_acdm
        	LEFT JOIN
        	(
        	    SELECT
        	        sprfees_studentid,
        	        academic_year,
        	        SUM(IFNULL(sprfees_amount, 0)) AS total_paid
        	    FROM sprfees
        	    WHERE academic_year NOT IN ('1', '2', '3', '4')
        	    GROUP BY
        	        sprfees_studentid,
        	        academic_year
        	) p2
        	    ON p2.sprfees_studentid = c2.STVCLAS_student_id
        	    AND p2.academic_year = c2.STVCLAS_acdm
        	WHERE c2.STVCLAS_student_id = s.id
          	AND c2.STVCLAS_acdm NOT IN ('1', '2', '3', '4')
    		) AS balance_installment
		FROM spriden s
		INNER JOIN stvclas c
    		ON c.STVCLAS_student_id = s.id
		LEFT JOIN stvfstr f
    		ON f.stvfstr_std = c.STVCLAS_standard
    		AND f.stvfstr_acdm_year = '$year'
		LEFT JOIN
		(
		    SELECT
		        sprfees_studentid,
		        SUM(IFNULL(sprfees_amount, 0)) AS total_paid
		    FROM sprfees
		    WHERE academic_year = '$year'
		    GROUP BY sprfees_studentid
		) p
		    ON p.sprfees_studentid = s.id
		LEFT JOIN stvpcnf pc
		    ON pc.stvpcnf_student_id = s.id
		WHERE
		    c.STVCLAS_standard = '$class'
		    AND c.STVCLAS_class = '$div'
		    AND c.STVCLAS_acdm = '$year'
		    AND s.spriden_student_status = 'A'
		    AND (s.rte_status IS NULL OR s.rte_status = 'No')
		    AND (IFNULL(f.stvfstr_fees, 0) - IFNULL(p.total_paid, 0)) > 0

		ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED);";
		return $this->db->query($sql)->result_array();
	}

}

