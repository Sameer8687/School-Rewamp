/**
 * ClassReport Model — Student Profile DB (CI3: ClassReport.php)
 * Queries live MySQL tables: spriden, stvclas, stvpcnf, sprmark, spratnd, stvrmrk, stvsubj, stvexam
 */
import pool from '../config/db.js';

class ClassReportModel {
  static async getActiveAcademicYear() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year ? String(rows[0].stvschool_acdm_year) : '';
  }

  static async getOptions() {
    const activeYear = await this.getActiveAcademicYear();
    const [standards] = await pool.query(`
      SELECT DISTINCT STVCLAS_standard AS standard
      FROM stvclas
      WHERE STVCLAS_standard IS NOT NULL
        AND TRIM(STVCLAS_standard) <> ''
        AND STVCLAS_standard NOT LIKE '%PHP%'
        AND STVCLAS_standard NOT LIKE '%Error%'
        AND LENGTH(STVCLAS_standard) <= 15
      ORDER BY (STVCLAS_standard + 0) ASC, STVCLAS_standard ASC
    `);
    const [divisions] = await pool.query(`
      SELECT DISTINCT STVCLAS_class AS division
      FROM stvclas
      WHERE STVCLAS_class IS NOT NULL
        AND TRIM(STVCLAS_class) <> ''
        AND LENGTH(STVCLAS_class) <= 5
      ORDER BY STVCLAS_class ASC
    `);
    return { active_year: activeYear, standards, divisions };
  }

  static async getClassStudents({ standard, division, academic_year }) {
    const year = academic_year || (await this.getActiveAcademicYear());
    let sql = `
      SELECT
        s.id AS student_id,
        s.SPRIDEN_student_id AS student_code,
        s.spriden_gr_number AS gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        c.STVCLAS_rollno AS roll_no
      FROM stvclas c
      INNER JOIN spriden s ON s.id = c.STVCLAS_student_id
      WHERE c.STVCLAS_acdm = ?
        AND s.spriden_student_status = 'A'
        AND s.SPRIDEN_user_type = 'st'
    `;
    const params = [year];
    if (standard) {
      sql += ' AND c.STVCLAS_standard = ?';
      params.push(standard);
    }
    if (division) {
      sql += ' AND c.STVCLAS_class = ?';
      params.push(division);
    }
    sql += ' ORDER BY (c.STVCLAS_rollno + 0) ASC, s.SPRIDEN_student_name ASC';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  static async getStudentProfileData(studentId, academic_year) {
    const year = academic_year || (await this.getActiveAcademicYear());

    const [infoRows] = await pool.query(
      `
      SELECT
        s.id AS student_id,
        s.SPRIDEN_student_id AS student_code,
        s.spriden_gr_number AS gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.SPRIDEN_father_name AS father_name,
        s.SPRIDEN_birthdate AS dob,
        s.SPRIDEN_BGRP AS blood_group,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        c.STVCLAS_rollno AS roll_no,
        (SELECT COALESCE(p.stvpcnf_mobile, p.stvpcnf_mobile2) FROM stvpcnf p WHERE p.stvpcnf_student_id = s.id LIMIT 1) AS contact
      FROM spriden s
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = ?
      WHERE s.id = ?
      LIMIT 1
      `,
      [year, studentId]
    );

    if (infoRows.length === 0) return null;

    const [marks] = await pool.query(
      `
      SELECT
        m.sprmark_id AS id,
        COALESCE(sub.stvsubj_subject, m.sprmark_subject) AS subject,
        COALESCE(ex.stvexam_name, m.sprmark_exam_name) AS exam_name,
        m.sprmark_mark AS obtained_marks,
        m.sprmark_total_marks AS max_marks,
        DATE_FORMAT(m.sprmark_date, '%Y-%m-%d') AS exam_date
      FROM sprmark m
      LEFT JOIN stvsubj sub ON sub.stvsubj_id = m.sprmark_subject
      LEFT JOIN stvexam ex ON ex.stvexam_id = m.sprmark_exam_name
      WHERE m.sprmark_student_id = ?
        AND m.sprmark_academic_year = ?
      ORDER BY m.sprmark_id DESC
      LIMIT 100
      `,
      [studentId, year]
    );

    const [attendance] = await pool.query(
      `
      SELECT
        DATE_FORMAT(SPRATND_date, '%Y-%m-%d') AS date,
        SPRATND_status AS status,
        SPRATND_comment AS comment
      FROM spratnd
      WHERE SPRATND_student_id = ?
        AND SPRATND_acdm_year = ?
      ORDER BY SPRATND_date DESC
      LIMIT 60
      `,
      [studentId, year]
    );

    const summary = attendance.reduce(
      (acc, r) => {
        if (r.status === 'P') acc.present += 1;
        else if (r.status === 'A') acc.absent += 1;
        else acc.other += 1;
        return acc;
      },
      { present: 0, absent: 0, other: 0 }
    );

    const [remarks] = await pool.query(
      `
      SELECT
        r.stvrmrk_id AS id,
        DATE_FORMAT(r.stvrmrk_date, '%Y-%m-%d') AS date,
        r.stvrmrk_remark AS remark,
        r.stvrmrk_reply AS reply,
        r.stvrmrk_status AS status,
        (SELECT CONCAT(IFNULL(t.SPRIDEN_surname, ''), ' ', IFNULL(t.SPRIDEN_student_name, ''))
         FROM spriden t WHERE t.SPRIDEN_student_id = r.stvrmrk_teacher_id LIMIT 1) AS teacher_name
      FROM stvrmrk r
      WHERE r.stvrmrk_student_id = ?
        AND (r.stvrmrk_academic_year = ? OR r.stvrmrk_academic_year IS NULL)
      ORDER BY r.stvrmrk_id DESC
      LIMIT 50
      `,
      [studentId, year]
    );

    return {
      info: infoRows[0],
      marks,
      attendance_summary: summary,
      attendance,
      remarks,
    };
  }
}

export default ClassReportModel;

