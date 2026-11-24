import pool from '../config/db.js';

class MeetingModel {
  /**
   * Get all parents meetings
   */
  static async getAllMeetings(academicYear) {
    let query = `
      SELECT 
        m.prameet_id AS id,
        m.prameet_date AS meeting_date,
        m.prameet_time AS meeting_time,
        m.prameet_vanue AS venue,
        m.prameet_agenda AS agenda,
        m.prameet_class AS class_name,
        m.prameet_academic_year AS academic_year,
        a.stvacdm_year
      FROM parents_meet m
      LEFT JOIN stvacdm a ON a.stvacdm_id = m.prameet_academic_year
      WHERE 1=1
    `;
    const params = [];
    if (academicYear) {
      query += ` AND m.prameet_academic_year = ?`;
      params.push(academicYear);
    }
    query += ` ORDER BY m.prameet_date DESC, m.prameet_id DESC`;

    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Schedule new meeting
   */
  static async createMeeting(data) {
    const {
      prameet_date,
      prameet_time,
      prameet_vanue,
      prameet_agenda,
      prameet_class,
      prameet_academic_year,
    } = data;

    const [result] = await pool.query(
      `INSERT INTO parents_meet (
        prameet_date, prameet_time, prameet_vanue, 
        prameet_agenda, prameet_class, prameet_academic_year
      ) VALUES (?, ?, ?, ?, ?, ?)`,
      [
        prameet_date,
        prameet_time,
        prameet_vanue,
        prameet_agenda,
        prameet_class,
        prameet_academic_year,
      ]
    );
    return result.insertId;
  }

  /**
   * Delete meeting
   */
  static async deleteMeeting(id) {
    const [result] = await pool.query('DELETE FROM parents_meet WHERE prameet_id = ?', [id]);
    return result.affectedRows > 0;
  }
}

export default MeetingModel;
