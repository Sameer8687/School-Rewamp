/**
 * StudentExtras Model — ID Cards (stvicard), Uniform/Tailor Tracking (stvstlr), and Remarks (stvrmrk, sprremark)
 * Matches CI3 Student/i_card, Student/student_card, Student/tailor_details, Student/remarks, Student/student_comments
 */
import pool from '../config/db.js';

class StudentExtrasModel {
  static async getActiveAcademicYear() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 9;
  }

  static async getStandardsAndDivisions() {
    const [standards] = await pool.query(`
      SELECT DISTINCT STVCLAS_standard AS standard
      FROM stvclas
      WHERE STVCLAS_standard IS NOT NULL
        AND STVCLAS_standard != ''
        AND STVCLAS_standard NOT LIKE '%PHP%'
        AND STVCLAS_standard NOT LIKE '%Error%'
        AND LENGTH(STVCLAS_standard) <= 15
      ORDER BY STVCLAS_standard ASC
    `);
    const [divisions] = await pool.query(`
      SELECT DISTINCT STVCLAS_class AS division
      FROM stvclas
      WHERE STVCLAS_class IS NOT NULL AND STVCLAS_class != ''
      ORDER BY STVCLAS_class ASC
    `);
    const [years] = await pool.query(`
      SELECT stvacdm_id AS id, stvacdm_year AS year
      FROM stvacdm
      ORDER BY stvacdm_id DESC
    `);
    const [school] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    const activeYear = school[0]?.stvschool_acdm_year || (years[0]?.id ? String(years[0].id) : '9');

    return {
      standards: standards.map(s => s.standard),
      divisions: divisions.map(d => d.division),
      academic_years: years,
      active_year: activeYear
    };
  }

  // ═══════════════════════════════════════════════════════════════════
  // 1. STUDENT ID CARDS (stvicard)
  // ═══════════════════════════════════════════════════════════════════
  static async getICardStudents({ standard, division, academic_year }) {
    const year = academic_year || await this.getActiveAcademicYear();
    let sql = `
      SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS student_code,
        s.spriden_gr_number AS gr_number,
        s.SPRIDEN_birthdate AS dob,
        s.SPRIDEN_BGRP AS blood_group,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        c.STVCLAS_rollno AS roll_no,
        p.stvpcnf_mobile AS mobile,
        p.stvpcnf_address AS address,
        ic.id AS icard_id,
        ic.stvicard_stu_img AS photo_url,
        DATE_FORMAT(ic.updated_at, '%Y-%m-%d') AS photo_updated_at
      FROM spriden s
      JOIN stvclas c ON s.id = c.STVCLAS_student_id
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      LEFT JOIN stvicard ic ON ic.stvicard_student_id = s.id
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.spriden_student_status = 'A'
        AND c.STVCLAS_acdm = ?
    `;
    const params = [year];

    if (standard) {
      sql += ' AND c.STVCLAS_standard = ?';
      params.push(standard);
    }
    if (division && division !== 'all') {
      sql += ' AND c.STVCLAS_class = ?';
      params.push(division);
    }

    sql += ' ORDER BY CAST(c.STVCLAS_rollno AS UNSIGNED) ASC, s.SPRIDEN_student_name ASC';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  static async saveICardPhoto(studentId, photoPath) {
    const [existing] = await pool.query(
      'SELECT id FROM stvicard WHERE stvicard_student_id = ? LIMIT 1',
      [studentId]
    );
    if (existing.length > 0) {
      await pool.query(
        'UPDATE stvicard SET stvicard_stu_img = ?, updated_at = NOW() WHERE id = ?',
        [photoPath, existing[0].id]
      );
      return existing[0].id;
    } else {
      const [res] = await pool.query(
        'INSERT INTO stvicard (stvicard_student_id, stvicard_stu_img, created_at, updated_at) VALUES (?, ?, NOW(), NOW())',
        [studentId, photoPath]
      );
      return res.insertId;
    }
  }

  // ═══════════════════════════════════════════════════════════════════
  // 2. TAILOR / UNIFORM TRACKING (stvstlr)
  // ═══════════════════════════════════════════════════════════════════
  static async getTailorStudents({ standard, division, academic_year }) {
    const year = academic_year || await this.getActiveAcademicYear();
    let sql = `
      SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS student_code,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        t.stvstlr_id AS tailor_record_id,
        IFNULL(t.stvstlr_tailor, '') AS tailor_name,
        IFNULL(t.stvstlr_isrec, 'No') AS is_received,
        t.stvstlr_updated_at AS updated_at
      FROM spriden s
      JOIN stvclas c ON s.id = c.STVCLAS_student_id
      LEFT JOIN stvstlr t ON t.stvstlr_sid = s.id
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.spriden_student_status = 'A'
        AND c.STVCLAS_acdm = ?
    `;
    const params = [year];

    if (standard) {
      sql += ' AND c.STVCLAS_standard = ?';
      params.push(standard);
    }
    if (division && division !== 'all') {
      sql += ' AND c.STVCLAS_class = ?';
      params.push(division);
    }

    sql += ' ORDER BY s.SPRIDEN_student_name ASC';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  static async saveTailorDetails(records, userId) {
    if (!Array.isArray(records) || records.length === 0) return 0;
    const today = new Date().toISOString().slice(0, 10);
    let updated = 0;

    for (const r of records) {
      const [existing] = await pool.query(
        'SELECT stvstlr_id FROM stvstlr WHERE stvstlr_sid = ? LIMIT 1',
        [r.student_id]
      );
      if (existing.length > 0) {
        await pool.query(
          `UPDATE stvstlr 
           SET stvstlr_tailor = ?, stvstlr_isrec = ?, stvstlr_updated_by = ?, stvstlr_updated_at = ?
           WHERE stvstlr_id = ?`,
          [r.tailor_name || '', r.is_received || 'No', userId || '1', today, existing[0].stvstlr_id]
        );
      } else if (r.tailor_name || r.is_received === 'Yes') {
        await pool.query(
          `INSERT INTO stvstlr (
            stvstlr_class, stvstlr_div, stvstlr_sid, stvstlr_student_id, stvstlr_sname,
            stvstlr_tailor, stvstlr_created_by, stvstlr_created_on, stvstlr_isrec
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
          [
            r.standard || '',
            r.division || '',
            r.student_id,
            r.student_code || '',
            r.student_name || '',
            r.tailor_name || '',
            userId || '1',
            today,
            r.is_received || 'No'
          ]
        );
      }
      updated++;
    }
    return updated;
  }

  // ═══════════════════════════════════════════════════════════════════
  // 3. STUDENT REMARKS & ADMIN COMMENTS (stvrmrk & sprremark)
  // ═══════════════════════════════════════════════════════════════════
  static async getRemarks({ standard, student_id, academic_year }) {
    let sql = `
      SELECT 
        r.stvrmrk_id AS id,
        r.stvrmrk_teacher_id AS teacher_code,
        r.stvrmrk_student_id AS student_id,
        r.stvrmrk_std AS standard,
        DATE_FORMAT(r.stvrmrk_date, '%Y-%m-%d') AS remark_date,
        r.stvrmrk_remark AS remark,
        r.stvrmrk_reply AS reply,
        DATE_FORMAT(r.stvrmrk_date_of_reply, '%Y-%m-%d') AS reply_date,
        r.stvrmrk_status AS status,
        r.stvrmrk_academic_year AS academic_year,
        (SELECT CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, ''))
         FROM spriden s WHERE s.id = r.stvrmrk_student_id OR s.SPRIDEN_student_id = r.stvrmrk_student_id LIMIT 1) AS student_name,
        (SELECT s.SPRIDEN_student_id FROM spriden s WHERE s.id = r.stvrmrk_student_id OR s.SPRIDEN_student_id = r.stvrmrk_student_id LIMIT 1) AS student_code,
        (SELECT CONCAT(IFNULL(t.SPRIDEN_surname, ''), ' ', IFNULL(t.SPRIDEN_student_name, ''))
         FROM spriden t WHERE t.SPRIDEN_student_id = r.stvrmrk_teacher_id OR t.id = r.stvrmrk_teacher_id LIMIT 1) AS teacher_name
      FROM stvrmrk r
      WHERE 1=1
    `;
    const params = [];

    if (academic_year && academic_year !== 'all') {
      sql += ' AND (r.stvrmrk_academic_year = ? OR r.stvrmrk_academic_year IS NULL)';
      params.push(academic_year);
    }
    if (standard && standard !== 'all') {
      sql += ' AND r.stvrmrk_std = ?';
      params.push(standard);
    }
    if (student_id) {
      sql += ' AND r.stvrmrk_student_id = ?';
      params.push(student_id);
    }

    sql += ' ORDER BY r.stvrmrk_id DESC LIMIT 250';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  static async createRemark({ teacher_id, student_id, standard, remark, academic_year }) {
    const year = academic_year || await this.getActiveAcademicYear();
    const today = new Date().toISOString().slice(0, 10);
    const [res] = await pool.query(
      `INSERT INTO stvrmrk (
        stvrmrk_teacher_id, stvrmrk_student_id, stvrmrk_std, stvrmrk_date, stvrmrk_remark, stvrmrk_status, stvrmrk_academic_year
      ) VALUES (?, ?, ?, ?, ?, 'Pending', ?)`,
      [teacher_id || 'Admin', student_id, standard || '', today, remark, year]
    );
    return res.insertId;
  }

  static async replyRemark(id, { reply, status }) {
    const today = new Date().toISOString().slice(0, 10);
    const [res] = await pool.query(
      'UPDATE stvrmrk SET stvrmrk_reply = ?, stvrmrk_date_of_reply = ?, stvrmrk_status = ? WHERE stvrmrk_id = ?',
      [reply || '', today, status || 'Replied', id]
    );
    return res.affectedRows > 0;
  }

  static async deleteRemark(id) {
    const [res] = await pool.query('DELETE FROM stvrmrk WHERE stvrmrk_id = ?', [id]);
    return res.affectedRows > 0;
  }

  // Admin comments (sprremark)
  static async getAdminNotes() {
    const [rows] = await pool.query(`
      SELECT 
        r.remark_id AS id,
        r.id AS student_id,
        r.student_name,
        r.due_date,
        r.note,
        s.SPRIDEN_student_id AS student_code
      FROM sprremark r
      LEFT JOIN spriden s ON s.id = r.id
      ORDER BY r.remark_id DESC
      LIMIT 150
    `);
    return rows;
  }

  static async createAdminNote({ student_id, student_name, due_date, note }) {
    const [res] = await pool.query(
      'INSERT INTO sprremark (id, student_name, due_date, note) VALUES (?, ?, ?, ?)',
      [student_id, student_name || '', due_date || '', note]
    );
    return res.insertId;
  }

  static async deleteAdminNote(id) {
    const [res] = await pool.query('DELETE FROM sprremark WHERE remark_id = ?', [id]);
    return res.affectedRows > 0;
  }
}

export default StudentExtrasModel;
