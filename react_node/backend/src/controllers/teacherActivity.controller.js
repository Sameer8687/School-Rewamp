/**
 * TeacherActivity Controller — Teacher Activity & Attendance Submission Audit
 */
import TeacherActivityModel from '../models/TeacherActivity.model.js';

export const getOptions = async (req, res, next) => {
  try {
    const data = await TeacherActivityModel.getOptions();
    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

export const getActivityReport = async (req, res, next) => {
  try {
    const { academic_year, from_date, to_date, teacher_id } = req.query;
    const data = await TeacherActivityModel.getActivityReport({
      academic_year,
      from_date,
      to_date,
      teacher_id,
    });
    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

export const getLoginTracking = async (req, res, next) => {
  try {
    const { from_date, to_date, user_type } = req.query;
    const data = await TeacherActivityModel.getLoginTracking({
      from_date,
      to_date,
      user_type,
    });
    return res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

