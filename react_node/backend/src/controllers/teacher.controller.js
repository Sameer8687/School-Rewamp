import TeacherModel from '../models/Teacher.model.js';

export const getTeachers = async (req, res) => {
  try {
    const { status, search, academic_year_id } = req.query;
    const teachers = await TeacherModel.getTeachers({
      status,
      search,
      academicYearId: academic_year_id
    });
    res.json({ success: true, teachers });
  } catch (error) {
    console.error('getTeachers error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch teachers' });
  }
};

export const getNextTeacherId = async (req, res) => {
  try {
    const nextId = await TeacherModel.getNextTeacherId();
    res.json({ success: true, nextId });
  } catch (error) {
    console.error('getNextTeacherId error:', error);
    res.status(500).json({ success: false, message: 'Failed to generate teacher ID' });
  }
};

export const getTeacherById = async (req, res) => {
  try {
    const { id } = req.params;
    const teacher = await TeacherModel.getTeacherById(id);
    if (!teacher) {
      return res.status(404).json({ success: false, message: 'Teacher not found' });
    }
    res.json({ success: true, teacher });
  } catch (error) {
    console.error('getTeacherById error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch teacher profile' });
  }
};

export const createTeacher = async (req, res) => {
  try {
    const result = await TeacherModel.createTeacher(req.body);
    res.json({
      success: true,
      message: 'Teacher registered successfully!',
      teacher_id: result.teacher_id,
      id: result.id
    });
  } catch (error) {
    console.error('createTeacher error:', error);
    res.status(500).json({ success: false, message: 'Failed to register teacher' });
  }
};

export const updateTeacher = async (req, res) => {
  try {
    const { id } = req.params;
    const result = await TeacherModel.updateTeacher(id, req.body);
    res.json({ success: true, message: 'Teacher details updated successfully', result });
  } catch (error) {
    console.error('updateTeacher error:', error);
    res.status(500).json({ success: false, message: 'Failed to update teacher' });
  }
};

export const updateTeacherStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const { status } = req.body;
    if (!status || !['A', 'I'].includes(status)) {
      return res.status(400).json({ success: false, message: 'Valid status (A or I) required' });
    }

    const updated = await TeacherModel.updateTeacherStatus(id, status);
    res.json({ success: true, message: `Teacher marked as ${status === 'A' ? 'Active' : 'Inactive'}` });
  } catch (error) {
    console.error('updateTeacherStatus error:', error);
    res.status(500).json({ success: false, message: 'Failed to update teacher status' });
  }
};

export const getAssignedClassTeachers = async (req, res) => {
  try {
    const academicYearId = req.query.academic_year_id || (await TeacherModel.getActiveAcademicYearId());
    const assignments = await TeacherModel.getAssignedClassTeachers(academicYearId);
    res.json({ success: true, academic_year_id: academicYearId, assignments });
  } catch (error) {
    console.error('getAssignedClassTeachers error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch class assignments' });
  }
};

export const assignClassTeacher = async (req, res) => {
  try {
    const { standard, division, academic_year_id } = req.body;
    const teacher_emp_id = req.body.teacher_emp_id || req.body.teacher_id || req.body.teacherId;
    if (!standard || !division || !teacher_emp_id) {
      return res.status(400).json({
        success: false,
        message: 'Standard, division, and teacher employee ID are required'
      });
    }

    const acdmYear = academic_year_id || (await TeacherModel.getActiveAcademicYearId());
    const result = await TeacherModel.assignClassTeacher(standard, division, teacher_emp_id, acdmYear);

    res.json({ success: true, message: 'Class teacher assigned successfully!', result });
  } catch (error) {
    console.error('assignClassTeacher error:', error);
    res.status(500).json({ success: false, message: 'Failed to assign class teacher' });
  }
};

export const getTimetable = async (req, res) => {
  try {
    const { standard, division } = req.query;
    if (!standard || !division) {
      return res.status(400).json({ success: false, message: 'Standard and division are required' });
    }

    const timetable = await TeacherModel.getTimetable(standard, division);
    res.json({ success: true, standard, division, ...timetable });
  } catch (error) {
    console.error('getTimetable error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch timetable' });
  }
};

export const saveTimetable = async (req, res) => {
  try {
    const { standard, division } = req.body;
    const schedule = Array.isArray(req.body.schedule)
      ? req.body.schedule
      : (req.body.day && req.body.period ? [req.body] : null);

    if (!standard || !division || !schedule || !Array.isArray(schedule)) {
      return res.status(400).json({
        success: false,
        message: 'Standard, division, and schedule rows array are required'
      });
    }

    const result = await TeacherModel.saveTimetable(standard, division, schedule);
    res.json({ success: true, message: 'Timetable saved successfully', count: result.count });
  } catch (error) {
    console.error('saveTimetable error:', error);
    res.status(500).json({ success: false, message: 'Failed to save timetable' });
  }
};

export const getLessons = async (req, res) => {
  try {
    const { academic_year, standard, subject } = req.query;
    const acdmYear = academic_year || (await TeacherModel.getActiveAcademicYearId());

    const lessons = await TeacherModel.getLessons(acdmYear, standard, subject);
    res.json({ success: true, academic_year: acdmYear, lessons });
  } catch (error) {
    console.error('getLessons error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch lessons' });
  }
};

export const saveLesson = async (req, res) => {
  try {
    const { id, academic_year } = req.body;
    const type = req.body.type || 'theory';
    const standard = req.body.standard || req.body.class || '1';
    const subject = req.body.subject || req.body.subject_id || req.body.subjectId;
    const lesson_no = req.body.lesson_no || req.body.lesson_number || req.body.lessonNo || 1;
    const lesson_name = req.body.lesson_name || req.body.lesson_title || req.body.name || req.body.title;

    if (!standard || !subject || !lesson_name) {
      return res.status(400).json({
        success: false,
        message: 'Standard, subject, and lesson name are required'
      });
    }

    const acdmYear = academic_year || (await TeacherModel.getActiveAcademicYearId());
    const result = await TeacherModel.saveLesson({
      id,
      academic_year: acdmYear,
      standard,
      subject,
      lesson_no,
      lesson_name,
      type
    });

    res.json({
      success: true,
      message: result.updated ? 'Lesson updated successfully!' : 'Lesson created successfully!',
      result
    });
  } catch (error) {
    console.error('saveLesson error:', error);
    res.status(500).json({ success: false, message: 'Failed to save lesson' });
  }
};

export const deleteLesson = async (req, res) => {
  try {
    const { id } = req.params;
    const deleted = await TeacherModel.deleteLesson(id);
    if (!deleted) {
      return res.status(404).json({ success: false, message: 'Lesson not found' });
    }
    res.json({ success: true, message: 'Lesson deleted successfully' });
  } catch (error) {
    console.error('deleteLesson error:', error);
    res.status(500).json({ success: false, message: 'Failed to delete lesson' });
  }
};
