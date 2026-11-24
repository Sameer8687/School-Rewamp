import api from './api';

export const teacherService = {
  // Directory & CRUD
  getTeachers: (params) => {
    return api.get('/teachers', { params });
  },

  getNextTeacherId: () => {
    return api.get('/teachers/next-id');
  },

  getTeacherById: (id) => {
    return api.get(`/teachers/profile/${id}`);
  },

  createTeacher: (data) => {
    return api.post('/teachers/register', data);
  },

  updateTeacher: (id, data) => {
    return api.put(`/teachers/update/${id}`, data);
  },

  updateTeacherStatus: (id, status) => {
    return api.patch(`/teachers/status/${id}`, { status });
  },

  // Class Teacher Assignments
  getAssignments: (academicYearId) => {
    return api.get('/teachers/assignments', {
      params: { academic_year_id: academicYearId }
    });
  },

  assignClassTeacher: (data) => {
    return api.post('/teachers/assign-class', data);
  },

  // Timetable
  getTimetable: (standard, division) => {
    return api.get('/teachers/timetable', {
      params: { standard, division }
    });
  },

  saveTimetable: (data) => {
    return api.post('/teachers/timetable', data);
  },

  // Lesson Management
  getLessons: (params) => {
    return api.get('/teachers/lessons', { params });
  },

  saveLesson: (data) => {
    return api.post('/teachers/lessons', data);
  },

  deleteLesson: (id) => {
    return api.delete(`/teachers/lessons/${id}`);
  }
};

export default teacherService;
