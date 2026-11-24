import FeesAlertModel from '../models/FeesAlert.model.js';

export const getFeesMessageStudents = async (req, res, next) => {
  try {
    const { standard, std, division, div, academicYear, cur_acdm, academic_year } = req.query;
    const finalStandard = standard || std || 'all';
    const finalDivision = division || div || 'all';
    let finalAcademicYear = academicYear || cur_acdm || academic_year;

    if (!finalAcademicYear) {
      const pool = (await import('../config/db.js')).default;
      const [rows] = await pool.query('SELECT stvschool_acdm_year FROM stvschool LIMIT 1');
      finalAcademicYear = rows[0]?.stvschool_acdm_year || 9;
    }

    const students = await FeesAlertModel.getFeesMessageStudents({
      standard: finalStandard,
      division: finalDivision,
      academicYear: finalAcademicYear,
    });

    res.json({
      success: true,
      data: students,
      count: students.length,
    });
  } catch (error) {
    next(error);
  }
};

export const getStudentFeesHistory = async (req, res, next) => {
  try {
    const { id } = req.params;
    if (!id) {
      return res.status(400).json({ success: false, message: 'Student ID is required' });
    }

    const history = await FeesAlertModel.getStudentFeesHistory(id);
    res.json({
      success: true,
      data: history,
    });
  } catch (error) {
    next(error);
  }
};

export const getAdmissionConfirmationAlerts = async (req, res, next) => {
  try {
    const { academicYear, year, standard, std, division, div } = req.query;
    const finalYear = academicYear || year;
    const finalStandard = standard || std;
    const finalDivision = division || div;

    if (!finalYear || !finalStandard || !finalDivision) {
      return res.status(400).json({
        success: false,
        message: 'academicYear, standard, and division are required query parameters',
      });
    }

    const students = await FeesAlertModel.getAdmissionConfirmationAlerts({
      academicYear: finalYear,
      standard: finalStandard,
      division: finalDivision,
    });

    res.json({
      success: true,
      data: students,
      count: students.length,
    });
  } catch (error) {
    next(error);
  }
};
