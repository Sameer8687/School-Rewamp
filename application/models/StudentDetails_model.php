<?php

class StudentDetails_model extends CI_Model

{



    // get personal information



    function getPersonalDetails($id)

    {

        return $this->db->query("select sp.*,(select distinct lc_status from lc_status where student_id= sp.id)lc_status  from spriden sp  where sp.id  = " . $id . "")->row_array();
    }

    function getRteDetails($id)

    {

        return $this->db->query("select  ifnull(rte_status,'No') as status from spriden where id = " . $id . "")->row_array();
    }

    function RteStatus()

    {

        return $this->db->query("select  distinct ifnull(rte_status,'No') as status from spriden")->result_array();
    }



    //get class details

    function getClassDetails($id, $acdm_year)

    {

        return $this->db->query("SELECT distinct STVCLAS_standard FROM stvclas where STVCLAS_acdm = $acdm_year and STVCLAS_student_id = $id")->row_array();
    }

    function admission_class($id)

    {

        return $this->db->query("SELECT 	
		* FROM spriden WHERE id = $id ")->row_array();
    }



    // get all fees records

    function fees_records($id)

    {

        return $this->db->query("select sprfees.*,stvacdm.stvacdm_year  from sprfees,stvacdm where sprfees_studentid = " . $id . "

        and sprfees.academic_year = stvacdm.stvacdm_id")->result_array();
    }



    // balance fees

    function balance_fees($id)

    {

        $acdm_year = $this->db->query("select stvschool_acdm_year from stvschool")->row_array();

        $year = $acdm_year['stvschool_acdm_year'];

        return $this->db->query("select 10000 - sum(sprfees_amount) total from sprfees where  sprfees_studentid = " . $id . " and academic_year = " . $year . "")->row_array();
    }

    //get contact details

    function getContactDetails($id)

    {

        return $this->db->query("select * from stvpcnf where stvpcnf_student_id = " . $id . "")->row_array();
    }

    function studentListAll()
    {

        return $this->db->query("select distinct id, concat(`SPRIDEN_surname`, ' ', `SPRIDEN_student_name`, ' ', `SPRIDEN_father_name` ) as name from spriden where SPRIDEN_user_type = 'st'")->result_array();
    }
}
