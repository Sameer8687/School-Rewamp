import api from './api';

const studentExtrasService = {
  getOptions: async () => {
    const response = await api.get('/student-extras/options');
    return response.data;
  },

  // I-Cards
  getICardStudents: async (params) => {
    const response = await api.get('/student-extras/icards', { params });
    return response.data;
  },

  uploadICardPhoto: async (studentId, file) => {
    const formData = new FormData();
    formData.append('photo', file);
    const response = await api.post(`/student-extras/icards/${studentId}/photo`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    return response.data;
  },

  // Tailor Tracking
  getTailorStudents: async (params) => {
    const response = await api.get('/student-extras/tailor', { params });
    return response.data;
  },

  saveTailorRecords: async (records) => {
    const response = await api.post('/student-extras/tailor', { records });
    return response.data;
  },

  // Remarks & Admin Notes
  getRemarksAndNotes: async (params) => {
    const response = await api.get('/student-extras/remarks', { params });
    return response.data;
  },

  createRemark: async (payload) => {
    const response = await api.post('/student-extras/remarks', payload);
    return response.data;
  },

  replyRemark: async (id, payload) => {
    const body = typeof payload === 'string' ? { reply: payload } : payload;
    const response = await api.put(`/student-extras/remarks/${id}/reply`, body);
    return response.data;
  },

  deleteRemark: async (id) => {
    const response = await api.delete(`/student-extras/remarks/${id}`);
    return response.data;
  },

  createAdminNote: async (payload) => {
    const response = await api.post('/student-extras/admin-notes', payload);
    return response.data;
  },

  deleteAdminNote: async (id) => {
    const response = await api.delete(`/student-extras/admin-notes/${id}`);
    return response.data;
  },
};

export default studentExtrasService;

