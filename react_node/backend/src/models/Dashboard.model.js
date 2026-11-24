/**
 * Dashboard Model — all database queries for the role-based dashboard.
 *
 * CI3 equivalents:
 *   Admin   → Stats_model, Welcome::index(), Task_model
 *   Teacher → Dashboard_model, Dashboard::index()
 *   Student → Profile_model, Profile::index()
 */
import pool from '../config/db.js';

// ─────────────────────────────────────────────────────────────────────────────
// ADMIN DASHBOARD QUERIES
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Student / teacher headcounts matching CI3 Stats_model exactly:
 * - student_total_active: active students without RTE
 * - rte_student: active RTE students
 * - total_active: active + rte
 * - teacher_total: active teachers
 * - inActive_student: inactive students
 */
export async function getStudentTeacherCounts() {
  const [[activeStudents]]   = await pool.query(`SELECT COUNT(*) AS active_student FROM spriden WHERE spriden_student_status='A' AND SPRIDEN_user_type='st' AND (rte_status='No' OR rte_status IS NULL)`);
  const [[rteStudents]]      = await pool.query(`SELECT COUNT(*) AS rte FROM spriden WHERE spriden_student_status='A' AND (rte_status='Yes' OR rte_status='Y' OR rte_status='1') AND SPRIDEN_user_type='st'`);
  const [[inactiveStudents]] = await pool.query(`SELECT COUNT(*) AS noshow_student FROM spriden WHERE spriden_student_status='n' AND SPRIDEN_user_type='st'`);
  const [[totalStudents]]    = await pool.query(`SELECT COUNT(*) AS total FROM spriden WHERE SPRIDEN_user_type='st'`);
  const [[activeTeachers]]   = await pool.query(`SELECT COUNT(*) AS teacher_total FROM spriden WHERE SPRIDEN_user_type='tc' AND spriden_student_status='A'`);
  const [[inactiveTeachers]] = await pool.query(`SELECT COUNT(*) AS teacher_total_i FROM spriden WHERE SPRIDEN_user_type='tc' AND spriden_student_status='I'`);

  const activeCount = Number(activeStudents?.active_student || 0);
  const rteCount = Number(rteStudents?.rte || 0);
  const totalActive = activeCount + rteCount;

  return {
    activeStudents: activeCount,
    rteStudents: rteCount,
    totalActiveStudents: totalActive,
    inactiveStudents: Number(inactiveStudents?.noshow_student || 0),
    totalStudents: Number(totalStudents?.total || 0),
    activeTeachers: Number(activeTeachers?.teacher_total || 0),
    inactiveTeachers: Number(inactiveTeachers?.teacher_total_i || 0),
  };
}

/**
 * Current academic year details.
 * CI3: Stats_model::getAcademicDetails()
 */
export async function getCurrentAcademicYear() {
  const [rows] = await pool.query(
    `SELECT a.stvacdm_id, a.stvacdm_year, a.stvacdm_start, a.stvacdm_end, s.stvschool_acdm_year
     FROM stvschool s
     LEFT JOIN stvacdm a ON s.stvschool_acdm_year = a.stvacdm_id
     LIMIT 1`
  );
  return rows[0] || { stvacdm_id: 1, stvacdm_year: '2026-27' };
}

/**
 * Recent audit logins from stvlgin joined to spruser.
 * CI3: Stats_model::last_login() / current_login()
 */
export async function getRecentLogins(userId, limit = 8) {
  const [rows] = await pool.query(
    `SELECT l.stvlgin_id, l.stvlgin_date, l.stvlgin_time,
            l.stvlgin_userid, l.stvlgin_status,
            u.spruser_name, u.spruser_username, u.spruser_usertype
     FROM stvlgin l
     LEFT JOIN spruser u ON l.stvlgin_userid = u.spruser_id
     ORDER BY l.stvlgin_id DESC
     LIMIT ?`,
    [limit]
  );

  let currentLogin = null;
  if (userId) {
    const [curRows] = await pool.query(
      `SELECT * FROM stvlgin WHERE stvlgin_userid = ? ORDER BY stvlgin_id DESC LIMIT 1`,
      [userId]
    );
    currentLogin = curRows[0] || null;
  }

  return { recent: rows, current: currentLogin };
}

/**
 * Standard-wise student enrollment for current academic year.
 * CI3: Stats_model::chart_data()
 */
export async function getChartData() {
  const [rows] = await pool.query(
    `SELECT IFNULL(COUNT(*), 0) AS TOTAL_STUDENTS, STVCLAS_standard
     FROM stvclas, spriden
     WHERE stvclas_acdm = (SELECT stvschool_acdm_year FROM stvschool)
       AND spriden_student_status = 'A'
       AND SPRIDEN_user_type = 'st'
       AND spriden.id = STVCLAS_student_id
     GROUP BY STVCLAS_standard
     ORDER BY (STVCLAS_standard + 0) ASC, STVCLAS_standard ASC`
  );
  return rows;
}

/**
 * Student Fees Due Date records.
 * CI3: Stats_model::fetchDueDateRecords()
 */
export async function fetchDueDateRecords(targetDate = null) {
  const dateStr = targetDate || new Date().toISOString().slice(0, 10);
  const [rows] = await pool.query(
    `SELECT DISTINCT f.sprfees_studentid,
            SUM(f.sprfees_amount) AS total_paid,
            SUM(f.sprfees_exumption) AS total_exumpted,
            CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', s.SPRIDEN_father_name) AS name,
            s.SPRIDEN_student_id,
            s.id,
            c.STVCLAS_standard,
            (SELECT stvfstr_fees FROM stvfstr WHERE stvfstr_std = c.STVCLAS_standard AND stvfstr_acdm_year = f.academic_year LIMIT 1) AS class_fees,
            (SELECT COALESCE(stvpcnf_mobile, stvpcnf_mobile2) FROM stvpcnf WHERE stvpcnf_student_id = f.sprfees_studentid LIMIT 1) AS mobile
     FROM sprfees f
     JOIN spriden s ON s.id = f.sprfees_studentid
     JOIN stvclas c ON s.id = c.STVCLAS_student_id AND c.STVCLAS_acdm = f.academic_year
     WHERE f.sprfees_due_date = ?
       AND f.academic_year IN (SELECT stvschool_acdm_year FROM stvschool)
     GROUP BY f.sprfees_studentid
     ORDER BY f.sprfees_studentid DESC`,
    [dateStr]
  );
  return rows;
}

/**
 * Daily fees report breakdown by payment mode.
 * CI3: Stats_model::daily_fees_report(from, to, 'stats', year, new_admission)
 */
export async function getDailyFeesReport(from, to, acdmYear = 'current', newAdmission = 'off') {
  const [[school]] = await pool.query(`SELECT stvschool_acdm_year FROM stvschool LIMIT 1`);
  const runningYear = school?.stvschool_acdm_year || 1;

  const fromDate = from || new Date().toISOString().slice(0, 10);
  const toDate   = to   || new Date().toISOString().slice(0, 10);

  // Current academic year breakdown
  const [currentBreakdown] = await pool.query(
    `SELECT SUM(f.sprfees_amount) AS amount, f.payment_mode
     FROM sprfees f
     WHERE f.sprfees_feesdate BETWEEN ? AND ?
       AND f.academic_year IN (?)
     GROUP BY f.payment_mode`,
    [fromDate, toDate, runningYear]
  );

  const currentTotal = currentBreakdown.reduce((sum, item) => sum + Number(item.amount || 0), 0);

  // Previous academic years breakdown
  const [previousBreakdown] = await pool.query(
    `SELECT SUM(f.sprfees_amount) AS amount, f.payment_mode
     FROM sprfees f
     WHERE f.sprfees_feesdate BETWEEN ? AND ?
       AND f.academic_year NOT IN (?)
     GROUP BY f.payment_mode`,
    [fromDate, toDate, runningYear]
  );

  const previousTotal = previousBreakdown.reduce((sum, item) => sum + Number(item.amount || 0), 0);

  return {
    from: fromDate,
    to: toDate,
    current_fees_breakdown: currentBreakdown,
    current_total: currentTotal,
    previous_fees_breakdown: previousBreakdown,
    previous_total: previousTotal,
    grand_total: currentTotal + previousTotal,
  };
}

/**
 * Instant Student Fee Lookup (Search box on Dashboard).
 * CI3: Student::fetchReadmissionDetails()
 */
export async function fetchStudentFeeDetails(studentId) {
  const trimmed = String(studentId).trim();
  const [studRows] = await pool.execute(
    `SELECT s.*, c.STVCLAS_standard, c.STVCLAS_class, c.STVCLAS_acdm
     FROM spriden s
     LEFT JOIN stvclas c ON s.id = c.STVCLAS_student_id
     WHERE s.SPRIDEN_student_id = ? OR s.id = ?
     LIMIT 1`,
    [trimmed, isNaN(Number(trimmed)) ? 0 : Number(trimmed)]
  );

  if (!studRows.length) return null;

  const student = studRows[0];

  // Fetch fees history records
  const [feesDetails] = await pool.execute(
    `SELECT f.*,
            c.STVCLAS_standard AS class,
            IFNULL(f.sprfees_amount, 0) AS total_paid,
            IFNULL(f.sprfees_exumption, 0) AS exemption,
            IFNULL(fs.stvfstr_fees, 0) AS class_fees,
            (IFNULL(fs.stvfstr_fees, 0) - IFNULL(f.sprfees_amount, 0) - IFNULL(f.sprfees_exumption, 0)) AS balance,
            a.stvacdm_year AS academic_year
     FROM sprfees f
     LEFT JOIN stvclas c ON f.sprfees_studentid = c.STVCLAS_student_id AND f.academic_year = c.STVCLAS_acdm
     LEFT JOIN stvfstr fs ON fs.stvfstr_std = c.STVCLAS_standard AND fs.stvfstr_acdm_year = f.academic_year
     LEFT JOIN stvacdm a ON f.academic_year = a.stvacdm_id
     WHERE f.sprfees_studentid = ?
     ORDER BY f.sprfees_id DESC`,
    [student.id]
  );

  return {
    student_details: student,
    class_details: {
      STVCLAS_standard: student.STVCLAS_standard,
      STVCLAS_class: student.STVCLAS_class,
    },
    fees_details: feesDetails,
  };
}

// ─────────────────────────────────────────────────────────────────────────────
// TASK MANAGER (CRUD for Dashboard)
// CI3: Task.php / Task_model.php
// ─────────────────────────────────────────────────────────────────────────────

export async function getAllTasks(status = null, title = null) {
  let sql = `SELECT * FROM tasks WHERE 1=1`;
  const params = [];

  if (status && status !== 'all') {
    sql += ` AND status = ?`;
    params.push(status);
  } else if (!status) {
    sql += ` AND status NOT IN ('Complete', 'complete', 'Cancelled', 'cancelled', 'Close', 'close')`;
  }

  if (title && title.trim()) {
    sql += ` AND title LIKE ?`;
    params.push(`%${title.trim()}%`);
  }

  sql += ` ORDER BY id DESC`;

  const [rows] = await pool.query(sql, params);
  return rows;
}

export async function insertTask(data) {
  const [result] = await pool.execute(
    `INSERT INTO tasks (title, start_date, reminder_date, status, restart_count)
     VALUES (?, ?, ?, ?, ?)`,
    [
      data.title,
      data.start_date || null,
      data.reminder_date || null,
      data.status || 'pending',
      0,
    ]
  );
  return result.insertId;
}

export async function updateTask(id, data) {
  const [result] = await pool.execute(
    `UPDATE tasks SET title = ?, start_date = ?, reminder_date = ? WHERE id = ?`,
    [data.title, data.start_date || null, data.reminder_date || null, id]
  );
  return result.affectedRows > 0;
}

export async function updateTaskStatus(id, status) {
  let sql = `UPDATE tasks SET status = ?`;
  const params = [status];

  if (status === 'restart') {
    sql += `, restart_count = restart_count + 1`;
  }
  sql += ` WHERE id = ?`;
  params.push(id);

  const [result] = await pool.execute(sql, params);
  return result.affectedRows > 0;
}

export async function deleteTask(id) {
  const [result] = await pool.execute(`DELETE FROM tasks WHERE id = ?`, [id]);
  return result.affectedRows > 0;
}

// ─────────────────────────────────────────────────────────────────────────────
// TEACHER DASHBOARD QUERIES
// CI3: Dashboard_model.php
// ─────────────────────────────────────────────────────────────────────────────

export async function getTeacherAllocatedClasses(teacherId) {
  const [rows] = await pool.execute(
    `SELECT SPATESD_id, SPATESD_teacher_id, SPATESD_std, SPATESD_div, SPATESD_academic_year
     FROM spatesd
     WHERE SPATESD_teacher_id = ?
     ORDER BY SPATESD_std, SPATESD_div`,
    [teacherId]
  );
  return rows;
}

export async function getClassRoster(standard, division, limit = 100) {
  const [rows] = await pool.execute(
    `SELECT c.STVCLAS_rollno,
            s.id, s.SPRIDEN_student_id, s.SPRIDEN_surname,
            s.SPRIDEN_student_name, s.SPRIDEN_father_name,
            s.spriden_student_status, s.SPRIDEN_gender, s.SPRIDEN_aadhar_no
     FROM stvclas c
     JOIN spriden s ON c.STVCLAS_student_id = s.id
     WHERE c.STVCLAS_standard = ? AND c.STVCLAS_class = ?
     ORDER BY (c.STVCLAS_rollno + 0) ASC, s.SPRIDEN_student_name ASC
     LIMIT ?`,
    [standard, division, limit]
  );
  return rows;
}

export async function getTeacherRemarksCounts(teacherId) {
  const [[pending]] = await pool.query(
    `SELECT COUNT(*) AS cnt FROM stvrmrk WHERE stvrmrk_teacher_id = ? AND (stvrmrk_reply IS NULL OR stvrmrk_reply = '')`,
    [teacherId]
  );
  const [[replied]] = await pool.query(
    `SELECT COUNT(*) AS cnt FROM stvrmrk WHERE stvrmrk_teacher_id = ? AND stvrmrk_reply IS NOT NULL AND stvrmrk_reply != ''`,
    [teacherId]
  );
  return {
    total_pending: pending?.cnt || 0,
    total_replied: replied?.cnt || 0,
  };
}

// ─────────────────────────────────────────────────────────────────────────────
// STUDENT DASHBOARD QUERIES
// CI3: Profile_model.php / StudentProfile_model.php
// ─────────────────────────────────────────────────────────────────────────────

export async function getStudentProfile(spridenId) {
  const [rows] = await pool.execute(
    `SELECT s.*,
            c.STVCLAS_rollno, c.STVCLAS_standard, c.STVCLAS_class,
            a.stvacdm_year
     FROM spriden s
     LEFT JOIN stvclas c ON s.id = c.STVCLAS_student_id
     LEFT JOIN stvacdm a ON s.SPRIDEN_academic_year = a.stvacdm_id
     WHERE s.id = ?
     LIMIT 1`,
    [spridenId]
  );
  return rows[0] || null;
}

export async function getStudentAttendanceSummary(spridenId) {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const from = `${year}-${month}-01`;
  const to = new Date(year, today.getMonth() + 1, 0).toISOString().slice(0, 10);

  const [records] = await pool.execute(
    `SELECT SPRATND_date, SPRATND_status
     FROM spratnd
     WHERE SPRATND_student_id = ?
       AND SPRATND_date BETWEEN ? AND ?
     ORDER BY SPRATND_date ASC`,
    [spridenId, from, to]
  );

  let present = 0;
  let absent = 0;
  records.forEach((r) => {
    if (r.SPRATND_status === 'P' || r.SPRATND_status === 'Present') present++;
    else absent++;
  });

  return { records, present, absent, totalDays: records.length };
}

export async function getStudentRemarks(spridenId) {
  const [rows] = await pool.execute(
    `SELECT r.*,
            CONCAT(t.SPRIDEN_surname, ' ', t.SPRIDEN_student_name) AS teacher_name
     FROM stvrmrk r
     LEFT JOIN spriden t ON r.stvrmrk_teacher_id = t.SPRIDEN_student_id
     WHERE r.stvrmrk_student_id = ?
     ORDER BY r.stvrmrk_date DESC
     LIMIT 15`,
    [spridenId]
  );
  return rows;
}

export async function getStudentClassTeacher(standard, division) {
  const [rows] = await pool.execute(
    `SELECT a.SPATESD_teacher_id,
            t.SPRIDEN_student_name, t.SPRIDEN_surname, t.SPRIDEN_father_name
     FROM spatesd a
     LEFT JOIN spriden t ON a.SPATESD_teacher_id = t.SPRIDEN_student_id
     WHERE a.SPATESD_std = ? AND a.SPATESD_div = ?
     LIMIT 1`,
    [standard, division]
  );
  return rows[0] || null;
}

export async function getAllFormsList() {
  const [rows] = await pool.query(
    `SELECT stvfrms_id, stvfrms_url_name, stvfrms_form_name
     FROM stvfrms
     ORDER BY stvfrms_form_name ASC`
  );
  return rows;
}

export async function getAllAcademicYearsForAdmin() {
  const [rows] = await pool.query(
    `SELECT stvacdm_id, stvacdm_year, status FROM stvacdm ORDER BY stvacdm_id DESC`
  );
  return rows;
}

/**
 * Fetch allocated forms for user from modules, form, user_access
 * CI3: Access_model::fetchForms($id)
 */
export async function fetchAllocatedForms(userId) {
  try {
    if (!userId) return [];
    const [modules] = await pool.query(
      `SELECT id, icon, module_name 
       FROM modules 
       WHERE id IN (SELECT DISTINCT module_id FROM user_access WHERE user_id = ?) 
       ORDER BY module_name`,
      [userId]
    );

    const forms = [];
    for (const module of modules) {
      const [moduleForms] = await pool.query(
        `SELECT id, form_name, form_link 
         FROM form 
         WHERE id IN (SELECT DISTINCT form_id FROM user_access WHERE user_id = ? AND module_id = ?)`,
        [userId, module.id]
      );
      forms.push({
        module_id: module.id,
        module_name: module.module_name,
        icon: module.icon,
        forms: moduleForms,
      });
    }
    return forms;
  } catch (err) {
    console.error('fetchAllocatedForms error:', err);
    return [];
  }
}


