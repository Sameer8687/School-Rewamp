/**
 * TCM Controller — Handles Teacher Calling & Fee Recovery requests
 */
import TcmModel from '../models/Tcm.model.js';

export const getTcmRecords = async (req, res, next) => {
  try {
    const { academic_year } = req.query;
    const records = await TcmModel.getAllRecords(academic_year ? parseInt(academic_year) : null);
    return res.json({ success: true, data: records });
  } catch (error) {
    next(error);
  }
};

export const getTeachers = async (req, res, next) => {
  try {
    const teachers = await TcmModel.getTeachers();
    return res.json({ success: true, data: teachers });
  } catch (error) {
    next(error);
  }
};

export const searchStudents = async (req, res, next) => {
  try {
    const { query, academic_year } = req.query;
    if (!query || query.trim().length < 2) {
      return res.json({ success: true, data: [] });
    }
    const students = await TcmModel.searchStudents(
      query,
      academic_year ? parseInt(academic_year) : null
    );
    return res.json({ success: true, data: students });
  } catch (error) {
    next(error);
  }
};

export const getStudentDetails = async (req, res, next) => {
  try {
    const { id } = req.params;
    const { academic_year } = req.query;
    const student = await TcmModel.getStudentFeeDetails(
      id,
      academic_year ? parseInt(academic_year) : null
    );
    if (!student) {
      return res.status(404).json({ success: false, message: 'Student not found' });
    }
    return res.json({ success: true, data: student });
  } catch (error) {
    next(error);
  }
};

export const createTcmRecord = async (req, res, next) => {
  try {
    const student_id = req.body.student_id || req.body.studentId || req.body.id;
    const teacher_id = req.body.teacher_id || req.body.teacherId || req.body.teacher_emp_id || req.user?.id || 1;
    const notes = req.body.notes || req.body.remarks || req.body.discussion || '';
    const landmark = req.body.landmark || '';

    if (!student_id) {
      return res.status(400).json({
        success: false,
        message: 'Student ID is required'
      });
    }

    const created_by = req.user?.username || req.user?.id || 'Admin';
    const insertId = await TcmModel.createRecord({
      student_id,
      teacher_id,
      notes,
      landmark,
      created_by
    });

    return res.status(201).json({
      success: true,
      message: 'Calling record saved successfully',
      data: { id: insertId }
    });
  } catch (error) {
    next(error);
  }
};

export const deleteTcmRecord = async (req, res, next) => {
  try {
    const { id } = req.params;
    const success = await TcmModel.deleteRecord(id);
    if (!success) {
      return res.status(404).json({ success: false, message: 'Record not found' });
    }
    return res.json({ success: true, message: 'Record deleted successfully' });
  } catch (error) {
    next(error);
  }
};

