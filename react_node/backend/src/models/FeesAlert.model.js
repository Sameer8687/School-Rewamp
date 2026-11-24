import pool from '../config/db.js';

class FeesAlertModel {
  /**
   * Helper to parse installments string format: "amount->date->exemption->received_by,..."
   */
  static parseInstallments(instStr) {
    if (!instStr) return [];
    return instStr.split(',').map((item) => {
      const parts = item.split('->').map((p) => p.trim());
      return {
        amount: parseFloat(parts[0]) || 0,
        date: parts[1] || '',
        exemption: parseFloat(parts[2]) || 0,
        receivedBy: parts[3] || 'NA',
      };
    });
  }

  /**
   * Helper to parse balance installment per year string format:
   * "acdm_id -> year -> total_paid -> total_fees -> balance, ..."
   */
  static parseBalanceInstallment(str) {
    if (!str) return [];
    return str.split(', ').map((item) => {
      const parts = item.split(' -> ').map((p) => p.trim());
      return {
        academicYearId: parts[0] || '',
        academicYearName: parts[1] || '',
        paid: parseFloat(parts[2]) || 0,
        total: parseFloat(parts[3]) || 0,
        balance: parseFloat(parts[4]) || 0,
      };
    });
  }

  /**
   * Get student fees alert data by Class, Division, and Academic Year
   * (Maps to CI3 Welcome::send_msg and Student_model::getFeesMassage)
   */
  static async getFeesMessageStudents({ standard, division, academicYear }) {
    const sql = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        s.SPRIDEN_student_name,
        s.SPRIDEN_father_name,
        s.SPRIDEN_surname,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        s.rte_status,
        IFNULL(SUM(f.sprfees_exumption), 0) AS total_exemption,
        cn.stvpcnf_whatsapp,
        cn.stvpcnf_mobile,
        IFNULL(fs.stvfstr_fees, 0) AS total_fees,
        IFNULL(SUM(f.sprfees_amount), 0) AS paid_amount,
        (IFNULL(SUM(f.sprfees_amount), 0) + IFNULL(SUM(f.sprfees_exumption), 0)) AS total_paid,
        (IFNULL(fs.stvfstr_fees, 0) - (IFNULL(SUM(f.sprfees_amount), 0) + IFNULL(SUM(f.sprfees_exumption), 0))) AS balance,
        (
          SELECT GROUP_CONCAT(CONCAT(sf.sprfees_amount, '->', IFNULL(sf.sprfees_feesdate, ''), '->', IFNULL(sf.sprfees_exumption, 0), '->', IFNULL(u.spruser_name, 'NA')))
          FROM sprfees sf
          LEFT JOIN spruser u ON u.spruser_id = sf.sprfees_recivedby
          WHERE sf.academic_year = c.STVCLAS_acdm AND sf.sprfees_studentid = s.id
        ) AS installments
      FROM spriden s
      JOIN stvclas c ON c.STVCLAS_student_id = s.id
      LEFT JOIN stvpcnf cn ON cn.stvpcnf_student_id = s.id
      LEFT JOIN sprfees f ON f.sprfees_studentid = s.id AND f.academic_year = c.STVCLAS_acdm
      LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = c.STVCLAS_acdm
      WHERE s.spriden_student_status = 'A'
        AND (s.rte_status = 'No' OR s.rte_status IS NULL)
        AND s.SPRIDEN_user_type = 'st'
        AND (c.STVCLAS_standard = ? OR ? = 'all')
        AND (c.STVCLAS_class = ? OR ? = 'all')
        AND c.STVCLAS_acdm = ?
      GROUP BY 
        s.id, s.SPRIDEN_student_id, s.spriden_gr_number, s.SPRIDEN_student_name, s.SPRIDEN_father_name,
        s.SPRIDEN_surname, c.STVCLAS_standard, c.STVCLAS_class, s.rte_status, cn.stvpcnf_whatsapp, cn.stvpcnf_mobile, fs.stvfstr_fees
      ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED), name ASC
    `;

    const [rows] = await pool.query(sql, [standard, standard, division, division, academicYear]);
    return rows.map((r) => ({
      ...r,
      total_fees: parseFloat(r.total_fees) || 0,
      paid_amount: parseFloat(r.paid_amount) || 0,
      total_exemption: parseFloat(r.total_exemption) || 0,
      total_paid: parseFloat(r.total_paid) || 0,
      balance: parseFloat(r.balance) || 0,
      installments_list: this.parseInstallments(r.installments),
    }));
  }

  /**
   * Get detailed student fee balance history across academic years
   * (Maps to CI3 Student_model::getFeesDetails)
   */
  static async getStudentFeesHistory(studentId) {
    const sql = `
      SELECT 
        s.id, 
        s.SPRIDEN_student_id, 
        s.SPRIDEN_surname, 
        s.SPRIDEN_student_name, 
        s.SPRIDEN_father_name, 
        c.STVCLAS_standard, 
        c.STVCLAS_class, 
        cn.stvpcnf_whatsapp, 
        cn.stvpcnf_mobile,
        SUM(f.sprfees_amount) AS total_paid, 
        f.academic_year, 
        a.stvacdm_year, 
        fs.stvfstr_fees, 
        (fs.stvfstr_fees - SUM(f.sprfees_amount)) AS balance, 
        GROUP_CONCAT(CONCAT(f.sprfees_amount, '->', IFNULL(f.sprfees_feesdate, ''), '->', IFNULL(f.sprfees_exumption, 0), '->', IFNULL(u.spruser_name, 'NA')) ORDER BY f.sprfees_feesdate ASC) AS installments 
      FROM stvclas c 
      JOIN spriden s ON s.id = c.STVCLAS_student_id 
      JOIN sprfees f ON f.sprfees_studentid = s.id AND f.academic_year = c.STVCLAS_acdm 
      JOIN stvacdm a ON a.stvacdm_id = c.STVCLAS_acdm 
      JOIN stvfstr fs ON fs.stvfstr_acdm_year = c.STVCLAS_acdm AND fs.stvfstr_std = c.STVCLAS_standard 
      LEFT JOIN stvpcnf cn ON cn.stvpcnf_student_id = s.id 
      LEFT JOIN spruser u ON u.spruser_id = f.sprfees_recivedby 
      WHERE s.id = ?
      GROUP BY 
        s.id, s.SPRIDEN_student_id, c.STVCLAS_standard, c.STVCLAS_class, cn.stvpcnf_whatsapp, cn.stvpcnf_mobile, f.academic_year, a.stvacdm_year, fs.stvfstr_fees 
      HAVING (fs.stvfstr_fees - SUM(f.sprfees_amount)) > 0 
      ORDER BY f.academic_year
    `;

    const [rows] = await pool.query(sql, [studentId]);
    return rows.map((r) => ({
      ...r,
      total_paid: parseFloat(r.total_paid) || 0,
      stvfstr_fees: parseFloat(r.stvfstr_fees) || 0,
      balance: parseFloat(r.balance) || 0,
      installments_list: this.parseInstallments(r.installments),
    }));
  }

  /**
   * Admission Confirmation pending alerts
   * (Maps to CI3 FeesAlert_model::admission_confirmation)
   */
  static async getAdmissionConfirmationAlerts({ academicYear, standard, division }) {
    const sql = `
      SELECT
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
        pc.stvpcnf_mobile,
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
          LEFT JOIN stvacdm a ON a.stvacdm_id = c2.STVCLAS_acdm
          LEFT JOIN (
            SELECT
              stvfstr_std,
              stvfstr_acdm_year,
              SUM(IFNULL(stvfstr_fees, 0)) AS total_fees
            FROM stvfstr
            WHERE stvfstr_acdm_year NOT IN ('1', '2', '3', '4')
            GROUP BY stvfstr_std, stvfstr_acdm_year
          ) f2 ON f2.stvfstr_std = c2.STVCLAS_standard AND f2.stvfstr_acdm_year = c2.STVCLAS_acdm
          LEFT JOIN (
            SELECT
              sprfees_studentid,
              academic_year,
              SUM(IFNULL(sprfees_amount, 0)) AS total_paid
            FROM sprfees
            WHERE academic_year NOT IN ('1', '2', '3', '4')
            GROUP BY sprfees_studentid, academic_year
          ) p2 ON p2.sprfees_studentid = c2.STVCLAS_student_id AND p2.academic_year = c2.STVCLAS_acdm
          WHERE c2.STVCLAS_student_id = s.id
            AND c2.STVCLAS_acdm NOT IN ('1', '2', '3', '4')
        ) AS balance_installment
      FROM spriden s
      INNER JOIN stvclas c ON c.STVCLAS_student_id = s.id
      LEFT JOIN stvfstr f ON f.stvfstr_std = c.STVCLAS_standard AND f.stvfstr_acdm_year = ?
      LEFT JOIN (
        SELECT
          sprfees_studentid,
          SUM(IFNULL(sprfees_amount, 0)) AS total_paid
        FROM sprfees
        WHERE academic_year = ?
        GROUP BY sprfees_studentid
      ) p ON p.sprfees_studentid = s.id
      LEFT JOIN stvpcnf pc ON pc.stvpcnf_student_id = s.id
      WHERE c.STVCLAS_standard = ?
        AND c.STVCLAS_class = ?
        AND c.STVCLAS_acdm = ?
        AND s.spriden_student_status = 'A'
        AND (s.rte_status IS NULL OR s.rte_status = 'No')
        AND (IFNULL(f.stvfstr_fees, 0) - IFNULL(p.total_paid, 0)) > 0
      ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED)
    `;

    const [rows] = await pool.query(sql, [academicYear, academicYear, standard, division, academicYear]);
    return rows.map((r) => {
      const balanceHistory = this.parseBalanceInstallment(r.balance_installment);
      return {
        ...r,
        name: [r.SPRIDEN_surname, r.SPRIDEN_student_name, r.SPRIDEN_father_name].filter(Boolean).join(' '),
        total_fees_to_be_paid: parseFloat(r.total_fees_to_be_paid) || 0,
        total_fees_paid_by_st: parseFloat(r.total_fees_paid_by_st) || 0,
        remaining_fees_to_be_paid: parseFloat(r.remaining_fees_to_be_paid) || 0,
        balance_history: balanceHistory,
      };
    });
  }
}

export default FeesAlertModel;
