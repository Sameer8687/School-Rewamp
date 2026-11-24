import api from './api';

export const onlineApplicationService = {
  // List all applications (school and junior college)
  getApplications: (params) => {
    return api.get('/online-applications', { params });
  },

  // Get application details by ID
  getApplicationDetails: (id, type) => {
    return api.get(`/online-applications/${id}`, { params: { type } });
  },

  // Submit Junior College application (public)
  submitJuniorCollege: (data) => {
    return api.post('/online-applications/public/submit-college', data);
  },

  // Update application status
  updateStatus: (id, data) => {
    return api.put(`/online-applications/${id}/status`, data);
  },

  // Delete application
  deleteApplication: (id, type) => {
    return api.delete(`/online-applications/${id}`, { params: { type } });
  },

};

export default onlineApplicationService;
