import pool from '../config/db.js';

class FeesModel {
  /**
   * Get Active Academic Year ID from stvschool
   */
  static async getActiveAcademicYearId() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 9;
  }

  /**
   * Search students for fees form
   */
  static async searchStudentsForFees(name, academicYear, includeInactive = false) {
    let statusCondition = "s.spriden_student_status = 'A'";
    if (includeInactive) {
      statusCondition = "s.spriden_student_status IN ('A', 'I')";
    }

    const query = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        s.spriden_student_status,
        s.rte_status,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_acdm
      FROM spriden s
      JOIN stvclas c ON c.STVCLAS_student_id = s.id
      WHERE ${statusCondition}
        AND c.STVCLAS_acdm = ?
        AND (
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) LIKE ?
          OR s.SPRIDEN_student_id LIKE ?
        )
      ORDER BY s.SPRIDEN_student_name ASC
      LIMIT 25
    `;

    const searchTerm = `%${name.trim()}%`;
    const [rows] = await pool.query(query, [academicYear, searchTerm, searchTerm]);
    return rows;
  }

  /**
   * Get student details and fee ledger for a specific academic year
   */
  static async getStudentFeeLedger(studentId, academicYear) {
    // 1. Student Info + current class
    const [infoRows] = await pool.query(
      `SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        s.spriden_student_status,
        s.rte_status,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_acdm
       FROM spriden s
       LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
       WHERE s.id = ?
       LIMIT 1`,
      [academicYear, studentId]
    );

    if (infoRows.length === 0) return null;
    const studentInfo = infoRows[0];

    // 2. Father Contact info
    const [contactRows] = await pool.query(
      `SELECT stvpcnf_mobile AS mobile, stvpcnf_whatsapp AS whatsapp
       FROM stvpcnf
       WHERE stvpcnf_student_id = ? AND stvpcnf_parent_typ = 'Father'
       LIMIT 1`,
      [studentId]
    );
    const contactDetails = contactRows[0] || { mobile: null, whatsapp: null };

    // 3. Fee structure for this standard & academic year
    const [feeStructureRows] = await pool.query(
      `SELECT stvfstr_id, stvfstr_std, CAST(IFNULL(stvfstr_fees, 0) AS UNSIGNED) AS stvfstr_fees, stvfstr_acdm_year
       FROM stvfstr
       WHERE stvfstr_std = ? AND stvfstr_acdm_year = ?
       LIMIT 1`,
      [studentInfo.STVCLAS_standard, academicYear]
    );
    const feeStructure = feeStructureRows[0] || {
      stvfstr_std: studentInfo.STVCLAS_standard,
      stvfstr_fees: 0,
      stvfstr_acdm_year: academicYear
    };

    // 4. Receipts for the selected academic year
    const [receipts] = await pool.query(
      `SELECT 
        f.sprfees_pk,
        f.sprfees_studentid,
        f.sprfees_standard,
        CAST(IFNULL(f.sprfees_amount, 0) AS UNSIGNED) AS sprfees_amount,
        f.sprfees_feesdate,
        f.sprfees_timestamp,
        f.sprfees_recivedby,
        u.spruser_name,
        f.sprfees_notes,
        f.sprfees_due_date,
        f.sprfees_status,
        f.academic_year,
        f.sprfees_source,
        CAST(IFNULL(f.sprfees_exumption, 0) AS UNSIGNED) AS sprfees_exumption,
        f.sprfees_ex_notes,
        f.payment_mode
       FROM sprfees f
       LEFT JOIN spruser u ON u.spruser_id = f.sprfees_recivedby
       WHERE f.sprfees_studentid = ? AND f.academic_year = ?
       ORDER BY f.sprfees_feesdate ASC, f.sprfees_pk ASC`,
      [studentId, academicYear]
    );

    // Sum paid and exempted for this year
    let totalPaid = 0;
    let totalExemption = 0;
    receipts.forEach(r => {
      totalPaid += Number(r.sprfees_amount) || 0;
      totalExemption += Number(r.sprfees_exumption) || 0;
    });

    const totalFees = Number(feeStructure.stvfstr_fees) || 0;
    const balance = totalFees - (totalPaid + totalExemption);

    // 5. Complete Academic History (all years fees summary)
    const [allYearsRows] = await pool.query(
      `SELECT 
        c.STVCLAS_acdm AS academic_year_id,
        a.stvacdm_year,
        c.STVCLAS_standard,
        CAST(IFNULL(fs.stvfstr_fees, 0) AS UNSIGNED) AS class_fees,
        CAST(IFNULL(SUM(f.sprfees_amount), 0) AS UNSIGNED) AS total_paid,
        CAST(IFNULL(SUM(f.sprfees_exumption), 0) AS UNSIGNED) AS total_exemption,
        (CAST(IFNULL(fs.stvfstr_fees, 0) AS SIGNED) - CAST(IFNULL(SUM(f.sprfees_amount), 0) AS SIGNED) - CAST(IFNULL(SUM(f.sprfees_exumption), 0) AS SIGNED)) AS balance
       FROM stvclas c
       JOIN stvacdm a ON a.stvacdm_id = c.STVCLAS_acdm
       LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
       LEFT JOIN sprfees f ON f.sprfees_studentid = c.STVCLAS_student_id AND f.academic_year = c.STVCLAS_acdm
       WHERE c.STVCLAS_student_id = ?
       GROUP BY c.STVCLAS_acdm, c.STVCLAS_standard
       ORDER BY c.STVCLAS_acdm DESC`,
      [studentId]
    );

    // Also get all installments breakdown for all years
    const [allReceipts] = await pool.query(
      `SELECT 
        f.sprfees_pk,
        f.sprfees_studentid,
        f.sprfees_standard,
        CAST(IFNULL(f.sprfees_amount, 0) AS UNSIGNED) AS sprfees_amount,
        f.sprfees_feesdate,
        f.sprfees_timestamp,
        u.spruser_name,
        f.sprfees_notes,
        f.academic_year,
        CAST(IFNULL(f.sprfees_exumption, 0) AS UNSIGNED) AS sprfees_exumption,
        f.payment_mode
       FROM sprfees f
       LEFT JOIN spruser u ON u.spruser_id = f.sprfees_recivedby
       WHERE f.sprfees_studentid = ?
       ORDER BY f.sprfees_feesdate ASC, f.sprfees_pk ASC`,
      [studentId]
    );

    // 6. Previous Unpaid Balances (from years other than current active school year)
    const [activeYearRow] = await pool.query(
      `SELECT stvschool_acdm_year FROM stvschool LIMIT 1`
    );
    const activeYearId = activeYearRow[0]?.stvschool_acdm_year;

    const [prevBalances] = await pool.query(
      `SELECT 
        c.STVCLAS_standard AS class,
        c.STVCLAS_acdm AS acdm,
        a.stvacdm_year,
        CAST(IFNULL(SUM(f.sprfees_amount), 0) AS UNSIGNED) AS paid,
        CAST(IFNULL(SUM(f.sprfees_exumption), 0) AS UNSIGNED) AS exumption,
        CAST(IFNULL(fs.stvfstr_fees, 0) AS UNSIGNED) AS total,
        (CAST(IFNULL(fs.stvfstr_fees, 0) AS SIGNED) - CAST(IFNULL(SUM(f.sprfees_amount), 0) AS SIGNED) - CAST(IFNULL(SUM(f.sprfees_exumption), 0) AS SIGNED)) AS balance
       FROM stvclas c
       JOIN stvacdm a ON a.stvacdm_id = c.STVCLAS_acdm
       LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
       LEFT JOIN sprfees f ON f.sprfees_studentid = c.STVCLAS_student_id AND f.academic_year = c.STVCLAS_acdm
       WHERE c.STVCLAS_student_id = ?
         AND c.STVCLAS_acdm != ?
       GROUP BY c.STVCLAS_acdm, c.STVCLAS_standard
       HAVING balance > 0
       ORDER BY c.STVCLAS_acdm DESC`,
      [studentId, activeYearId]
    );

    return {
      info: studentInfo,
      contact_details: contactDetails,
      fees_details: feeStructure,
      receipts,
      total_fees: totalFees,
      total_paid: totalPaid,
      total_exemption: totalExemption,
      total_balance: balance,
      all_years_summary: allYearsRows,
      all_receipts: allReceipts,
      prev_balances: prevBalances
    };
  }

  /**
   * Add Fee Transaction
   */
  static async collectFees(data, userId) {
    const {
      student_id,
      standard,
      amount,
      fees_date,
      details,
      due_date,
      academic_year,
      concession_amount = 0,
      concession_note = 'NA',
      payment_mode = 'cash'
    } = data;

    // Format timestamp like CI3: "15-Sep-2026 01:30:38"
    const now = new Date();
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const pad = (n) => String(n).padStart(2, '0');
    const timestamp = `${pad(now.getDate())}-${months[now.getMonth()]}-${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

    const query = `
      INSERT INTO sprfees (
        sprfees_studentid,
        sprfees_standard,
        sprfees_amount,
        sprfees_feesdate,
        sprfees_timestamp,
        sprfees_recivedby,
        sprfees_notes,
        sprfees_due_date,
        sprfees_status,
        academic_year,
        sprfees_source,
        sprfees_exumption,
        sprfees_ex_notes,
        payment_mode
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Unpaid', ?, 'Fees Form', ?, ?, ?)
    `;

    const values = [
      student_id,
      standard,
      parseInt(amount, 10) || 0,
      fees_date || now.toISOString().split('T')[0],
      timestamp,
      String(userId || 1),
      details || '-',
      due_date || null,
      String(academic_year),
      String(concession_amount || 0),
      concession_note || 'NA',
      payment_mode || 'cash'
    ];

    const [result] = await pool.query(query, values);
    return { insertId: result.insertId };
  }

  /**
   * Fees Report by Academic Year
   */
  static async getFeesReport(academicYear, standard = null) {
    let standardCondition = '';
    const params = [academicYear];

    if (standard && standard !== 'all') {
      standardCondition = ' AND c.STVCLAS_standard = ? ';
      params.push(standard);
    }

    const query = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        s.spriden_student_status,
        s.rte_status,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        CAST(IFNULL(fs.stvfstr_fees, 0) AS UNSIGNED) AS total_fees,
        CAST(IFNULL(SUM(f.sprfees_amount), 0) AS UNSIGNED) AS total_paid,
        CAST(IFNULL(SUM(f.sprfees_exumption), 0) AS UNSIGNED) AS total_exemption,
        (CAST(IFNULL(fs.stvfstr_fees, 0) AS SIGNED) - CAST(IFNULL(SUM(f.sprfees_amount), 0) AS SIGNED) - CAST(IFNULL(SUM(f.sprfees_exumption), 0) AS SIGNED)) AS balance,
        MAX(f.sprfees_due_date) AS due_date,
        MAX(f.sprfees_feesdate) AS last_payment_date,
        GROUP_CONCAT(DISTINCT u.spruser_name SEPARATOR ', ') AS received_by
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
      WHERE c.STVCLAS_acdm = ?
        ${standardCondition}
      GROUP BY s.id, c.STVCLAS_standard, c.STVCLAS_class, fs.stvfstr_fees
      ORDER BY c.STVCLAS_standard ASC, s.SPRIDEN_student_name ASC
    `;

    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Get Receipt Details for Printing
   */
  static async getReceiptDetails(studentId, academicYear = null) {
    // 1. Profile
    const [profileRows] = await pool.query(
      `SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS class,
        c.STVCLAS_acdm,
        a.stvacdm_year,
        CAST(IFNULL(fs.stvfstr_fees, 0) AS UNSIGNED) AS total_fees
       FROM spriden s
       LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id
       LEFT JOIN stvacdm a ON a.stvacdm_id = c.STVCLAS_acdm
       LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
       WHERE s.id = ? ${academicYear ? 'AND c.STVCLAS_acdm = ?' : ''}
       ORDER BY c.STVCLAS_acdm DESC
       LIMIT 1`,
      academicYear ? [studentId, academicYear] : [studentId]
    );

    if (profileRows.length === 0) return null;
    const profile = profileRows[0];

    // 2. Receipts
    const [receipts] = await pool.query(
      `SELECT 
        f.sprfees_pk,
        f.sprfees_amount,
        f.sprfees_feesdate,
        f.sprfees_timestamp,
        f.payment_mode,
        f.sprfees_exumption,
        f.sprfees_notes,
        f.sprfees_due_date,
        u.spruser_name
       FROM sprfees f
       LEFT JOIN spruser u ON u.spruser_id = f.sprfees_recivedby
       WHERE f.sprfees_studentid = ? AND f.academic_year = ?
       ORDER BY f.sprfees_feesdate ASC, f.sprfees_pk ASC`,
      [studentId, profile.STVCLAS_acdm]
    );

    let totalPaid = 0;
    let totalExemption = 0;
    receipts.forEach(r => {
      totalPaid += Number(r.sprfees_amount) || 0;
      totalExemption += Number(r.sprfees_exumption) || 0;
    });

    return {
      profile,
      receipts,
      total_fees: profile.total_fees,
      total_paid: totalPaid,
      total_exemption: totalExemption,
      balance: profile.total_fees - (totalPaid + totalExemption)
    };
  }

  /**
   * Fee Structure: Get all records by academic year
   */
  static async getFeeStructure(academicYear) {
    const [rows] = await pool.query(
      `SELECT 
        fs.stvfstr_id,
        fs.stvfstr_std,
        CAST(IFNULL(fs.stvfstr_fees, 0) AS UNSIGNED) AS stvfstr_fees,
        fs.stvfstr_acdm_year,
        a.stvacdm_year
       FROM stvfstr fs
       LEFT JOIN stvacdm a ON a.stvacdm_id = fs.stvfstr_acdm_year
       WHERE fs.stvfstr_acdm_year = ?
       ORDER BY fs.stvfstr_id ASC`,
      [academicYear]
    );
    return rows;
  }

  /**
   * Fee Structure: Upsert standard fee
   */
  static async saveFeeStructure(standard, fees, academicYear) {
    const [existing] = await pool.query(
      `SELECT stvfstr_id FROM stvfstr WHERE stvfstr_std = ? AND stvfstr_acdm_year = ? LIMIT 1`,
      [standard, academicYear]
    );

    if (existing.length > 0) {
      await pool.query(
        `UPDATE stvfstr SET stvfstr_fees = ? WHERE stvfstr_id = ?`,
        [fees, existing[0].stvfstr_id]
      );
      return { id: existing[0].stvfstr_id, updated: true };
    } else {
      const [result] = await pool.query(
        `INSERT INTO stvfstr (stvfstr_std, stvfstr_fees, stvfstr_acdm_year) VALUES (?, ?, ?)`,
        [standard, fees, academicYear]
      );
      return { id: result.insertId, created: true };
    }
  }

  /**
   * Bank Reconciliation: Get bank transactions
   */
  static async getBankTransactions(fromDate, toDate) {
    const query = `
      SELECT id, value_date, txn_posted_date, mode, description, transaction_amount
      FROM fees_bank_up_transactions
      WHERE value_date BETWEEN ? AND ?
      ORDER BY value_date DESC, id DESC
    `;
    const [rows] = await pool.query(query, [fromDate, toDate]);
    return rows;
  }

  /**
   * Bank Reconciliation: Get daily system fees
   */
  static async getDailyFees(fromDate, toDate) {
    const query = `
      SELECT
        f.sprfees_pk,
        f.sprfees_studentid,
        f.sprfees_amount,
        f.sprfees_feesdate,
        f.payment_mode,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name
      FROM sprfees f
      LEFT JOIN spriden s ON s.id = f.sprfees_studentid
      WHERE f.sprfees_feesdate BETWEEN ? AND ?
      ORDER BY f.sprfees_feesdate DESC, f.sprfees_pk DESC
    `;
    const [rows] = await pool.query(query, [fromDate, toDate]);
    return rows;
  }

  /**
   * Bank Reconciliation: Update fee date
   */
  static async updateFeeDate(sprfeesPk, studentId, updateDate, currentDate) {
    const query = `
      UPDATE sprfees
      SET sprfees_feesdate = ?
      WHERE sprfees_pk = ?
        AND sprfees_studentid = ?
        AND sprfees_feesdate = ?
    `;
    const [result] = await pool.query(query, [updateDate, sprfeesPk, studentId, currentDate]);
    return result.affectedRows > 0;
  }

  /**
   * Fees Executive Analytics Dashboard (CI3: FeesDashboard.php / DB_model.php)
   */
  static async getFeesExecutiveDashboard({ academicYear, fromDate, toDate }) {
    const acdmYear = academicYear || (await this.getActiveAcademicYearId());

    // 1. Overall totals (Active students, targets, collected, exemptions)
    const [overallStudents] = await pool.query(
      `SELECT COUNT(DISTINCT s.id) AS total_students
       FROM spriden s
       JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
       WHERE s.spriden_student_status = 'A' AND s.SPRIDEN_user_type = 'st'`,
      [acdmYear]
    );

    let paidQuery = `
      SELECT 
        IFNULL(SUM(sprfees_amount), 0) AS total_paid,
        IFNULL(SUM(sprfees_exumption), 0) AS total_exemption,
        COUNT(sprfees_pk) AS transaction_count
      FROM sprfees
      WHERE academic_year = ?
    `;
    const paidParams = [acdmYear];
    if (fromDate && toDate) {
      paidQuery += ` AND sprfees_feesdate BETWEEN ? AND ?`;
      paidParams.push(fromDate, toDate);
    }
    const [paidRows] = await pool.query(paidQuery, paidParams);

    // 2. RTE Students count
    const [rteRows] = await pool.query(
      `SELECT COUNT(DISTINCT s.id) AS total_rte
       FROM spriden s
       JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
       WHERE s.spriden_student_status = 'A' AND LOWER(IFNULL(s.rte_status, '')) = 'yes'`,
      [acdmYear]
    );

    // 3. Class-wise targets vs collections
    const [classRows] = await pool.query(
      `SELECT 
        st.stvfstr_std AS standard,
        st.stvfstr_fees AS fee_per_student,
        COUNT(DISTINCT c.STVCLAS_student_id) AS total_students,
        (COUNT(DISTINCT c.STVCLAS_student_id) * st.stvfstr_fees) AS target_fees,
        IFNULL(paid.fees_paid, 0) AS fees_paid,
        IFNULL(paid.exemption, 0) AS exemption,
        ((COUNT(DISTINCT c.STVCLAS_student_id) * st.stvfstr_fees) - (IFNULL(paid.fees_paid, 0) + IFNULL(paid.exemption, 0))) AS balance,
        ROUND(IFNULL(paid.fees_paid, 0) * 100.0 / NULLIF((COUNT(DISTINCT c.STVCLAS_student_id) * st.stvfstr_fees), 0), 1) AS collection_percentage
       FROM stvfstr st
       LEFT JOIN stvclas c ON c.STVCLAS_standard = st.stvfstr_std AND c.STVCLAS_acdm = st.stvfstr_acdm_year
       LEFT JOIN spriden s ON s.id = c.STVCLAS_student_id AND s.spriden_student_status = 'A' AND s.SPRIDEN_user_type = 'st'
       LEFT JOIN (
         SELECT 
           f.sprfees_standard,
           SUM(f.sprfees_amount) AS fees_paid,
           SUM(f.sprfees_exumption) AS exemption
         FROM sprfees f
         WHERE f.academic_year = ?
         GROUP BY f.sprfees_standard
       ) paid ON paid.sprfees_standard = st.stvfstr_std
       WHERE st.stvfstr_acdm_year = ?
       GROUP BY st.stvfstr_id, st.stvfstr_std, st.stvfstr_fees, paid.fees_paid, paid.exemption
       ORDER BY st.stvfstr_id ASC`,
      [acdmYear, acdmYear]
    );

    // 4. Monthly collection trends
    const [monthlyRows] = await pool.query(
      `SELECT 
        DATE_FORMAT(sprfees_feesdate, '%Y-%m') AS month_key,
        DATE_FORMAT(sprfees_feesdate, '%b %Y') AS month_name,
        SUM(sprfees_amount) AS total_collected,
        COUNT(sprfees_pk) AS transaction_count
       FROM sprfees
       WHERE academic_year = ? AND sprfees_feesdate IS NOT NULL
       GROUP BY month_key, month_name
       ORDER BY month_key ASC`,
      [acdmYear]
    );

    // 5. Payment mode breakdown
    const [modeRows] = await pool.query(
      `SELECT 
        IFNULL(NULLIF(payment_mode, ''), 'Cash / Direct') AS mode,
        SUM(sprfees_amount) AS total_amount,
        COUNT(*) AS tx_count
       FROM sprfees
       WHERE academic_year = ?
       GROUP BY mode
       ORDER BY total_amount DESC`,
      [acdmYear]
    );

    const totalStudents = Number(overallStudents[0]?.total_students || 0);
    const totalPaid = parseFloat(paidRows[0]?.total_paid || 0);
    const totalExemption = parseFloat(paidRows[0]?.total_exemption || 0);
    const totalRte = Number(rteRows[0]?.total_rte || 0);
    const targetFees = classRows.reduce((sum, r) => sum + (parseFloat(r.target_fees) || 0), 0);
    const totalBalance = Math.max(0, targetFees - (totalPaid + totalExemption));
    const overallPercentage = targetFees > 0 ? Math.round((totalPaid / targetFees) * 1000) / 10 : 0;

    return {
      academic_year: acdmYear,
      summary: {
        total_students: totalStudents,
        target_fees: targetFees,
        total_paid: totalPaid,
        total_exemption: totalExemption,
        total_balance: totalBalance,
        total_rte: totalRte,
        collection_percentage: overallPercentage,
      },
      classwise: classRows.map((r) => ({
        ...r,
        fee_per_student: parseFloat(r.fee_per_student) || 0,
        target_fees: parseFloat(r.target_fees) || 0,
        fees_paid: parseFloat(r.fees_paid) || 0,
        exemption: parseFloat(r.exemption) || 0,
        balance: parseFloat(r.balance) || 0,
        collection_percentage: parseFloat(r.collection_percentage) || 0,
      })),
      monthly_trends: monthlyRows.map((r) => ({
        ...r,
        total_collected: parseFloat(r.total_collected) || 0,
      })),
      payment_modes: modeRows.map((r) => ({
        ...r,
        total_amount: parseFloat(r.total_amount) || 0,
      })),
    };
  }

  /**
   * Fees Comments Report (CI3: Report::Fees_comment)
   */
  static async getFeesComments(academicYear) {
    const acdm = academicYear || (await this.getActiveAcademicYearId());
    const query = `
      WITH paid_fees AS (
        SELECT 
          sprfees_studentid,
          academic_year,
          SUM(COALESCE(sprfees_amount, 0)) AS total_paid,
          SUM(CASE WHEN COALESCE(sprfees_exumption, 0) > 0 THEN sprfees_exumption ELSE 0 END) AS total_exempted
        FROM sprfees
        WHERE academic_year = ?
        GROUP BY sprfees_studentid, academic_year
      ),
      latest_fee_payment_ranked AS (
        SELECT 
          sprfees_studentid,
          sprfees_feesdate,
          sprfees_notes,
          sprfees_amount,
          academic_year,
          ROW_NUMBER() OVER (
            PARTITION BY sprfees_studentid
            ORDER BY sprfees_feesdate DESC, sprfees_pk DESC
          ) AS row_num
        FROM sprfees
        WHERE COALESCE(sprfees_amount, 0) > 0 AND sprfees_feesdate IS NOT NULL
      ),
      latest_fee_payment AS (
        SELECT 
          sprfees_studentid,
          sprfees_feesdate AS last_paid_date,
          sprfees_notes AS last_payment_notes,
          sprfees_amount AS last_paid_amount,
          academic_year AS last_payment_academic_year_id
        FROM latest_fee_payment_ranked
        WHERE row_num = 1
      ),
      student_class AS (
        SELECT 
          STVCLAS_student_id,
          STVCLAS_acdm,
          STVCLAS_standard,
          MAX(STVCLAS_class) AS STVCLAS_class
        FROM stvclas
        WHERE STVCLAS_acdm = ?
        GROUP BY STVCLAS_student_id, STVCLAS_acdm, STVCLAS_standard
      ),
      father_contact AS (
        SELECT 
          stvpcnf_student_id,
          MAX(NULLIF(TRIM(stvpcnf_mobile), '')) AS father_mobile_number,
          MAX(NULLIF(TRIM(stvpcnf_whatsapp), '')) AS father_whatsapp_number
        FROM stvpcnf
        WHERE LOWER(TRIM(stvpcnf_parent_typ)) = 'father'
        GROUP BY stvpcnf_student_id
      )
      SELECT 
        s.id AS id,
        s.SPRIDEN_student_id AS student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        sc.STVCLAS_standard AS class_name,
        sc.STVCLAS_class AS division,
        a.stvacdm_year AS academic_year,
        fc.father_mobile_number,
        fc.father_whatsapp_number,
        COALESCE(fs.stvfstr_fees, 0) AS total_fee,
        COALESCE(pf.total_paid, 0) AS total_paid,
        COALESCE(pf.total_exempted, 0) AS total_exempted,
        (COALESCE(fs.stvfstr_fees, 0) - COALESCE(pf.total_paid, 0) - COALESCE(pf.total_exempted, 0)) AS balance_fee,
        lfp.last_paid_date,
        lfp.last_paid_amount,
        lfp.last_payment_notes,
        last_a.stvacdm_year AS last_payment_academic_year
      FROM spriden s
      INNER JOIN student_class sc ON sc.STVCLAS_student_id = s.id
      INNER JOIN stvacdm a ON a.stvacdm_id = sc.STVCLAS_acdm
      LEFT JOIN stvfstr fs ON fs.stvfstr_std = sc.STVCLAS_standard AND fs.stvfstr_acdm_year = sc.STVCLAS_acdm
      LEFT JOIN paid_fees pf ON pf.sprfees_studentid = s.id AND pf.academic_year = sc.STVCLAS_acdm
      LEFT JOIN latest_fee_payment lfp ON lfp.sprfees_studentid = s.id
      LEFT JOIN stvacdm last_a ON last_a.stvacdm_id = lfp.last_payment_academic_year_id
      LEFT JOIN father_contact fc ON fc.stvpcnf_student_id = s.id
      WHERE s.SPRIDEN_student_status = 'A'
        AND s.SPRIDEN_user_type = 'st'
        AND (LOWER(TRIM(s.rte_status)) = 'no' OR s.rte_status IS NULL OR TRIM(s.rte_status) = '')
        AND sc.STVCLAS_acdm = ?
        AND COALESCE(TRIM(sc.STVCLAS_class), '') <> '*'
        AND lfp.last_payment_notes IS NOT NULL AND TRIM(lfp.last_payment_notes) != '' AND TRIM(lfp.last_payment_notes) != '-'
      ORDER BY sc.STVCLAS_standard, sc.STVCLAS_class, student_name
    `;
    const [rows] = await pool.query(query, [acdm, acdm, acdm]);
    return rows;
  }

  // ─── 13. All Previous Fees Report (CI3 Report::all_previous_fees_report) ───
  static async getAllPreviousFeesReport() {
    // 1. Get academic years for previous balance columns (between 5 and 8)
    const [years] = await pool.query(
      `SELECT stvacdm_id AS academic_year_id, stvacdm_year AS academic_year_name 
       FROM stvacdm 
       WHERE stvacdm_id BETWEEN 5 AND 8 
       ORDER BY stvacdm_id ASC`
    );

    const sql = `
      WITH paid_fees AS (
        SELECT
          sprfees_studentid,
          academic_year,
          SUM(COALESCE(sprfees_amount, 0)) AS total_paid,
          SUM(CASE WHEN COALESCE(sprfees_exumption, 0) > 0 THEN sprfees_exumption ELSE 0 END) AS total_exumpted,
          SUM(COALESCE(sprfees_amount, 0)) + SUM(CASE WHEN COALESCE(sprfees_exumption, 0) > 0 THEN sprfees_exumption ELSE 0 END) AS total_adjusted_paid
        FROM sprfees
        WHERE academic_year BETWEEN 5 AND 8
        GROUP BY sprfees_studentid, academic_year
      ),
      student_class AS (
        SELECT
          STVCLAS_student_id,
          STVCLAS_acdm,
          STVCLAS_standard,
          MAX(STVCLAS_class) AS STVCLAS_class
        FROM stvclas
        WHERE STVCLAS_acdm BETWEEN 5 AND 8
        GROUP BY STVCLAS_student_id, STVCLAS_acdm, STVCLAS_standard
      ),
      father_contact AS (
        SELECT
          stvpcnf_student_id,
          COALESCE(
            MAX(NULLIF(TRIM(stvpcnf_whatsapp), '')),
            MAX(NULLIF(TRIM(stvpcnf_mobile), '')),
            ''
          ) AS MobileNumber
        FROM stvpcnf
        WHERE LOWER(TRIM(stvpcnf_parent_typ)) = 'father'
        GROUP BY stvpcnf_student_id
      ),
      pending_fee_details AS (
        SELECT
          s.id,
          s.SPRIDEN_student_id,
          TRIM(CONCAT_WS(' ', NULLIF(TRIM(s.SPRIDEN_surname), ''), NULLIF(TRIM(s.SPRIDEN_student_name), ''), NULLIF(TRIM(s.SPRIDEN_father_name), ''))) AS name,
          y.stvacdm_id,
          y.stvacdm_year,
          sc.STVCLAS_standard,
          sc.STVCLAS_class,
          COALESCE(fs.stvfstr_fees, 0) AS total_fee,
          COALESCE(pf.total_adjusted_paid, 0) AS total_paid,
          (COALESCE(fs.stvfstr_fees, 0) - COALESCE(pf.total_adjusted_paid, 0)) AS pending_fee
        FROM spriden s
        INNER JOIN student_class sc ON sc.STVCLAS_student_id = s.id
        INNER JOIN stvacdm y ON y.stvacdm_id = sc.STVCLAS_acdm
        INNER JOIN stvfstr fs ON fs.stvfstr_std = sc.STVCLAS_standard AND fs.stvfstr_acdm_year = sc.STVCLAS_acdm
        LEFT JOIN paid_fees pf ON pf.sprfees_studentid = s.id AND pf.academic_year = sc.STVCLAS_acdm
        WHERE s.SPRIDEN_student_status = 'A'
          AND s.SPRIDEN_user_type = 'st'
          AND (LOWER(TRIM(s.rte_status)) = 'no' OR s.rte_status IS NULL OR TRIM(s.rte_status) = '')
          AND y.stvacdm_id BETWEEN 5 AND 8
      ),
      student_pending AS (
        SELECT
          d.id,
          d.SPRIDEN_student_id,
          d.name,
          COALESCE(fc.MobileNumber, '') AS MobileNumber,
          MAX(CASE WHEN d.stvacdm_id = 5 AND d.pending_fee > 0 THEN ROUND(d.pending_fee, 0) ELSE 0 END) AS Pending_5,
          MAX(CASE WHEN d.stvacdm_id = 6 AND d.pending_fee > 0 THEN ROUND(d.pending_fee, 0) ELSE 0 END) AS Pending_6,
          MAX(CASE WHEN d.stvacdm_id = 7 AND d.pending_fee > 0 THEN ROUND(d.pending_fee, 0) ELSE 0 END) AS Pending_7,
          MAX(CASE WHEN d.stvacdm_id = 8 AND d.pending_fee > 0 THEN ROUND(d.pending_fee, 0) ELSE 0 END) AS Pending_8
        FROM pending_fee_details d
        LEFT JOIN father_contact fc ON fc.stvpcnf_student_id = d.id
        GROUP BY d.id, d.SPRIDEN_student_id, d.name, fc.MobileNumber
      )
      SELECT
        *,
        (Pending_5 + Pending_6 + Pending_7 + Pending_8) AS TotalPending
      FROM student_pending
      WHERE (Pending_5 + Pending_6 + Pending_7 + Pending_8) > 0
      ORDER BY TotalPending DESC
    `;

    const [records] = await pool.query(sql);
    const totalPending = records.reduce((sum, r) => sum + Number(r.TotalPending || 0), 0);
    const averagePending = records.length > 0 ? Math.round(totalPending / records.length) : 0;

    return {
      academic_years: years,
      records,
      summary: {
        students: records.length,
        totalPending,
        averagePending,
      },
    };
  }

  // ─── 14. Last Year Fees Report (CI3 Report::last_year_fees_report) ───
  static async getLastYearFeesReport(academicYearId = null) {
    const targetYearId = academicYearId || 8; // Default to previous academic year (8: 2025-26)
    const sql = `
      WITH paid_fees AS (
        SELECT
          sprfees.sprfees_studentid,
          sprfees.academic_year,
          SUM(COALESCE(sprfees.sprfees_amount, 0)) AS total_paid,
          SUM(CASE WHEN COALESCE(sprfees_exumption, 0) > 0 THEN sprfees_exumption ELSE 0 END) AS total_exumpted,
          SUM(COALESCE(sprfees_amount, 0)) + SUM(CASE WHEN COALESCE(sprfees_exumption, 0) > 0 THEN sprfees_exumption ELSE 0 END) AS total_adjusted_paid
        FROM sprfees
        WHERE sprfees.academic_year = ?
        GROUP BY sprfees.sprfees_studentid, sprfees.academic_year
      ),
      student_class AS (
        SELECT
          stvclas.STVCLAS_student_id,
          stvclas.STVCLAS_acdm,
          stvclas.STVCLAS_standard,
          MAX(stvclas.STVCLAS_class) AS STVCLAS_class
        FROM stvclas
        WHERE stvclas.STVCLAS_acdm = ?
        GROUP BY stvclas.STVCLAS_student_id, stvclas.STVCLAS_acdm, stvclas.STVCLAS_standard
      ),
      father_contact AS (
        SELECT
          stvpcnf.stvpcnf_student_id,
          COALESCE(
            MAX(NULLIF(TRIM(stvpcnf_whatsapp), '')),
            MAX(NULLIF(TRIM(stvpcnf_mobile), '')),
            ''
          ) AS MobileNumber
        FROM stvpcnf
        WHERE LOWER(TRIM(stvpcnf_parent_typ)) = 'father'
        GROUP BY stvpcnf_student_id
      )
      SELECT
        s.id,
        s.SPRIDEN_student_id,
        TRIM(CONCAT_WS(' ', NULLIF(TRIM(s.SPRIDEN_surname), ''), NULLIF(TRIM(s.SPRIDEN_student_name), ''), NULLIF(TRIM(s.SPRIDEN_father_name), ''))) AS name,
        COALESCE(fc.MobileNumber, '') AS MobileNumber,
        y.stvacdm_year AS Academic_year,
        sc.STVCLAS_standard AS Standard,
        sc.STVCLAS_class AS Division,
        COALESCE(fs.stvfstr_fees, 0) AS Total_fee,
        COALESCE(pf.total_paid, 0) AS Total_paid,
        COALESCE(pf.total_exumpted, 0) AS Total_exumpted,
        (COALESCE(fs.stvfstr_fees, 0) - COALESCE(pf.total_adjusted_paid, 0)) AS Pending_fees
      FROM spriden s
      INNER JOIN student_class sc ON sc.STVCLAS_student_id = s.id
      INNER JOIN stvacdm y ON y.stvacdm_id = sc.STVCLAS_acdm
      INNER JOIN stvfstr fs ON fs.stvfstr_std = sc.STVCLAS_standard AND fs.stvfstr_acdm_year = sc.STVCLAS_acdm
      LEFT JOIN paid_fees pf ON pf.sprfees_studentid = s.id AND pf.academic_year = sc.STVCLAS_acdm
      LEFT JOIN father_contact fc ON fc.stvpcnf_student_id = s.id
      WHERE s.SPRIDEN_student_status = 'A'
        AND s.SPRIDEN_user_type = 'st'
        AND (LOWER(TRIM(s.rte_status)) = 'no' OR s.rte_status IS NULL OR TRIM(s.rte_status) = '')
        AND (COALESCE(fs.stvfstr_fees, 0) - COALESCE(pf.total_adjusted_paid, 0)) > 0
      ORDER BY Pending_fees DESC
    `;

    const [records] = await pool.query(sql, [targetYearId, targetYearId]);
    const totalPending = records.reduce((sum, r) => sum + Number(r.Pending_fees || 0), 0);
    const totalPaid = records.reduce((sum, r) => sum + Number(r.Total_paid || 0), 0);
    const totalExempted = records.reduce((sum, r) => sum + Number(r.Total_exumpted || 0), 0);

    return {
      academic_year_id: targetYearId,
      records,
      summary: {
        students: records.length,
        totalPending,
        totalPaid,
        totalExempted,
      },
    };
  }

  // ─── 15. Daily & Datewise Fees Report (CI3 Report::daily_fees_report) ───
  static async getDailyFeesReport({ fromDate, toDate, academicYear, standard, division, newAdmission }) {
    const from = fromDate || new Date().toISOString().slice(0, 10);
    const to = toDate || from;

    let query = `
      SELECT 
        f.sprfees_pk AS id,
        f.sprfees_pk AS receipt_no,
        f.sprfees_feesdate AS fee_date,
        f.sprfees_amount AS amount,
        f.payment_mode,
        COALESCE(u.spruser_name, f.sprfees_recivedby, 'System') AS received_by,
        f.sprfees_notes AS notes,
        f.sprfees_exumption AS exemption,
        f.academic_year AS academic_year_id,
        ac.stvacdm_year AS academic_year,
        s.SPRIDEN_student_id AS student_id,
        s.spriden_gr_number AS gr_no,
        TRIM(CONCAT_WS(' ', NULLIF(TRIM(s.SPRIDEN_surname), ''), NULLIF(TRIM(s.SPRIDEN_student_name), ''), NULLIF(TRIM(s.SPRIDEN_father_name), ''))) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division
      FROM sprfees f
      JOIN spriden s ON s.id = f.sprfees_studentid
      LEFT JOIN spruser u ON f.sprfees_recivedby = u.spruser_id
      LEFT JOIN stvacdm ac ON ac.stvacdm_id = f.academic_year
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = f.academic_year
      WHERE 1=1
    `;

    const params = [];

    if (academicYear && academicYear !== 'all') {
      query += ` AND f.academic_year = ?`;
      params.push(academicYear);
    }
    if (from && to) {
      query += ` AND f.sprfees_feesdate BETWEEN ? AND ?`;
      params.push(from, to);
    }
    if (standard && standard !== 'all') {
      query += ` AND c.STVCLAS_standard = ?`;
      params.push(standard);
    }
    if (division && division !== 'all') {
      query += ` AND c.STVCLAS_class = ?`;
      params.push(division);
    }
    if (newAdmission === '1' || newAdmission === true) {
      query += ` AND s.spriden_new_admission = '1'`;
    }

    query += ` ORDER BY f.sprfees_feesdate DESC, f.sprfees_pk DESC LIMIT 500`;

    const [records] = await pool.query(query, params);

    const totalCollected = records.reduce((sum, r) => sum + Number(r.amount || 0), 0);
    const cashTotal = records.filter(r => (r.payment_mode || '').toLowerCase() === 'cash').reduce((sum, r) => sum + Number(r.amount || 0), 0);
    const onlineTotal = records.filter(r => (r.payment_mode || '').toLowerCase() === 'online' || (r.payment_mode || '').toLowerCase() === 'bank').reduce((sum, r) => sum + Number(r.amount || 0), 0);
    const otherTotal = totalCollected - cashTotal - onlineTotal;

    return {
      from_date: from,
      to_date: to,
      records,
      summary: {
        total_transactions: records.length,
        total_collected: totalCollected,
        cash_total: cashTotal,
        online_total: onlineTotal,
        other_total: otherTotal,
      },
    };
  }
}

export default FeesModel;
