import api from './api';

const classReportService = {
  getOptions: async () => {
    const response = await api.get('/class-report/options');
    return response.data;
  },

  getClassStudents: async (params) => {
    const response = await api.get('/class-report/students', { params });
    return response.data;
  },

  getStudentProfileData: async (studentId, academicYear) => {
    const response = await api.get(`/class-report/student/${studentId}`, {
      params: academicYear ? { academic_year: academicYear } : {},
    });
    return response.data;
  },
};

export default classReportService;

