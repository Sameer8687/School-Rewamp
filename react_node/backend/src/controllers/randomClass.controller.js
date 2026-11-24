/**
 * RandomClass Controller — Manages random student allocation to teachers
 */
import RandomClassModel from '../models/RandomClass.model.js';

export const getOptions = async (req, res, next) => {
  try {
    const [standards, teachers] = await Promise.all([
      RandomClassModel.getStandards(),
      RandomClassModel.getTeachers()
    ]);
    return res.json({
      success: true,
      data: { standards, teachers }
    });
  } catch (error) {
    next(error);
  }
};

export const getStudentsByClass = async (req, res, next) => {
  try {
    const { standard, academic_year } = req.query;
    if (!standard) {
      return res.status(400).json({ success: false, message: 'Class/standard is required' });
    }

    const students = await RandomClassModel.getStudentsByClass(
      standard,
      academic_year ? parseInt(academic_year) : null
    );
    return res.json({ success: true, data: students });
  } catch (error) {
    next(error);
  }
};

export const saveAssignments = async (req, res, next) => {
  try {
    const { standard, assignments } = req.body;
    if (!standard || !Array.isArray(assignments)) {
      return res.status(400).json({
        success: false,
        message: 'Standard and assignments array are required'
      });
    }

    await RandomClassModel.saveAssignments(standard, assignments);
    return res.json({
      success: true,
      message: 'Class allocation saved successfully'
    });
  } catch (error) {
    next(error);
  }
};

