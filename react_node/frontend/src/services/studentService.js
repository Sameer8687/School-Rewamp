/**
 * Student Service — API client for Student Module.
 */
import api from './api.js';

export async function fetchStudents(params = {}) {
  const res = await api.get('/students', { params });
  return res.data;
}

export async function fetchStudentOptions() {
  const res = await api.get('/students/options');
  const raw = res.data?.data || res.data || {};
  const classes = raw.classes || raw.options?.classes || [];
  const academicYears = raw.academicYears || raw.options?.academicYears || [];
  const divisions = raw.divisions || raw.options?.divisions || [];
  const currentAcdmId = raw.currentAcdmId || raw.options?.currentAcdmId || '';

  const normalized = {
    ...res.data,
    data: { ...raw, classes, academicYears, divisions, currentAcdmId },
    options: { ...raw, classes, academicYears, divisions, currentAcdmId },
    classes,
    academicYears,
    divisions,
    currentAcdmId,
  };
  return { data: normalized, ...normalized };
}

export async function fetchNextStudentId(academicYearId) {
  const res = await api.get('/students/next-id', {
    params: { academic_year_id: academicYearId },
  });
  return res.data;
}

export async function fetchStudentById(id) {
  const res = await api.get(`/students/${id}`);
  return res.data;
}

export async function createStudent(formData) {
  const isFormData = formData instanceof FormData;
  const res = await api.post('/students', formData, {
    headers: isFormData ? { 'Content-Type': 'multipart/form-data' } : {},
  });
  return res.data;
}

export async function updateStudent(id, formData) {
  const isFormData = formData instanceof FormData;
  const res = await api.put(`/students/${id}`, formData, {
    headers: isFormData ? { 'Content-Type': 'multipart/form-data' } : {},
  });
  return res.data;
}

export async function updateStudentStatus(id, { status, reason }) {
  const res = await api.patch(`/students/${id}/status`, { status, reason });
  return res.data;
}

export async function fetchPromotionCandidates(params) {
  const res = await api.get('/students/promotions/candidates', { params });
  return res.data;
}

export async function submitPromotions(payload) {
  const res = await api.post('/students/promotions', payload);
  return res.data;
}

export async function fetchReadmissionDetails(studentId, acdmYear) {
  const res = await api.get('/students/readmission/details', {
    params: { student_id: studentId, acdm_year: acdmYear },
  });
  return res.data;
}

export async function submitReadmission(payload) {
  const res = await api.post('/students/readmission', payload);
  return res.data;
}

export async function fetchLcRecords(params) {
  const res = await api.get('/students/lc/records', { params });
  return res.data;
}

export async function issueLeavingCertificate(id, payload) {
  const res = await api.post(`/students/${id}/issue-lc`, payload);
  return res.data;
}
