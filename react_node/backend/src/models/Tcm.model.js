/**
 * TCM Model — Teacher Calling & Fee Recovery Management (Matches CI3 TCM_model)
 * Interacts with stvtcmr, spriden, stvclas, stvpcnf, sprfees, stvfstr
 */
import pool from '../config/db.js';

class TcmModel {
  /**
   * Get active academic year id
   */
  static async getActiveAcademicYear() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 3;
  }

  /**
   * Get list of teachers available for calling assignment
   */
  static async getTeachers() {
    const [rows] = await pool.query(`
      SELECT 
        id,
        SPRIDEN_student_id AS teacher_code,
        CONCAT(IFNULL(SPRIDEN_surname, ''), ' ', IFNULL(SPRIDEN_student_name, ''), ' ', IFNULL(SPRIDEN_father_name, '')) AS name
      FROM spriden 
      WHERE SPRIDEN_user_type = 'tc' 
        AND SPRIDEN_student_id IS NOT NULL
        AND (SPRIDEN_student_status = 'A' OR SPRIDEN_student_status IS NULL)
      ORDER BY SPRIDEN_student_name ASC
    `);
    return rows;
  }

  /**
   * Search students for calling follow-up
   */
  static async searchStudents(searchTerm, academicYearId = null) {
    const year = academicYearId || await this.getActiveAcademicYear();
    const term = `%${(searchTerm || '').trim()}%`;

    const [students] = await pool.query(`
      SELECT 
        s.id,
        s.SPRIDEN_student_id AS student_code,
        s.SPRIDEN_surname AS surname,
        s.SPRIDEN_student_name AS student_name,
        s.SPRIDEN_father_name AS father_name,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS full_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS phone,
        (SELECT IFNULL(SUM(sprfees_amount), 0) FROM sprfees WHERE sprfees_studentid = s.id AND academic_year = ?) AS fees_paid,
        (SELECT IFNULL(stvfstr_fees, 0) FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = ? LIMIT 1) AS total_fees
      FROM spriden s
      JOIN stvclas c ON s.id = c.STVCLAS_student_id
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.SPRIDEN_student_status = 'A'
        AND c.STVCLAS_acdm = ?
        AND (
          CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) LIKE ?
          OR s.SPRIDEN_student_id LIKE ?
        )
      LIMIT 20
    `, [year, year, year, term, term]);

    return students.map(st => {
      const paid = Number(st.fees_paid) || 0;
      const total = Number(st.total_fees) || 0;
      return {
        ...st,
        fees_paid: paid,
        total_fees: total,
        balance_fees: Math.max(0, total - paid)
      };
    });
  }

  /**
   * Get single student fee & contact details
   */
  static async getStudentFeeDetails(studentId, academicYearId = null) {
    const year = academicYearId || await this.getActiveAcademicYear();

    const [rows] = await pool.query(`
      SELECT 
        s.id,
        s.SPRIDEN_student_id AS student_code,
        s.SPRIDEN_surname AS surname,
        s.SPRIDEN_student_name AS student_name,
        s.SPRIDEN_father_name AS father_name,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS full_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS phone,
        (SELECT IFNULL(SUM(sprfees_amount), 0) FROM sprfees WHERE sprfees_studentid = s.id AND academic_year = ?) AS fees_paid,
        (SELECT IFNULL(stvfstr_fees, 0) FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = ? LIMIT 1) AS total_fees
      FROM spriden s
      LEFT JOIN stvclas c ON s.id = c.STVCLAS_student_id AND c.STVCLAS_acdm = ?
      WHERE s.id = ? AND s.SPRIDEN_user_type = 'st'
      LIMIT 1
    `, [year, year, year, studentId]);

    if (!rows.length) return null;

    const st = rows[0];
    const paid = Number(st.fees_paid) || 0;
    const total = Number(st.total_fees) || 0;

    return {
      ...st,
      fees_paid: paid,
      total_fees: total,
      balance_fees: Math.max(0, total - paid)
    };
  }

  /**
   * Get all calling records
   */
  static async getAllRecords(academicYearId = null) {
    const year = academicYearId || await this.getActiveAcademicYear();

    const [rows] = await pool.query(`
      SELECT 
        t.stvtcmr_id AS id,
        t.stvtcmr_student_id AS student_id,
        t.stvtcmr_teacher_id AS teacher_code,
        t.stvtcmr_notes AS notes,
        t.stvtcmr_landmark AS landmark,
        DATE_FORMAT(t.created_at, '%Y-%m-%d') AS created_at,
        t.created_by,
        s.SPRIDEN_student_id AS student_code,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        p.stvpcnf_mobile AS phone,
        (SELECT CONCAT(IFNULL(tch.SPRIDEN_surname, ''), ' ', IFNULL(tch.SPRIDEN_student_name, ''), ' ', IFNULL(tch.SPRIDEN_father_name, '')) 
         FROM spriden tch WHERE tch.SPRIDEN_student_id = t.stvtcmr_teacher_id LIMIT 1) AS teacher_name,
        (SELECT IFNULL(SUM(sprfees_amount), 0) FROM sprfees WHERE sprfees_studentid = s.id AND academic_year = ?) AS fees_paid,
        (SELECT IFNULL(stvfstr_fees, 0) FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = ? LIMIT 1) AS total_fees
      FROM stvtcmr t
      JOIN spriden s ON s.id = t.stvtcmr_student_id
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      ORDER BY t.stvtcmr_id DESC
    `, [year, year, year]);

    return rows.map(r => {
      const paid = Number(r.fees_paid) || 0;
      const total = Number(r.total_fees) || 0;
      return {
        ...r,
        fees_paid: paid,
        total_fees: total,
        balance_fees: Math.max(0, total - paid)
      };
    });
  }

  /**
   * Create calling record
   */
  static async createRecord({ student_id, teacher_id, notes, landmark, created_by }) {
    const today = new Date().toISOString().slice(0, 10);
    const [result] = await pool.query(`
      INSERT INTO stvtcmr (
        stvtcmr_student_id,
        stvtcmr_teacher_id,
        stvtcmr_notes,
        stvtcmr_landmark,
        created_at,
        created_by
      ) VALUES (?, ?, ?, ?, ?, ?)
    `, [student_id, teacher_id, notes, landmark, today, created_by || 'Admin']);

    return result.insertId;
  }

  /**
   * Delete record
   */
  static async deleteRecord(id) {
    const [result] = await pool.query('DELETE FROM stvtcmr WHERE stvtcmr_id = ?', [id]);
    return result.affectedRows > 0;
  }
}

export default TcmModel;

