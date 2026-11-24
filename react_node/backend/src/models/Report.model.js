import pool from '../config/db.js';

class ReportModel {
  /**
   * Get Active Academic Year from stvschool or stvacdm
   */
  static async getActiveAcademicYear() {
    const [rows] = await pool.query(
      'SELECT stvschool_acdm_year FROM stvschool LIMIT 1'
    );
    if (rows.length > 0 && rows[0].stvschool_acdm_year) {
      return Number(rows[0].stvschool_acdm_year);
    }
    const [fallback] = await pool.query(
      'SELECT stvacdm_id FROM stvacdm ORDER BY stvacdm_id DESC LIMIT 1'
    );
    return fallback[0]?.stvacdm_id || 9;
  }

  /**
   * Top-level KPI overview across the school
   */
  static async getReportsKPIs(academicYear) {
    const acdm = academicYear || (await this.getActiveAcademicYear());

    // 1. Student totals (active, inactive, RTE)
    const [studentStats] = await pool.query(
      `SELECT 
        COUNT(CASE WHEN s.spriden_student_status = 'A' THEN 1 END) AS active_students,
        COUNT(CASE WHEN s.spriden_student_status = 'I' THEN 1 END) AS inactive_students,
        COUNT(CASE WHEN s.rte_status = 'Yes' AND s.spriden_student_status = 'A' THEN 1 END) AS rte_students
       FROM spriden s
       JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
       WHERE s.SPRIDEN_user_type = 'st'`,
      [acdm]
    );

    // 2. Fees stats (today's collections, total academic year collections, exemptions)
    const [feesStats] = await pool.query(
      `SELECT 
        IFNULL(SUM(CASE WHEN f.sprfees_feesdate = CURDATE() THEN f.sprfees_amount ELSE 0 END), 0) AS fees_today,
        IFNULL(SUM(f.sprfees_amount), 0) AS fees_total_collected,
        IFNULL(SUM(f.sprfees_exumption), 0) AS total_exemptions
       FROM sprfees f
       WHERE f.academic_year = ?`,
      [acdm]
    );

    // 3. Total fees expectation from fee structure * active students per class
    const [expectedFees] = await pool.query(
      `SELECT IFNULL(SUM(fs.stvfstr_fees), 0) AS total_expected_fees
       FROM stvclas c
       JOIN spriden s ON s.id = c.STVCLAS_student_id AND s.spriden_student_status = 'A' AND (s.rte_status IS NULL OR s.rte_status != 'Yes')
       JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
       WHERE c.STVCLAS_acdm = ?`,
      [acdm]
    );

    // 4. Today's Attendance stats
    const [todayAtnd] = await pool.query(
      `SELECT 
        COUNT(CASE WHEN SPRATND_status = 'P' THEN 1 END) AS present_today,
        COUNT(CASE WHEN SPRATND_status = 'A' THEN 1 END) AS absent_today
       FROM spratnd
       WHERE SPRATND_date = CURDATE() AND SPRATND_acdm_year = ?`,
      [String(acdm)]
    );

    // 5. Total LCs issued
    const [lcStats] = await pool.query(
      `SELECT COUNT(*) AS total_lc_issued
       FROM spriden
       WHERE leaving_date IS NOT NULL OR lc_date IS NOT NULL`
    );

    const totalCollected = Number(feesStats[0]?.fees_total_collected || 0);
    const totalExpected = Number(expectedFees[0]?.total_expected_fees || 0);
    const totalExemptions = Number(feesStats[0]?.total_exemptions || 0);
    const totalBalance = Math.max(0, totalExpected - totalCollected - totalExemptions);

    return {
      academic_year: acdm,
      active_students: studentStats[0]?.active_students || 0,
      inactive_students: studentStats[0]?.inactive_students || 0,
      rte_students: studentStats[0]?.rte_students || 0,
      fees_today: feesStats[0]?.fees_today || 0,
      fees_total_collected: totalCollected,
      total_expected_fees: totalExpected,
      total_balance_fees: totalBalance,
      present_today: todayAtnd[0]?.present_today || 0,
      absent_today: todayAtnd[0]?.absent_today || 0,
      total_lc_issued: lcStats[0]?.total_lc_issued || 0,
    };
  }

  /**
   * Comprehensive Student & Admission Reports
   * Types: 'all', 'new', 'readmission', 'rte', 'inactive', 'summary', 'aadhar'
   */
  static async getStudentReports(params = {}) {
    const acdm = params.academicYear || (await this.getActiveAcademicYear());
    const reportType = params.type || 'all';
    const standard = params.standard || 'all';
    const division = params.division || 'all';
    const search = params.search ? `%${params.search.trim()}%` : null;

    if (reportType === 'summary') {
      // Grouped breakdown by standard & division
      const summaryQuery = `
        SELECT 
          c.STVCLAS_standard,
          c.STVCLAS_class,
          COUNT(s.id) AS total_students,
          COUNT(CASE WHEN s.SPRIDEN_gender IN ('Male', 'M', 'Boy') THEN 1 END) AS boys,
          COUNT(CASE WHEN s.SPRIDEN_gender IN ('Female', 'F', 'Girl') THEN 1 END) AS girls,
          COUNT(CASE WHEN s.rte_status = 'Yes' THEN 1 END) AS rte_count,
          COUNT(CASE WHEN s.spriden_student_status = 'A' THEN 1 END) AS active_count,
          COUNT(CASE WHEN s.spriden_student_status = 'I' THEN 1 END) AS inactive_count
        FROM stvclas c
        JOIN spriden s ON s.id = c.STVCLAS_student_id AND s.SPRIDEN_user_type = 'st'
        WHERE c.STVCLAS_acdm = ?
        GROUP BY c.STVCLAS_standard, c.STVCLAS_class
        ORDER BY c.STVCLAS_standard ASC, c.STVCLAS_class ASC
      `;
      const [summaryRows] = await pool.query(summaryQuery, [acdm]);
      return { reportType, records: summaryRows };
    }

    if (reportType === 'balance_student') {
      let query = `
        SELECT 
          s.id,
          s.SPRIDEN_student_id,
          s.spriden_gr_number,
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
          s.SPRIDEN_father_name,
          s.SPRIDEN_mother_name,
          s.SPRIDEN_gender,
          s.SPRIDEN_birthdate,
          s.SPRIDEN_aadhar_no,
          s.spriden_student_status,
          s.rte_status,
          c.STVCLAS_standard,
          c.STVCLAS_class,
          c.STVCLAS_rollno,
          p.stvpcnf_mobile,
          p.stvpcnf_whatsapp,
          IFNULL(fs.stvfstr_fees, 0) AS total_class_fees,
          IFNULL(SUM(f.sprfees_amount), 0) AS total_fees_paid,
          IFNULL(SUM(f.sprfees_exumption), 0) AS total_exemptions,
          (IFNULL(fs.stvfstr_fees, 0) - IFNULL(SUM(f.sprfees_amount), 0) - IFNULL(SUM(f.sprfees_exumption), 0)) AS balance_amount
        FROM spriden s
        JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
        LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
        LEFT JOIN sprfees f ON f.sprfees_studentid = s.id AND f.academic_year = c.STVCLAS_acdm
        LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
        WHERE s.SPRIDEN_user_type = 'st'
          AND s.spriden_student_status = 'A'
          AND (s.rte_status IS NULL OR s.rte_status != 'Yes')
      `;
      const queryParams = [acdm];

      if (standard !== 'all') {
        query += ` AND c.STVCLAS_standard = ?`;
        queryParams.push(standard);
      }
      if (division !== 'all') {
        query += ` AND c.STVCLAS_class = ?`;
        queryParams.push(division);
      }
      if (search) {
        query += ` AND (
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) LIKE ?
          OR s.SPRIDEN_student_id LIKE ?
          OR s.spriden_gr_number LIKE ?
          OR p.stvpcnf_mobile LIKE ?
        )`;
        queryParams.push(search, search, search, search);
      }

      query += ` GROUP BY s.id, c.STVCLAS_standard, c.STVCLAS_class, fs.stvfstr_fees HAVING balance_amount > 0 ORDER BY balance_amount DESC`;

      const [records] = await pool.query(query, queryParams);
      return { reportType, count: records.length, records };
    }

    let query = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.SPRIDEN_father_name,
        s.SPRIDEN_mother_name,
        s.SPRIDEN_gender,
        s.SPRIDEN_birthdate,
        s.SPRIDEN_aadhar_no,
        s.spriden_student_status,
        s.rte_status,
        s.SPRIDEN_admission_class,
        s.spriden_admission_year,
        s.admsn_acdm_year,
        s.leaving_date,
        s.leaving_reason,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_rollno,
        p.stvpcnf_mobile,
        p.stvpcnf_whatsapp,
        (SELECT SUM(f.sprfees_amount) FROM sprfees f WHERE f.sprfees_studentid = s.id AND f.academic_year = ?) AS total_fees_paid,
        (SELECT fs.stvfstr_fees FROM stvfstr fs WHERE fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = ? LIMIT 1) AS total_class_fees
      FROM spriden s
      JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      WHERE s.SPRIDEN_user_type = 'st'
    `;

    const queryParams = [acdm, acdm, acdm];

    // Status / Type conditions
    if (reportType === 'inactive') {
      query += ` AND s.spriden_student_status = 'I'`;
    } else {
      query += ` AND s.spriden_student_status = 'A'`;
    }

    if (reportType === 'rte') {
      query += ` AND s.rte_status = 'Yes'`;
    } else if (reportType !== 'all' && reportType !== 'inactive' && reportType !== 'aadhar') {
      query += ` AND (s.rte_status IS NULL OR s.rte_status != 'Yes')`;
    }

    if (reportType === 'new') {
      query += ` AND (s.spriden_admission_year = ? OR s.admsn_acdm_year = ?)`;
      queryParams.push(acdm, acdm);
    } else if (reportType === 'readmission') {
      query += ` AND (s.spriden_admission_year != ? AND s.spriden_admission_year IS NOT NULL)`;
      queryParams.push(acdm);
    }

    if (standard !== 'all') {
      query += ` AND c.STVCLAS_standard = ?`;
      queryParams.push(standard);
    }

    if (division !== 'all') {
      query += ` AND c.STVCLAS_class = ?`;
      queryParams.push(division);
    }

    if (search) {
      query += ` AND (
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) LIKE ?
        OR s.SPRIDEN_student_id LIKE ?
        OR s.spriden_gr_number LIKE ?
        OR p.stvpcnf_mobile LIKE ?
        OR s.SPRIDEN_aadhar_no LIKE ?
      )`;
      queryParams.push(search, search, search, search, search);
    }

    query += ` ORDER BY c.STVCLAS_standard ASC, c.STVCLAS_class ASC, c.STVCLAS_rollno ASC, s.SPRIDEN_student_name ASC`;

    const [records] = await pool.query(query, queryParams);
    return { reportType, count: records.length, records };
  }

  /**
   * Comprehensive Financial & Fees Reports
   * Types: 'daily', 'datewise', 'balance', 'zero_paid', 'installments', 'all_previous', 'last_year_balance'
   */
  static async getFeesReports(params = {}) {
    const acdm = params.academicYear || (await this.getActiveAcademicYear());
    const reportType = params.type || 'daily';
    const fromDate = params.from_date || new Date().toISOString().slice(0, 10);
    const toDate = params.to_date || new Date().toISOString().slice(0, 10);
    const standard = params.standard || 'all';
    const division = params.division || 'all';

    if (reportType === 'daily' || reportType === 'datewise') {
      let query = `
        SELECT 
          f.sprfees_pk,
          f.sprfees_studentid,
          f.sprfees_amount,
          f.sprfees_feesdate,
          f.sprfees_timestamp,
          f.sprfees_recivedby,
          f.payment_mode,
          f.sprfees_notes,
          f.sprfees_exumption,
          s.SPRIDEN_student_id,
          s.spriden_gr_number,
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
          c.STVCLAS_standard,
          c.STVCLAS_class,
          p.stvpcnf_mobile
        FROM sprfees f
        JOIN spriden s ON s.id = f.sprfees_studentid
        LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = f.academic_year
        LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
        WHERE f.academic_year = ?
      `;
      const queryParams = [acdm];

      if (reportType === 'daily') {
        query += ` AND f.sprfees_feesdate = ?`;
        queryParams.push(fromDate);
      } else {
        query += ` AND f.sprfees_feesdate BETWEEN ? AND ?`;
        queryParams.push(fromDate, toDate);
      }

      if (standard !== 'all') {
        query += ` AND c.STVCLAS_standard = ?`;
        queryParams.push(standard);
      }
      if (division !== 'all') {
        query += ` AND c.STVCLAS_class = ?`;
        queryParams.push(division);
      }

      query += ` ORDER BY f.sprfees_feesdate DESC, f.sprfees_pk DESC`;

      const [records] = await pool.query(query, queryParams);
      const totalAmount = records.reduce((acc, r) => acc + Number(r.sprfees_amount || 0), 0);
      const totalExemptions = records.reduce((acc, r) => acc + Number(r.sprfees_exumption || 0), 0);

      return {
        reportType,
        fromDate,
        toDate,
        totalAmount,
        totalExemptions,
        count: records.length,
        records,
      };
    }

    if (reportType === 'balance' || reportType === 'zero_paid' || reportType === 'last_year_balance') {
      let query = `
        SELECT 
          s.id,
          s.SPRIDEN_student_id,
          s.spriden_gr_number,
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
          c.STVCLAS_standard,
          c.STVCLAS_class,
          p.stvpcnf_mobile,
          p.stvpcnf_whatsapp,
          IFNULL(fs.stvfstr_fees, 0) AS total_fee,
          IFNULL(SUM(f.sprfees_amount), 0) AS paid_fee,
          IFNULL(SUM(f.sprfees_exumption), 0) AS exemption_fee,
          (IFNULL(fs.stvfstr_fees, 0) - IFNULL(SUM(f.sprfees_amount), 0) - IFNULL(SUM(f.sprfees_exumption), 0)) AS balance_fee,
          MAX(f.sprfees_feesdate) AS last_payment_date
        FROM spriden s
        JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
        LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
        LEFT JOIN sprfees f ON f.sprfees_studentid = s.id AND f.academic_year = c.STVCLAS_acdm
        LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
        WHERE s.SPRIDEN_user_type = 'st'
          AND s.spriden_student_status = 'A'
          AND (s.rte_status IS NULL OR s.rte_status != 'Yes')
      `;
      const queryParams = [acdm];

      if (standard !== 'all') {
        query += ` AND c.STVCLAS_standard = ?`;
        queryParams.push(standard);
      }
      if (division !== 'all') {
        query += ` AND c.STVCLAS_class = ?`;
        queryParams.push(division);
      }

      query += ` GROUP BY s.id, c.STVCLAS_standard, c.STVCLAS_class, fs.stvfstr_fees`;

      if (reportType === 'zero_paid') {
        query += ` HAVING paid_fee = 0`;
      } else {
        query += ` HAVING balance_fee > 0`;
      }

      query += ` ORDER BY balance_fee DESC, c.STVCLAS_standard ASC`;

      const [records] = await pool.query(query, queryParams);
      const totalBalance = records.reduce((acc, r) => acc + Number(r.balance_fee || 0), 0);

      return {
        reportType,
        totalBalance,
        count: records.length,
        records,
      };
    }

    if (reportType === 'installments') {
      let query = `
        SELECT 
          s.id,
          s.SPRIDEN_student_id,
          s.spriden_gr_number,
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
          c.STVCLAS_standard,
          c.STVCLAS_class,
          p.stvpcnf_mobile,
          IFNULL(fs.stvfstr_fees, 0) AS total_fee,
          IFNULL(SUM(f.sprfees_amount), 0) AS paid_fee,
          GROUP_CONCAT(CONCAT('₹', f.sprfees_amount, ' (', DATE_FORMAT(f.sprfees_feesdate, '%d/%m/%Y'), ')') ORDER BY f.sprfees_feesdate ASC SEPARATOR ', ') AS installments
        FROM spriden s
        JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
        LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
        LEFT JOIN sprfees f ON f.sprfees_studentid = s.id AND f.academic_year = c.STVCLAS_acdm
        LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
        WHERE s.SPRIDEN_user_type = 'st'
          AND s.spriden_student_status = 'A'
      `;
      const queryParams = [acdm];

      if (standard !== 'all') {
        query += ` AND c.STVCLAS_standard = ?`;
        queryParams.push(standard);
      }
      if (division !== 'all') {
        query += ` AND c.STVCLAS_class = ?`;
        queryParams.push(division);
      }

      query += ` GROUP BY s.id, c.STVCLAS_standard, c.STVCLAS_class, fs.stvfstr_fees ORDER BY c.STVCLAS_standard ASC, s.id ASC`;

      const [records] = await pool.query(query, queryParams);
      const totalAmount = records.reduce((acc, r) => acc + Number(r.paid_fee || 0), 0);

      return {
        reportType,
        totalAmount,
        count: records.length,
        records,
      };
    }

    // Default fallback
    return { reportType, records: [] };
  }

  /**
   * Attendance & Absenteeism Reports
   */
  static async getAttendanceReports(params = {}) {
    const acdm = params.academicYear || (await this.getActiveAcademicYear());
    const date = params.date || new Date().toISOString().slice(0, 10);
    const standard = params.standard || 'all';
    const division = params.division || 'all';

    // 1. Class-wise attendance percentage breakdown
    let summaryQuery = `
      SELECT 
        c.STVCLAS_standard,
        c.STVCLAS_class,
        COUNT(s.id) AS total_enrolled,
        COUNT(CASE WHEN a.SPRATND_status = 'P' THEN 1 END) AS present_count,
        COUNT(CASE WHEN a.SPRATND_status = 'A' THEN 1 END) AS absent_count,
        COUNT(CASE WHEN a.SPRATND_status IS NOT NULL THEN 1 END) AS marked_count
      FROM stvclas c
      JOIN spriden s ON s.id = c.STVCLAS_student_id AND s.spriden_student_status = 'A'
      LEFT JOIN spratnd a ON a.SPRATND_student_id = s.id 
        AND a.SPRATND_date = ? 
        AND a.SPRATND_acdm_year = ?
      WHERE c.STVCLAS_acdm = ?
    `;
    const summaryParams = [date, String(acdm), acdm];

    if (standard !== 'all') {
      summaryQuery += ` AND c.STVCLAS_standard = ?`;
      summaryParams.push(standard);
    }
    if (division !== 'all') {
      summaryQuery += ` AND c.STVCLAS_class = ?`;
      summaryParams.push(division);
    }

    summaryQuery += ` GROUP BY c.STVCLAS_standard, c.STVCLAS_class ORDER BY c.STVCLAS_standard ASC, c.STVCLAS_class ASC`;

    const [summaryRows] = await pool.query(summaryQuery, summaryParams);

    // 2. Specific absent student list for this date (CI3 Absent_student.php)
    let absentQuery = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_rollno,
        p.stvpcnf_mobile,
        p.stvpcnf_whatsapp,
        a.SPRATND_date,
        a.SPRATND_status,
        a.SPRATND_comment
      FROM spratnd a
      JOIN spriden s ON s.id = a.SPRATND_student_id
      JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      WHERE a.SPRATND_date = ? 
        AND a.SPRATND_status = 'A'
        AND a.SPRATND_acdm_year = ?
    `;
    const absentParams = [acdm, date, String(acdm)];

    if (standard !== 'all') {
      absentQuery += ` AND c.STVCLAS_standard = ?`;
      absentParams.push(standard);
    }
    if (division !== 'all') {
      absentQuery += ` AND c.STVCLAS_class = ?`;
      absentParams.push(division);
    }

    absentQuery += ` ORDER BY c.STVCLAS_standard ASC, c.STVCLAS_class ASC, c.STVCLAS_rollno ASC`;

    const [absentRows] = await pool.query(absentQuery, absentParams);

    return {
      date,
      academic_year: acdm,
      classSummary: summaryRows,
      absentCount: absentRows.length,
      absentStudents: absentRows,
    };
  }

  /**
   * Certificates (Leaving Certificate LC & Bonafide) Reports
   */
  static async getCertificatesReports(params = {}) {
    const reportType = params.type || 'issued'; // 'issued', 'requests'

    if (reportType === 'requests') {
      const [requests] = await pool.query(`
        SELECT 
          r.id,
          r.student_id,
          r.class,
          r.academic_year,
          r.status,
          r.requested_at,
          r.approved_at,
          s.SPRIDEN_student_id,
          s.spriden_gr_number,
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
          p.stvpcnf_mobile
        FROM lc_requests r
        JOIN spriden s ON s.id = r.student_id
        LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
        ORDER BY r.id DESC
      `);
      return { reportType: 'requests', count: requests.length, records: requests };
    }

    // Issued LCs from spriden
    const [issued] = await pool.query(`
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.SPRIDEN_gender,
        s.leaving_date,
        s.leaving_reason,
        s.conduct,
        s.progress,
        s.student_next_school,
        s.lc_date,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        p.stvpcnf_mobile
      FROM spriden s
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = (SELECT max(stvclas_acdm) FROM stvclas WHERE STVCLAS_student_id = s.id)
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      WHERE (s.leaving_date IS NOT NULL OR s.lc_date IS NOT NULL)
      ORDER BY s.leaving_date DESC, s.id DESC
      LIMIT 100
    `);

    return { reportType: 'issued', count: issued.length, records: issued };
  }

  /**
   * Teacher Activity & Lesson Planning Reports
   */
  static async getTeacherActivityReports(params = {}) {
    const acdm = params.academicYear || (await this.getActiveAcademicYear());
    const date = params.date || new Date().toISOString().slice(0, 10);

    // Fast indexed counts for today's attendance and assigned periods
    const [attCounts] = await pool.query(
      `SELECT SPRATND_teacher_id, COUNT(*) AS cnt 
       FROM spratnd 
       WHERE SPRATND_date = ? 
       GROUP BY SPRATND_teacher_id`,
      [date]
    );
    const attMap = {};
    attCounts.forEach((r) => {
      attMap[r.SPRATND_teacher_id] = r.cnt;
    });

    const [periodCounts] = await pool.query(
      `SELECT SPATESD_teacher_id, COUNT(*) AS cnt 
       FROM spatesd 
       GROUP BY SPATESD_teacher_id`
    );
    const periodMap = {};
    periodCounts.forEach((r) => {
      periodMap[r.SPATESD_teacher_id] = r.cnt;
    });

    // All active teachers
    const [teachers] = await pool.query(`
      SELECT 
        t.id,
        t.SPRIDEN_student_id AS teacher_code,
        CONCAT(IFNULL(t.SPRIDEN_surname, ''), ' ', IFNULL(t.SPRIDEN_student_name, ''), ' ', IFNULL(t.SPRIDEN_father_name, '')) AS teacher_name,
        p.stvpcnf_mobile
      FROM spriden t
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = t.id
      WHERE t.SPRIDEN_user_type = 'tc' AND t.spriden_student_status = 'A'
      ORDER BY t.SPRIDEN_student_name ASC
    `);

    const enriched = teachers.map((t) => ({
      ...t,
      attendance_taken_today: attMap[t.teacher_code] || 0,
      assigned_periods: periodMap[t.teacher_code] || 0,
    }));

    return {
      date,
      count: enriched.length,
      teachers: enriched,
    };
  }

  /**
   * User Audit & Login Tracking Reports (CI3 app_track & stvlgin)
   */
  static async getAuditReports(params = {}) {
    const limit = Number(params.limit) || 100;

    // Login logs from stvlgin + app_tracking
    const [loginLogs] = await pool.query(`
      SELECT 
        l.stvlgin_id AS id,
        l.stvlgin_date AS log_date,
        l.stvlgin_time AS log_time,
        l.stvlgin_userid AS user_id,
        l.stvlgin_status AS status,
        COALESCE(u.spruser_name, s.SPRIDEN_student_name, l.stvlgin_userid) AS user_name,
        CASE 
          WHEN u.spruser_id IS NOT NULL THEN 'Staff / Admin'
          WHEN s.SPRIDEN_user_type = 'tc' THEN 'Teacher'
          WHEN s.SPRIDEN_user_type = 'st' THEN 'Student'
          ELSE 'User'
        END AS role
      FROM stvlgin l
      LEFT JOIN spruser u ON (u.spruser_username = l.stvlgin_userid OR CAST(u.spruser_id AS CHAR) = l.stvlgin_userid)
      LEFT JOIN spriden s ON s.SPRIDEN_student_id = l.stvlgin_userid
      ORDER BY l.stvlgin_date DESC, l.stvlgin_time DESC
      LIMIT ?
    `, [limit]);

    return { count: loginLogs.length, records: loginLogs };
  }

  /**
   * Update LC Request Status (Approve / Reject)
   */
  static async updateLcRequestStatus(requestId, status, userId) {
    const [result] = await pool.query(
      `UPDATE lc_requests 
       SET status = ?, approved_by = ?, approved_at = CURDATE()
       WHERE id = ?`,
      [status, userId, requestId]
    );
    return result.affectedRows > 0;
  }
}

export default ReportModel;
