/**
 * Communication Service — API Client for Message Templates & Events
 */
import api from './api';

export const communicationService = {
  // Message Templates
  getTemplates: () => {
    return api.get('/communication/templates');
  },

  createTemplate: (data) => {
    return api.post('/communication/templates', data);
  },

  updateTemplate: (id, data) => {
    return api.put(`/communication/templates/${id}`, data);
  },

  deleteTemplate: (id) => {
    return api.delete(`/communication/templates/${id}`);
  },

  // Events
  getEvents: (params) => {
    return api.get('/communication/events', { params });
  },

  createEvent: (data) => {
    return api.post('/communication/events', data);
  },

  updateEvent: (id, data) => {
    return api.put(`/communication/events/${id}`, data);
  },

  deleteEvent: (id) => {
    return api.delete(`/communication/events/${id}`);
  },
};

export default communicationService;
