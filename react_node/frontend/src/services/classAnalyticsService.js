import api from './api';

export const classAnalyticsService = {
  getOptions: () => {
    return api.get('/class-analytics/options');
  },

  getClassAnalytics: (params) => {
    return api.get('/class-analytics/overview', { params });
  },

  getStudentDeepDive: (studentId, params = {}) => {
    return api.get(`/class-analytics/student/${studentId}`, { params });
  }
};

export default classAnalyticsService;

