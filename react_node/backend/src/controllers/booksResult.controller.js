import BooksResultModel from '../models/BooksResult.model.js';
import pool from '../config/db.js';
import { success, failure } from '../utils/helpers.js';

export async function getBooksResultList(req, res) {
  try {
    const records = await BooksResultModel.getAllBooksResult(req.query);
    return success(res, { records, data: records, count: records.length });
  } catch (error) {
    console.error('Error in getBooksResultList:', error);
    return failure(res, error.message || 'Failed to fetch records', 500);
  }
}

export async function createBooksResultRecord(req, res) {
  try {
    let student_id = req.body.student_id || req.body.studentId || req.body.id;
    let standard = req.body.standard || req.body.class;
    if (!student_id) {
      return failure(res, 'Student ID is required.', 400);
    }
    // Resolve numeric ID to alphanumeric student code if necessary
    if (/^\d+$/.test(String(student_id))) {
      const [stu] = await pool.query('SELECT SPRIDEN_student_id FROM spriden WHERE id = ?', [student_id]);
      if (stu[0]?.SPRIDEN_student_id) {
        student_id = stu[0].SPRIDEN_student_id;
      }
    }
    if (!standard) {
      const [cls] = await pool.query(
        `SELECT c.STVCLAS_standard, c.STVCLAS_class 
         FROM stvclas c
         JOIN spriden s ON s.id = c.STVCLAS_student_id
         WHERE s.SPRIDEN_student_id = ?
         ORDER BY c.STVCLAS_acdm DESC LIMIT 1`,
        [student_id]
      );
      standard = cls[0]?.STVCLAS_standard || cls[0]?.STVCLAS_class || 'I';
    }
    const id = await BooksResultModel.createBooksResult({
      ...req.body,
      student_id,
      standard,
      user_id: req.user?.id || 1,
    });
    return success(res, { id, message: 'Books & Result record created successfully.' }, 201);
  } catch (error) {
    console.error('Error in createBooksResultRecord:', error);
    return failure(res, error.message || 'Failed to create record', 500);
  }
}

export async function deleteBooksResultRecord(req, res) {
  try {
    const { id } = req.params;
    await BooksResultModel.deleteBooksResult(id);
    return success(res, { message: 'Record deleted.' });
  } catch (error) {
    console.error('Error in deleteBooksResultRecord:', error);
    return failure(res, error.message || 'Failed to delete record', 500);
  }
}
