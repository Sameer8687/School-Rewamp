import pool from '../config/db.js';

class BooksResultModel {
  /**
   * Get all books and results records
   */
  static async getAllBooksResult(params = {}) {
    let query = `
      SELECT 
        b.STVBOOK_id AS id,
        b.STVBOOK_student_id,
        b.STVBOOK_standard,
        b.STVBOOK_result,
        b.STVBOOK_books,
        b.STVBOOK_teacher_name,
        b.STVBOOK_paid_amonut,
        b.STVBOOK_bal_amount,
        b.STVBOOK_paid_date,
        b.STVBOOK_due_date,
        b.STVBOOK_details,
        b.STVBOOK_area,
        b.STVBOOK_updated_date,
        s.id AS student_pk,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        s.spriden_gr_number,
        c.STVCLAS_class,
        p.stvpcnf_mobile
      FROM stvbook b
      LEFT JOIN spriden s ON (s.SPRIDEN_student_id = b.STVBOOK_student_id OR CAST(s.id AS CHAR) = b.STVBOOK_student_id)
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = (SELECT max(stvclas_acdm) FROM stvclas WHERE STVCLAS_student_id = s.id)
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      WHERE 1=1
    `;
    const queryParams = [];

    if (params.academic_year && params.academic_year !== 'all') {
      query += ` AND (c.STVCLAS_acdm = ? OR EXISTS (SELECT 1 FROM stvclas c2 WHERE c2.STVCLAS_student_id = s.id AND c2.STVCLAS_acdm = ?))`;
      queryParams.push(params.academic_year, params.academic_year);
    }
    if (params.standard && params.standard !== 'all') {
      query += ` AND b.STVBOOK_standard = ?`;
      queryParams.push(params.standard);
    }
    if (params.search) {
      query += ` AND (
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, '')) LIKE ?
        OR b.STVBOOK_student_id LIKE ?
      )`;
      const q = `%${params.search.trim()}%`;
      queryParams.push(q, q);
    }

    query += ` ORDER BY b.STVBOOK_id DESC`;

    const [rows] = await pool.query(query, queryParams);
    return rows;
  }

  /**
   * Add or update books & result distribution record (CI3: Student/add_books_and_result_data)
   */
  static async createBooksResult(data) {
    const {
      student_id,
      standard,
      result = 'Yes',
      books = 'Yes',
      teacher_name = '',
      paid_amount = 0,
      bal_amount = 0,
      paid_date = new Date().toISOString().slice(0, 10),
      due_date = null,
      details = '',
      area = '',
      user_id = 1,
    } = data;

    // Check if student already has a record in stvbook (matching CI3 check_books_data_available)
    const [existing] = await pool.query(
      'SELECT STVBOOK_id FROM stvbook WHERE STVBOOK_student_id = ?',
      [student_id]
    );

    if (existing.length > 0) {
      await pool.query(
        `UPDATE stvbook SET 
          STVBOOK_standard = ?,
          STVBOOK_result = ?,
          STVBOOK_books = ?,
          STVBOOK_teacher_name = ?,
          STVBOOK_paid_amonut = ?,
          STVBOOK_bal_amount = ?,
          STVBOOK_due_date = ?,
          STVBOOK_details = ?,
          STVBOOK_area = ?,
          STVBOOK_updated_date = ?,
          STVBOOK_update_by = ?
         WHERE STVBOOK_id = ?`,
        [
          standard,
          result,
          books,
          teacher_name,
          paid_amount,
          bal_amount,
          due_date || null,
          details,
          area,
          paid_date,
          user_id,
          existing[0].STVBOOK_id,
        ]
      );
      return existing[0].STVBOOK_id;
    } else {
      const [res] = await pool.query(
        `INSERT INTO stvbook (
          STVBOOK_student_id, STVBOOK_standard, STVBOOK_result, STVBOOK_books,
          STVBOOK_teacher_name, STVBOOK_paid_amonut, STVBOOK_bal_amount,
          STVBOOK_paid_date, STVBOOK_due_date, STVBOOK_details, STVBOOK_area, STVBOOK_user_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
        [
          student_id,
          standard,
          result,
          books,
          teacher_name,
          paid_amount,
          bal_amount,
          paid_date,
          due_date || null,
          details,
          area,
          user_id,
        ]
      );
      return res.insertId;
    }
  }

  /**
   * Delete record
   */
  static async deleteBooksResult(id) {
    const [res] = await pool.query('DELETE FROM stvbook WHERE STVBOOK_id = ?', [id]);
    return res.affectedRows > 0;
  }
}

export default BooksResultModel;
