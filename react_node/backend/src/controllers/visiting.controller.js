import VisitingModel from '../models/Visiting.model.js';
import pool from '../config/db.js';
import { success, failure } from '../utils/helpers.js';

export async function getVisitingRecords(req, res) {
  try {
    const records = await VisitingModel.getAllVisiting(req.query);
    return success(res, { records, count: records.length });
  } catch (error) {
    console.error('Error in getVisitingRecords:', error);
    return failure(res, error.message || 'Failed to fetch visiting records', 500);
  }
}

export async function createVisitingRecord(req, res) {
  try {
    const student_id = req.body.visiting_student_id || req.body.student_id;
    let resolvedId = student_id || 0;
    if (student_id && isNaN(Number(student_id))) {
      const [rows] = await pool.query(
        'SELECT id FROM spriden WHERE SPRIDEN_student_id = ? LIMIT 1',
        [student_id]
      );
      if (rows[0]) resolvedId = rows[0].id;
    }

    const payload = {
      visiting_student_id: Number(resolvedId) || student_id,
      visiting_student_class: req.body.visiting_student_class || req.body.student_class || req.body.standard || '',
      visiting_student_fees_paid: req.body.visiting_student_fees_paid !== undefined ? req.body.visiting_student_fees_paid : (req.body.fees_paid || 0),
      visiting_date: req.body.visiting_date || req.body.date || new Date().toISOString().slice(0, 10),
      visiting_fees_status_of: req.body.visiting_fees_status_of || req.body.fees_status_of || '',
      visiting_fees_due_date: req.body.visiting_fees_due_date || req.body.due_date || null,
      visiting_book_fees: req.body.visiting_book_fees !== undefined ? req.body.visiting_book_fees : (req.body.book_fees || 0),
      visiting_added_by: req.user?.id || 1,
    };

    const id = await VisitingModel.createVisiting(payload);
    return success(res, { id, message: 'Visiting record created successfully.' }, 201);
  } catch (error) {
    console.error('Error in createVisitingRecord:', error);
    return failure(res, error.message || 'Failed to create visiting record', 500);
  }
}

export async function deleteVisitingRecord(req, res) {
  try {
    const { id } = req.params;
    await VisitingModel.deleteVisiting(id);
    return success(res, { message: 'Visiting record deleted.' });
  } catch (error) {
    console.error('Error in deleteVisitingRecord:', error);
    return failure(res, error.message || 'Failed to delete visiting record', 500);
  }
}
