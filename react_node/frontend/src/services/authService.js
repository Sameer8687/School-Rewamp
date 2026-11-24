/**
 * Auth & Dashboard API service functions.
 * Direct mapping to Express backend routes (which 1:1 port CI3 controllers).
 */
import api from './api.js';

// ─────────────────────────────────────────────
// Dedicated Login Endpoints
// ─────────────────────────────────────────────

/** Admin Login → POST /api/auth/admin-login */
export async function loginAdmin(credentials) {
  const { data } = await api.post('/auth/admin-login', credentials);
  return data;
}

/** Teacher Login → POST /api/auth/teacher-login */
export async function loginTeacher(credentials) {
  const { data } = await api.post('/auth/teacher-login', credentials);
  return data;
}

/** Student Login → POST /api/auth/student-login */
export async function loginStudent(credentials) {
  const { data } = await api.post('/auth/student-login', credentials);
  return data;
}

/** Student Token URL Login → GET /api/auth/student-token-auth */
export async function loginStudentWithToken(studentId, token) {
  const { data } = await api.get('/auth/student-token-auth', {
    params: { 'student-id': studentId, token },
  });
  return data;
}

/** Backward compatible / fallback login */
export async function loginUser(payload) {
  const { data } = await api.post('/auth/login', payload);
  return data;
}

/** GET /api/auth/me */
export async function getMe() {
  const { data } = await api.get('/auth/me');
  return data;
}

/** POST /api/auth/logout */
export async function logoutUser() {
  const { data } = await api.post('/auth/logout');
  return data;
}

/** GET /api/auth/academic-years */
export async function fetchAcademicYears() {
  const { data } = await api.get('/auth/academic-years');
  return data;
}

/** POST /api/auth/teacher-classes */
export async function fetchTeacherClasses(teacherId) {
  const { data } = await api.post('/auth/teacher-classes', { teacherId });
  return data;
}

// ─────────────────────────────────────────────
// Dashboard Endpoints
// ─────────────────────────────────────────────

/** GET /api/dashboard/stats */
export async function getDashboardStats(params = {}) {
  const { data } = await api.get('/dashboard/stats', { params });
  return data;
}

/** POST /api/dashboard/set-academic-year */
export async function updateAcademicYear(academic_year) {
  const { data } = await api.post('/dashboard/set-academic-year', { academic_year });
  return data;
}

/** GET /api/dashboard/student-fees-search */
export async function searchStudentFees(studentId) {
  const { data } = await api.get('/dashboard/student-fees-search', {
    params: { 'student-id': studentId },
  });
  return data;
}

// ─────────────────────────────────────────────
// Task Manager Endpoints
// ─────────────────────────────────────────────

/** GET /api/dashboard/tasks */
export async function fetchTasks(filter = {}) {
  const { data } = await api.get('/dashboard/tasks', { params: filter });
  return data;
}

/** POST /api/dashboard/tasks */
export async function createTask(taskData) {
  const { data } = await api.post('/dashboard/tasks', taskData);
  return data;
}

/** PATCH /api/dashboard/tasks/:id/status */
export async function updateTaskStatus(id, status) {
  const { data } = await api.patch(`/dashboard/tasks/${id}/status`, { status });
  return data;
}

/** DELETE /api/dashboard/tasks/:id */
export async function deleteTask(id) {
  const { data } = await api.delete(`/dashboard/tasks/${id}`);
  return data;
}
