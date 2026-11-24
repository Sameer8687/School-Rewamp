import api from './api';

export const taskService = {
  getTasks: (status) => {
    return api.get('/tasks', { params: { status } });
  },

  createTask: (data) => {
    return api.post('/tasks', data);
  },

  updateStatus: (id, status) => {
    return api.put(`/tasks/${id}/status`, { status });
  },

  deleteTask: (id) => {
    return api.delete(`/tasks/${id}`);
  },
};

export default taskService;
