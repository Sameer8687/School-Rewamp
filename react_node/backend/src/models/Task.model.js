import pool from '../config/db.js';

class TaskModel {
  /**
   * Get all tasks
   */
  static async getAllTasks(status) {
    let query = `
      SELECT 
        id,
        title,
        status,
        start_date,
        reminder_date,
        restart_count,
        created_at
      FROM tasks
      WHERE 1=1
    `;
    const params = [];
    if (status && status !== 'all') {
      query += ` AND status = ?`;
      params.push(status);
    }
    query += ` ORDER BY id DESC`;

    const [rows] = await pool.query(query, params);
    return rows;
  }

  /**
   * Create task
   */
  static async createTask(data) {
    const { title, start_date, reminder_date, status = 'pending' } = data;
    const [result] = await pool.query(
      `INSERT INTO tasks (title, start_date, reminder_date, status, restart_count)
       VALUES (?, ?, ?, ?, 0)`,
      [title, start_date, reminder_date, status]
    );
    return result.insertId;
  }

  /**
   * Update task status
   */
  static async updateTaskStatus(id, status) {
    const [result] = await pool.query(
      `UPDATE tasks SET status = ? WHERE id = ?`,
      [status, id]
    );
    return result.affectedRows > 0;
  }

  /**
   * Delete task
   */
  static async deleteTask(id) {
    const [result] = await pool.query('DELETE FROM tasks WHERE id = ?', [id]);
    return result.affectedRows > 0;
  }
}

export default TaskModel;
