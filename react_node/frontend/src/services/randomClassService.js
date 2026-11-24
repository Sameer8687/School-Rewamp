import api from './api';

export const randomClassService = {
  getOptions: () => {
    return api.get('/random-class/options');
  },

  getStudentsByClass: (standard, params = {}) => {
    return api.get('/random-class/students', { params: { standard, ...params } });
  },

  saveAssignments: (data) => {
    return api.post('/random-class/assign', data);
  }
};

export default randomClassService;

