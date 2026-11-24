import pool from '../config/db.js';

class NotesModel {
  /**
   * Get all notes with class/subject filters
   */
  static async getAllNotes(params = {}) {
    let query = `
      SELECT 
        n.id,
        n.cur_acdm,
        n.std_filter,
        n.div_filter,
        n.subject,
        n.doc_title,
        n.doc_type,
        n.created_at,
        n.created_by,
        n.is_active,
        n.downloadable,
        n.attachment,
        a.stvacdm_year
      FROM notes n
      LEFT JOIN stvacdm a ON a.stvacdm_id = n.cur_acdm
      WHERE 1=1
    `;
    const queryParams = [];

    if (params.academicYear) {
      query += ` AND n.cur_acdm = ?`;
      queryParams.push(params.academicYear);
    }
    if (params.standard && params.standard !== 'all') {
      query += ` AND n.std_filter = ?`;
      queryParams.push(params.standard);
    }
    if (params.division && params.division !== 'all') {
      query += ` AND n.div_filter = ?`;
      queryParams.push(params.division);
    }
    if (params.subject && params.subject !== 'all') {
      query += ` AND n.subject = ?`;
      queryParams.push(params.subject);
    }
    if (params.search) {
      query += ` AND (n.doc_title LIKE ? OR n.subject LIKE ?)`;
      queryParams.push(`%${params.search.trim()}%`, `%${params.search.trim()}%`);
    }

    query += ` ORDER BY n.id DESC`;

    const [rows] = await pool.query(query, queryParams);
    return rows;
  }

  /**
   * Insert new note
   */
  static async createNote(data) {
    const {
      cur_acdm = 9,
      std_filter,
      div_filter = data.division || data.div || 'all',
      subject,
      doc_title,
      doc_type = 'pdf',
      downloadable = 1,
      attachment = '',
      created_by = 1,
    } = data;

    const [result] = await pool.query(
      `INSERT INTO notes (
        cur_acdm, std_filter, div_filter, subject, 
        doc_title, doc_type, downloadable, attachment, created_by, is_active
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)`,
      [
        cur_acdm,
        std_filter,
        div_filter,
        subject,
        doc_title,
        doc_type,
        downloadable ? 1 : 0,
        attachment,
        created_by,
      ]
    );
    return result.insertId;
  }

  /**
   * Delete note
   */
  static async deleteNote(id) {
    const [result] = await pool.query('DELETE FROM notes WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }
}

export default NotesModel;
