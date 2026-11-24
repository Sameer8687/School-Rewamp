import pool from '../config/db.js';

class BankUploadModel {
  /**
   * Check if a transaction ID already exists in fees_bank_up_transactions
   */
  static async transactionExists(transactionId) {
    const [[row]] = await pool.query(
      `SELECT id FROM fees_bank_up_transactions WHERE transaction_id = ? LIMIT 1`,
      [transactionId]
    );
    return Boolean(row);
  }

  /**
   * Insert a single bank transaction
   */
  static async insertTransaction(data) {
    const {
      group_id,
      transaction_id,
      value_date,
      txn_posted_date,
      cheque_no,
      description,
      mode,
      transaction_amount,
      uploaded_by,
    } = data;

    try {
      const [res] = await pool.query(
        `INSERT INTO fees_bank_up_transactions (
          group_id,
          transaction_id,
          value_date,
          txn_posted_date,
          cheque_no,
          description,
          mode,
          transaction_amount,
          uploaded_by,
          uploaded_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())`,
        [
          group_id,
          transaction_id,
          value_date,
          txn_posted_date,
          cheque_no || null,
          description,
          mode || null,
          transaction_amount,
          uploaded_by || null,
        ]
      );
      return { status: 'inserted', id: res.insertId };
    } catch (err) {
      if (err.code === 'ER_DUP_ENTRY' || err.errno === 1062) {
        return { status: 'duplicate', message: 'Duplicate transaction ID' };
      }
      throw err;
    }
  }

  /**
   * Get filtered transactions with search and dates
   */
  static async getFilteredTransactions(filters = {}) {
    let query = `
      SELECT 
        id,
        group_id,
        transaction_id,
        value_date,
        txn_posted_date,
        cheque_no,
        description,
        mode,
        transaction_amount,
        uploaded_at,
        uploaded_by
      FROM fees_bank_up_transactions
      WHERE 1=1
    `;
    const params = [];

    if (filters.group_id) {
      query += ` AND group_id = ?`;
      params.push(filters.group_id);
    }
    if (filters.transaction_id) {
      query += ` AND transaction_id LIKE ?`;
      params.push(`%${filters.transaction_id.trim()}%`);
    }
    if (filters.search) {
      query += ` AND (transaction_id LIKE ? OR description LIKE ? OR cheque_no LIKE ?)`;
      const q = `%${filters.search.trim()}%`;
      params.push(q, q, q);
    }
    if (filters.value_date) {
      query += ` AND value_date = ?`;
      params.push(filters.value_date);
    }
    if (filters.from_date && filters.to_date) {
      query += ` AND value_date BETWEEN ? AND ?`;
      params.push(filters.from_date, filters.to_date);
    }
    if (filters.uploaded_at) {
      query += ` AND DATE(uploaded_at) = ?`;
      params.push(filters.uploaded_at);
    }

    query += ` ORDER BY txn_posted_date DESC, id DESC`;

    if (filters.limit) {
      query += ` LIMIT ?`;
      params.push(Number(filters.limit));
      if (filters.offset) {
        query += ` OFFSET ?`;
        params.push(Number(filters.offset));
      }
    }

    const [rows] = await pool.query(query, params);

    // Also get summary total
    let sumQuery = `
      SELECT 
        COUNT(*) AS total_count,
        COALESCE(SUM(transaction_amount), 0) AS total_amount
      FROM fees_bank_up_transactions
      WHERE 1=1
    `;
    const sumParams = [];
    if (filters.group_id) {
      sumQuery += ` AND group_id = ?`;
      sumParams.push(filters.group_id);
    }
    if (filters.transaction_id) {
      sumQuery += ` AND transaction_id LIKE ?`;
      sumParams.push(`%${filters.transaction_id.trim()}%`);
    }
    if (filters.search) {
      sumQuery += ` AND (transaction_id LIKE ? OR description LIKE ? OR cheque_no LIKE ?)`;
      const q = `%${filters.search.trim()}%`;
      sumParams.push(q, q, q);
    }
    if (filters.from_date && filters.to_date) {
      sumQuery += ` AND value_date BETWEEN ? AND ?`;
      sumParams.push(filters.from_date, filters.to_date);
    }

    const [[sumRow]] = await pool.query(sumQuery, sumParams);

    return {
      transactions: rows,
      total_count: sumRow.total_count,
      total_amount: parseFloat(sumRow.total_amount) || 0,
    };
  }

  /**
   * Get list of upload batches
   */
  static async getUploadGroups() {
    const [rows] = await pool.query(`
      SELECT 
        group_id,
        MIN(uploaded_at) AS uploaded_at,
        COUNT(*) AS record_count,
        SUM(transaction_amount) AS total_amount,
        MIN(value_date) AS min_date,
        MAX(value_date) AS max_date
      FROM fees_bank_up_transactions
      GROUP BY group_id
      ORDER BY uploaded_at DESC
      LIMIT 100
    `);
    return rows;
  }
}

export default BankUploadModel;
