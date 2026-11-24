import pool from '../config/db.js';

class TeacherModel {
  /**
   * Get Active Academic Year ID
   */
  static async getActiveAcademicYearId() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 9;
  }

  /**
   * Get next generated Teacher Employee ID (e.g. E20260025)
   */
  static async getNextTeacherId() {
    const currentYear = new Date().getFullYear();
    const prefix = `E${currentYear}`;

    const [rows] = await pool.query(
      `SELECT SPRIDEN_student_id 
       FROM spriden 
       WHERE SPRIDEN_user_type = 'tc' 
         AND SPRIDEN_student_id LIKE ? 
       ORDER BY SPRIDEN_student_id DESC 
       LIMIT 1`,
      [`${prefix}%`]
    );

    if (rows.length === 0 || !rows[0].SPRIDEN_student_id) {
      return `${prefix}0001`;
    }

    const lastId = rows[0].SPRIDEN_student_id;
    const numPart = parseInt(lastId.slice(-4), 10);
    const nextNum = isNaN(numPart) ? 1 : numPart + 1;
    return `${prefix}${String(nextNum).padStart(4, '0')}`;
  }

  /**
   * Fetch teachers with filtering, search, and pagination
   */
  static async getTeachers(filters = {}) {
    const { status = 'ALL', search = '', academicYearId = null } = filters;

    let conditions = ["SPRIDEN_user_type = 'tc'"];
    const params = [];

    if (status && status !== 'ALL') {
      conditions.push('spriden_student_status = ?');
      params.push(status);
    }

    if (search && search.trim()) {
      const q = `%${search.trim()}%`;
      conditions.push(`(
        SPRIDEN_student_id LIKE ? OR
        SPRIDEN_surname LIKE ? OR
        SPRIDEN_student_name LIKE ? OR
        SPRIDEN_father_name LIKE ?
      )`);
      params.push(q, q, q, q);
    }

    const query = `
      SELECT 
        id,
        SPRIDEN_student_id AS teacher_id,
        CONCAT(IFNULL(SPRIDEN_surname, ''), ' ', IFNULL(SPRIDEN_student_name, ''), ' ', IFNULL(SPRIDEN_father_name, '')) AS name,
        SPRIDEN_surname AS surname,
        SPRIDEN_student_name AS first_name,
        SPRIDEN_father_name AS father_name,
        SPRIDEN_gender AS gender,
        SPRIDEN_birthdate AS date_of_birth,
        SPRIDEN_religion AS religion,
        SPRIDEN_aadhar_no AS aadhar_number,
        SPRIDEN_main_address AS address,
        SPRIDEN_photo_path AS photo_path,
        spriden_student_status AS status,
        SPRIDEN_ug_college AS qualification,
        SPRIDEN_date_created AS joining_date
      FROM spriden
      WHERE ${conditions.join(' AND ')}
      ORDER BY SPRIDEN_student_name ASC
    `;

    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Get single teacher by ID
   */
  static async getTeacherById(id) {
    const query = `
      SELECT *
      FROM spriden
      WHERE id = ? AND SPRIDEN_user_type = 'tc'
      LIMIT 1
    `;
    const [rows] = await pool.query(query, [id]);
    return rows[0] || null;
  }

  /**
   * Register new teacher
   */
  static async createTeacher(data) {
    const nextId = await TeacherModel.getNextTeacherId();

    const insertData = {
      SPRIDEN_student_id: nextId,
      SPRIDEN_surname: data.surname || '',
      SPRIDEN_student_name: data.student_name || '',
      SPRIDEN_father_name: data.father_name || '',
      SPRIDEN_mother_name: data.mother_name || '',
      SPRIDEN_religion: data.religion || 'Islam',
      SPRIDEN_gender: data.gender || 'Male',
      SPRIDEN_nationality: data.nationality || 'Indian',
      SPRIDEN_mother_toungue: data.mother_tongue || 'Urdu',
      SPRIDEN_birth_place: data.birth_place || '',
      SPRIDEN_aadhar_no: data.aadhar_number || '',
      SPRIDEN_birthdate: data.date_of_birth || null,
      SPRIDEN_main_address: data.main_address || '',
      SPRIDEN_alt_address: data.alternate_address || '',
      SPRIDEN_office_add: data.office_address || '',
      SPRIDEN_photo_path: data.photo_path || '',
      SPRIDEN_user_type: 'tc',
      SPRIDEN_date_created: new Date().toISOString().split('T')[0],
      SPRIDEN_ten_school: data.ten_school || '',
      SPRIDEN_ten_board: data.ten_board || '',
      SPRIDEN_ten_year: data.ten_year || '',
      SPRIDEN_twelve_college: data.twe_school || '',
      SPRIDEN_twelve_board: data.twe_board || '',
      SPRIDEN_twelve_year: data.twe_year || '',
      SPRIDEN_ug_college: data.ug_school || '',
      SPRIDEN_ug_uni: data.ug_board || '',
      SPRIDEN_ug_year: data.ug_year || '',
      SPRIDEN_pg_college: data.pg_school || '',
      SPRIDEN_pg_uni: data.pg_board || '',
      SPRIDEN_pg_year: data.pg_year || '',
      SPRIDEN_phd_college: data.phd_school || '',
      SPRIDEN_phd_uni: data.phd_board || '',
      SPRIDEN_phd_year: data.phd_year || '',
      spriden_student_status: 'A',
      spriden_tc_pass: data.password || 'Olive@123'
    };

    const keys = Object.keys(insertData);
    const values = Object.values(insertData);
    const placeholders = keys.map(() => '?').join(', ');

    const [result] = await pool.query(
      `INSERT INTO spriden (${keys.join(', ')}) VALUES (${placeholders})`,
      values
    );

    return { id: result.insertId, teacher_id: nextId };
  }

  /**
   * Update teacher record
   */
  static async updateTeacher(id, data) {
    const updateFields = {
      SPRIDEN_surname: data.surname,
      SPRIDEN_student_name: data.student_name,
      SPRIDEN_father_name: data.father_name,
      SPRIDEN_mother_name: data.mother_name,
      SPRIDEN_religion: data.religion,
      SPRIDEN_gender: data.gender,
      SPRIDEN_nationality: data.nationality,
      SPRIDEN_mother_toungue: data.mother_tongue,
      SPRIDEN_birth_place: data.birth_place,
      SPRIDEN_aadhar_no: data.aadhar_number,
      SPRIDEN_birthdate: data.date_of_birth,
      SPRIDEN_main_address: data.main_address,
      SPRIDEN_alt_address: data.alternate_address,
      SPRIDEN_ten_school: data.ten_school,
      SPRIDEN_ten_board: data.ten_board,
      SPRIDEN_ten_year: data.ten_year,
      SPRIDEN_twelve_college: data.twe_school,
      SPRIDEN_twelve_board: data.twe_board,
      SPRIDEN_twelve_year: data.twe_year,
      SPRIDEN_ug_college: data.ug_school,
      SPRIDEN_ug_uni: data.ug_board,
      SPRIDEN_ug_year: data.ug_year,
      SPRIDEN_pg_college: data.pg_school,
      SPRIDEN_pg_uni: data.pg_board,
      SPRIDEN_pg_year: data.pg_year,
      spriden_student_status: data.status || 'A'
    };

    const setClauses = [];
    const values = [];

    Object.entries(updateFields).forEach(([k, v]) => {
      if (v !== undefined) {
        setClauses.push(`${k} = ?`);
        values.push(v);
      }
    });

    if (setClauses.length === 0) return { updated: false };

    values.push(id);
    const [result] = await pool.query(
      `UPDATE spriden SET ${setClauses.join(', ')} WHERE id = ? AND SPRIDEN_user_type = 'tc'`,
      values
    );

    return { updated: result.affectedRows > 0 };
  }

  /**
   * Toggle teacher status (Active 'A' / Inactive 'I')
   */
  static async updateTeacherStatus(id, newStatus) {
    const [result] = await pool.query(
      `UPDATE spriden SET spriden_student_status = ? WHERE id = ? AND SPRIDEN_user_type = 'tc'`,
      [newStatus, id]
    );
    return result.affectedRows > 0;
  }

  /**
   * Class Teacher Assignments (spatesd)
   */
  static async getAssignedClassTeachers(academicYearId) {
    const query = `
      SELECT 
        a.SPATESD_id,
        a.SPATESD_academic_year,
        a.SPATESD_teacher_id,
        a.SPATESD_std,
        a.SPATESD_div,
        a.SPATESD_assigned_date,
        s.id AS spriden_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS teacher_name
      FROM spatesd a
      LEFT JOIN spriden s ON s.SPRIDEN_student_id = a.SPATESD_teacher_id
      WHERE a.SPATESD_academic_year = ?
      ORDER BY a.SPATESD_std ASC, a.SPATESD_div ASC
    `;
    const [rows] = await pool.query(query, [academicYearId]);
    return rows;
  }

  /**
   * Assign or update Class Teacher
   */
  static async assignClassTeacher(standard, division, teacherEmpId, academicYearId) {
    // Check if assignment already exists for standard & division
    const [existing] = await pool.query(
      `SELECT SPATESD_id 
       FROM spatesd 
       WHERE SPATESD_std = ? AND SPATESD_div = ? AND SPATESD_academic_year = ?`,
      [standard, division, academicYearId]
    );

    if (existing.length > 0) {
      await pool.query(
        `UPDATE spatesd 
         SET SPATESD_teacher_id = ?, SPATESD_assigned_date = ? 
         WHERE SPATESD_id = ?`,
        [teacherEmpId, new Date().toISOString().split('T')[0], existing[0].SPATESD_id]
      );
      return { id: existing[0].SPATESD_id, updated: true };
    } else {
      const [result] = await pool.query(
        `INSERT INTO spatesd (
          SPATESD_academic_year,
          SPATESD_teacher_id,
          SPATESD_std,
          SPATESD_div,
          SPATESD_assigned_date
        ) VALUES (?, ?, ?, ?, ?)`,
        [academicYearId, teacherEmpId, standard, division, new Date().toISOString().split('T')[0]]
      );
      return { id: result.insertId, created: true };
    }
  }

  /**
   * Timetable: Get slots & class schedule
   */
  static async getTimetable(standard, division) {
    const [slots] = await pool.query(
      `SELECT stvtbtm_id, stvtbtm_start_time, stvtbtm_end_time, stvtbtm_status 
       FROM stvtbtm 
       WHERE stvtbtm_status = 'Active' OR stvtbtm_status IS NULL
       ORDER BY stvtbtm_start_time ASC`
    );

    const [entries] = await pool.query(
      `SELECT * FROM stvttbl WHERE stvttbl_std = ? AND stvttbl_div = ?`,
      [standard, division]
    );

    return { slots, entries };
  }

  /**
   * Timetable: Save weekly schedule
   */
  static async saveTimetable(standard, division, scheduleRows) {
    // Delete existing entries for this class
    await pool.query(
      `DELETE FROM stvttbl WHERE stvttbl_std = ? AND stvttbl_div = ?`,
      [standard, division]
    );

    if (!scheduleRows || scheduleRows.length === 0) return { count: 0 };

    const insertValues = scheduleRows.map((r) => [
      r.time_slot || '',
      r.monday || '',
      r.tuesday || '',
      r.wednesday || '',
      r.thursday || '',
      r.friday || '',
      r.saturday || '',
      standard,
      division
    ]);

    const [result] = await pool.query(
      `INSERT INTO stvttbl (
        stvttbl_time,
        stvttbl_monday,
        stvttbl_tuesday,
        stvttbl_wednesday,
        stvttbl_thurday,
        stvttbl_friday,
        stvttbl_saturday,
        stvttbl_std,
        stvttbl_div
      ) VALUES ?`,
      [insertValues]
    );

    return { count: result.affectedRows };
  }

  /**
   * Lesson Plans: Fetch lessons
   */
  static async getLessons(academicYear, standard = null, subject = null) {
    let conditions = ['1=1'];
    const params = [];

    if (academicYear && academicYear !== 'all') {
      conditions.push('academic_year = ?');
      params.push(academicYear);
    }

    if (standard && standard !== 'all') {
      conditions.push('class = ?');
      params.push(standard);
    }
    if (subject && subject !== 'all') {
      conditions.push('subject = ?');
      params.push(subject);
    }

    const query = `
      SELECT 
        id,
        class AS standard,
        subject,
        lesson_no,
        lesson_name,
        type,
        academic_year,
        created_at,
        updated_at
      FROM lessons
      WHERE ${conditions.join(' AND ')}
      ORDER BY class ASC, lesson_no ASC
    `;

    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Lesson Plans: Save / Update lesson
   */
  static async saveLesson(data) {
    const { id, academic_year, standard, subject, lesson_no, lesson_name, type } = data;

    if (id) {
      await pool.query(
        `UPDATE lessons 
         SET class = ?, subject = ?, lesson_no = ?, lesson_name = ?, type = ?, updated_at = NOW() 
         WHERE id = ?`,
        [standard, subject, lesson_no, lesson_name, type || 'Theory', id]
      );
      return { id, updated: true };
    } else {
      const [result] = await pool.query(
        `INSERT INTO lessons (
          academic_year,
          class,
          subject,
          lesson_no,
          lesson_name,
          type,
          created_at
        ) VALUES (?, ?, ?, ?, ?, ?, NOW())`,
        [academic_year, standard, subject, lesson_no, lesson_name, type || 'Theory']
      );
      return { id: result.insertId, created: true };
    }
  }

  /**
   * Lesson Plans: Delete lesson
   */
  static async deleteLesson(id) {
    const [result] = await pool.query('DELETE FROM lessons WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }
}

export default TeacherModel;
