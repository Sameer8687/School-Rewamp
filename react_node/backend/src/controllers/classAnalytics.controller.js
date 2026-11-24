/**
 * ClassAnalytics Controller — Handles Class & Student Examination Performance requests
 */
import ClassAnalyticsModel from '../models/ClassAnalytics.model.js';

export const getFilterOptions = async (req, res, next) => {
  try {
    const options = await ClassAnalyticsModel.getFilterOptions();
    return res.json({ success: true, data: options });
  } catch (error) {
    next(error);
  }
};

export const getClassAnalytics = async (req, res, next) => {
  try {
    const { academic_year, exam_id, standard, division, subject_id } = req.query;
    if (!standard) {
      return res.status(400).json({ success: false, message: 'Class/standard is required' });
    }

    const data = await ClassAnalyticsModel.getClassAnalytics({
      academic_year: academic_year ? parseInt(academic_year) : null,
      exam_id: exam_id ? parseInt(exam_id) : null,
      standard,
      division: division || null,
      subject_id: subject_id ? parseInt(subject_id) : null
    });

    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

export const getStudentDeepDive = async (req, res, next) => {
  try {
    const { student_id } = req.params;
    const { academic_year, exam_id } = req.query;

    const data = await ClassAnalyticsModel.getStudentDeepDive({
      student_id,
      academic_year: academic_year ? parseInt(academic_year) : null,
      exam_id: exam_id ? parseInt(exam_id) : null
    });

    if (!data) {
      return res.status(404).json({ success: false, message: 'Student data not found' });
    }

    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

