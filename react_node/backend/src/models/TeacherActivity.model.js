/**
 * TeacherActivity Model — Queries `teacher_activity`, `spatesd`, `spratnd`, `spriden`, and `stvacdm`
 * Replicates CI3 `Stats::activity_report` / `Stats_model::fetchActivities`.
 */
import pool from '../config/db.js';

class TeacherActivityModel {
  static async getOptions() {
    const [school] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    const activeYear = school[0]?.stvschool_acdm_year ? String(school[0].stvschool_acdm_year) : '';

    const [years] = await pool.query(`
      SELECT 
        stvacdm_id AS id,
        stvacdm_year AS name,
        stvacdm_start AS start_date,
        stvacdm_end AS end_date,
        status AS is_active
      FROM stvacdm
      ORDER BY stvacdm_id DESC
    `);

    const [teachers] = await pool.query(`
      SELECT 
        SPRIDEN_student_id AS teacher_id,
        CONCAT(IFNULL(SPRIDEN_surname, ''), ' ', IFNULL(SPRIDEN_student_name, ''), ' ', IFNULL(SPRIDEN_father_name, '')) AS teacher_name
      FROM spriden
      WHERE SPRIDEN_user_type = 'tc'
        AND spriden_student_status = 'A'
        AND SPRIDEN_student_id IS NOT NULL
      ORDER BY SPRIDEN_student_name
    `);

    return { active_year: activeYear, years, teachers };
  }

  static async getActivityReport({ academic_year, from_date, to_date, teacher_id }) {
    let year = academic_year;
    if (!year) {
      const [school] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
      year = school[0]?.stvschool_acdm_year ? String(school[0].stvschool_acdm_year) : '';
    }

    const [yearRows] = await pool.query(
      'SELECT stvacdm_start AS start_date, stvacdm_end AS end_date FROM stvacdm WHERE stvacdm_id = ? LIMIT 1',
      [year]
    );

    const defaultStart = yearRows[0]?.start_date
      ? new Date(yearRows[0].start_date).toISOString().slice(0, 10)
      : `${new Date().getFullYear()}-06-01`;
    const defaultEnd = yearRows[0]?.end_date
      ? new Date(yearRows[0].end_date).toISOString().slice(0, 10)
      : new Date().toISOString().slice(0, 10);

    const from = from_date || defaultStart;
    const to = to_date || defaultEnd;

    let teacherSql = `
      SELECT 
        SPRIDEN_student_id AS teacher_id,
        CONCAT(IFNULL(SPRIDEN_surname, ''), ' ', IFNULL(SPRIDEN_student_name, ''), ' ', IFNULL(SPRIDEN_father_name, '')) AS teacher_name
      FROM spriden
      WHERE SPRIDEN_user_type = 'tc'
        AND spriden_student_status = 'A'
        AND SPRIDEN_student_id IS NOT NULL
    `;
    const teacherParams = [];
    if (teacher_id && teacher_id !== 'all') {
      teacherSql += ' AND SPRIDEN_student_id = ?';
      teacherParams.push(teacher_id);
    }

    const [teachers] = await pool.query(teacherSql, teacherParams);
    if (teachers.length === 0) {
      return { from_date: from, to_date: to, academic_year: year, teachers: [] };
    }

    const teacherIds = teachers.map((t) => t.teacher_id);
    const placeholders = teacherIds.map(() => '?').join(',');

    const [rows] = await pool.query(
      `
      SELECT
        t.user_id AS teacher_id,
        DATE_FORMAT(t.activity_date, '%Y-%m-%d') AS activity_date,
        t.activity_time,
        t.activity_sorce AS activity_source,
        DATE_FORMAT(t.action_date, '%Y-%m-%d') AS action_date,
        (SELECT CONCAT(IFNULL(c.SPATESD_std, ''), ' - ', IFNULL(c.SPATESD_div, '')) 
         FROM spatesd c 
         WHERE c.SPATESD_teacher_id = t.user_id AND (c.SPATESD_academic_year = ? OR ? IS NULL) 
         LIMIT 1) AS class_name,
        0 AS present_count,
        0 AS absent_count
      FROM teacher_activity t
      WHERE t.user_id IN (${placeholders})
        AND t.activity_date >= ?
        AND t.activity_date <= ?
      ORDER BY t.activity_date DESC, t.activity_time DESC
      LIMIT 100
      `,
      [year, year, ...teacherIds, from, to]
    );

    const grouped = {};
    for (const t of teachers) {
      grouped[t.teacher_id] = {
        teacher_id: t.teacher_id,
        teacher_name: (t.teacher_name || '').trim(),
        total_activities: 0,
        activities: [],
      };
    }

    for (const r of rows) {
      if (grouped[r.teacher_id]) {
        grouped[r.teacher_id].activities.push({
          ...r,
          present_count: Number(r.present_count || 0),
          absent_count: Number(r.absent_count || 0),
        });
        grouped[r.teacher_id].total_activities += 1;
      }
    }

    const activeTeachers = Object.values(grouped).filter((g) => g.activities.length > 0);
    return {
      from_date: from,
      to_date: to,
      academic_year: year,
      teachers: activeTeachers,
    };
  }

  static async getLoginTracking({ from_date, to_date, user_type }) {
    const today = new Date().toISOString().slice(0, 10);
    const weekAgo = new Date(Date.now() - 7 * 86400000).toISOString().slice(0, 10);
    const from = from_date || weekAgo;
    const to = to_date || today;
    const type = user_type === 'Student' || user_type === 'st' ? 'st' : 'tc';

    const [rows] = await pool.query(
      `
      SELECT
        at.user_type,
        at.user_id,
        DATE_FORMAT(at.login_date, '%Y-%m-%d') AS login_date,
        at.login_time,
        at.last_login,
        at.login_count,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        s.SPRIDEN_student_id AS user_code
      FROM app_tracking at
      INNER JOIN spriden s
        ON (
          (at.user_type = 'tc' AND at.user_id = s.SPRIDEN_student_id)
          OR
          (at.user_type = 'st' AND at.user_id = s.id)
        )
      WHERE at.user_type = ?
        AND at.login_date BETWEEN ? AND ?
      ORDER BY at.login_date DESC, at.login_time DESC
      LIMIT 300
      `,
      [type, from, to]
    );
    return { from_date: from, to_date: to, user_type: type, logs: rows };
  }
}

export default TeacherActivityModel;
