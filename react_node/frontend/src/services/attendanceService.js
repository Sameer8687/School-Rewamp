import api from './api';

export const attendanceService = {
  // Fetch students for attendance entry
  getStudentsForAttendance: (params) => {
    return api.get('/attendance/students', { params });
  },

  // Save attendance
  saveAttendance: (data) => {
    return api.post('/attendance/save', data);
  },

  // Class daily attendance summary
  getClassAttendance: (params) => {
    return api.get('/attendance/class-summary', { params });
  },

  // School-wide daily overview
  getSchoolOverview: (params) => {
    return api.get('/attendance/school-overview', { params });
  },

  // Absentee & Streaks Report
  getAbsenteeReport: (params) => {
    return api.get('/attendance/absentee-report', { params });
  },

  // Individual Student Absent Dates & Parent WhatsApp
  getDailyAbsentDates: (studentId, academicYear) => {
    return api.get(`/attendance/absent-dates/${studentId}`, {
      params: { academic_year: academicYear }
    });
  }
};

export default attendanceService;
