import pool from '../config/db.js';

class LessonApprovalModel {
  /**
   * Allowed approval status columns
   */
  static ALLOWED_COLUMNS = [
    'lesson_status',
    'quiz_status',
    'question_status',
    'worksheet_status',
    'pre_request_status',
    'learning_outcome_status',
  ];

  /**
   * Get subjects for class and academic year (with CI3 fallback across years if current year not seeded)
   */
  static async getSubjectsByClassYear(academicYear, standard) {
    let query = `
      SELECT stvsubj_id, stvsubj_subject, stvsubj_std, stvsubj_acdm
      FROM stvsubj
      WHERE (stvsubj_std = ? OR ? IS NULL OR ? = 'all')
    `;
    const params = [standard, standard, standard];
    if (academicYear && academicYear !== 'all') {
      const [yearRows] = await pool.query(
        query + ` AND stvsubj_acdm = ? ORDER BY stvsubj_subject ASC`,
        [...params, academicYear]
      );
      if (yearRows.length > 0) return yearRows;
    }
    // Fallback: return subjects for standard across available years, deduplicated by name
    const [fallbackRows] = await pool.query(query + ` ORDER BY stvsubj_subject ASC`, params);
    const unique = [];
    const seen = new Set();
    for (const r of fallbackRows) {
      const key = `${r.stvsubj_std || ''}_${r.stvsubj_subject || ''}`.toLowerCase();
      if (!seen.has(key)) {
        seen.add(key);
        unique.push(r);
      }
    }
    return unique;
  }

  /**
   * Get distinct lesson names for class and subject
   */
  static async getLessonNames(standard, subjectId) {
    let query = `
      SELECT DISTINCT lesson, chapter
      FROM lesson
      WHERE 1=1
    `;
    const params = [];
    if (standard && standard !== 'all') {
      query += ` AND standard = ?`;
      params.push(standard);
    }
    if (subjectId && subjectId !== 'all') {
      query += ` AND subject_id = ?`;
      params.push(subjectId);
    }
    query += ` ORDER BY chapter ASC, lesson ASC`;
    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Get filtered lesson submissions with all approval columns
   */
  static async getFilteredLessons({ standard, division, subjectId, lesson, status } = {}) {
    let query = `
      SELECT 
        l.lesson_id,
        l.standard,
        l.division,
        l.subject_id,
        s.stvsubj_subject AS subject_name,
        l.chapter,
        l.lesson,
        l.lesson_pdf,
        l.lesson_status,
        l.quiz_pdf,
        l.quiz_status,
        l.question_pdf,
        l.question_status,
        l.worksheet_pdf,
        l.worksheet_status,
        l.pre_request,
        l.pre_request_status,
        l.learning_outcome,
        l.learning_outcome_status,
        l.created_at,
        l.updated_at
      FROM lesson l
      LEFT JOIN stvsubj s ON s.stvsubj_id = l.subject_id
      WHERE 1=1
    `;
    const params = [];

    if (standard && standard !== 'all') {
      query += ` AND l.standard = ?`;
      params.push(standard);
    }
    if (division && division !== 'all') {
      query += ` AND l.division = ?`;
      params.push(division);
    }
    if (subjectId && subjectId !== 'all') {
      query += ` AND l.subject_id = ?`;
      params.push(subjectId);
    }
    if (lesson) {
      query += ` AND l.lesson LIKE ?`;
      params.push(`%${lesson.trim()}%`);
    }
    if (status && status !== 'all') {
      query += ` AND (
        l.lesson_status = ? 
        OR l.quiz_status = ? 
        OR l.question_status = ? 
        OR l.worksheet_status = ? 
        OR l.pre_request_status = ? 
        OR l.learning_outcome_status = ?
      )`;
      params.push(status, status, status, status, status, status);
    }

    query += ` ORDER BY l.lesson_id DESC`;

    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Update status column for a specific lesson component
   */
  static async updateStatusColumn(lessonId, columnName, columnValue) {
    if (!this.ALLOWED_COLUMNS.includes(columnName)) {
      throw new Error(`Invalid column name: ${columnName}`);
    }

    const query = `
      UPDATE lesson
      SET \`${columnName}\` = ?, updated_at = NOW()
      WHERE lesson_id = ?
    `;
    const [result] = await pool.query(query, [columnValue, lessonId]);
    return result.affectedRows > 0;
  }
}

export default LessonApprovalModel;
