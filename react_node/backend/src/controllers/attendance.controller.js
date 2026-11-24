import AttendanceModel from '../models/Attendance.model.js';

export const getStudentsForAttendance = async (req, res) => {
  try {
    const { standard, division, date, academic_year } = req.query;

    if (!standard || !division) {
      return res.status(400).json({
        success: false,
        message: 'Standard and division are required'
      });
    }

    const attendanceDate = date || new Date().toISOString().split('T')[0];
    const acdmYear = academic_year || (await AttendanceModel.getActiveAcademicYearId());

    const students = await AttendanceModel.getStudentsForAttendance(
      standard,
      division,
      acdmYear,
      attendanceDate
    );

    res.json({
      success: true,
      standard,
      division,
      date: attendanceDate,
      academic_year: acdmYear,
      students
    });
  } catch (error) {
    console.error('getStudentsForAttendance error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch students for attendance' });
  }
};

export const saveAttendance = async (req, res) => {
  try {
    const teacherId = req.user?.id || req.user?.spruser_id || 1;
    const date = req.body.date || req.body.attendance_date || req.body.attendanceDate;
    const standard = req.body.standard || req.body.std || req.body.class;
    const division = req.body.division || req.body.div;
    const academic_year = req.body.academic_year || req.body.academicYear;
    const records = req.body.records || req.body.attendance || req.body.attendanceData;

    if (!date || !standard || !division || !records || !Array.isArray(records)) {
      return res.status(400).json({
        success: false,
        message: 'Date, standard, division, and student records array are required'
      });
    }

    const acdmYear = academic_year || (await AttendanceModel.getActiveAcademicYearId());

    const result = await AttendanceModel.saveAttendance({
      date,
      standard,
      division,
      academicYear: acdmYear,
      records,
      teacherId
    });

    res.json({
      success: true,
      message: `Attendance marked successfully for ${result.count} students!`,
      count: result.count
    });
  } catch (error) {
    console.error('saveAttendance error:', error);
    res.status(500).json({ success: false, message: 'Failed to record attendance' });
  }
};

export const getClassAttendance = async (req, res) => {
  try {
    const { standard, division, date, academic_year } = req.query;

    if (!standard || !division) {
      return res.status(400).json({
        success: false,
        message: 'Standard and division are required'
      });
    }

    const attendanceDate = date || new Date().toISOString().split('T')[0];
    const acdmYear = academic_year || (await AttendanceModel.getActiveAcademicYearId());

    const report = await AttendanceModel.getClassAttendance(
      standard,
      division,
      acdmYear,
      attendanceDate
    );

    res.json({ success: true, ...report });
  } catch (error) {
    console.error('getClassAttendance error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch class attendance' });
  }
};

export const getAllClassesOverview = async (req, res) => {
  try {
    const { date, academic_year } = req.query;
    const attendanceDate = date || new Date().toISOString().split('T')[0];
    const acdmYear = academic_year || (await AttendanceModel.getActiveAcademicYearId());

    const overview = await AttendanceModel.getAllClassesOverview(acdmYear, attendanceDate);
    res.json({ success: true, date: attendanceDate, academic_year: acdmYear, ...overview });
  } catch (error) {
    console.error('getAllClassesOverview error:', error);
    res.status(500).json({ success: false, message: 'Failed to fetch school-wide attendance' });
  }
};

export const getAbsenteeReport = async (req, res) => {
  try {
    const standard = req.query.standard || req.query.std || req.body?.std || req.body?.standard;
    const division = req.query.division || req.query.div || req.body?.div || req.body?.division || 'all';
    const academic_year = req.query.academic_year || req.query.year || req.body?.year || req.body?.academic_year;

    if (!standard) {
      if (req.originalUrl.includes('Absent_student')) {
        return res.json([]);
      }
      return res.status(400).json({
        success: false,
        message: 'Standard is required'
      });
    }

    const acdmYear = academic_year || (await AttendanceModel.getActiveAcademicYearId());
    const students = await AttendanceModel.getAbsenteeReport(standard, division, acdmYear);

    // If request originates from CI3 route or requests raw array
    if (req.originalUrl.includes('Absent_student') || req.query.raw === 'true') {
      return res.json(students);
    }

    res.json({
      success: true,
      standard,
      division,
      academic_year: acdmYear,
      students
    });
  } catch (error) {
    console.error('getAbsenteeReport error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getDailyAbsentDates = async (req, res) => {
  try {
    const studentId = req.params.studentId || req.body?.id || req.query.id;
    const academic_year = req.query.academic_year || req.body?.year || req.query.year;

    if (!studentId) {
      return res.status(400).json({
        success: false,
        message: 'Student ID is required'
      });
    }

    const acdmYear = academic_year || (await AttendanceModel.getActiveAcademicYearId());
    const dates = await AttendanceModel.getDailyAbsentDates(studentId, acdmYear);

    // If called via CI3 legacy endpoint or raw format
    if (req.originalUrl.includes('Absent_student') || req.query.raw === 'true') {
      return res.json(dates);
    }

    res.json({
      success: true,
      student_id: studentId,
      academic_year: acdmYear,
      dates
    });
  } catch (error) {
    console.error('getDailyAbsentDates error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

