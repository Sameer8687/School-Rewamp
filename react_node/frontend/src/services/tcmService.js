import api from './api';

export const tcmService = {
  getAllRecords: (params) => {
    return api.get('/tcm', { params });
  },

  getTeachers: () => {
    return api.get('/tcm/teachers');
  },

  searchStudents: (query, params = {}) => {
    return api.get('/tcm/search-students', { params: { query, ...params } });
  },

  getStudentDetails: (id, params = {}) => {
    return api.get(`/tcm/student/${id}`, { params });
  },

  createRecord: (data) => {
    return api.post('/tcm', data);
  },

  deleteRecord: (id) => {
    return api.delete(`/tcm/${id}`);
  }
};

export default tcmService;

