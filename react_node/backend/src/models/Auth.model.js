/**
 * Auth Model — all database queries for authentication.
 *
 * CI3 equivalent: application/models/Login_model.php
 *
 * Tables:
 *   spruser      – admin/staff users
 *   spriden      – teachers (SPRIDEN_user_type='tc') & students (SPRIDEN_user_type='st')
 *   stvlgin      – login history
 *   app_tracking – mobile/app login tracking
 *   stvschool    – school-level settings (current academic year)
 *   stvacdm      – academic year master list
 *   spatesd      – teacher–class assignments
 */
import pool from '../config/db.js';
import crypto from 'crypto';

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Find admin user by username + password (plaintext match — legacy schema).
 * CI3: Login_model::validateUser()
 */
export async function findAdminByCredentials(username, password) {
  const [rows] = await pool.execute(
    `SELECT * FROM spruser WHERE spruser_username = ? AND spruser_password = ?`,
    [username, password]
  );
  return rows[0] || null;
}

/**
 * Store sha256 auth token in spruser (mirrors legacy hrms_auth_token cookie).
 * CI3: Login::validate() – $token = hash("sha256", $user['spruser_id'])
 */
export async function updateAdminAuthToken(spruser_id) {
  const token = crypto.createHash('sha256').update(String(spruser_id)).digest('hex');
  await pool.execute(
    `UPDATE spruser SET spruser_auth_token = ? WHERE spruser_id = ?`,
    [token, spruser_id]
  );
  return token;
}

// ─────────────────────────────────────────────────────────────────────────────
// TEACHER
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Validate teacher credentials.
 * CI3: Login_model::validateTeacher()
 * Fields: SPRIDEN_student_id, spriden_tc_pass, SPRIDEN_user_type='tc'
 */
export async function findTeacherByCredentials(teacherId, password) {
  const [rows] = await pool.execute(
    `SELECT * FROM spriden
     WHERE SPRIDEN_student_id = ? AND spriden_tc_pass = ? AND SPRIDEN_user_type = 'tc'`,
    [teacherId, password]
  );
  return rows[0] || null;
}

/**
 * Fetch all classes assigned to a teacher.
 * CI3: Login_model::getAssignedClasses($teacher_id)
 */
export async function getTeacherAssignedClasses(teacherId) {
  const [rows] = await pool.execute(
    `SELECT SPATESD_id, SPATESD_teacher_id, SPATESD_std, SPATESD_div, SPATESD_academic_year
     FROM spatesd
     WHERE SPATESD_teacher_id = ?
     ORDER BY SPATESD_std, SPATESD_div`,
    [teacherId]
  );
  return rows;
}

// ─────────────────────────────────────────────────────────────────────────────
// STUDENT
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Find student by student ID (no password – ID-only login).
 * CI3: Login_model::validateStudent($id)
 */
export async function findStudentById(studentId) {
  const [rows] = await pool.execute(
    `SELECT * FROM spriden WHERE SPRIDEN_student_id = ? AND SPRIDEN_user_type = 'st'`,
    [studentId]
  );
  return rows[0] || null;
}

// ─────────────────────────────────────────────────────────────────────────────
// LOGIN HISTORY / TRACKING
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Insert a login/logout event into stvlgin.
 * CI3: Login_model::addLoginHistory($data)
 */
export async function addLoginHistory(userId, status = 'Logged In') {
  try {
    const now   = new Date();
    const date  = now.toISOString().slice(0, 10);
    const time  = now.toTimeString().slice(0, 8);
    await pool.execute(
      `INSERT INTO stvlgin (stvlgin_date, stvlgin_time, stvlgin_userid, stvlgin_status)
       VALUES (?, ?, ?, ?)`,
      [date, time, userId, status]
    );
  } catch (err) {
    // Non-fatal — never break the login flow for audit failures
    console.warn('[Model:Auth] addLoginHistory failed:', err.message);
  }
}

/**
 * Upsert app_tracking row (teacher / student mobile app tracking).
 * CI3: Login_model::manageLoginHistory() & studentLoginHistory()
 */
export async function upsertAppTracking(userId, userType) {
  try {
    const now     = new Date();
    const today   = now.toISOString().slice(0, 10);
    const timeStr = now.toTimeString().slice(0, 8);

    const [existing] = await pool.execute(
      `SELECT * FROM app_tracking WHERE user_id = ? AND login_date = ?`,
      [userId, today]
    );

    if (existing.length === 0) {
      await pool.execute(
        `INSERT INTO app_tracking (user_id, user_type, login_date, login_time, last_login, login_count)
         VALUES (?, ?, ?, ?, ?, 1)`,
        [userId, userType, today, timeStr, timeStr]
      );
    } else {
      const row = existing[0];
      await pool.execute(
        `UPDATE app_tracking SET last_login = ?, login_count = ? WHERE Id = ?`,
        [timeStr, Number(row.login_count) + 1, row.Id]
      );
    }
  } catch (err) {
    console.warn('[Model:Auth] upsertAppTracking failed:', err.message);
  }
}

// ─────────────────────────────────────────────────────────────────────────────
// ACADEMIC YEARS (Student login dropdown)
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Fetch all academic years + currently active year.
 * CI3: Student_login::index() — stvacdm + stvschool queries
 */
export async function getAcademicYears() {
  const [years] = await pool.execute(
    `SELECT stvacdm_id, stvacdm_year FROM stvacdm ORDER BY stvacdm_year DESC`
  );
  const [schoolRows] = await pool.execute(
    `SELECT stvschool_acdm_year FROM stvschool LIMIT 1`
  );
  return {
    academicYears:       years,
    selectedAcademicYear: schoolRows[0]?.stvschool_acdm_year ?? null,
  };
}

/**
 * Change password for admin or teacher account.
 * CI3: Stats_model::change_password()
 */
export async function updateUserPassword(userId, role, currentPassword, newPassword) {
  if (role === 'admin') {
    const [rows] = await pool.execute(
      `SELECT * FROM spruser WHERE spruser_id = ? AND spruser_password = ?`,
      [userId, currentPassword]
    );
    if (!rows.length) return { success: false, message: 'Current password does not match.' };

    await pool.execute(
      `UPDATE spruser SET spruser_password = ? WHERE spruser_id = ?`,
      [newPassword, userId]
    );
    return { success: true };
  } else if (role === 'teacher') {
    const [rows] = await pool.execute(
      `SELECT * FROM spriden WHERE id = ? AND spriden_tc_pass = ? AND SPRIDEN_user_type = 'tc'`,
      [userId, currentPassword]
    );
    if (!rows.length) return { success: false, message: 'Current password does not match.' };

    await pool.execute(
      `UPDATE spriden SET spriden_tc_pass = ? WHERE id = ?`,
      [newPassword, userId]
    );
    return { success: true };
  } else {
    return { success: false, message: 'Password change not supported for this user role.' };
  }
}

