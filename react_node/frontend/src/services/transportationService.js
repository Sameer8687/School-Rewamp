/**
 * Transportation Service — API Client for Vehicles, Drivers, and Bus Routes
 */
import api from './api';

export const transportationService = {
  // Drivers
  getDrivers: () => {
    return api.get('/transportation/drivers');
  },

  createDriver: (data) => {
    return api.post('/transportation/drivers', data);
  },

  updateDriver: (id, data) => {
    return api.put(`/transportation/drivers/${id}`, data);
  },

  deleteDriver: (id) => {
    return api.delete(`/transportation/drivers/${id}`);
  },

  // Vehicles
  getVehicles: () => {
    return api.get('/transportation/vehicles');
  },

  createVehicle: (data) => {
    return api.post('/transportation/vehicles', data);
  },

  updateVehicle: (id, data) => {
    return api.put(`/transportation/vehicles/${id}`, data);
  },

  deleteVehicle: (id) => {
    return api.delete(`/transportation/vehicles/${id}`);
  },

  // Student Mapping
  getStudentTransport: (params) => {
    return api.get('/transportation/students', { params });
  },

  assignStudentTransport: (data) => {
    return api.post('/transportation/assign-student', data);
  },
};

export default transportationService;
