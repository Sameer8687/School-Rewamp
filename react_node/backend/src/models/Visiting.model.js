import pool from '../config/db.js';

class VisitingModel {
  /**
   * Get all visiting records with filters
   */
  static async getAllVisiting(params = {}) {
    let query = `
      SELECT 
        v.visiting_id AS id,
        v.visiting_student_id,
        v.visiting_student_class,
        v.visiting_student_fees_paid,
        v.visiting_date,
        v.visiting_fees_status_of,
        v.visiting_fees_due_date,
        v.visiting_book_fees,
        v.visiting_added_by,
        u.spruser_name AS staff_name,
        s.SPRIDEN_student_id,
        s.spriden_gr_number,
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, ''), ' ', IFNULL(s.SPRIDEN_father_name, '')) AS student_name,
        c.STVCLAS_standard,
        c.STVCLAS_class,
        p.stvpcnf_mobile,
        p.stvpcnf_whatsapp
      FROM visiting v
      JOIN spriden s ON s.id = v.visiting_student_id
      LEFT JOIN spruser u ON u.spruser_id = v.visiting_added_by
      LEFT JOIN stvclas c ON c.STVCLAS_student_id = s.id AND c.STVCLAS_acdm = (SELECT max(stvclas_acdm) FROM stvclas WHERE STVCLAS_student_id = s.id)
      LEFT JOIN stvpcnf p ON p.stvpcnf_student_id = s.id
      WHERE 1=1
    `;
    const queryParams = [];

    if (params.standard && params.standard !== 'all') {
      query += ` AND (c.STVCLAS_standard = ? OR v.visiting_student_class = ?)`;
      queryParams.push(params.standard, params.standard);
    }
    if (params.fromDate && params.toDate) {
      query += ` AND v.visiting_date BETWEEN ? AND ?`;
      queryParams.push(params.fromDate, params.toDate);
    } else if (params.date) {
      query += ` AND v.visiting_date = ?`;
      queryParams.push(params.date);
    }
    if (params.search) {
      query += ` AND (
        CONCAT(IFNULL(s.SPRIDEN_surname, ''), ' ', IFNULL(s.SPRIDEN_student_name, '')) LIKE ?
        OR s.SPRIDEN_student_id LIKE ?
        OR p.stvpcnf_mobile LIKE ?
      )`;
      const s = `%${params.search.trim()}%`;
      queryParams.push(s, s, s);
    }

    query += ` ORDER BY v.visiting_id DESC`;

    const [rows] = await pool.query(query, queryParams);
    return rows;
  }

  /**
   * Create visiting entry
   */
  static async createVisiting(data) {
    const {
      visiting_student_id,
      visiting_student_class = '',
      visiting_student_fees_paid = 0,
      visiting_date = new Date().toISOString().slice(0, 10),
      visiting_fees_status_of = '',
      visiting_fees_due_date = null,
      visiting_book_fees = 0,
      visiting_added_by = 1,
    } = data;

    const [result] = await pool.query(
      `INSERT INTO visiting (
        visiting_student_id, visiting_student_class, visiting_student_fees_paid,
        visiting_date, visiting_fees_status_of, visiting_fees_due_date,
        visiting_book_fees, visiting_added_by
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)`,
      [
        visiting_student_id,
        visiting_student_class,
        visiting_student_fees_paid,
        visiting_date,
        visiting_fees_status_of,
        visiting_fees_due_date,
        visiting_book_fees,
        visiting_added_by,
      ]
    );
    return result.insertId;
  }

  /**
   * Delete visiting entry
   */
  static async deleteVisiting(id) {
    const [result] = await pool.query('DELETE FROM visiting WHERE visiting_id = ?', [id]);
    return result.affectedRows > 0;
  }
}

export default VisitingModel;
