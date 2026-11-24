import api from './api';

export const examService = {
  // Exams & subjects
  getExamsList: (params) => {
    return api.get('/exams/exams-list', { params });
  },

  createExam: (data) => {
    return api.post('/exams/create-exam', data);
  },

  getSubjects: (params) => {
    return api.get('/exams/subjects', { params });
  },

  getExamSetup: (params) => {
    return api.get('/exams/setup', { params });
  },

  saveExamSetup: (data) => {
    return api.post('/exams/setup', data);
  },

  // Marks entry
  getStudentMarks: (params) => {
    return api.get('/exams/marks', { params });
  },

  saveStudentMarks: (data) => {
    return api.post('/exams/marks', data);
  },

  // Hall tickets
  getHallTickets: (params) => {
    return api.get('/exams/hall-tickets', { params });
  },

  getHallTicketById: (id) => {
    return api.get(`/exams/hall-tickets/${id}`);
  },

  saveHallTicket: (data) => {
    return api.post('/exams/hall-tickets', data);
  },

  deleteHallTicket: (id) => {
    return api.delete(`/exams/hall-tickets/${id}`);
  },

  getHallTicketStudents: (params) => {
    return api.get('/exams/hall-tickets/students', { params });
  },

  // Report cards & Results
  getStudentReportCard: (studentId, params) => {
    return api.get(`/exams/report-card/${studentId}`, { params });
  },

  getClassResults: (params) => {
    return api.get('/exams/class-results', { params });
  },
};

export default examService;
