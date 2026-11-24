/**
 * RandomClass Model — Manages random/special student-to-teacher allocation (Matches CI3 RandomClass_model)
 * Interacts with stvrndm, spriden, stvclas, stvschool
 */
import pool from '../config/db.js';

class RandomClassModel {
  /**
   * Get active academic year id
   */
  static async getActiveAcademicYear() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 3;
  }

  /**
   * Get available standards/classes
   */
  static async getStandards() {
    const [rows] = await pool.query(`
      SELECT DISTINCT STVCLAS_standard AS standard 
      FROM stvclas 
      WHERE STVCLAS_standard IS NOT NULL 
        AND STVCLAS_standard != ''
        AND STVCLAS_standard NOT LIKE '%PHP%'
        AND STVCLAS_standard NOT LIKE '%Error%'
        AND LENGTH(STVCLAS_standard) <= 15
      ORDER BY STVCLAS_standard ASC
    `);
    return rows.map(r => r.standard);
  }

  /**
   * Get active teachers for allocation
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
   * Get students for a specific class with their allocation details from stvrndm
   */
  static async getStudentsByClass(standard, academicYearId = null) {
    const year = academicYearId || await this.getActiveAcademicYear();

    const [students] = await pool.query(`
      SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS student_code,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        r.stvrndm_id,
        r.stvrndm_teacher_id AS teacher_code,
        IFNULL(r.stvrndm_status, '0') AS status,
        (SELECT CONCAT(IFNULL(tch.SPRIDEN_surname, ''), ' ', IFNULL(tch.SPRIDEN_student_name, ''), ' ', IFNULL(tch.SPRIDEN_father_name, ''))
         FROM spriden tch WHERE tch.SPRIDEN_student_id = r.stvrndm_teacher_id COLLATE utf8_general_ci LIMIT 1) AS assigned_teacher_name
      FROM spriden s
      JOIN stvclas c ON s.id = c.STVCLAS_student_id
      LEFT JOIN stvrndm r ON r.stvrndm_student_id COLLATE utf8_general_ci = CAST(s.id AS CHAR) 
                         AND r.stvrndm_class COLLATE utf8_general_ci = c.STVCLAS_standard
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.SPRIDEN_student_status = 'A'
        AND c.STVCLAS_standard = ?
        AND c.STVCLAS_acdm = ?
      ORDER BY s.SPRIDEN_student_name ASC
    `, [standard, year]);

    return students;
  }

  /**
   * Bulk save or update assignments
   * assignments: Array of { student_id, random_id, teacher_id, status }
   */
  static async saveAssignments(standard, assignments) {
    if (!Array.isArray(assignments) || assignments.length === 0) return true;

    for (const item of assignments) {
      const studentId = item.student_id;
      const teacherId = item.teacher_id || null;
      const status = item.status === true || item.status === '1' || item.status === 1 || item.status === 'Yes' ? '1' : '0';
      const randomId = item.random_id ? parseInt(item.random_id) : 0;

      if (randomId > 0) {
        // Update existing record
        await pool.query(`
          UPDATE stvrndm 
          SET stvrndm_status = ?, stvrndm_teacher_id = ? 
          WHERE stvrndm_id = ?
        `, [status, teacherId, randomId]);
      } else if (status === '1' && teacherId) {
        // Check if existing mapping already exists by student and class
        const [existing] = await pool.query(`
          SELECT stvrndm_id FROM stvrndm 
          WHERE stvrndm_student_id = ? AND stvrndm_class COLLATE utf8_general_ci = ?
          LIMIT 1
        `, [studentId, standard]);

        if (existing.length > 0) {
          await pool.query(`
            UPDATE stvrndm 
            SET stvrndm_status = ?, stvrndm_teacher_id = ?
            WHERE stvrndm_id = ?
          `, [status, teacherId, existing[0].stvrndm_id]);
        } else {
          await pool.query(`
            INSERT INTO stvrndm (stvrndm_class, stvrndm_student_id, stvrndm_teacher_id, stvrndm_status)
            VALUES (?, ?, ?, ?)
          `, [standard, studentId, teacherId, status]);
        }
      }
    }

    return true;
  }
}

export default RandomClassModel;
