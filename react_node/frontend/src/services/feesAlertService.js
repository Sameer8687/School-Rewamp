import api from './api';

export const feesAlertService = {
  getFeesMessageStudents: (params) => {
    return api.get('/fees-alert/students', { params });
  },

  getStudentFeesHistory: (id) => {
    return api.get(`/fees-alert/student-history/${id}`);
  },

  getAdmissionConfirmationAlerts: (params) => {
    return api.get('/fees-alert/admission-confirmation', { params });
  },

  getMessageTemplates: () => {
    return api.get('/communication/templates');
  },
};

export default feesAlertService;
