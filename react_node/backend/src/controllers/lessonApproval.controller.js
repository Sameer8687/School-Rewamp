import LessonApprovalModel from '../models/LessonApproval.model.js';

export const getSubjects = async (req, res, next) => {
  try {
    const { academic_year, academicYear, class: standard, std } = req.query;
    const subjects = await LessonApprovalModel.getSubjectsByClassYear(
      academic_year || academicYear || null,
      standard || std || null
    );
    res.json({ success: true, subjects });
  } catch (error) {
    next(error);
  }
};

export const getLessonNames = async (req, res, next) => {
  try {
    const { class: standard, std, subject_id, subjectId } = req.query;
    const lessons = await LessonApprovalModel.getLessonNames(
      standard || std || null,
      subject_id || subjectId || null
    );
    res.json({ success: true, lessons });
  } catch (error) {
    next(error);
  }
};

export const getLessons = async (req, res, next) => {
  try {
    const {
      class: standard,
      std,
      division,
      div,
      subject,
      subject_id,
      subjectId,
      lesson,
      status,
    } = req.query;

    const lessons = await LessonApprovalModel.getFilteredLessons({
      standard: standard || std || 'all',
      division: division || div || 'all',
      subjectId: subject || subject_id || subjectId || 'all',
      lesson: lesson || '',
      status: status || 'all',
    });

    res.json({
      success: true,
      data: lessons,
      count: lessons.length,
    });
  } catch (error) {
    next(error);
  }
};

export const updateStatus = async (req, res, next) => {
  try {
    const { lesson_id, lessonId, column_name, columnName, column_value, columnValue } = req.body;
    const targetId = lesson_id || lessonId;
    const targetCol = column_name || columnName;
    const targetVal = column_value !== undefined ? column_value : columnValue;

    if (!targetId || !targetCol || targetVal === undefined) {
      return res.status(400).json({
        success: false,
        message: 'lesson_id, column_name, and column_value are required.',
      });
    }

    const updated = await LessonApprovalModel.updateStatusColumn(targetId, targetCol, targetVal);
    if (!updated) {
      return res.status(404).json({ success: false, message: 'Lesson record not found.' });
    }

    res.json({
      success: true,
      message: `${targetCol} updated to "${targetVal}" successfully.`,
    });
  } catch (error) {
    next(error);
  }
};
