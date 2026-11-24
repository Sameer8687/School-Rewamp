import api from './api';

export const visitingService = {
  getVisiting: (params) => {
    return api.get('/visiting', { params });
  },

  getVisitingById: (id) => {
    return api.get(`/visiting/${id}`);
  },

  createVisiting: (data) => {
    return api.post('/visiting', data);
  },

  deleteVisiting: (id) => {
    return api.delete(`/visiting/${id}`);
  },
};

export default visitingService;
