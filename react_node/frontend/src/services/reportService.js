import api from './api';

export const reportService = {
  // Top KPI overview
  getKPIs: (academicYear) => {
    return api.get('/reports/kpis', { params: { academicYear } });
  },

  // Student & Admission Reports
  getStudentReports: (params) => {
    return api.get('/reports/students', { params });
  },

  // Financial & Fees Reports
  getFeesReports: (params) => {
    return api.get('/reports/fees', { params });
  },

  // Attendance & Absence Reports
  getAttendanceReports: (params) => {
    return api.get('/reports/attendance', { params });
  },

  // Certificates (LC & Bonafide)
  getCertificatesReports: (params) => {
    return api.get('/reports/certificates', { params });
  },

  // Update LC Request
  updateLcRequestStatus: (requestId, status) => {
    return api.post('/reports/lc-request/update', { requestId, status });
  },

  // Teacher & Academic Activity
  getTeacherActivityReports: (params) => {
    return api.get('/reports/teacher-activity', { params });
  },

  // Audit & Login Tracking Logs
  getAuditReports: (params) => {
    return api.get('/reports/audit', { params });
  },
};

export default reportService;
