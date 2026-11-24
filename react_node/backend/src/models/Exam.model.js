/**
 * Exam & Marks Model — Database operations for Olive English School Examination Module
 * Matches CI3 tables: stvexam, exam_setup, stamrks, stvsubj, stvhtkt_header, stvhtkt_line
 */
import pool from '../config/db.js';

class ExamModel {
  /**
   * Get all exams optionally filtered by academic year
   */
  static async getExams(year = null) {
    let sql = `
      SELECT 
        e.stvexam_id AS id,
        e.stvexam_name AS name,
        e.year AS academic_year,
        e.stvexam_added_by AS added_by
      FROM stvexam e
    `;
    const params = [];
    if (year) {
      sql += ' WHERE e.year = ? OR e.year IS NULL';
      params.push(year);
    }
    sql += ' ORDER BY e.stvexam_id DESC';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  /**
   * Create a new exam in stvexam
   */
  static async createExam({ name, year, addedBy = 'admin' }) {
    const [result] = await pool.query(
      'INSERT INTO stvexam (stvexam_name, year, stvexam_added_by) VALUES (?, ?, ?)',
      [name, year, addedBy]
    );
    return { id: result.insertId, name, year };
  }

  /**
   * Get subjects for a class/standard from stvsubj
   */
  static async getSubjectsByStandard(standard) {
    const [rows] = await pool.query(
      `SELECT 
        stvsubj_id AS id, 
        stvsubj_subject AS name, 
        stvsubj_std AS standard 
       FROM stvsubj 
       WHERE stvsubj_std = ? 
       ORDER BY stvsubj_subject ASC`,
      [standard]
    );
    return rows;
  }

  /**
   * Get exam setup (max marks, passing marks per subject)
   */
  static async getExamSetup(year, standard, examId = null) {
    let sql = `
      SELECT 
        es.id,
        es.exam AS exam_id,
        ex.stvexam_name AS exam_name,
        es.subject AS subject_id,
        sub.stvsubj_subject AS subject_name,
        es.class AS standard,
        es.Total_marks AS total_marks,
        es.passing_marks,
        es.year AS academic_year
      FROM exam_setup es
      LEFT JOIN stvexam ex ON es.exam = ex.stvexam_id
      LEFT JOIN stvsubj sub ON es.subject = sub.stvsubj_id
      WHERE (es.year = ? OR ? IS NULL)
        AND es.class = ?
    `;
    const params = [year, year, standard];
    if (examId) {
      sql += ' AND es.exam = ?';
      params.push(examId);
    }
    sql += ' ORDER BY sub.stvsubj_subject ASC';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  /**
   * Upsert exam setup (total marks & passing marks)
   */
  static async saveExamSetup({ examId, subjectId, standard, totalMarks, passingMarks, year }) {
    // Check if configuration exists
    const [existing] = await pool.query(
      `SELECT id FROM exam_setup 
       WHERE exam = ? AND subject = ? AND class = ? AND year = ? LIMIT 1`,
      [examId, subjectId, standard, year]
    );

    if (existing.length > 0) {
      await pool.query(
        `UPDATE exam_setup 
         SET Total_marks = ?, passing_marks = ? 
         WHERE id = ?`,
        [totalMarks, passingMarks, existing[0].id]
      );
      return { id: existing[0].id, updated: true };
    } else {
      const [res] = await pool.query(
        `INSERT INTO exam_setup (exam, subject, class, Total_marks, passing_marks, year) 
         VALUES (?, ?, ?, ?, ?, ?)`,
        [examId, subjectId, standard, totalMarks, passingMarks, year]
      );
      return { id: res.insertId, created: true };
    }
  }

  /**
   * Get active students of a class and division with their marks for a given exam & subject
   */
  static async getStudentMarks(year, standard, division, examId, subjectId) {
    const sql = `
      SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS enrollment_no,
        IFNULL(c.STVCLAS_rollno, '') AS roll_no,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        IFNULL(m.id, 0) AS mark_id,
        IFNULL(m.marks, '') AS marks,
        IFNULL(es.Total_marks, 100) AS max_marks,
        IFNULL(es.passing_marks, 35) AS passing_marks
      FROM spriden s
      INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
      LEFT JOIN stamrks m ON (
        m.student_id = s.id 
        AND m.academic_year = ? 
        AND m.class = ? 
        AND m.division = ? 
        AND m.exam_id = ? 
        AND m.subject_id = ?
      )
      LEFT JOIN exam_setup es ON (
        es.exam = ? 
        AND es.class = ? 
        AND es.subject = ? 
        AND (es.year = ? OR es.year IS NULL)
      )
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.spriden_student_status = 'A'
        AND c.STVCLAS_standard = ?
        AND c.STVCLAS_class = ?
        AND (c.STVCLAS_acdm = ? OR ? IS NULL)
      ORDER BY 
        CASE 
          WHEN c.STVCLAS_rollno IS NOT NULL AND c.STVCLAS_rollno != '' 
          THEN CAST(c.STVCLAS_rollno AS UNSIGNED) 
          ELSE 999999 
        END ASC,
        student_name ASC
    `;

    const params = [
      year, standard, division, examId, subjectId,
      examId, standard, subjectId, year,
      standard, division, year, year
    ];

    const [rows] = await pool.query(sql, params);
    return rows;
  }

  /**
   * Bulk save or update student marks
   */
  static async saveMarks({ year, standard, division, examId, subjectId, marksRecords, userId = 'admin' }) {
    let savedCount = 0;
    const now = new Date();

    for (const record of marksRecords) {
      const studentId = record.student_id;
      const marksVal = record.marks !== undefined && record.marks !== null && record.marks !== ''
        ? Number(record.marks)
        : null;

      if (marksVal === null) continue;

      // Check if entry already exists in stamrks
      const [existing] = await pool.query(
        `SELECT id FROM stamrks 
         WHERE student_id = ? AND academic_year = ? AND class = ? 
           AND division = ? AND exam_id = ? AND subject_id = ? LIMIT 1`,
        [studentId, year, standard, division, examId, subjectId]
      );

      if (existing.length > 0) {
        await pool.query(
          `UPDATE stamrks 
           SET marks = ?, update_at = ?, update_by = ? 
           WHERE id = ?`,
          [marksVal, now, userId, existing[0].id]
        );
      } else {
        await pool.query(
          `INSERT INTO stamrks 
           (student_id, academic_year, class, division, exam_id, subject_id, marks, created_at, created_by) 
           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
          [studentId, year, standard, division, examId, subjectId, marksVal, now, userId]
        );
      }
      savedCount++;
    }

    return { savedCount };
  }

  /**
   * Get Hall Tickets list with subject schedule lines
   */
  static async getHallTickets({ academicYear, standard, division, examId }) {
    let sql = `
      SELECT 
        h.stvhtkt_id AS id,
        h.stvhtkt_class AS standard,
        h.stvhtkt_div AS division,
        h.stvhtkt_exam AS exam_id,
        ex.stvexam_name AS exam_name,
        h.stvhtkt_acdm AS academic_year,
        h.shift,
        h.stvhtkt_created_at AS created_at,
        COUNT(l.stvhtkt_id) AS total_subjects
      FROM stvhtkt_header h
      LEFT JOIN stvexam ex ON h.stvhtkt_exam = ex.stvexam_id
      LEFT JOIN stvhtkt_line l ON h.stvhtkt_id = l.stvhtkt_form_id
      WHERE 1=1
    `;
    const params = [];
    if (academicYear) {
      sql += ' AND h.stvhtkt_acdm = ?';
      params.push(academicYear);
    }
    if (standard) {
      sql += ' AND h.stvhtkt_class = ?';
      params.push(standard);
    }
    if (division) {
      sql += ' AND h.stvhtkt_div = ?';
      params.push(division);
    }
    if (examId) {
      sql += ' AND h.stvhtkt_exam = ?';
      params.push(examId);
    }

    sql += ' GROUP BY h.stvhtkt_id ORDER BY h.stvhtkt_id DESC';
    const [rows] = await pool.query(sql, params);
    return rows;
  }

  /**
   * Get single Hall Ticket details with timetable lines
   */
  static async getHallTicketById(headerId) {
    const [headers] = await pool.query(
      `SELECT 
        h.stvhtkt_id AS id,
        h.stvhtkt_class AS standard,
        h.stvhtkt_div AS division,
        h.stvhtkt_exam AS exam_id,
        ex.stvexam_name AS exam_name,
        h.stvhtkt_acdm AS academic_year,
        h.shift
       FROM stvhtkt_header h
       LEFT JOIN stvexam ex ON h.stvhtkt_exam = ex.stvexam_id
       WHERE h.stvhtkt_id = ?`,
      [headerId]
    );

    if (headers.length === 0) return null;
    const header = headers[0];

    const [lines] = await pool.query(
      `SELECT 
        l.stvhtkt_id AS line_id,
        l.stvhtkt_subject AS subject,
        l.stvhtkt_date AS exam_date,
        l.stvhtkt_time_start AS time_start,
        l.stvhtkt_time_end AS time_end,
        l.stvhtkt_type AS exam_type
       FROM stvhtkt_line l
       WHERE l.stvhtkt_form_id = ?
       ORDER BY l.stvhtkt_date ASC, l.stvhtkt_time_start ASC`,
      [headerId]
    );

    return { ...header, lines };
  }

  /**
   * Save (create or update) Hall Ticket with timetable schedule
   */
  static async saveHallTicket({ headerId, standard, division, examId, academicYear, shift = 'Morning', lines = [], userId = 'admin' }) {
    let targetHeaderId = headerId;
    const now = new Date();

    if (targetHeaderId) {
      await pool.query(
        `UPDATE stvhtkt_header 
         SET stvhtkt_class = ?, stvhtkt_div = ?, stvhtkt_exam = ?, stvhtkt_acdm = ?, shift = ?, stvhtkt_updated_by = ?, stvhtkt_updated_at = ?
         WHERE stvhtkt_id = ?`,
        [standard, division, examId, academicYear, shift, userId, now, targetHeaderId]
      );
      // Remove old lines to replace with new schedule
      await pool.query('DELETE FROM stvhtkt_line WHERE stvhtkt_form_id = ?', [targetHeaderId]);
    } else {
      const [res] = await pool.query(
        `INSERT INTO stvhtkt_header 
         (stvhtkt_class, stvhtkt_div, stvhtkt_exam, stvhtkt_acdm, shift, stvhtkt_created_by, stvhtkt_created_at) 
         VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [standard, division, examId, academicYear, shift, userId, now]
      );
      targetHeaderId = res.insertId;
    }

    // Insert lines
    for (let i = 0; i < lines.length; i++) {
      const item = lines[i];
      if (!item.subject) continue;
      await pool.query(
        `INSERT INTO stvhtkt_line 
         (stvhtkt_form_id, stvhtkt_line_id, stvhtkt_subject, stvhtkt_date, stvhtkt_time_start, stvhtkt_time_end, stvhtkt_type) 
         VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [
          targetHeaderId,
          i + 1,
          item.subject,
          item.exam_date || null,
          item.time_start || '09:00 AM',
          item.time_end || '12:00 PM',
          item.exam_type || 'Theory'
        ]
      );
    }

    return { id: targetHeaderId };
  }

  /**
   * Delete hall ticket header and lines
   */
  static async deleteHallTicket(headerId) {
    await pool.query('DELETE FROM stvhtkt_line WHERE stvhtkt_form_id = ?', [headerId]);
    const [res] = await pool.query('DELETE FROM stvhtkt_header WHERE stvhtkt_id = ?', [headerId]);
    return res.affectedRows > 0;
  }

  /**
   * Get eligible students with hall tickets for printing
   * Supports filtering by fee payment clearance and RTE
   */
  static async getStudentsForHallTicket(standard, division, academicYear, feesClearanceOnly = false) {
    const sql = `
      SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS enrollment_no,
        IFNULL(c.STVCLAS_rollno, '') AS roll_no,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.SPRIDEN_mother_name AS mother_name,
        s.SPRIDEN_dob AS dob,
        s.SPRIDEN_gender AS gender,
        s.SPRIDEN_rte_student AS rte_status,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        -- Total fees check
        IFNULL((
          SELECT SUM(STVFPRM_fee_amount) 
          FROM stvfprm 
          WHERE STVFPRM_standard = c.STVCLAS_standard 
            AND STVFPRM_academic_year = c.STVCLAS_acdm
        ), 0) AS total_fee,
        IFNULL((
          SELECT SUM(amount) 
          FROM fee_collection 
          WHERE student_id = s.id 
            AND academic_year = c.STVCLAS_acdm
        ), 0) AS paid_fee
      FROM spriden s
      INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
      WHERE s.SPRIDEN_user_type = 'st'
        AND s.spriden_student_status = 'A'
        AND c.STVCLAS_standard = ?
        AND c.STVCLAS_class = ?
        AND (c.STVCLAS_acdm = ? OR ? IS NULL)
      ORDER BY 
        CASE 
          WHEN c.STVCLAS_rollno IS NOT NULL AND c.STVCLAS_rollno != '' 
          THEN CAST(c.STVCLAS_rollno AS UNSIGNED) 
          ELSE 999999 
        END ASC,
        student_name ASC
    `;

    const [rows] = await pool.query(sql, [standard, division, academicYear, academicYear]);

    if (!feesClearanceOnly) {
      return rows;
    }

    // Filter students whose dues are cleared or RTE
    return rows.filter((st) => {
      if (st.rte_status === 'Y' || st.rte_status === '1') return true;
      const due = Number(st.total_fee) - Number(st.paid_fee);
      return due <= 0;
    });
  }

  /**
   * Get Student Full Report Card / Result
   */
  static async getStudentReportCard(studentId, academicYear, examId = null) {
    // 1. Student detail
    const [students] = await pool.query(
      `SELECT 
        s.id,
        s.SPRIDEN_student_id AS enrollment_no,
        IFNULL(c.STVCLAS_rollno, '') AS roll_no,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.SPRIDEN_mother_name AS mother_name,
        s.SPRIDEN_birthdate AS dob,
        s.SPRIDEN_BGRP AS blood_group,
        s.SPRIDEN_gender AS gender,
        s.SPRIDEN_aadhar_no AS aadhar_no,
        c.STVCLAS_standard AS standard,
        c.STVCLAS_class AS division,
        ay.stvacdm_year AS academic_year_name
       FROM spriden s
       INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
       LEFT JOIN stvacdm ay ON c.STVCLAS_acdm = ay.stvacdm_id
       WHERE s.id = ? AND (c.STVCLAS_acdm = ? OR ? IS NULL)
       LIMIT 1`,
      [studentId, academicYear, academicYear]
    );

    if (students.length === 0) return null;
    const student = students[0];

    // 2. Marks per subject
    let marksSql = `
      SELECT 
        m.id AS mark_id,
        m.exam_id,
        ex.stvexam_name AS exam_name,
        m.subject_id,
        sub.stvsubj_subject AS subject_name,
        m.marks,
        IFNULL(es.Total_marks, 100) AS total_marks,
        IFNULL(es.passing_marks, 35) AS passing_marks,
        CASE 
          WHEN m.marks >= IFNULL(es.passing_marks, 35) THEN 'Pass'
          ELSE 'Fail'
        END AS status,
        CASE 
          WHEN (m.marks / IFNULL(es.Total_marks, 100)) >= 0.9 THEN 'A+'
          WHEN (m.marks / IFNULL(es.Total_marks, 100)) >= 0.8 THEN 'A'
          WHEN (m.marks / IFNULL(es.Total_marks, 100)) >= 0.7 THEN 'B+'
          WHEN (m.marks / IFNULL(es.Total_marks, 100)) >= 0.6 THEN 'B'
          WHEN (m.marks / IFNULL(es.Total_marks, 100)) >= 0.5 THEN 'C'
          WHEN (m.marks / IFNULL(es.Total_marks, 100)) >= 0.35 THEN 'D'
          ELSE 'E'
        END AS grade
      FROM stamrks m
      LEFT JOIN stvexam ex ON m.exam_id = ex.stvexam_id
      LEFT JOIN stvsubj sub ON m.subject_id = sub.stvsubj_id
      LEFT JOIN exam_setup es ON (
        es.exam = m.exam_id 
        AND es.subject = m.subject_id 
        AND BINARY es.class = BINARY m.class 
        AND (es.year = m.academic_year OR es.year IS NULL)
      )
      WHERE m.student_id = ?
        AND (m.academic_year = ? OR ? IS NULL)
    `;
    const marksParams = [studentId, academicYear, academicYear];
    if (examId) {
      marksSql += ' AND m.exam_id = ?';
      marksParams.push(examId);
    }
    marksSql += ' ORDER BY sub.stvsubj_subject ASC';

    const [marks] = await pool.query(marksSql, marksParams);

    // Compute aggregations
    let totalScored = 0;
    let totalMax = 0;
    let allPassed = marks.length > 0;

    marks.forEach((item) => {
      totalScored += Number(item.marks || 0);
      totalMax += Number(item.total_marks || 100);
      if (item.status === 'Fail') allPassed = false;
    });

    const percentage = totalMax > 0 ? Number(((totalScored / totalMax) * 100).toFixed(2)) : 0;
    let overallGrade = 'E';
    if (percentage >= 90) overallGrade = 'A+';
    else if (percentage >= 80) overallGrade = 'A';
    else if (percentage >= 70) overallGrade = 'B+';
    else if (percentage >= 60) overallGrade = 'B';
    else if (percentage >= 50) overallGrade = 'C';
    else if (percentage >= 35) overallGrade = 'D';

    return {
      student,
      marks,
      summary: {
        totalScored,
        totalMax,
        percentage,
        overallGrade,
        overallResult: allPassed && marks.length > 0 ? 'Passed' : (marks.length === 0 ? 'N/A' : 'Needs Improvement'),
      }
    };
  }

  /**
   * Get class-level result overview and rankings
   */
  static async getClassResults(academicYear, standard, division, examId) {
    const [students] = await pool.query(
      `SELECT 
        s.id AS student_id,
        s.SPRIDEN_student_id AS enrollment_no,
        IFNULL(c.STVCLAS_rollno, '') AS roll_no,
        CONCAT(s.SPRIDEN_surname, ' ', s.SPRIDEN_student_name, ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name
       FROM spriden s
       INNER JOIN stvclas c ON s.id = c.STVCLAS_student_id
       WHERE s.SPRIDEN_user_type = 'st'
         AND s.spriden_student_status = 'A'
         AND c.STVCLAS_standard = ?
         AND c.STVCLAS_class = ?
         AND (c.STVCLAS_acdm = ? OR ? IS NULL)
       ORDER BY student_name ASC`,
      [standard, division, academicYear, academicYear]
    );

    const results = [];
    for (const st of students) {
      const card = await this.getStudentReportCard(st.student_id, academicYear, examId);
      if (card && card.marks.length > 0) {
        results.push({
          student_id: st.student_id,
          enrollment_no: st.enrollment_no,
          roll_no: st.roll_no,
          student_name: st.student_name,
          subjects_count: card.marks.length,
          total_scored: card.summary.totalScored,
          total_max: card.summary.totalMax,
          percentage: card.summary.percentage,
          grade: card.summary.overallGrade,
          result: card.summary.overallResult
        });
      }
    }

    // Sort by percentage descending to assign ranks
    results.sort((a, b) => b.percentage - a.percentage);
    results.forEach((item, index) => {
      item.rank = index + 1;
    });

    return results;
  }
}

export default ExamModel;
