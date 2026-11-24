import api from './api';

const teacherActivityService = {
  getOptions: async () => {
    const response = await api.get('/teacher-activity/options');
    return response.data;
  },

  getActivityReport: async (params) => {
    const response = await api.get('/teacher-activity/report', { params });
    return response.data;
  },

  getLoginTracking: async (params) => {
    const response = await api.get('/teacher-activity/login-tracking', { params });
    return response.data;
  },
};

export default teacherActivityService;

