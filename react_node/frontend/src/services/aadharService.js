import api from './api.js';

/**
 * Aadhar & KYC Management Service
 */
export async function fetchAadharReport(params = {}) {
  const res = await api.get('/aadhar/report', { params });
  return res.data;
}

export async function fetchStudentAadhar(studentId) {
  const res = await api.get(`/aadhar/student/${studentId}`);
  return res.data;
}

export async function saveStudentAadhar(data) {
  const res = await api.post('/aadhar/save', data);
  return res.data;
}

export default {
  fetchAadharReport,
  fetchStudentAadhar,
  saveStudentAadhar,
};
