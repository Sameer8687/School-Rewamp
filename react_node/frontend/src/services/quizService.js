import api from './api';

export const quizService = {
  getQuizzes: (params) => {
    return api.get('/quizzes/list', { params });
  },

  getSubmissions: (params) => {
    return api.get('/quizzes/submissions', { params });
  },

  getQuizReview: (quizId, studentId) => {
    return api.get(`/quizzes/review/${quizId}/${studentId}`);
  }
};

export default quizService;

