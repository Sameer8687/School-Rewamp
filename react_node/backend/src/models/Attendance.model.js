import pool from '../config/db.js';

class AttendanceModel {
  /**
   * Get active academic year ID from stvschool
   */
  static async getActiveAcademicYearId() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 9;
  }

  /**
   * Get students of standard & division with attendance status for a given date
   */
  static async getStudentsForAttendance(standard, division, academicYear, date) {
    const query = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        s.rte_status,
        (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS mobile,
        (SELECT stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS whatsapp,
        att.SPRATND_status AS status,
        att.SPRATND_comment AS comment,
        att.SPRATND_id
      FROM spriden s
      JOIN stvclas c 
        ON c.STVCLAS_student_id = s.id 
       AND c.STVCLAS_acdm = ?
      LEFT JOIN spratnd att 
        ON att.SPRATND_student_id = s.id 
       AND att.SPRATND_date = ?
       AND att.SPRATND_acdm_year = c.STVCLAS_acdm
      WHERE c.STVCLAS_standard = ?
        AND c.STVCLAS_class = ?
        AND s.spriden_student_status = 'A'
      ORDER BY s.SPRIDEN_student_name ASC
    `;

    const [rows] = await pool.query(query, [academicYear, date, standard, division]);
    return rows;
  }

  /**
   * Save or update attendance for a class on a specific date
   */
  static async saveAttendance(params) {
    const { date, standard, division, academicYear, records, teacherId = '1' } = params;

    // Delete existing attendance records for this class & date to ensure idempotency
    await pool.query(
      `DELETE FROM spratnd 
       WHERE SPRATND_date = ? 
         AND SPRATND_std = ? 
         AND SPRATND_div = ? 
         AND SPRATND_acdm_year = ?`,
      [date, standard, division, String(academicYear)]
    );

    if (!records || records.length === 0) {
      return { count: 0 };
    }

    const insertValues = records.map((r) => [
      String(teacherId),
      String(r.student_id),
      date,
      r.status === 'A' ? 'A' : 'P',
      standard,
      division,
      String(academicYear),
      r.comment || ''
    ]);

    const query = `
      INSERT INTO spratnd (
        SPRATND_teacher_id,
        SPRATND_student_id,
        SPRATND_date,
        SPRATND_status,
        SPRATND_std,
        SPRATND_div,
        SPRATND_acdm_year,
        SPRATND_comment
      ) VALUES ?
    `;

    const [result] = await pool.query(query, [insertValues]);
    return { count: result.affectedRows };
  }

  /**
   * Get class attendance summary for a given date
   */
  static async getClassAttendance(standard, division, academicYear, date) {
    const query = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS name,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS mobile,
        (SELECT stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS whatsapp,
        IFNULL(att.SPRATND_status, 'Unmarked') AS status,
        att.SPRATND_comment AS comment
      FROM spriden s
      JOIN stvclas c 
        ON c.STVCLAS_student_id = s.id 
       AND c.STVCLAS_acdm = ?
      LEFT JOIN spratnd att 
        ON att.SPRATND_student_id = s.id 
       AND att.SPRATND_date = ? 
       AND att.SPRATND_acdm_year = c.STVCLAS_acdm
      WHERE c.STVCLAS_standard = ?
        AND c.STVCLAS_class = ?
        AND s.spriden_student_status = 'A'
      ORDER BY s.SPRIDEN_student_name ASC
    `;

    const [rows] = await pool.query(query, [academicYear, date, standard, division]);

    const presentCount = rows.filter((r) => r.status === 'P').length;
    const absentCount = rows.filter((r) => r.status === 'A').length;
    const unmarkedCount = rows.filter((r) => r.status === 'Unmarked').length;

    return {
      total: rows.length,
      present: presentCount,
      absent: absentCount,
      unmarked: unmarkedCount,
      percentage: rows.length > 0 ? Math.round((presentCount / rows.length) * 100) : 0,
      students: rows
    };
  }

  /**
   * Daily overview across all classes for a given date
   */
  static async getAllClassesOverview(academicYear, date) {
    const query = `
      SELECT 
        c.STVCLAS_standard,
        c.STVCLAS_class,
        COUNT(s.id) AS total_students,
        COUNT(CASE WHEN att.SPRATND_status = 'P' THEN 1 END) AS present_count,
        COUNT(CASE WHEN att.SPRATND_status = 'A' THEN 1 END) AS absent_count,
        CASE 
          WHEN COUNT(att.SPRATND_id) = 0 THEN 'Not Marked'
          ELSE 'Marked'
        END AS attendance_status
      FROM stvclas c
      JOIN spriden s 
        ON s.id = c.STVCLAS_student_id 
       AND s.spriden_student_status = 'A'
      LEFT JOIN spratnd att 
        ON att.SPRATND_student_id = s.id 
       AND att.SPRATND_date = ?
       AND att.SPRATND_acdm_year = c.STVCLAS_acdm
      WHERE c.STVCLAS_acdm = ?
        AND c.STVCLAS_standard NOT LIKE '%PHP%'
        AND c.STVCLAS_standard NOT LIKE '%<%'
      GROUP BY c.STVCLAS_standard, c.STVCLAS_class
      ORDER BY c.STVCLAS_standard ASC, c.STVCLAS_class ASC
    `;

    const [rows] = await pool.query(query, [date, academicYear]);

    let schoolTotal = 0;
    let schoolPresent = 0;
    let schoolAbsent = 0;

    rows.forEach((r) => {
      schoolTotal += Number(r.total_students) || 0;
      schoolPresent += Number(r.present_count) || 0;
      schoolAbsent += Number(r.absent_count) || 0;
      r.percentage =
        r.total_students > 0
          ? Math.round((Number(r.present_count) / Number(r.total_students)) * 100)
          : 0;
    });

    return {
      summary: {
        total_students: schoolTotal,
        present: schoolPresent,
        absent: schoolAbsent,
        percentage: schoolTotal > 0 ? Math.round((schoolPresent / schoolTotal) * 100) : 0
      },
      classes: rows
    };
  }

  /**
   * Absentee & Continuous Streaks Report
   */
  static async getAbsenteeReport(standard, division, academicYear) {
    const query = `
      SELECT 
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        (SELECT stvpcnf_mobile FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS mobile,
        (SELECT stvpcnf_whatsapp FROM stvpcnf WHERE stvpcnf_student_id = s.id LIMIT 1) AS whatsapp,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 1 THEN 1 END) AS jan,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 2 THEN 1 END) AS feb,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 3 THEN 1 END) AS mar,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 4 THEN 1 END) AS apr,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 5 THEN 1 END) AS may,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 6 THEN 1 END) AS jun,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 7 THEN 1 END) AS jul,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 8 THEN 1 END) AS aug,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 9 THEN 1 END) AS sep,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 10 THEN 1 END) AS oct,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 11 THEN 1 END) AS nov,
        COUNT(CASE WHEN MONTH(att.SPRATND_date) = 12 THEN 1 END) AS \`dec\`,
        COUNT(att.SPRATND_id) AS total_absent
      FROM spriden s
      JOIN stvclas c 
        ON c.STVCLAS_student_id = s.id
      LEFT JOIN spratnd att 
        ON att.SPRATND_student_id = s.id 
       AND att.SPRATND_acdm_year = c.STVCLAS_acdm 
       AND att.SPRATND_status = 'A'
      WHERE c.STVCLAS_acdm = ?
        AND (c.STVCLAS_standard = ? OR ? = 'all')
        AND (c.STVCLAS_class = ? OR ? = 'all')
        AND s.spriden_student_status = 'A'
      GROUP BY s.id, s.SPRIDEN_student_id, student_name, c.STVCLAS_standard, c.STVCLAS_class
      ORDER BY student_name ASC
    `;

    const [students] = await pool.query(query, [academicYear, standard, standard, division, division]);

    if (students.length === 0) return [];

    // Fetch individual absent dates only for students who have absences to calculate continuous absence streaks
    const absentStudentIds = students.filter((s) => Number(s.total_absent) > 0).map((s) => s.id);
    let absentDatesRows = [];
    if (absentStudentIds.length > 0) {
      const [rows] = await pool.query(
        `SELECT SPRATND_student_id, SPRATND_date
         FROM spratnd
         WHERE SPRATND_acdm_year = ?
           AND SPRATND_status = 'A'
           AND SPRATND_student_id IN (?)
         ORDER BY SPRATND_student_id ASC, SPRATND_date ASC`,
        [academicYear, absentStudentIds]
      );
      absentDatesRows = rows;
    }

    const absentByStudent = {};
    absentDatesRows.forEach((r) => {
      if (!absentByStudent[r.SPRATND_student_id]) {
        absentByStudent[r.SPRATND_student_id] = [];
      }
      absentByStudent[r.SPRATND_student_id].push(r.SPRATND_date);
    });

    students.forEach((s) => {
      const dates = absentByStudent[s.id] || [];
      s.continuous_absent = AttendanceModel.calculateStreaks(dates);
    });

    return students;
  }

  /**
   * Helper: Calculate consecutive date streaks (2 or more days in a row)
   * Matches CI3 calculate_streaks exact output shape
   */
  static calculateStreaks(dates) {
    if (!dates || dates.length === 0) return [];

    const sorted = [...dates].sort((a, b) => new Date(a) - new Date(b));
    const streaks = [];
    let currentStreak = [];

    for (let i = 0; i < sorted.length; i++) {
      const d = sorted[i];
      if (currentStreak.length === 0) {
        currentStreak.push(d);
      } else {
        const prev = currentStreak[currentStreak.length - 1];
        const prevTime = new Date(prev).getTime();
        const currTime = new Date(d).getTime();
        const diffDays = Math.round((currTime - prevTime) / (1000 * 60 * 60 * 24));

        if (diffDays === 1) {
          currentStreak.push(d);
        } else if (diffDays > 1) {
          if (currentStreak.length >= 2) {
            streaks.push({
              days: currentStreak.length,
              dates: [...currentStreak],
              start: currentStreak[0],
              end: currentStreak[currentStreak.length - 1]
            });
          }
          currentStreak = [d];
        }
      }
    }

    if (currentStreak.length >= 2) {
      streaks.push({
        days: currentStreak.length,
        dates: [...currentStreak],
        start: currentStreak[0],
        end: currentStreak[currentStreak.length - 1]
      });
    }

    return streaks;
  }

  /**
   * Get all absent dates and parent contact info for a specific student & academic year
   * Matches CI3 Absent_student_model::getDailyAbsentDates
   */
  static async getDailyAbsentDates(studentId, academicYear) {
    const query = `
      SELECT 
        DATE_FORMAT(att.SPRATND_date, '%Y-%m-%d') AS date,
        att.SPRATND_date,
        (SELECT IFNULL(stvpcnf_whatsapp, stvpcnf_mobile) FROM stvpcnf WHERE stvpcnf_student_id = att.SPRATND_student_id LIMIT 1) AS phone,
        s.id,
        s.SPRIDEN_student_id,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name
      FROM spratnd att
      JOIN spriden s ON CAST(s.id AS CHAR) = att.SPRATND_student_id
      WHERE att.SPRATND_student_id = ?
        AND att.SPRATND_acdm_year = ?
        AND att.SPRATND_status = 'A'
      ORDER BY att.SPRATND_date ASC
    `;

    const [rows] = await pool.query(query, [String(studentId), String(academicYear)]);
    return rows.map((r) => ({
      date: r.date || (r.SPRATND_date ? new Date(r.SPRATND_date).toISOString().split('T')[0] : ''),
      phone: r.phone || r.stvpcnf_whatsapp || r.stvpcnf_mobile || '',
      student_id: r.SPRIDEN_student_id,
      student_name: r.student_name
    }));
  }
}

export default AttendanceModel;
