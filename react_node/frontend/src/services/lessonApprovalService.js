import api from './api';

export const lessonApprovalService = {
  getSubjects: (params) => {
    return api.get('/lesson-approval/subjects', { params });
  },

  getLessonNames: (params) => {
    return api.get('/lesson-approval/lesson-names', { params });
  },

  getLessons: (params) => {
    return api.get('/lesson-approval/lessons', { params });
  },

  updateStatus: (data) => {
    return api.post('/lesson-approval/update-status', data);
  },
};

export default lessonApprovalService;
