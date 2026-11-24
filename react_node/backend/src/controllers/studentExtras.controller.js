/**
 * StudentExtras Controller — ID Cards, Tailor/Uniform Tracking, and Student Remarks
 */
import StudentExtrasModel from '../models/StudentExtras.model.js';

export const getOptions = async (req, res, next) => {
  try {
    const data = await StudentExtrasModel.getStandardsAndDivisions();
    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

// ─── ID Cards ───
export const getICardStudents = async (req, res, next) => {
  try {
    const { standard, division, academic_year } = req.query;
    const rows = await StudentExtrasModel.getICardStudents({
      standard,
      division,
      academic_year: academic_year ? parseInt(academic_year) : null
    });
    return res.json({ success: true, data: rows });
  } catch (error) {
    next(error);
  }
};

export const uploadICardPhoto = async (req, res, next) => {
  try {
    const { studentId } = req.params;
    if (!req.file) {
      return res.status(400).json({ success: false, message: 'Please upload a photo file.' });
    }
    const photoPath = `/uploads/icards/${req.file.filename}`;
    await StudentExtrasModel.saveICardPhoto(studentId, photoPath);
    return res.json({
      success: true,
      message: 'ID Card photo uploaded successfully.',
      data: { photo_url: photoPath }
    });
  } catch (error) {
    next(error);
  }
};

// ─── Tailor / Uniform ───
export const getTailorStudents = async (req, res, next) => {
  try {
    const { standard, division, academic_year } = req.query;
    const rows = await StudentExtrasModel.getTailorStudents({
      standard,
      division,
      academic_year: academic_year ? parseInt(academic_year) : null
    });
    return res.json({ success: true, data: rows });
  } catch (error) {
    next(error);
  }
};

export const saveTailorDetails = async (req, res, next) => {
  try {
    const { records } = req.body;
    if (!Array.isArray(records) || records.length === 0) {
      return res.status(400).json({ success: false, message: 'No tailor records provided.' });
    }
    const count = await StudentExtrasModel.saveTailorDetails(records, req.user?.id || '1');
    return res.json({
      success: true,
      message: `Updated tailor/uniform records for ${count} student(s).`
    });
  } catch (error) {
    next(error);
  }
};

// ─── Remarks & Admin Notes ───
export const getRemarks = async (req, res, next) => {
  try {
    const { standard, student_id, academic_year } = req.query;
    const [teacherRemarks, adminNotes] = await Promise.all([
      StudentExtrasModel.getRemarks({
        standard,
        student_id,
        academic_year: academic_year ? parseInt(academic_year) : null
      }),
      StudentExtrasModel.getAdminNotes()
    ]);
    return res.json({
      success: true,
      data: { teacher_remarks: teacherRemarks, admin_notes: adminNotes }
    });
  } catch (error) {
    next(error);
  }
};

export const createRemark = async (req, res, next) => {
  try {
    const { student_id, standard, remark, teacher_id, academic_year } = req.body;
    if (!student_id || !remark) {
      return res.status(400).json({ success: false, message: 'Student and remark are required.' });
    }
    const id = await StudentExtrasModel.createRemark({
      teacher_id: teacher_id || req.user?.username || req.user?.id || 'Admin',
      student_id,
      standard,
      remark,
      academic_year
    });
    return res.status(201).json({ success: true, message: 'Remark added successfully.', data: { id } });
  } catch (error) {
    next(error);
  }
};

export const replyRemark = async (req, res, next) => {
  try {
    const { id } = req.params;
    const { reply, status } = req.body;
    const ok = await StudentExtrasModel.replyRemark(id, { reply, status });
    if (!ok) return res.status(404).json({ success: false, message: 'Remark not found.' });
    return res.json({ success: true, message: 'Reply saved.' });
  } catch (error) {
    next(error);
  }
};

export const deleteRemark = async (req, res, next) => {
  try {
    const { id } = req.params;
    const ok = await StudentExtrasModel.deleteRemark(id);
    if (!ok) return res.status(404).json({ success: false, message: 'Remark not found.' });
    return res.json({ success: true, message: 'Remark deleted.' });
  } catch (error) {
    next(error);
  }
};

export const createAdminNote = async (req, res, next) => {
  try {
    const student_id = req.body.student_id || req.body.studentId;
    const student_name = req.body.student_name || req.body.studentName || '';
    const due_date = req.body.due_date || req.body.dueDate || null;
    const note = req.body.note || req.body.description || req.body.title || req.body.content;

    if (!student_id || !note) {
      return res.status(400).json({ success: false, message: 'Student and note are required.' });
    }
    const id = await StudentExtrasModel.createAdminNote({ student_id, student_name, due_date, note });
    return res.status(201).json({ success: true, message: 'Admin note added.', data: { id } });
  } catch (error) {
    next(error);
  }
};

export const deleteAdminNote = async (req, res, next) => {
  try {
    const { id } = req.params;
    const ok = await StudentExtrasModel.deleteAdminNote(id);
    if (!ok) return res.status(404).json({ success: false, message: 'Note not found.' });
    return res.json({ success: true, message: 'Admin note deleted.' });
  } catch (error) {
    next(error);
  }
};

