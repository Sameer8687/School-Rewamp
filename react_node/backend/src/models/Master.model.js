/**
 * Master & Configuration Model — Database operations for Olive English School ERP Masters
 * Matches CI3 tables: stvacdm, stvsubj, stvpsch, stvshif, stvgrds, web_app_visibility, stvuacs, modules, form
 */
import pool from '../config/db.js';

class MasterModel {
  // ─── 1. Academic Years Master (stvacdm) ───
  static async getAcademicYears() {
    const [rows] = await pool.query(
      `SELECT 
        stvacdm_id AS id,
        stvacdm_start AS start_date,
        stvacdm_end AS end_date,
        stvacdm_year AS year_name,
        stvacdm_notes AS notes,
        stvacdm_added_by AS added_by,
        status,
        CASE WHEN status = '1' OR status = 'A' THEN 1 ELSE 0 END AS is_active
       FROM stvacdm 
       ORDER BY stvacdm_id DESC`
    );
    return rows;
  }

  static async createAcademicYear({ start, end, year, notes, addedBy = 'admin' }) {
    // If year name not provided, calculate from start and end dates (e.g., 2025-26)
    const yearName = year || (start && end ? `${start.substring(0, 4)}-${end.substring(2, 4)}` : '2025-26');
    const [res] = await pool.query(
      `INSERT INTO stvacdm (stvacdm_start, stvacdm_end, stvacdm_year, stvacdm_notes, stvacdm_added_by, status)
       VALUES (?, ?, ?, ?, ?, '0')`,
      [start, end, yearName, notes || '', addedBy]
    );
    return { id: res.insertId, yearName };
  }

  static async updateAcademicYear(id, { start, end, year, notes }) {
    const yearName = year || (start && end ? `${start.substring(0, 4)}-${end.substring(2, 4)}` : undefined);
    await pool.query(
      `UPDATE stvacdm 
       SET stvacdm_start = ?, stvacdm_end = ?, stvacdm_year = IFNULL(?, stvacdm_year), stvacdm_notes = ?
       WHERE stvacdm_id = ?`,
      [start, end, yearName, notes, id]
    );
    return { id, updated: true };
  }

  static async setActiveAcademicYear(id) {
    // Set all to inactive '0', then set target to '1'
    await pool.query("UPDATE stvacdm SET status = '0'");
    await pool.query("UPDATE stvacdm SET status = '1' WHERE stvacdm_id = ?", [id]);
    return { activeId: id };
  }

  // ─── 2. Subjects Master (stvsubj) ───
  static async getSubjects({ standard, academicYear }) {
    let sql = `
      SELECT 
        s.stvsubj_id AS id,
        s.stvsubj_subject AS name,
        s.stvsubj_std AS standard,
        s.stvsubj_acdm AS academic_year,
        s.stvsubj_added_by AS added_by
      FROM stvsubj s
      WHERE 1=1
    `;
    const params = [];
    if (standard && standard !== 'all') {
      sql += ' AND s.stvsubj_std = ?';
      params.push(standard);
    }
    if (academicYear && academicYear !== 'all') {
      const [yearRows] = await pool.query(
        sql + ' AND s.stvsubj_acdm = ? ORDER BY s.stvsubj_std ASC, s.stvsubj_subject ASC',
        [...params, academicYear]
      );
      if (yearRows.length > 0) return yearRows;
    }
    sql += ' ORDER BY s.stvsubj_std ASC, s.stvsubj_subject ASC';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  static async createSubject({ name, standard, academicYear, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvsubj (stvsubj_subject, stvsubj_std, stvsubj_acdm, stvsubj_added_by)
       VALUES (?, ?, ?, ?)`,
      [name, standard, academicYear || null, addedBy]
    );
    return { id: res.insertId, name, standard };
  }

  static async updateSubject(id, { name, standard, academicYear }) {
    await pool.query(
      `UPDATE stvsubj 
       SET stvsubj_subject = ?, stvsubj_std = ?, stvsubj_acdm = ?
       WHERE stvsubj_id = ?`,
      [name, standard, academicYear || null, id]
    );
    return { id, updated: true };
  }

  static async deleteSubject(id) {
    const [res] = await pool.query('DELETE FROM stvsubj WHERE stvsubj_id = ?', [id]);
    return res.affectedRows > 0;
  }

  // ─── 3. School Profile Master (stvpsch) ───
  static async getSchoolProfile() {
    const [rows] = await pool.query('SELECT * FROM stvpsch LIMIT 1');
    if (rows.length === 0) {
      return {
        name: 'Olive English School',
        city: 'Malegaon',
        state: 'Maharashtra',
        address: 'Olive Campus, Malegaon',
        email: 'info@oliveenglishschool.com',
        phone: '02554-222333',
      };
    }
    const s = rows[0];
    return {
      code: s.STVPSCH_code,
      name: s.STVPSCH_name,
      city: s.STVPSCH_city,
      state: s.STVPSCH_states,
      address: s.STVPSCH_address,
      email: s.STVPSCH_email,
      phone: s.STVPSCH_phone,
    };
  }

  static async updateSchoolProfile({ code, name, city, state, address, email, phone, addedBy = 'admin' }) {
    const [rows] = await pool.query('SELECT STVPSCH_code FROM stvpsch LIMIT 1');
    if (rows.length > 0) {
      await pool.query(
        `UPDATE stvpsch 
         SET STVPSCH_name = ?, STVPSCH_city = ?, STVPSCH_states = ?, STVPSCH_address = ?, STVPSCH_email = ?, STVPSCH_phone = ?
         WHERE STVPSCH_code = ?`,
        [name, city, state, address, email, phone, rows[0].STVPSCH_code]
      );
    } else {
      await pool.query(
        `INSERT INTO stvpsch (STVPSCH_code, STVPSCH_name, STVPSCH_city, STVPSCH_states, STVPSCH_address, STVPSCH_email, STVPSCH_phone, STVPSCH_added_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
        [code || 'OES01', name, city, state, address, email, phone, addedBy]
      );
    }
    return { updated: true };
  }

  // ─── 4. Web App Visibility Controls (web_app_visibility) ───
  static async getVisibilitySettings() {
    const [rows] = await pool.query('SELECT * FROM web_app_visibility LIMIT 1');
    if (rows.length === 0) {
      return {
        student_profile: 'Y',
        marks_visibility: 'Y',
        attendance_visibility: 'Y',
        last_action: 'admin',
        last_action_on: new Date(),
      };
    }
    return rows[0];
  }

  static async updateVisibilitySettings({ student_profile, marks_visibility, attendance_visibility, user = 'admin' }) {
    const [rows] = await pool.query('SELECT 1 FROM web_app_visibility LIMIT 1');
    const now = new Date();
    if (rows.length > 0) {
      await pool.query(
        `UPDATE web_app_visibility 
         SET student_profile = ?, marks_visibility = ?, attendance_visibility = ?, last_action = ?, last_action_on = ?`,
        [student_profile, marks_visibility, attendance_visibility, user, now]
      );
    } else {
      await pool.query(
        `INSERT INTO web_app_visibility (student_profile, marks_visibility, attendance_visibility, last_action, last_action_on)
         VALUES (?, ?, ?, ?, ?)`,
        [student_profile, marks_visibility, attendance_visibility, user, now]
      );
    }
    return { success: true };
  }

  // ─── 5. User Access & Form Allocations (modules, form, stvuacs) ───
  static async getAllStaffUsers() {
    const [rows] = await pool.query(
      `SELECT 
        s.id,
        s.SPRIDEN_student_id AS emp_id,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        s.SPRIDEN_user_type AS role,
        s.spriden_student_status AS status
       FROM spriden s
       WHERE s.SPRIDEN_user_type IN ('tc', 'ad', 'st')
       ORDER BY s.SPRIDEN_user_type ASC, name ASC`
    );
    return rows;
  }

  static async getModulesAndForms() {
    const [modules] = await pool.query('SELECT * FROM modules ORDER BY id ASC');
    const [forms] = await pool.query('SELECT * FROM form ORDER BY id ASC');

    return modules.map((m) => ({
      id: m.id,
      name: m.module_name,
      icon: m.icon,
      description: m.module_description,
      forms: forms.filter((f) => String(f.module_id) === String(m.id)).map((f) => ({
        id: f.id,
        name: f.form_name,
        url: f.form_link || f.form_url,
      })),
    }));
  }

  // ─── 6. Module Management CRUD (CI3: Modules_Model.php) ───
  static async getAllModules() {
    const [rows] = await pool.query(
      `SELECT m.*, (SELECT COUNT(*) FROM form f WHERE f.module_id = m.id) as form_count 
       FROM modules m 
       ORDER BY m.id ASC`
    );
    return rows;
  }

  static async getModuleById(id) {
    const [rows] = await pool.query('SELECT * FROM modules WHERE id = ?', [id]);
    return rows[0] || null;
  }

  static async createModule(data) {
    const [res] = await pool.query(
      'INSERT INTO modules (module_name, module_description, icon) VALUES (?, ?, ?)',
      [data.module_name, data.module_description || '', data.icon || 'folder']
    );
    return { id: res.insertId, ...data };
  }

  static async updateModule(id, data) {
    await pool.query(
      'UPDATE modules SET module_name = ?, module_description = ?, icon = ? WHERE id = ?',
      [data.module_name, data.module_description || '', data.icon || 'folder', id]
    );
    return { id, ...data };
  }

  static async deleteModule(id) {
    await pool.query('DELETE FROM form WHERE module_id = ?', [id]);
    const [res] = await pool.query('DELETE FROM modules WHERE id = ?', [id]);
    return res.affectedRows > 0;
  }

  // ─── 7. Form Management CRUD (CI3: Forms_Model.php) ───
  static async getAllForms() {
    const [rows] = await pool.query(
      `SELECT f.*, m.module_name 
       FROM form f 
       LEFT JOIN modules m ON f.module_id = m.id 
       ORDER BY f.id DESC`
    );
    return rows;
  }

  static async getFormById(id) {
    const [rows] = await pool.query(
      `SELECT f.*, m.module_name 
       FROM form f 
       LEFT JOIN modules m ON f.module_id = m.id 
       WHERE f.id = ?`,
      [id]
    );
    return rows[0] || null;
  }

  static async createForm(data) {
    const [res] = await pool.query(
      'INSERT INTO form (form_name, form_code, form_type, form_link, module_id) VALUES (?, ?, ?, ?, ?)',
      [
        data.form_name,
        data.form_code || null,
        data.form_type || null,
        data.form_link || data.form_url,
        data.module_id,
      ]
    );
    return { id: res.insertId, ...data };
  }

  static async updateForm(id, data) {
    await pool.query(
      'UPDATE form SET form_name = ?, form_code = ?, form_type = ?, form_link = ?, module_id = ? WHERE id = ?',
      [
        data.form_name,
        data.form_code || null,
        data.form_type || null,
        data.form_link || data.form_url,
        data.module_id,
        id,
      ]
    );
    return { id, ...data };
  }

  static async deleteForm(id) {
    const [res] = await pool.query('DELETE FROM form WHERE id = ?', [id]);
    return res.affectedRows > 0;
  }

  // ─── 8. User Access Reports & Matrix (CI3: user_access.php & user_report.php & User_Model.php) ───
  static async getSprUsers() {
    const [rows] = await pool.query(
      `SELECT spruser_id, spruser_name, spruser_username, spruser_mobile, spruser_email, spruser_usertype 
       FROM spruser 
       ORDER BY spruser_username ASC`
    );
    return rows;
  }

  static async getSprUserById(userId) {
    const [rows] = await pool.query(
      `SELECT spruser_id, spruser_name, spruser_username, spruser_mobile, spruser_email, spruser_usertype 
       FROM spruser 
       WHERE spruser_id = ?`,
      [userId]
    );
    return rows[0] || null;
  }

  static async getUsersWithAccess() {
    const [rows] = await pool.query(
      `SELECT spruser.spruser_id, spruser.spruser_username, spruser.spruser_name 
       FROM user_access 
       JOIN spruser ON user_access.user_id = spruser.spruser_id 
       GROUP BY user_access.user_id 
       ORDER BY spruser.spruser_username ASC`
    );
    return rows;
  }

  static async getFormsByModuleId(moduleId) {
    const [rows] = await pool.query(
      `SELECT id, form_name, form_code, form_type, form_link, module_id 
       FROM form 
       WHERE module_id = ? 
       ORDER BY form_name ASC`,
      [moduleId]
    );
    return rows;
  }

  static async getUserAccessMatrix(userId) {
    const [rows] = await pool.query(
      `SELECT 
         ua.id as access_id,
         ua.user_id,
         ua.module_id,
         ua.form_id,
         ua.access,
         CASE WHEN ua.from_date IS NULL OR ua.from_date = '0000-00-00' THEN '' ELSE DATE_FORMAT(ua.from_date, '%Y-%m-%d') END as from_date,
         CASE WHEN ua.to_date IS NULL OR ua.to_date = '0000-00-00' THEN '' ELSE DATE_FORMAT(ua.to_date, '%Y-%m-%d') END as to_date,
         ua.hold,
         m.module_name,
         f.form_name,
         f.form_link
       FROM user_access ua
       LEFT JOIN modules m ON m.id = ua.module_id
       LEFT JOIN form f ON f.id = ua.form_id
       WHERE ua.user_id = ?
       ORDER BY ua.id ASC`,
      [userId]
    );
    return rows;
  }

  static async saveUserAccessMatrix({ userId, rows = [], deletedRows = [] }) {
    // 1. Process deletions if provided
    if (Array.isArray(deletedRows) && deletedRows.length > 0) {
      for (const row of deletedRows) {
        if (row.module_id && row.form_id) {
          await pool.query(
            'DELETE FROM user_access WHERE user_id = ? AND module_id = ? AND form_id = ?',
            [userId, row.module_id, row.form_id]
          );
        }
      }
    }

    // 2. Process upserts matching CI3 insertData
    for (const row of rows) {
      if (!row.module_id || !row.form_id) continue;

      const fromDate = row.from_date && row.from_date !== '0000-00-00' ? row.from_date : null;
      const toDate = row.to_date && row.to_date !== '0000-00-00' ? row.to_date : null;
      const access = row.access || 'Read Write';
      const hold = row.hold || 'Active';

      const [existing] = await pool.query(
        'SELECT id FROM user_access WHERE user_id = ? AND module_id = ? AND form_id = ?',
        [userId, row.module_id, row.form_id]
      );

      if (existing.length > 0) {
        await pool.query(
          `UPDATE user_access 
           SET access = ?, from_date = ?, to_date = ?, hold = ? 
           WHERE id = ?`,
          [access, fromDate, toDate, hold, existing[0].id]
        );
      } else {
        await pool.query(
          `INSERT INTO user_access (user_id, module_id, form_id, access, from_date, to_date, hold)
           VALUES (?, ?, ?, ?, ?, ?, ?)`,
          [userId, row.module_id, row.form_id, access, fromDate, toDate, hold]
        );
      }
    }

    // 3. Keep stvuacs in sync for active forms (backwards compatibility)
    const [currentAccess] = await pool.query(
      `SELECT f.form_link 
       FROM user_access ua 
       JOIN form f ON f.id = ua.form_id 
       WHERE ua.user_id = ? AND ua.hold = 'Active'`,
      [userId]
    );
    await pool.query('DELETE FROM stvuacs WHERE stvuacs_userid = ?', [userId]);
    for (const ca of currentAccess) {
      if (ca.form_link) {
        await pool.query(
          'INSERT INTO stvuacs (stvuacs_userid, stvuacs_formurl) VALUES (?, ?)',
          [userId, ca.form_link]
        );
      }
    }

    return { success: true, count: rows.length };
  }

  static async deleteUserAccessRows(deletedRows = []) {
    for (const row of deletedRows) {
      if (row.user_id && row.module_id && row.form_id) {
        await pool.query(
          'DELETE FROM user_access WHERE user_id = ? AND module_id = ? AND form_id = ?',
          [row.user_id, row.module_id, row.form_id]
        );
      }
    }
    return true;
  }

  // ─── 8. User Access Reports (CI3: user_report.php & User_Model.php) ───
  static async getUserReports() {
    const [rows] = await pool.query(
      `SELECT 
         u.spruser_id,
         u.spruser_name,
         u.spruser_username,
         u.spruser_usertype,
         COUNT(ua.form_id) as form_count
       FROM spruser u
       LEFT JOIN user_access ua ON u.spruser_id = ua.user_id
       GROUP BY u.spruser_id, u.spruser_name, u.spruser_username, u.spruser_usertype
       ORDER BY form_count DESC, u.spruser_name ASC`
    );
    return rows;
  }

  static async getUserAccessList(userId) {
    const [rows] = await pool.query(
      `SELECT 
         ua.id as access_id,
         ua.user_id,
         ua.module_id,
         ua.form_id,
         ua.access,
         ua.from_date,
         ua.to_Date,
         ua.hold,
         m.module_name,
         f.form_name,
         f.form_link
       FROM user_access ua
       LEFT JOIN modules m ON m.id = ua.module_id
       LEFT JOIN form f ON f.id = ua.form_id
       WHERE ua.user_id = ?
       ORDER BY m.module_name ASC, f.form_name ASC`,
      [userId]
    );
    return rows;
  }

  static async deleteUserAccess(userId) {
    await pool.query('DELETE FROM user_access WHERE user_id = ?', [userId]);
    await pool.query('DELETE FROM stvuacs WHERE stvuacs_userid = ?', [userId]);
    return true;
  }

  static async getUserFormAccess(userId) {
    const [rows] = await pool.query(
      'SELECT stvuacs_formurl AS url FROM stvuacs WHERE stvuacs_userid = ?',
      [userId]
    );
    return rows.map((r) => r.url);
  }

  static async saveUserFormAccess(userId, formUrls = []) {
    // Clear existing allocations
    await pool.query('DELETE FROM stvuacs WHERE stvuacs_userid = ?', [userId]);

    // Insert new allocations
    for (const url of formUrls) {
      if (!url) continue;
      await pool.query(
        'INSERT INTO stvuacs (stvuacs_userid, stvuacs_formurl) VALUES (?, ?)',
        [userId, url]
      );
    }

    return { userId, allocatedCount: formUrls.length };
  }

  // ─── 9. Shift Master (stvshif) ───
  static async getShifts() {
    const [rows] = await pool.query(
      `SELECT STVSHIF_id AS id, STVSHIF_code AS code, STVSHIF_from AS start_time, STVSHIF_to AS end_time, STVSHIF_added_by AS added_by
       FROM stvshif ORDER BY STVSHIF_id DESC`
    );
    return rows;
  }

  static async createShift({ code, startTime, endTime, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvshif (STVSHIF_code, STVSHIF_from, STVSHIF_to, STVSHIF_added_by) VALUES (?, ?, ?, ?)`,
      [code, startTime, endTime, addedBy]
    );
    return { id: res.insertId, code, startTime, endTime };
  }

  static async updateShift(id, { code, startTime, endTime }) {
    await pool.query(
      `UPDATE stvshif SET STVSHIF_code = ?, STVSHIF_from = ?, STVSHIF_to = ? WHERE STVSHIF_id = ?`,
      [code, startTime, endTime, id]
    );
    return { id, updated: true };
  }

  static async deleteShift(id) {
    const [res] = await pool.query(`DELETE FROM stvshif WHERE STVSHIF_id = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 10. Position / Designation Master (stvposn) ───
  static async getPositions() {
    const [rows] = await pool.query(
      `SELECT STVPOSN_id AS id, STVPOSN_code AS code, STVPOSN_name AS name, STVPOSN_added_by AS added_by
       FROM stvposn ORDER BY STVPOSN_id DESC`
    );
    return rows;
  }

  static async createPosition({ code, name, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvposn (STVPOSN_code, STVPOSN_name, STVPOSN_added_by) VALUES (?, ?, ?)`,
      [code, name, addedBy]
    );
    return { id: res.insertId, code, name };
  }

  static async updatePosition(id, { code, name }) {
    await pool.query(
      `UPDATE stvposn SET STVPOSN_code = ?, STVPOSN_name = ? WHERE STVPOSN_id = ?`,
      [code, name, id]
    );
    return { id, updated: true };
  }

  static async deletePosition(id) {
    const [res] = await pool.query(`DELETE FROM stvposn WHERE STVPOSN_id = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 11. Grades Master (stvgrds) ───
  static async getGrades() {
    const [rows] = await pool.query(
      `SELECT STVGRDS_id AS id, STVGRDS_grade AS grade, STVGRDS_point AS point, STVGRDS_marks_from AS marks_from, STVGRDS_marks_to AS marks_to, STVGRDS_notes AS notes, STVGRDS_added_by AS added_by
       FROM stvgrds ORDER BY STVGRDS_id DESC`
    );
    return rows;
  }

  static async createGrade({ grade, point, marksFrom, marksTo, notes, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvgrds (STVGRDS_grade, STVGRDS_point, STVGRDS_marks_from, STVGRDS_marks_to, STVGRDS_notes, STVGRDS_added_by)
       VALUES (?, ?, ?, ?, ?, ?)`,
      [grade, point, marksFrom, marksTo, notes || '', addedBy]
    );
    return { id: res.insertId, grade, point, marksFrom, marksTo };
  }

  static async updateGrade(id, { grade, point, marksFrom, marksTo, notes }) {
    await pool.query(
      `UPDATE stvgrds SET STVGRDS_grade = ?, STVGRDS_point = ?, STVGRDS_marks_from = ?, STVGRDS_marks_to = ?, STVGRDS_notes = ?
       WHERE STVGRDS_id = ?`,
      [grade, point, marksFrom, marksTo, notes || '', id]
    );
    return { id, updated: true };
  }

  static async deleteGrade(id) {
    const [res] = await pool.query(`DELETE FROM stvgrds WHERE STVGRDS_id = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 12. State Master (stvstts) ───
  static async getStates() {
    const [rows] = await pool.query(
      `SELECT stvstts_id AS id, stvstts_state_code AS state_code, stvstts_state_name AS state_name, stvstts_added_by AS added_by
       FROM stvstts ORDER BY stvstts_state_name ASC`
    );
    return rows;
  }

  static async createState({ stateCode, stateName, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvstts (stvstts_state_code, stvstts_state_name, stvstts_added_by) VALUES (?, ?, ?)`,
      [stateCode, stateName, addedBy]
    );
    return { id: res.insertId, stateCode, stateName };
  }

  static async updateState(id, { stateCode, stateName }) {
    await pool.query(
      `UPDATE stvstts SET stvstts_state_code = ?, stvstts_state_name = ? WHERE stvstts_id = ?`,
      [stateCode, stateName, id]
    );
    return { id, updated: true };
  }

  static async deleteState(id) {
    const [res] = await pool.query(`DELETE FROM stvstts WHERE stvstts_id = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 13. City Master (stvcity) ───
  static async getCities(stateCode) {
    let sql = `SELECT STVCITY_id AS id, STVCITY_code AS city_code, STVCITY_name AS city_name, STVCITY_state_code AS state_code, STVCITY_added_by AS added_by FROM stvcity`;
    const params = [];
    if (stateCode) {
      sql += ` WHERE STVCITY_state_code = ?`;
      params.push(stateCode);
    }
    sql += ` ORDER BY STVCITY_name ASC`;
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  static async createCity({ cityCode, cityName, stateCode, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvcity (STVCITY_code, STVCITY_name, STVCITY_state_code, STVCITY_added_by) VALUES (?, ?, ?, ?)`,
      [cityCode, cityName, stateCode, addedBy]
    );
    return { id: res.insertId, cityCode, cityName, stateCode };
  }

  static async updateCity(id, { cityCode, cityName, stateCode }) {
    await pool.query(
      `UPDATE stvcity SET STVCITY_code = ?, STVCITY_name = ?, STVCITY_state_code = ? WHERE STVCITY_id = ?`,
      [cityCode, cityName, stateCode, id]
    );
    return { id, updated: true };
  }

  static async deleteCity(id) {
    const [res] = await pool.query(`DELETE FROM stvcity WHERE STVCITY_id = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 14. Hold Reasons Master (stvhold) ───
  static async getHoldReasons() {
    const [rows] = await pool.query(
      `SELECT stvhold_id AS id, stvhold_type AS type, stvhold_added_by AS added_by FROM stvhold ORDER BY stvhold_id DESC`
    );
    return rows;
  }

  static async createHoldReason({ type, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvhold (stvhold_type, stvhold_added_by) VALUES (?, ?)`,
      [type, addedBy]
    );
    return { id: res.insertId, type };
  }

  static async updateHoldReason(id, { type }) {
    await pool.query(`UPDATE stvhold SET stvhold_type = ? WHERE stvhold_id = ?`, [type, id]);
    return { id, updated: true };
  }

  static async deleteHoldReason(id) {
    const [res] = await pool.query(`DELETE FROM stvhold WHERE stvhold_id = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 15. University Master (stvuniv) ───
  static async getUniversities() {
    const [rows] = await pool.query(
      `SELECT STVUNIV_code AS id, STVUNIV_name AS name, STVUNIV_city AS city, STVUNIV_state AS state, STVUNIV_address AS address, STVUNIV_email AS email, STVUNIV_phone AS phone
       FROM stvuniv ORDER BY STVUNIV_name ASC`
    );
    return rows;
  }

  static async createUniversity(data) {
    const name = data.name || data.university_name || data.STVUNIV_name;
    const { city, state, address, email, phone, addedBy = 'admin' } = data;
    const [maxRows] = await pool.query('SELECT IFNULL(MAX(STVUNIV_code), 0) + 1 AS nextId FROM stvuniv');
    const nextId = maxRows[0]?.nextId || 1;
    await pool.query(
      `INSERT INTO stvuniv (STVUNIV_code, STVUNIV_name, STVUNIV_city, STVUNIV_state, STVUNIV_address, STVUNIV_email, STVUNIV_phone, STVUNIV_added_by)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      [nextId, name, city || '', state || '', address || '', email || '', phone || '', addedBy]
    );
    return { id: nextId, name };
  }

  static async updateUniversity(id, data) {
    const name = data.name || data.university_name || data.STVUNIV_name;
    const { city, state, address, email, phone } = data;
    await pool.query(
      `UPDATE stvuniv SET STVUNIV_name = ?, STVUNIV_city = ?, STVUNIV_state = ?, STVUNIV_address = ?, STVUNIV_email = ?, STVUNIV_phone = ?
       WHERE STVUNIV_code = ?`,
      [name, city || '', state || '', address || '', email || '', phone || '', id]
    );
    return { id, updated: true };
  }

  static async deleteUniversity(id) {
    const [res] = await pool.query(`DELETE FROM stvuniv WHERE STVUNIV_code = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 16. Major Master (stvmajr) ───
  static async getMajors() {
    const [rows] = await pool.query(
      `SELECT STVMAJR_code AS id, STVMAJR_name AS name, STVMAJR_details AS details, STVMAJR_added_by AS added_by
       FROM stvmajr ORDER BY STVMAJR_name ASC`
    );
    return rows;
  }

  static async createMajor({ name, details, addedBy = 'admin' }) {
    const [res] = await pool.query(
      `INSERT INTO stvmajr (STVMAJR_name, STVMAJR_details, STVMAJR_added_by) VALUES (?, ?, ?)`,
      [name, details || '', addedBy]
    );
    return { id: res.insertId, name, details };
  }

  static async updateMajor(id, { name, details }) {
    await pool.query(
      `UPDATE stvmajr SET STVMAJR_name = ?, STVMAJR_details = ? WHERE STVMAJR_code = ?`,
      [name, details || '', id]
    );
    return { id, updated: true };
  }

  static async deleteMajor(id) {
    const [res] = await pool.query(`DELETE FROM stvmajr WHERE STVMAJR_code = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 17. College Master (stvcoll) ───
  static async getColleges() {
    const [rows] = await pool.query(
      `SELECT STVCOLL_code AS id, STVCOLL_name AS name, STVCOLL_city AS city, STVCOLL_state AS state, STVCOLL_address AS address, STVCOLL_email AS email, STVCOLL_phone AS phone, STVCOLL_added_by AS added_by
       FROM stvcoll ORDER BY STVCOLL_name ASC`
    );
    return rows;
  }

  static async createCollege(data, addedBy = 'ADMIN') {
    const { name, city, state, address, email, phone } = data;
    const [res] = await pool.query(
      `INSERT INTO stvcoll (STVCOLL_name, STVCOLL_city, STVCOLL_state, STVCOLL_address, STVCOLL_email, STVCOLL_phone, STVCOLL_added_by)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [name, city || '', state || '', address || '', email || '', phone || '', addedBy]
    );
    return { id: res.insertId, ...data };
  }

  static async updateCollege(id, data) {
    const { name, city, state, address, email, phone } = data;
    await pool.query(
      `UPDATE stvcoll SET STVCOLL_name = ?, STVCOLL_city = ?, STVCOLL_state = ?, STVCOLL_address = ?, STVCOLL_email = ?, STVCOLL_phone = ?
       WHERE STVCOLL_code = ?`,
      [name, city || '', state || '', address || '', email || '', phone || '', id]
    );
    return { id, updated: true };
  }

  static async deleteCollege(id) {
    const [res] = await pool.query(`DELETE FROM stvcoll WHERE STVCOLL_code = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── 18. Student Active Holds (sprhold) ───
  static async getStudentHolds() {
    const [rows] = await pool.query(
      `SELECT 
        h.sprhold_id AS id,
        h.sprhold_hold_id AS hold_id,
        h.sprhold_student_id AS student_id,
        h.sprhold_from AS hold_from,
        h.sprhold_to AS hold_to,
        h.sprhold_date AS hold_date,
        h.sprhold_status AS status,
        h.sprhold_added_by AS added_by,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        r.stvhold_type AS hold_reason
       FROM sprhold h
       JOIN spriden s ON s.id = h.sprhold_student_id
       LEFT JOIN stvhold r ON r.stvhold_id = h.sprhold_hold_id
       ORDER BY h.sprhold_id DESC`
    );
    return rows;
  }

  static async createStudentHold(data, addedBy = 'ADMIN') {
    const { hold_id, student_id, hold_from, hold_to } = data;
    const [res] = await pool.query(
      `INSERT INTO sprhold (sprhold_hold_id, sprhold_student_id, sprhold_from, sprhold_to, sprhold_date, sprhold_status, sprhold_added_by)
       VALUES (?, ?, ?, ?, CURDATE(), 'Active', ?)`,
      [hold_id, student_id, hold_from || null, hold_to || null, addedBy]
    );
    return { id: res.insertId, ...data };
  }

  static async deleteStudentHold(id) {
    const [res] = await pool.query(`DELETE FROM sprhold WHERE sprhold_id = ?`, [id]);
    return res.affectedRows > 0;
  }

  // ─── Student Class / Division Update (CI3: MasterForm/studentStdDiv) ───
  static async getStdDivDataByID(studentIdentifier) {
    const [rows] = await pool.query(
      `SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        c.STVCLAS_code,
        c.STVCLAS_student_id,
        c.STVCLAS_rollno,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        c.STVCLAS_userid,
        c.STVCLAS_acdm,
        c.updated_flag,
        ac.stvacdm_year
       FROM spriden s
       LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id
       LEFT JOIN stvacdm ac ON ac.stvacdm_id = c.STVCLAS_acdm
       WHERE s.SPRIDEN_student_id = ? OR s.id = ?
       ORDER BY c.STVCLAS_acdm DESC`,
      [studentIdentifier, studentIdentifier]
    );
    return rows;
  }

  static async updateClassDetails({ student_id, code, standard, class: division, acdm, old_acdm, old_standard, old_class }) {
    const updates = [];
    const params = [];

    if (standard !== undefined && standard !== old_standard) {
      updates.push('STVCLAS_standard = ?');
      params.push(standard);
    }
    if (division !== undefined && division !== old_class) {
      updates.push('STVCLAS_class = ?');
      params.push(division);
    }
    if (acdm !== undefined && acdm !== old_acdm) {
      updates.push('STVCLAS_acdm = ?');
      params.push(acdm);
    }

    if (updates.length === 0) return true;

    params.push(student_id, code);
    const [res] = await pool.query(
      `UPDATE stvclas SET ${updates.join(', ')} WHERE STVCLAS_student_id = ? AND STVCLAS_code = ?`,
      params
    );
    return res.affectedRows > 0;
  }
}

export default MasterModel;

