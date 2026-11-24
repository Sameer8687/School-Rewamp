/**
 * ClassManagement Controller — Handles Class Creation, Class Roster, and Subject-Teacher Allocation
 */
import ClassManagementModel from '../models/ClassManagement.model.js';

export const getOptions = async (req, res, next) => {
  try {
    const data = await ClassManagementModel.getOptions();
    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

export const getUnassignedStudents = async (req, res, next) => {
  try {
    const { academic_year } = req.query;
    const students = await ClassManagementModel.getUnassignedStudents(
      academic_year ? parseInt(academic_year) : null
    );
    return res.json({ success: true, data: students });
  } catch (error) {
    next(error);
  }
};

export const assignStudentsToClass = async (req, res, next) => {
  try {
    const { academic_year, standard, division } = req.body;
    let rawStudents = req.body.students || req.body.student_ids || req.body.selectedStudents || [];
    if (!Array.isArray(rawStudents)) rawStudents = [rawStudents];
    const students = rawStudents.map((s) => (typeof s === 'object' && s !== null ? s : { student_id: Number(s) }));

    if (!standard || !division || students.length === 0) {
      return res.status(400).json({
        success: false,
        message: 'Standard, division, and at least one student are required.'
      });
    }
    const count = await ClassManagementModel.assignStudentsToClass({
      academic_year: academic_year ? parseInt(academic_year) : null,
      standard,
      division,
      students,
      user_id: req.user?.id || '1'
    });
    return res.json({
      success: true,
      message: `Assigned ${count} student(s) to ${standard} - ${division}`
    });
  } catch (error) {
    next(error);
  }
};

export const getClassRoster = async (req, res, next) => {
  try {
    const { academic_year, standard, division } = req.query;
    const rows = await ClassManagementModel.getClassRoster({
      academic_year: academic_year ? parseInt(academic_year) : null,
      standard,
      division
    });
    return res.json({ success: true, data: rows });
  } catch (error) {
    next(error);
  }
};

export const updateClassRosterRow = async (req, res, next) => {
  try {
    const { id } = req.params;
    const { standard, division, roll_no } = req.body;
    if (!standard || !division) {
      return res.status(400).json({ success: false, message: 'Standard and division are required.' });
    }
    const updated = await ClassManagementModel.updateClassRosterRow(id, {
      standard,
      division,
      roll_no
    });
    if (!updated) {
      return res.status(404).json({ success: false, message: 'Class record not found.' });
    }
    return res.json({ success: true, message: 'Class record updated successfully.' });
  } catch (error) {
    next(error);
  }
};

export const getSubjectTeachers = async (req, res, next) => {
  try {
    const { academic_year } = req.query;
    const rows = await ClassManagementModel.getSubjectTeachers(
      academic_year ? parseInt(academic_year) : null
    );
    return res.json({ success: true, data: rows });
  } catch (error) {
    next(error);
  }
};

export const createSubjectTeacher = async (req, res, next) => {
  try {
    const { teacher_code, standard, division, subject_id, academic_year } = req.body;
    if (!teacher_code || !standard || !division || !subject_id) {
      return res.status(400).json({
        success: false,
        message: 'Teacher, standard, division, and subject are required.'
      });
    }
    const id = await ClassManagementModel.createSubjectTeacher({
      teacher_code,
      standard,
      division,
      subject_id,
      academic_year: academic_year ? parseInt(academic_year) : null,
      user_id: req.user?.id || '1'
    });
    return res.status(201).json({
      success: true,
      message: 'Subject teacher assigned successfully.',
      data: { id }
    });
  } catch (error) {
    next(error);
  }
};

export const updateSubjectTeacher = async (req, res, next) => {
  try {
    const { id } = req.params;
    const { teacher_code, standard, division, subject_id, academic_year } = req.body;
    if (!teacher_code || !standard || !division || !subject_id) {
      return res.status(400).json({
        success: false,
        message: 'Teacher, standard, division, and subject are required.'
      });
    }
    const updated = await ClassManagementModel.updateSubjectTeacher(id, {
      teacher_code,
      standard,
      division,
      subject_id,
      academic_year: academic_year ? parseInt(academic_year) : null
    });
    if (!updated) {
      return res.status(404).json({ success: false, message: 'Subject teacher record not found.' });
    }
    return res.json({ success: true, message: 'Subject teacher allocation updated successfully.' });
  } catch (error) {
    next(error);
  }
};

export const deleteSubjectTeacher = async (req, res, next) => {
  try {
    const { id } = req.params;
    const deleted = await ClassManagementModel.deleteSubjectTeacher(id);
    if (!deleted) {
      return res.status(404).json({ success: false, message: 'Allocation not found.' });
    }
    return res.json({ success: true, message: 'Subject teacher allocation removed.' });
  } catch (error) {
    next(error);
  }
};

