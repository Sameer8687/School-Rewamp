/**
 * ClassManagement Model — Handles Class Creation, Class Roster Management, and Subject Teacher Allocation
 * Matches CI3 Welcome/create_class, Welcome/class, Welcome/subject_teacher & Teacher_model
 * Interacts with stvclas, stvstcr, stvsubj, spriden, stvacdm
 */
import pool from '../config/db.js';

class ClassManagementModel {
  static async getActiveAcademicYear() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 9;
  }

  /**
   * Get dropdown options (academic years, standards, divisions, subjects, teachers)
   */
  static async getOptions() {
    const activeYear = await this.getActiveAcademicYear();

    const [years] = await pool.query(
      'SELECT stvacdm_id AS id, stvacdm_year AS year, status FROM stvacdm ORDER BY stvacdm_id DESC'
    );

    // Get standards from stvfees (matching CI3 Stats_model::getAllClasses)
    const [feeStandards] = await pool.query(`
      SELECT DISTINCT stvfees_class AS standard
      FROM stvfees
      WHERE stvfees_class IS NOT NULL
        AND stvfees_class != ''
    `);

    const standardOrder = ['Nursery', 'Jr.Kg.', 'Sr.Kg.', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    const feeStdList = feeStandards.map(s => s.standard);
    const orderedStandards = [
      ...standardOrder.filter(s => feeStdList.includes(s)),
      ...feeStdList.filter(s => !standardOrder.includes(s))
    ];
    const finalStandards = orderedStandards.length > 0 ? orderedStandards : standardOrder;

    const [classDivs] = await pool.query(`
      SELECT DISTINCT STVCLAS_class AS division
      FROM stvclas
      WHERE STVCLAS_class IS NOT NULL 
        AND STVCLAS_class != '' 
        AND STVCLAS_class != '*'
      ORDER BY STVCLAS_class ASC
    `);
    const divList = classDivs.map(d => d.division);
    const finalDivisions = divList.length > 0 ? divList : ['A', 'B', 'C', 'D'];

    const [subjects] = await pool.query(`
      SELECT stvsubj_id AS id, stvsubj_subject AS name, stvsubj_std AS standard, stvsubj_acdm AS academic_year
      FROM stvsubj
      ORDER BY stvsubj_subject ASC
    `);

    const [teachers] = await pool.query(`
      SELECT 
        id,
        SPRIDEN_student_id AS teacher_code,
        TRIM(CONCAT(IFNULL(SPRIDEN_surname, ''), ' ', IFNULL(SPRIDEN_student_name, ''), ' ', IFNULL(SPRIDEN_father_name, ''))) AS teacher_name,
        TRIM(CONCAT(IFNULL(SPRIDEN_surname, ''), ' ', IFNULL(SPRIDEN_student_name, ''), ' ', IFNULL(SPRIDEN_father_name, ''))) AS name
      FROM spriden
      WHERE SPRIDEN_user_type = 'tc'
        AND SPRIDEN_student_id IS NOT NULL
        AND (SPRIDEN_student_status = 'A' OR SPRIDEN_student_status IS NULL)
      ORDER BY SPRIDEN_student_name ASC
    `);

    return {
      active_year: activeYear,
      years,
      standards: finalStandards.map(s => ({ standard: s, value: s, label: s })),
      divisions: finalDivisions.map(d => ({ division: d, value: d, label: d })),
      subjects,
      teachers
    };
  }

  /**
   * Get students NOT yet assigned to any class for the given academic year
   */
  static async getUnassignedStudents(academicYearId = null) {
    const year = academicYearId || await this.getActiveAcademicYear();
    const [rows] = await pool.query(`
      SELECT 
        s.id,
        s.SPRIDEN_student_id AS student_code,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.spriden_gr_number AS gr_number,
        s.SPRIDEN_admission_class AS admission_std
      FROM spriden s
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.spriden_student_status = 'A'
        AND s.id NOT IN (
          SELECT STVCLAS_student_id FROM stvclas WHERE STVCLAS_acdm = ?
        )
      ORDER BY s.id DESC
      LIMIT 200
    `, [year]);
    return rows;
  }

  /**
   * Assign unassigned students to a class/division/rollno (stvclas)
   */
  static async assignStudentsToClass({ academic_year, standard, division, students, user_id }) {
    const year = academic_year || await this.getActiveAcademicYear();
    if (!Array.isArray(students) || students.length === 0) return 0;

    let count = 0;
    for (const item of students) {
      const studentId = item.student_id;
      const rollNo = item.roll_no || '';

      const [existing] = await pool.query(
        'SELECT STVCLAS_code FROM stvclas WHERE STVCLAS_student_id = ? AND STVCLAS_acdm = ? LIMIT 1',
        [studentId, year]
      );

      if (existing.length > 0) {
        await pool.query(
          'UPDATE stvclas SET STVCLAS_standard = ?, STVCLAS_class = ?, STVCLAS_rollno = ? WHERE STVCLAS_code = ?',
          [standard, division, rollNo, existing[0].STVCLAS_code]
        );
      } else {
        await pool.query(
          `INSERT INTO stvclas (STVCLAS_student_id, STVCLAS_rollno, STVCLAS_standard, STVCLAS_class, STVCLAS_userid, STVCLAS_acdm)
           VALUES (?, ?, ?, ?, ?, ?)`,
          [studentId, rollNo, standard, division, user_id || '1', year]
        );
      }
      count++;
    }
    return count;
  }

  /**
   * View class roster (students enrolled in standard + division + academic year)
   */
  static async getClassRoster({ academic_year, standard, division }) {
    const year = academic_year || await this.getActiveAcademicYear();
    let sql = `
      SELECT 
        c.STVCLAS_code AS class_record_id,
        c.STVCLAS_student_id AS student_id,
        c.STVCLAS_rollno AS roll_no,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        c.STVCLAS_acdm AS academic_year,
        s.SPRIDEN_student_id AS student_code,
        s.spriden_gr_number AS gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        p.stvpcnf_mobile AS phone
      FROM stvclas c
      JOIN spriden s ON s.id = c.STVCLAS_student_id
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
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

  /**
   * Update a student's class, division, or roll number
   */
  static async updateClassRosterRow(classRecordId, { standard, division, roll_no }) {
    const [res] = await pool.query(
      'UPDATE stvclas SET STVCLAS_standard = ?, STVCLAS_class = ?, STVCLAS_rollno = ? WHERE STVCLAS_code = ?',
      [standard, division, roll_no || '', classRecordId]
    );
    return res.affectedRows > 0;
  }

  /**
   * Get all subject-teacher allocations (stvstcr)
   */
  static async getSubjectTeachers(academicYearId = null) {
    const year = academicYearId || await this.getActiveAcademicYear();
    const [rows] = await pool.query(`
      SELECT 
        st.stvstcr_id AS id,
        st.stvstcr_teacher_id AS teacher_code,
        st.stvstcr_std AS standard,
        st.stvstcr_div AS division,
        st.stvstcr_subject AS subject_id,
        st.stvstcr_acdm AS academic_year,
        CONCAT(IFNULL(t.SPRIDEN_surname, ''), ' ', IFNULL(t.SPRIDEN_student_name, ''), ' ', IFNULL(t.SPRIDEN_father_name, '')) AS teacher_name,
        sub.stvsubj_subject AS subject_name,
        ac.stvacdm_year AS academic_year_name
      FROM stvstcr st
      LEFT JOIN spriden t ON t.SPRIDEN_student_id = st.stvstcr_teacher_id AND t.SPRIDEN_user_type = 'tc'
      LEFT JOIN stvsubj sub ON sub.stvsubj_id = st.stvstcr_subject
      LEFT JOIN stvacdm ac ON ac.stvacdm_id = st.stvstcr_acdm
      WHERE st.stvstcr_acdm = ?
      ORDER BY st.stvstcr_id DESC
    `, [year]);
    return rows;
  }

  /**
   * Create subject-teacher allocation
   */
  static async createSubjectTeacher({ teacher_code, standard, division, subject_id, academic_year, user_id }) {
    const year = academic_year || await this.getActiveAcademicYear();

    // Prevent duplicate
    const [existing] = await pool.query(
      `SELECT stvstcr_id FROM stvstcr 
       WHERE stvstcr_teacher_id = ? AND stvstcr_std = ? AND stvstcr_div = ? AND stvstcr_subject = ? AND stvstcr_acdm = ?`,
      [teacher_code, standard, division, subject_id, year]
    );
    if (existing.length > 0) {
      return existing[0].stvstcr_id;
    }

    const [res] = await pool.query(
      `INSERT INTO stvstcr (stvstcr_teacher_id, stvstcr_std, stvstcr_div, stvstcr_subject, stvstcr_added_by, stvstcr_acdm)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [teacher_code, standard, division, subject_id, user_id || '1', year]
    );
    return res.insertId;
  }

  /**
   * Update subject-teacher allocation (matches CI3 Teacher::assign_subject_teacher when assigned_id is provided)
   */
  static async updateSubjectTeacher(id, { teacher_code, standard, division, subject_id, academic_year }) {
    const year = academic_year || await this.getActiveAcademicYear();
    const [res] = await pool.query(
      `UPDATE stvstcr 
       SET stvstcr_teacher_id = ?, stvstcr_std = ?, stvstcr_div = ?, stvstcr_subject = ?, stvstcr_acdm = ?
       WHERE stvstcr_id = ?`,
      [teacher_code, standard, division, subject_id, year, id]
    );
    return res.affectedRows > 0;
  }

  /**
   * Delete subject-teacher allocation
   */
  static async deleteSubjectTeacher(id) {
    const [res] = await pool.query('DELETE FROM stvstcr WHERE stvstcr_id = ?', [id]);
    return res.affectedRows > 0;
  }
}

export default ClassManagementModel;
