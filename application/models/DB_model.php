<?php
class DB_model extends CI_model
{
  function get_all_academic_years()
  {
    $sql = "select stvacdm_id, stvacdm_year from stvacdm";
    $sql = $this->db->query($sql)->result_array();
    return $sql;
  }

  function date()
  {
    $sql = "select stvacdm_start, stvacdm_end,stvacdm_year from stvacdm where stvacdm_id=(select stvschool_acdm_year from stvschool)";
    $sql = $this->db->query($sql)->row_array();
    return $sql;
  }

  function Total()
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $sql = "select count(stvfstr_fees) total_students, sum(stvfstr_fees) total_fees from ( select distinct s.id, c.STVCLAS_standard,fs.stvfstr_fees
    from spriden s, stvclas c,stvfstr fs
    where s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A'
    and fs.stvfstr_acdm_year = c.STVCLAS_acdm and fs.stvfstr_std = c.STVCLAS_standard
    and c.STVCLAS_student_id = s.id and c.STVCLAS_acdm = '$year' ) as temp";
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
  }

  function Paid()
  {
    // $academic_data = $schoolDB->query("select stvschool_acdm_year from stvschool")->row_array();
    // $academic_year = 'stvschool_acdm_year';
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $sql = "select sum(sprfees_amount) total_fees_paid from sprfees where academic_year = '$year'";
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
  }

  function classwise_chart($year)
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
        order by 1 desc";
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
  }

  function monthwise_chart()
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $month = 1;
    $curr_year = date('Y');
    $sql = "select
    distinct count(c.STVCLAS_student_id) total_active_students,
    st.stvfstr_std,
    st.stvfstr_fees,
    (count(c.STVCLAS_student_id) * st.stvfstr_fees) total_fees,
    ifnull((
            select
                sum(f.sprfees_amount) fees_paid
            from
                sprfees f,
                spriden s
            where
                f.academic_year = '$year'
                and f.sprfees_studentid = s.id
                and s.spriden_student_status = 'A'
                and s.SPRIDEN_user_type = 'st'
                and s.rte_status = 'no'
                and f.sprfees_standard = c.STVCLAS_standard  
          ),0)
           fees_paid,
    CASE
        WHEN st.stvfstr_std = 'Nursery' THEN 0
        WHEN st.stvfstr_std = 'Jr.Kg.' THEN 1
        WHEN st.stvfstr_std = 'Sr.Kg.' THEN 2
        WHEN st.stvfstr_std = 'I' THEN 3
        WHEN st.stvfstr_std = 'II' THEN 4
        WHEN st.stvfstr_std = 'III' THEN 5
        WHEN st.stvfstr_std = 'IV' THEN 6
        WHEN st.stvfstr_std = 'V' THEN 7
        WHEN st.stvfstr_std = 'VI' THEN 8
        WHEN st.stvfstr_std = 'VII' THEN 9
        WHEN st.stvfstr_std = 'VIII' THEN 10
        WHEN st.stvfstr_std = 'IX' THEN 11
        WHEN st.stvfstr_std = 'X' THEN 12
        WHEN st.stvfstr_std = 'XI' THEN 13
    END as seq_class 
from
    stvclas c,
    spriden s,
    stvfstr st
where
    c.STVCLAS_acdm = '$year'
    and c.STVCLAS_student_id = s.id
    and s.SPRIDEN_user_type = 'st'
    and s.spriden_student_status = 'A'
    and s.rte_status = 'no'
    and c.STVCLAS_standard = st.stvfstr_std
    and st.stvfstr_acdm_year = '$year'
    and st.stvfstr_acdm_year = c.STVCLAS_acdm
group by
    st.stvfstr_std,
    st.stvfstr_fees
order by
    seq_class";
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
  }

  function fetch_class()
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $type = $this->input->get('type');
    if ($type == 'All') {
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
      $sql = $this->db->query($sql)->result_array();
    } else if ($type == 'RTE') {
      $sql = "SELECT SPRIDEN_student_id AS student_id,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS student_name,
        c.STVCLAS_standard AS standard
        FROM spriden s
        JOIN stvclas c ON s.id = c.STVCLAS_student_id
        WHERE s.spriden_student_status = 'A'
          AND s.SPRIDEN_user_type = 'st'
          AND s.rte_status = 'Yes';";
      $sql = $this->db->query($sql)->result_array();
    } else if ($type == 'T') {
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

      $sql = $this->db->query($sql)->result_array();
    } else {
      $sql = '';
    }
    return $sql;
  }

  function fetch_data($year)
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $std = $this->input->post('std');
    $type = $this->input->post('type');
    if ($type == 'All') {
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
        ORDER BY c.STVCLAS_standard;";
    } else if ($type == 'RTE') {
      //For RTE STudent Query
    } else {
      $sql = "SELECT DISTINCT s.SPRIDEN_student_id,
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
        ORDER BY c.STVCLAS_standard;";
    }
    return $this->db->query($sql)->result_array();
  }

  function fetch_stud_id($id)
  {
    $sql = " select id , CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name
    from spriden s where spriden_student_id='$id';";
    return $this->db->query($sql)->row_array();
  }
  // function fetchs_name($id)
  // {
  //   $sql = "select id  from spriden where spriden_student_id='$id'";
  //   return $this->db->query($sql)->row_array();
  // }

  function student_dataChart($year)
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $std = $this->input->get('std');
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
    return $this->db->query($sql)->result_array();
  }

  function fetch_rte()
  {
    $sql = "select count(s.rte_status) rte_stuents from spriden s where 
        s.spriden_student_status = 'A'
        and s.SPRIDEN_user_type = 'st'
and s.rte_status='Yes';";
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
  }

  function Total_rte()
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $sql = "select count(*) total_students
    from spriden s, stvclas c
    where s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A' and s.rte_status = 'Yes'
    and c.STVCLAS_student_id = s.id and c.STVCLAS_acdm = '$year'";
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
  }

  function student_fetch($year)
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $sql = "SELECT s.id, CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', SPRIDEN_father_name) AS name,
        c.STVCLAS_standard, f.stvfstr_std
 FROM spriden s, stvclas c, stvfstr f
 WHERE s.SPRIDEN_user_type = 'st' 
   AND s.spriden_student_status = 'A' 
   AND s.id = c.STVCLAS_student_id 
   AND c.STVCLAS_acdm = $year
   AND c.STVCLAS_standard = f.stvfstr_std
   AND f.stvfstr_acdm_year = c.STVCLAS_acdm;";
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
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

  function monthfilter_old($year)
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $req = $this->input->post('request');
    if ($req == 1) {
      $sql = "SELECT distinct count(c.STVCLAS_student_id) total_active_students, st.stvfstr_std,st.stvfstr_fees,
      (count(c.STVCLAS_student_id) * st.stvfstr_fees) total_fees,
  
      (SELECT sum(f.sprfees_amount) fees_paid from sprfees f, spriden s
      where f.academic_year = '$year'
      and f.sprfees_studentid = s.id
      and s.spriden_student_status = 'A'
      and s.SPRIDEN_user_type = 'st'
         and MONTH(sprfees_feesdate) ='$req'
      and f.sprfees_standard = c.STVCLAS_standard) fees_paid
        from stvclas c, spriden s, stvfstr st
      where c.STVCLAS_acdm = '$year'
      and c.STVCLAS_student_id = s.id and s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A'
      and c.STVCLAS_standard = st.stvfstr_std and st.stvfstr_acdm_year = '$year' and st.stvfstr_acdm_year = c.STVCLAS_acdm
          group by st.stvfstr_std,st.stvfstr_fees
      order by 1 desc;";
    } else if ($req == 6) {

      $sql = "SELECT distinct count(c.STVCLAS_student_id) total_active_students, st.stvfstr_std,st.stvfstr_fees,
        (count(c.STVCLAS_student_id) * st.stvfstr_fees) total_fees,

        (SELECT sum(f.sprfees_amount) fees_paid from sprfees f, spriden s
        where f.academic_year = '$year'
        and f.sprfees_studentid = s.id
        and s.spriden_student_status = 'A'
        and s.SPRIDEN_user_type = 'st'
        and YEAR(f.sprfees_feesdate) = '2023'
        and MONTH(f.sprfees_feesdate)= '$req'
    and f.sprfees_standard = c.STVCLAS_standard) fees_paid
        from stvclas c, spriden s, stvfstr st
        where c.STVCLAS_acdm = '$year'
        and c.STVCLAS_student_id = s.id and s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A'
        and c.STVCLAS_standard = st.stvfstr_std and st.stvfstr_acdm_year = '$year' and st.stvfstr_acdm_year = c.STVCLAS_acdm
        group by st.stvfstr_std,st.stvfstr_fees
        order by 1 desc;";
    } else {
      $sql = "SELECT distinct count(c.STVCLAS_student_id) total_active_students, st.stvfstr_std,st.stvfstr_fees,
      (count(c.STVCLAS_student_id) * st.stvfstr_fees) total_fees,
      (SELECT sum(f.sprfees_amount) fees_paid from sprfees f, spriden s
      where f.academic_year = '$year'
      and f.sprfees_studentid = s.id
      and s.spriden_student_status = 'A'
      and s.SPRIDEN_user_type = 'st'
          and MONTH(f.sprfees_feesdate) = '$req'
    and f.sprfees_standard = c.STVCLAS_standard) fees_paid
      from stvclas c, spriden s, stvfstr st
      where c.STVCLAS_acdm = '$year'
      and c.STVCLAS_student_id = s.id and s.SPRIDEN_user_type = 'st' and s.spriden_student_status = 'A'
      and c.STVCLAS_standard = st.stvfstr_std and st.stvfstr_acdm_year = '$year' and st.stvfstr_acdm_year = c.STVCLAS_acdm
      group by st.stvfstr_std,st.stvfstr_fees
      order by 1 desc;";
    }
    $sqls = $this->db->query($sql)->result_array();
    return $sqls;
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
      return $this->db->query("SELECT total_students, total_paid, STVCLAS_standard, (stvfstr_fees * total_students) class_fees from (SELECT count(temp.id) total_students, sum(temp.total_paid) total_paid,temp.STVCLAS_standard, f.stvfstr_fees
      from (SELECT s.id, c.STVCLAS_standard,sum(sprfees_amount) total_paid
      from spriden s, stvclas c, sprfees sf
      where s.spriden_student_status = 'A' and s.SPRIDEN_user_type = 'st' and (s.rte_status is null or s.rte_status = 'No')
      and c.STVCLAS_student_id = s.id and c.STVCLAS_acdm in (SELECT stvschool_acdm_year from stvschool)
      and sf.sprfees_studentid = s.id 
      and sf.sprfees_studentid = c.STVCLAS_student_id
      and sf.sprfees_feesdate between '$start_date' AND '$end_date'
      group by s.id) as temp, stvfstr f
      where f.stvfstr_std = temp.STVCLAS_standard and f.stvfstr_acdm_year in (SELECT stvschool_acdm_year from stvschool)
      group by temp.STVCLAS_standard) as temp")->result_array();
    }
  }

  function filter($from, $to)
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $sql = "SELECT distinct count(c.STVCLAS_student_id) total_active_students, st.stvfstr_std,st.stvfstr_fees,
      (count(c.STVCLAS_student_id) * st.stvfstr_fees) total_fees,

      (SELECT sum(f.sprfees_amount) fees_paid from sprfees f, spriden s
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

  function student_details($student_id, $acdm_year)
  {
    return $this->db->query("SELECT sprfees.*, stvacdm.stvacdm_year, spruser.spruser_name,
  	(SELECT exumption from sprexumtion where exu_studentid = sprfees.sprfees_studentid and acdm_year  = '$acdm_year') exumption
  	from sprfees, stvacdm , spruser
  	where stvacdm.stvacdm_id = sprfees.academic_year and sprfees.sprfees_studentid = '$student_id'
  	and sprfees.sprfees_recivedby = spruser.spruser_id
       	order by stvacdm.stvacdm_year desc")->result_array();
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

  function chart8filter($from, $to)
  {
    $q = $this->db->query("SELECT stvschool_acdm_year FROM stvschool")->row_array();
    $year = $q['stvschool_acdm_year'];
    $sql = "SELECT 
    COUNT(id) AS total_students, 
      SUM(stvfstr_fees) AS total_fees, 
      ( 
        SELECT SUM(fees_paid) 
        FROM ( 
          SELECT SUM(f.sprfees_amount) AS fees_paid 
          FROM sprfees f 
          INNER JOIN spriden s ON f.sprfees_studentid = s.id 
          INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id 
          INNER JOIN stvfstr st ON c.STVCLAS_standard = st.stvfstr_std 
          WHERE s.SPRIDEN_user_type = 'st' 
            AND s.spriden_student_status = 'A' 
            AND c.STVCLAS_acdm = '$year' 
            AND YEAR(f.sprfees_feesdate) = '2023' 
            AND f.sprfees_feesdate BETWEEN '$from' AND '$to' 
            AND f.sprfees_standard = st.stvfstr_std 
            AND f.academic_year = st.stvfstr_acdm_year 
          GROUP BY f.sprfees_standard 
        ) AS subquery 
      ) AS fees_paid 
    FROM ( 
      SELECT 
        s.id, 
        c.STVCLAS_standard, 
        f.stvfstr_fees, 
        f.stvfstr_std 
      FROM spriden s 
      INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id 
      INNER JOIN stvfstr f ON c.STVCLAS_standard = f.stvfstr_std 
      WHERE s.SPRIDEN_user_type = 'st' 
        AND s.spriden_student_status = 'A' 
        AND c.STVCLAS_acdm = '$year' 
        AND f.stvfstr_acdm_year = c.STVCLAS_acdm 
    ) AS temp;";
    return $this->db->query($sql)->result_array();
  }

  function total_exemption()
  {
    $sql = "SELECT SUM(stvfstr_fees) AS total_fees, SUM(exumption) AS total_exemption
    FROM sprexumtion, stvfstr f";
    return $this->db->query($sql)->result_array();
  }

  function chart_stud()
  {
    $sql = "SELECT 
    (select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='A' and st.stvclas_acdm=(SELECT stvschool_acdm_year  FROM stvschool) and s.id=stvclas_student_id) AS TOTAL_STUDENT,
    (select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='A' and s.rte_status='Yes' and st.stvclas_acdm=(SELECT stvschool_acdm_year  FROM stvschool) and s.id=stvclas_student_id) AS RTE,
    (select count(*) from spriden s, stvclas st where s.spriden_user_type='st' and s.spriden_student_status='I' and st.stvclas_acdm=(SELECT stvschool_acdm_year  FROM stvschool) and s.id=stvclas_student_id
  ) AS INACTIVE";
    return $this->db->query($sql)->result_array();
  }

  // =============================== Kamran =============================
  function stud_dtails($std, $div, $acdm)
  {
    $sql = "SELECT 
      s.id,
      s.SPRIDEN_student_id,
      CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
      s.rte_status,
      c.STVCLAS_rollno,
      IFNULL(SUM(f.sprfees_amount), 0) AS total_paid,
      IFNULL(SUM(CASE WHEN f.sprfees_exumption > 0 THEN f.sprfees_exumption ELSE 0 END), 0) AS total_exemption,
      fstr.stvfstr_fees AS total_fees,
      fstr.stvfstr_fees - IFNULL(SUM(f.sprfees_amount), 0) - IFNULL(SUM(CASE WHEN f.sprfees_exumption > 0 THEN f.sprfees_exumption ELSE 0 END), 0) AS balance_fees
  FROM
      stvclas c
      JOIN spriden s ON s.id = c.STVCLAS_student_id
      LEFT JOIN sprfees f ON f.sprfees_studentid = c.STVCLAS_student_id
          AND f.academic_year = c.STVCLAS_acdm
      LEFT JOIN stvfstr fstr ON fstr.stvfstr_acdm_year = c.STVCLAS_acdm
          AND fstr.stvfstr_std = c.STVCLAS_standard
  WHERE
      c.STVCLAS_standard = '$std'
      AND c.STVCLAS_class = '$div'
      AND c.STVCLAS_acdm = $acdm
      AND s.spriden_student_status = 'A'
  GROUP BY s.SPRIDEN_student_id";
    // print_r($sql);
    return $this->db->query($sql)->result_array();
  }

  function all_students($std, $div, $acdm)
  {
    $sql = "SELECT 
      COUNT(c.STVCLAS_student_id) total
      FROM
      stvclas c
          JOIN
      spriden s ON s.id = c.STVCLAS_student_id
      WHERE
      c.STVCLAS_standard = '$std'
          AND c.STVCLAS_class = '$div'
          AND c.STVCLAS_acdm = $acdm
          AND s.spriden_student_status = 'A'";
    // print_r($sql);
    return $this->db->query($sql)->row_array();
  }

  function rte_student($std, $div, $acdm)
  {
    $sql = "SELECT 
      COUNT(c.STVCLAS_student_id) total
      FROM
      stvclas c
          JOIN
      spriden s ON s.id = c.STVCLAS_student_id
      WHERE
      c.STVCLAS_standard = '$std'
          AND c.STVCLAS_class = '$div'
          AND c.STVCLAS_acdm = $acdm
          AND s.spriden_student_status = 'A'
          AND s.rte_status = 'Yes'";
    // print_r($sql);
    return $this->db->query($sql)->row_array();
  }

  function total_fees($std, $div, $acdm)
  {
    $sql = "select sum(total_fees) total_fees from (
  select s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name, s.rte_status, c.STVCLAS_rollno,
  ifnull(SUM(f.sprfees_amount),0) as total_paid, fstr.stvfstr_fees as total_fees
  from stvclas c
  JOIN spriden s ON s.id = c.STVCLAS_student_id
  LEFT JOIN sprfees f on f.sprfees_studentid = c.STVCLAS_student_id AND f.academic_year = c.STVCLAS_acdm
  LEFT JOIN stvfstr fstr ON fstr.stvfstr_acdm_year = c.STVCLAS_acdm AND fstr.stvfstr_std = c.STVCLAS_standard
  WHERE c.STVCLAS_acdm = $acdm AND c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div'
  AND s.spriden_student_status = 'A'
  GROUP BY s.SPRIDEN_student_id) as temp;";
    // print_r($sql);
    // die();
    return $this->db->query($sql)->row_array();
  }

  function paid_fees($std, $div, $acdm)
  {
    $sql = "select sum(total_paid) total_paid  from (
  select s.SPRIDEN_student_id, concat(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name, s.rte_status, c.STVCLAS_rollno,
  ifnull(SUM(f.sprfees_amount),0) as total_paid, fstr.stvfstr_fees as total_fees
  from stvclas c
  JOIN spriden s ON s.id = c.STVCLAS_student_id
  LEFT JOIN sprfees f on f.sprfees_studentid = c.STVCLAS_student_id AND f.academic_year = c.STVCLAS_acdm
  LEFT JOIN stvfstr fstr ON fstr.stvfstr_acdm_year = c.STVCLAS_acdm AND fstr.stvfstr_std = c.STVCLAS_standard
  WHERE c.STVCLAS_acdm = $acdm AND c.STVCLAS_standard = '$std' AND c.STVCLAS_class = '$div'
  AND s.spriden_student_status = 'A'
  GROUP BY s.SPRIDEN_student_id) as temp;";
    // print_r($sql);
    // die();
    return $this->db->query($sql)->row_array();
  }

  function balance_fees($std, $div, $acdm)
  {
    $sql = "SELECT 
           SUM(total_fees - total_paid) as total_balance
  FROM (
      SELECT 
          s.SPRIDEN_student_id,
          CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) as name,
          s.rte_status,
          c.STVCLAS_rollno,
          IFNULL(SUM(f.sprfees_amount), 0) as total_paid,
          fstr.stvfstr_fees as total_fees
      FROM 
          stvclas c
          JOIN spriden s ON s.id = c.STVCLAS_student_id
          LEFT JOIN sprfees f ON f.sprfees_studentid = c.STVCLAS_student_id AND f.academic_year = c.STVCLAS_acdm
          LEFT JOIN stvfstr fstr ON fstr.stvfstr_acdm_year = c.STVCLAS_acdm AND fstr.stvfstr_std = c.STVCLAS_standard
      WHERE 
          c.STVCLAS_acdm = $acdm 
          AND c.STVCLAS_standard = '$std' 
          AND c.STVCLAS_class = '$div'
          AND s.spriden_student_status = 'A'
      GROUP BY 
          s.SPRIDEN_student_id
  ) as temp;";
    // print_r($sql);
    // die();
    return $this->db->query($sql)->row_array();
  }

  function exp_student($std, $div, $acdm)
  {
    $sql = "SELECT 
      IFNULL(SUM(CASE WHEN f.sprfees_exumption > 0 THEN f.sprfees_exumption ELSE 0 END), 0) AS total_exemption
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
  WHERE
      c.STVCLAS_standard = '$std'
          AND c.STVCLAS_class = '$div'
          AND c.STVCLAS_acdm = $acdm
          AND s.spriden_student_status = 'A';";
    // print_r($sql);
    // die();
    return $this->db->query($sql)->row_array();
  }

  function balance_fee($std, $div, $acdm)
  {
    $sql = "SELECT 
      CASE 
          WHEN balance BETWEEN 1 AND 1000 THEN '1-1000'
          WHEN balance BETWEEN 1001 AND 2000 THEN '1001-2000'
          WHEN balance BETWEEN 2001 AND 3000 THEN '2001-3000'
          WHEN balance BETWEEN 3001 AND 4000 THEN '3001-4000'
          WHEN balance BETWEEN 4001 AND 5000 THEN '4001-5000'
          WHEN balance BETWEEN 5001 AND 6000 THEN '5001-6000'
          WHEN balance BETWEEN 6001 AND 7000 THEN '6001-7000'
          WHEN balance BETWEEN 7001 AND 8000 THEN '7001-8000'
          WHEN balance BETWEEN 8001 AND 9000 THEN '8001-9000'
          WHEN balance BETWEEN 9001 AND 10000 THEN '9001-10000'
          WHEN balance BETWEEN 10001 AND 11000 THEN '10001-11000'
          WHEN balance BETWEEN 11001 AND 12000 THEN '11001-12000'
          WHEN balance BETWEEN 12001 AND 13000 THEN '12001-13000'
      END AS balance_range,
      COUNT(*) AS student_count
  FROM (
      SELECT 
          s.id,
          s.SPRIDEN_student_id,
          CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
          s.rte_status,
          c.STVCLAS_rollno,
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
      WHERE
          c.STVCLAS_standard = '$std'
          AND c.STVCLAS_class = '$div'
          AND c.STVCLAS_acdm = $acdm
          AND s.spriden_student_status = 'A'
      GROUP BY 
          s.SPRIDEN_student_id
      HAVING 
          balance IS NOT NULL
  ) AS TEMP
  WHERE balance BETWEEN 1 AND 13000
  GROUP BY balance_range
  ORDER BY MIN(balance);";
    // print_r($sql);
    return $this->db->query($sql)->result_array();
  }

  function fetchFeesRangeDetails($range, $std, $div, $acdm)
  {
    // Logic 1
    // $ranges = explode("-", $range);
    // $firstRange = isset($ranges[0]) ? $ranges[0] : '0';  
    // $secondRange = isset($ranges[1]) ? $ranges[1] : '0';
    // Logic 2
    $range = str_replace("-", " AND ", $range);
    $sql = "SELECT 
          s.id,
          s.SPRIDEN_student_id,
          CONCAT(s.SPRIDEN_surname,' ',s.SPRIDEN_student_name,' ',s.SPRIDEN_father_name) AS name,
          s.rte_status,
          c.STVCLAS_rollno,
          IFNULL(SUM(f.sprfees_amount), 0) AS total_paid,
          IFNULL(SUM(CASE
                      WHEN f.sprfees_exumption > 0 THEN f.sprfees_exumption
                      ELSE 0
                  END),
                  0) AS total_exemption,
          fstr.stvfstr_fees AS total_fees,
          (IFNULL(fstr.stvfstr_fees, 0) - IFNULL(SUM(f.sprfees_amount), 0)) AS balance,
          p.stvpcnf_whatsapp,
          c.STVCLAS_standard,
          c.STVCLAS_class,
          (SELECT 
                  sprfees_amount
              FROM
                  sprfees
              WHERE
                  sprfees_pk = (SELECT 
                          MAX(sprfees_pk)
                      FROM
                          sprfees
                      WHERE
                          sprfees_studentid = s.id
                              AND academic_year = '$acdm')) AS last_paid,
          (SELECT 
                  sprfees_feesdate
              FROM
                  sprfees
              WHERE
                  sprfees_pk = (SELECT 
                          MAX(sprfees_pk)
                      FROM
                          sprfees
                      WHERE
                          sprfees_studentid = s.id
                              AND academic_year = '$acdm')) AS last_date
          FROM
              stvclas c
                  JOIN
              spriden s ON s.id = c.STVCLAS_student_id
                  LEFT JOIN
              sprfees f ON f.sprfees_studentid = c.STVCLAS_student_id AND s.id = f.sprfees_studentid
                  AND f.academic_year = c.STVCLAS_acdm
                  LEFT JOIN
              stvfstr fstr ON fstr.stvfstr_acdm_year = c.STVCLAS_acdm
                  AND fstr.stvfstr_std = c.STVCLAS_standard
                  LEFT JOIN
              stvpcnf p ON  s.id = p.stvpcnf_student_id
          WHERE
              c.STVCLAS_standard = '$std'
                  AND c.STVCLAS_class = '$div'
                  AND c.STVCLAS_acdm = '$acdm'
                  AND s.spriden_student_status = 'A'
          GROUP BY s.SPRIDEN_student_id
          HAVING balance BETWEEN $range";
    // die($sql);
    return $this->db->query($sql)->result_array();
  }

  // Update by Ansari Kamran
  function getAcademicDetails()
  {
    $sql = "select * from stvacdm where stvacdm_id in (select stvschool_acdm_year from stvschool)";
    // print_r($sql);
    // die;
    return $this->db->query($sql)->row_array();
  }
  
  // <!-- update by ansari kamran -->
  function fetchCurrentAcdm()
  {
    return $this->db->query("select stvschool_acdm_year from stvschool")->row_array();

    // $array['stvschool_acdm_year'] = 7
  }
}
