/**
 * Communication & Events Model — Database operations for Olive English School Messaging & Calendar
 * Matches CI3 tables: stvmsgt, stvevnt
 */
import pool from '../config/db.js';

class CommunicationModel {
  // ─── 1. Message Templates (stvmsgt) ───
  static async getTemplates() {
    const [rows] = await pool.query('SELECT * FROM stvmsgt ORDER BY stvmsgt_id DESC');
    return rows.map((r) => ({
      id: r.stvmsgt_id,
      text: r.stvmsgt_text,
      created_by: r.stvmsgt_created_by,
      created_at: r.stvmsgt_created_at,
      updated_at: r.stvmsgt_updated_at,
    }));
  }

  static async createTemplate({ text, user = 'admin' }) {
    const now = new Date();
    const [res] = await pool.query(
      `INSERT INTO stvmsgt (stvmsgt_text, stvmsgt_created_by, stvmsgt_created_at) 
       VALUES (?, ?, ?)`,
      [text, user, now]
    );
    return { id: res.insertId, text };
  }

  static async updateTemplate(id, { text, user = 'admin' }) {
    const now = new Date();
    await pool.query(
      `UPDATE stvmsgt 
       SET stvmsgt_text = ?, stvmsgt_updated_by = ?, stvmsgt_updated_at = ?
       WHERE stvmsgt_id = ?`,
      [text, user, now, id]
    );
    return { id, updated: true };
  }

  static async deleteTemplate(id) {
    const [res] = await pool.query('DELETE FROM stvmsgt WHERE stvmsgt_id = ?', [id]);
    return res.affectedRows > 0;
  }

  // ─── 2. School Events & Calendar (stvevnt) ───
  static async getEvents({ academicYear, standard } = {}) {
    let sql = 'SELECT * FROM stvevnt WHERE 1=1';
    const params = [];
    if (academicYear) {
      sql += ' AND (stvevnt_academic_year = ? OR stvevnt_academic_year IS NULL)';
      params.push(academicYear);
    }
    if (standard) {
      sql += ' AND (stvevnt_std = ? OR stvevnt_std = "ALL" OR stvevnt_std IS NULL)';
      params.push(standard);
    }
    sql += ' ORDER BY stvevnt_start_date DESC';
    const [rows] = await pool.query(sql, params);
    return rows.map((r) => ({
      id: r.stvevnt_id,
      title: r.stvevnt_title,
      details: r.stvevnt_details,
      start_date: r.stvevnt_start_date,
      end_date: r.stvevnt_end_date,
      standard: r.stvevnt_std || 'All Classes',
      division: r.stvevnt_div || 'All',
      academic_year: r.stvevnt_academic_year,
    }));
  }

  static async createEvent({ title, details, startDate, endDate, standard, division, academicYear }) {
    const [res] = await pool.query(
      `INSERT INTO stvevnt (stvevnt_title, stvevnt_details, stvevnt_start_date, stvevnt_end_date, stvevnt_std, stvevnt_div, stvevnt_academic_year)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [
        title,
        details || '',
        startDate,
        endDate || startDate,
        standard || 'ALL',
        division || 'ALL',
        academicYear || null,
      ]
    );
    return { id: res.insertId, title };
  }

  static async updateEvent(id, { title, details, startDate, endDate, standard, division, academicYear }) {
    await pool.query(
      `UPDATE stvevnt 
       SET stvevnt_title = ?, stvevnt_details = ?, stvevnt_start_date = ?, stvevnt_end_date = ?, stvevnt_std = ?, stvevnt_div = ?, stvevnt_academic_year = ?
       WHERE stvevnt_id = ?`,
      [
        title,
        details || '',
        startDate,
        endDate || startDate,
        standard || 'ALL',
        division || 'ALL',
        academicYear || null,
        id,
      ]
    );
    return { id, updated: true };
  }

  static async deleteEvent(id) {
    const [res] = await pool.query('DELETE FROM stvevnt WHERE stvevnt_id = ?', [id]);
    return res.affectedRows > 0;
  }
}

export default CommunicationModel;
