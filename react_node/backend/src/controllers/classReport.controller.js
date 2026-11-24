/**
 * ClassReport Controller — Student Profile DB (CI3: ClassReport.php)
 */
import ClassReportModel from '../models/ClassReport.model.js';

export const getOptions = async (req, res, next) => {
  try {
    const data = await ClassReportModel.getOptions();
    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

export const getClassStudents = async (req, res, next) => {
  try {
    const { standard, division, academic_year } = req.query;
    const rows = await ClassReportModel.getClassStudents({
      standard,
      division,
      academic_year,
    });
    return res.json({ success: true, data: rows });
  } catch (error) {
    next(error);
  }
};

export const getStudentProfileData = async (req, res, next) => {
  try {
    const { studentId } = req.params;
    const { academic_year } = req.query;
    const data = await ClassReportModel.getStudentProfileData(studentId, academic_year);
    if (!data) {
      return res.status(404).json({ success: false, message: 'Student not found.' });
    }
    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

