/**
 * Quiz Model — Online Quiz, Assessment & Quiz Result Review (Matches CI3 Result/quiz_result)
 * Interacts with quiz, result, result_line, questions, options, spriden, stvsubj
 */
import pool from '../config/db.js';

class QuizModel {
  /**
   * Get list of quizzes with submission metrics
   */
  static async getQuizzes(filters = {}) {
    let sql = `
      SELECT 
        q.quiz_id,
        q.quiz_name,
        q.class AS standard,
        q.division,
        q.status,
        DATE_FORMAT(q.created_at, '%Y-%m-%d') AS created_at,
        q.created_by,
        sub.stvsubj_subject AS subject_name,
        (SELECT COUNT(*) FROM questions WHERE quizNO = q.quiz_id) AS total_questions,
        (SELECT COUNT(*) FROM result WHERE quiz_id = q.quiz_id) AS total_submissions
      FROM quiz q
      LEFT JOIN stvsubj sub ON sub.stvsubj_id = q.subject
      WHERE 1=1
    `;
    const params = [];

    if (filters.standard && filters.standard !== 'all') {
      sql += ` AND q.class = ?`;
      params.push(filters.standard);
    }
    if (filters.academic_year) {
      sql += ` AND q.academic_year = ?`;
      params.push(filters.academic_year);
    }
    if (filters.search) {
      sql += ` AND q.quiz_name LIKE ?`;
      params.push(`%${filters.search.trim()}%`);
    }

    sql += ` ORDER BY q.quiz_id DESC`;

    const [rows] = await pool.query(sql, params);
    return rows;
  }

  /**
   * Get student submissions for a quiz or across all quizzes
   */
  static async getSubmissions(filters = {}) {
    let sql = `
      SELECT 
        r.result_id,
        r.quiz_id,
        r.student_id,
        DATE_FORMAT(r.exam_date, '%Y-%m-%d') AS exam_date,
        r.marks_scored,
        r.total_questions,
        ROUND((r.marks_scored / NULLIF(r.total_questions, 0)) * 100, 1) AS percentage,
        r.status,
        q.quiz_name,
        q.class AS standard,
        q.division,
        sub.stvsubj_subject AS subject_name,
        s.SPRIDEN_student_id AS student_code,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name
      FROM result r
      JOIN quiz q ON q.quiz_id = r.quiz_id
      JOIN spriden s ON s.id = r.student_id
      LEFT JOIN stvsubj sub ON sub.stvsubj_id = q.subject
      WHERE 1=1
    `;
    const params = [];

    if (filters.quiz_id) {
      sql += ` AND r.quiz_id = ?`;
      params.push(filters.quiz_id);
    }
    if (filters.student_id) {
      sql += ` AND r.student_id = ?`;
      params.push(filters.student_id);
    }
    if (filters.standard && filters.standard !== 'all') {
      sql += ` AND q.class = ?`;
      params.push(filters.standard);
    }
    if (filters.search) {
      sql += ` AND (
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, '')) LIKE ?
        OR s.SPRIDEN_student_id LIKE ?
        OR q.quiz_name LIKE ?
      )`;
      const term = `%${filters.search.trim()}%`;
      params.push(term, term, term);
    }

    sql += ` ORDER BY r.result_id DESC LIMIT 100`;

    const [rows] = await pool.query(sql, params);
    return rows;
  }

  /**
   * Get detailed question-by-question review of a student's quiz attempt
   */
  static async getQuizReview(quizId, studentId) {
    // 1. Header info
    const [headerRows] = await pool.query(`
      SELECT 
        r.result_id,
        r.quiz_id,
        r.student_id,
        DATE_FORMAT(r.exam_date, '%Y-%m-%d') AS exam_date,
        r.marks_scored,
        r.total_questions,
        ROUND((r.marks_scored / NULLIF(r.total_questions, 0)) * 100, 1) AS percentage,
        q.quiz_name,
        q.class AS standard,
        q.division,
        sub.stvsubj_subject AS subject_name,
        s.SPRIDEN_student_id AS student_code,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name
      FROM result r
      JOIN quiz q ON q.quiz_id = r.quiz_id
      JOIN spriden s ON s.id = r.student_id
      LEFT JOIN stvsubj sub ON sub.stvsubj_id = q.subject
      WHERE r.quiz_id = ? AND r.student_id = ?
      LIMIT 1
    `, [quizId, studentId]);

    const header = headerRows[0] || null;

    // 2. Questions for this quiz
    const [questions] = await pool.query(`
      SELECT 
        questions_id,
        question_no,
        quizNO,
        question_text
      FROM questions
      WHERE quizNO = ?
      ORDER BY question_no ASC
    `, [quizId]);

    // 3. Options for all questions in this quiz
    const [options] = await pool.query(`
      SELECT 
        option_id,
        question_no,
        quiznumber,
        is_correct,
        optionText
      FROM options
      WHERE quiznumber = ?
      ORDER BY option_id ASC
    `, [quizId]);

    // 4. Selected answers by this student from result_line
    const [selectedRows] = await pool.query(`
      SELECT 
        question_id,
        option_selected
      FROM result_line
      WHERE quiz_id = ? AND student_id = ?
    `, [quizId, studentId]);

    const selectedMap = {};
    for (const s of selectedRows) {
      selectedMap[s.question_id] = String(s.option_selected);
    }

    // Map options to questions
    const questionReview = questions.map(q => {
      // Find options matching either question_no or question_id
      const qOptions = options.filter(o => o.question_no == q.question_no || o.question_no == q.questions_id);
      const selectedOptionId = selectedMap[q.questions_id] || selectedMap[q.question_no] || null;
      const correctOption = qOptions.find(o => o.is_correct == 1 || o.is_correct == '1');
      const isCorrect = selectedOptionId && correctOption && (selectedOptionId == correctOption.option_id);

      return {
        question_id: q.questions_id,
        question_no: q.question_no,
        question_text: q.question_text,
        options: qOptions.map(o => ({
          option_id: o.option_id,
          text: o.optionText,
          is_correct: o.is_correct == 1 || o.is_correct == '1',
          is_selected: o.option_id == selectedOptionId
        })),
        selected_option_id: selectedOptionId,
        correct_option_id: correctOption?.option_id || null,
        is_correct: isCorrect
      };
    });

    return {
      header,
      questions: questionReview
    };
  }
}

export default QuizModel;

