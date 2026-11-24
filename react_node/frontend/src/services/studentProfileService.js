import api from './api.js';

/**
 * Student Profile & Portal Service
 */
export async function fetchStudentPortalProfile(studentId = null) {
  const url = studentId ? `/students/portal-profile/${studentId}` : '/students/portal-profile';
  const res = await api.get(url);
  return res.data;
}

export default {
  fetchStudentPortalProfile,
};
