/**
 * ClassAnalytics Model — Academic Performance & Class Analytics (Matches CI3 Class_DB & Result_Db)
 * Interacts with stamrks, exam_setup, stvexam, stvsubj, spriden, stvclas, spratnd, stvacdm
 */
import pool from '../config/db.js';

class ClassAnalyticsModel {
  /**
   * Get active academic year id
   */
  static async getActiveAcademicYear() {
    const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
    return rows[0]?.stvschool_acdm_year || 3;
  }

  /**
   * Get options for dropdown filters
   */
  static async getFilterOptions() {
    const activeYear = await this.getActiveAcademicYear();

    const [years] = await pool.query(`
      SELECT stvacdm_id AS id, stvacdm_year AS year, status AS status
      FROM stvacdm 
      ORDER BY stvacdm_id DESC
    `);

    const [exams] = await pool.query(`
      SELECT stvexam_id AS id, stvexam_name AS name 
      FROM stvexam 
      ORDER BY stvexam_id ASC
    `);

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

    const [subjects] = await pool.query(`
      SELECT stvsubj_id AS id, stvsubj_subject AS name, stvsubj_std AS standard
      FROM stvsubj 
      ORDER BY stvsubj_subject ASC
    `);

    return {
      active_year: activeYear,
      years,
      exams,
      standards: standards.map(s => s.standard),
      divisions: divisions.map(d => d.division),
      subjects
    };
  }

  /**
   * Get overall class analytics report
   */
  static async getClassAnalytics({ academic_year, exam_id, standard, division, subject_id }) {
    const year = academic_year || await this.getActiveAcademicYear();

    // 1. Total active students in class
    let countSql = `
      SELECT COUNT(s.id) AS total_students 
      FROM spriden s
      JOIN stvclas c ON s.id = c.STVCLAS_student_id
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.SPRIDEN_student_status = 'A'
        AND c.STVCLAS_standard = ?
        AND c.STVCLAS_acdm = ?
    `;
    const countParams = [standard, year];
    if (division) {
      countSql += ` AND c.STVCLAS_class = ?`;
      countParams.push(division);
    }
    const [[countRow]] = await pool.query(countSql, countParams);
    const totalStudents = countRow?.total_students || 0;

    // 2. Class attendance percentage
    let atndSql = `
      SELECT 
        COUNT(*) AS total_records,
        SUM(CASE WHEN a.SPRATND_status = 'P' THEN 1 ELSE 0 END) AS present_count
      FROM spratnd a
      WHERE a.SPRATND_std = ?
        AND a.SPRATND_acdm_year = ?
    `;
    const atndParams = [standard, year];
    if (division) {
      atndSql += ` AND a.SPRATND_div = ?`;
      atndParams.push(division);
    }
    const [[atndRow]] = await pool.query(atndSql, atndParams);
    const totalAtnd = atndRow?.total_records || 0;
    const presentCount = atndRow?.present_count || 0;
    const attendancePercentage = totalAtnd > 0 ? Number(((presentCount / totalAtnd) * 100).toFixed(1)) : 0;

    // 3. Exam student marks ledger
    let marksSql = `
      SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS student_code,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        m.marks AS obtained_marks,
        sub.stvsubj_id AS subject_id,
        sub.stvsubj_subject AS subject_name,
        IFNULL(es.Total_marks, 100) AS total_marks,
        IFNULL(es.passing_marks, 35) AS passing_marks
      FROM spriden s
      JOIN stvclas c ON s.id = c.STVCLAS_student_id
      LEFT JOIN stamrks m ON m.student_id = s.id AND m.academic_year = ? AND (? IS NULL OR m.exam_id = ?)
      LEFT JOIN stvsubj sub ON sub.stvsubj_id = m.subject_id
      LEFT JOIN exam_setup es ON es.exam COLLATE utf8_general_ci = CAST(m.exam_id AS CHAR) 
                              AND es.subject COLLATE utf8_general_ci = CAST(m.subject_id AS CHAR) 
                              AND es.class COLLATE utf8_general_ci = c.STVCLAS_standard
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.SPRIDEN_student_status = 'A'
        AND c.STVCLAS_standard = ?
        AND c.STVCLAS_acdm = ?
    `;
    const marksParams = [year, exam_id || null, exam_id || null, standard, year];
    if (division) {
      marksSql += ` AND c.STVCLAS_class = ?`;
      marksParams.push(division);
    }
    if (subject_id) {
      marksSql += ` AND m.subject_id = ?`;
      marksParams.push(subject_id);
    }
    marksSql += ` ORDER BY s.SPRIDEN_student_name ASC`;

    const [marksRows] = await pool.query(marksSql, marksParams);

    // Group marks by student
    const studentMap = {};
    for (const r of marksRows) {
      if (!studentMap[r.student_id]) {
        studentMap[r.student_id] = {
          student_id: r.student_id,
          student_code: r.student_code,
          student_name: r.student_name,
          standard: r.standard,
          division: r.division,
          total_obtained: 0,
          total_max: 0,
          subjects: []
        };
      }
      if (r.subject_id) {
        const ob = parseFloat(r.obtained_marks) || 0;
        const max = parseFloat(r.total_marks) || 100;
        studentMap[r.student_id].total_obtained += ob;
        studentMap[r.student_id].total_max += max;
        studentMap[r.student_id].subjects.push({
          subject_id: r.subject_id,
          subject_name: r.subject_name,
          obtained_marks: ob,
          total_marks: max,
          passing_marks: r.passing_marks,
          passed: ob >= (parseFloat(r.passing_marks) || 35)
        });
      }
    }

    const studentsReport = Object.values(studentMap).map(st => {
      const pct = st.total_max > 0 ? Number(((st.total_obtained / st.total_max) * 100).toFixed(2)) : 0;
      let grade = 'F';
      if (pct >= 90) grade = 'A+';
      else if (pct >= 80) grade = 'A';
      else if (pct >= 70) grade = 'B+';
      else if (pct >= 60) grade = 'B';
      else if (pct >= 50) grade = 'C';
      else if (pct >= 35) grade = 'D';

      return {
        ...st,
        percentage: pct,
        grade
      };
    });

    // Sort students by percentage descending to get rankers
    const sortedByScore = [...studentsReport].filter(s => s.total_max > 0).sort((a, b) => b.percentage - a.percentage);
    const topRankers = sortedByScore.slice(0, 3);

    // 4. Subject averages & weak subjects calculation
    const subjectStatsMap = {};
    for (const r of marksRows) {
      if (r.subject_id) {
        if (!subjectStatsMap[r.subject_id]) {
          subjectStatsMap[r.subject_id] = {
            subject_id: r.subject_id,
            subject_name: r.subject_name,
            total_marks_obtained: 0,
            max_marks: parseFloat(r.total_marks) || 100,
            passing_marks: parseFloat(r.passing_marks) || 35,
            count: 0,
            below_45_count: 0
          };
        }
        const ob = parseFloat(r.obtained_marks) || 0;
        subjectStatsMap[r.subject_id].total_marks_obtained += ob;
        subjectStatsMap[r.subject_id].count++;
        if (ob < 45) {
          subjectStatsMap[r.subject_id].below_45_count++;
        }
      }
    }

    const subjectAverages = Object.values(subjectStatsMap).map(sub => {
      const avg = sub.count > 0 ? Number((sub.total_marks_obtained / sub.count).toFixed(2)) : 0;
      const pct = sub.max_marks > 0 ? Number(((avg / sub.max_marks) * 100).toFixed(1)) : 0;
      return {
        ...sub,
        average_obtained: avg,
        average_percentage: pct,
        is_weak: pct < 45
      };
    });

    const weakSubjects = subjectAverages.filter(s => s.is_weak);

    return {
      overview: {
        total_students: totalStudents,
        attendance_percentage: attendancePercentage,
        top_student: topRankers[0] || null,
        top_rankers: topRankers,
        weak_subjects_count: weakSubjects.length
      },
      subject_averages: subjectAverages,
      weak_subjects: weakSubjects,
      students_report: studentsReport
    };
  }

  /**
   * Get single student deep-dive performance analysis
   */
  static async getStudentDeepDive({ student_id, academic_year, exam_id }) {
    const year = academic_year || await this.getActiveAcademicYear();

    // 1. Student Identity
    const [stRows] = await pool.query(`
      SELECT 
        s.id,
        s.SPRIDEN_student_id AS student_code,
        IFNULL(c.STVCLAS_rollno, '') AS roll_no,
        s.spriden_gr_number AS gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division
      FROM spriden s
      LEFT JOIN stvclas c ON s.id = c.STVCLAS_student_id AND c.STVCLAS_acdm = ?
      WHERE s.id = ?
      LIMIT 1
    `, [year, student_id]);

    if (!stRows.length) return null;
    const student = stRows[0];

    // 2. Marks & Remarks
    const [marks] = await pool.query(`
      SELECT 
        m.id,
        m.marks,
        m.subject_id,
        sub.stvsubj_subject AS subject_name,
        IFNULL(es.Total_marks, 100) AS total_marks,
        IFNULL(es.passing_marks, 35) AS passing_marks,
        (SELECT CONCAT(IFNULL(tch.SPRIDEN_surname, ''), ' ', IFNULL(tch.SPRIDEN_student_name, ''))
         FROM spriden tch 
         JOIN stvstcr cr ON cr.stvstcr_teacher_id = tch.SPRIDEN_student_id
         WHERE cr.stvstcr_subject = m.subject_id LIMIT 1) AS teacher_name
      FROM stamrks m
      LEFT JOIN stvsubj sub ON sub.stvsubj_id = m.subject_id
      LEFT JOIN exam_setup es ON es.exam COLLATE utf8_general_ci = CAST(m.exam_id AS CHAR) 
                              AND es.subject COLLATE utf8_general_ci = CAST(m.subject_id AS CHAR) 
                              AND es.class COLLATE utf8_general_ci = ?
      WHERE m.student_id = ?
        AND m.academic_year = ?
        AND (? IS NULL OR m.exam_id = ?)
    `, [student.standard, student_id, year, exam_id || null, exam_id || null]);

    // 3. Attendance Stats
    const [atnd] = await pool.query(`
      SELECT 
        COUNT(*) AS total_days,
        SUM(CASE WHEN SPRATND_status = 'P' THEN 1 ELSE 0 END) AS present_days,
        SUM(CASE WHEN SPRATND_status = 'A' THEN 1 ELSE 0 END) AS absent_days
      FROM spratnd
      WHERE SPRATND_student_id = ?
        AND SPRATND_acdm_year = ?
    `, [student_id, year]);

    const totalDays = atnd[0]?.total_days || 0;
    const presentDays = atnd[0]?.present_days || 0;
    const absentDays = atnd[0]?.absent_days || 0;
    const atndPct = totalDays > 0 ? Number(((presentDays / totalDays) * 100).toFixed(1)) : 0;

    let totalObtained = 0;
    let totalMax = 0;
    const subjectsDetail = marks.map(m => {
      const ob = parseFloat(m.marks) || 0;
      const max = parseFloat(m.total_marks) || 100;
      totalObtained += ob;
      totalMax += max;
      return {
        ...m,
        obtained_marks: ob,
        percentage: max > 0 ? Number(((ob / max) * 100).toFixed(1)) : 0
      };
    });

    const overallPct = totalMax > 0 ? Number(((totalObtained / totalMax) * 100).toFixed(2)) : 0;

    return {
      student,
      attendance: {
        total_days: totalDays,
        present_days: presentDays,
        absent_days: absentDays,
        percentage: atndPct
      },
      academics: {
        total_obtained: totalObtained,
        total_max: totalMax,
        percentage: overallPct,
        subjects: subjectsDetail
      }
    };
  }
}

export default ClassAnalyticsModel;
