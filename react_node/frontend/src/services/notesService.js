import api from './api';

export const notesService = {
  getNotes: (params) => {
    return api.get('/notes', { params });
  },

  createNote: (data) => {
    return api.post('/notes', data);
  },

  deleteNote: (id) => {
    return api.delete(`/notes/${id}`);
  },
};

export default notesService;
