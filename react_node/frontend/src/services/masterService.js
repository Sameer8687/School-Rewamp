/**
 * Master Service — API Client for Master Forms & System Configurations
 */
import api from './api';

export const masterService = {
  // Academic Years
  getAcademicYears: () => {
    return api.get('/master/academic-years');
  },

  createAcademicYear: (data) => {
    return api.post('/master/academic-years', data);
  },

  updateAcademicYear: (id, data) => {
    return api.put(`/master/academic-years/${id}`, data);
  },

  setActiveAcademicYear: (id) => {
    return api.patch(`/master/academic-years/${id}/active`);
  },

  // Subjects
  getSubjects: (params) => {
    return api.get('/master/subjects', { params });
  },

  createSubject: (data) => {
    return api.post('/master/subjects', data);
  },

  updateSubject: (id, data) => {
    return api.put(`/master/subjects/${id}`, data);
  },

  deleteSubject: (id) => {
    return api.delete(`/master/subjects/${id}`);
  },

  // School Profile
  getSchoolProfile: () => {
    return api.get('/master/school-profile');
  },

  updateSchoolProfile: (data) => {
    return api.post('/master/school-profile', data);
  },

  // Web App Visibility Settings
  getVisibilitySettings: () => {
    return api.get('/master/visibility');
  },

  updateVisibilitySettings: (data) => {
    return api.post('/master/visibility', data);
  },

  // User Form Access Allocations (CI3: spruser & user_access)
  getStaffUsers: () => {
    return api.get('/master/users');
  },

  getSprUsers: () => {
    return api.get('/master/sprusers');
  },

  getSprUserById: (id) => {
    return api.get(`/master/sprusers/${id}`);
  },

  getUsersWithAccess: () => {
    return api.get('/master/users-with-access');
  },

  getFormsByModule: (moduleId) => {
    return api.get(`/master/forms-by-module/${moduleId}`);
  },

  getUserAccessMatrix: (userId) => {
    return api.get(`/master/user-access-matrix/${userId}`);
  },

  saveUserAccessMatrix: (payload) => {
    return api.post('/master/user-access-matrix', payload);
  },

  deleteUserAccessRows: (deletedRows) => {
    return api.post('/master/user-access-matrix/delete-rows', deletedRows);
  },

  getModulesAndForms: () => {
    return api.get('/master/modules-forms');
  },

  getUserFormAccess: (userId) => {
    return api.get(`/master/user-access/${userId}`);
  },

  saveUserFormAccess: (data) => {
    return api.post('/master/user-access', data);
  },

  // Modules Management (CI3: Welcome/modules)
  getModules: () => {
    return api.get('/master/modules');
  },

  createModule: (data) => {
    return api.post('/master/modules', data);
  },

  updateModule: (id, data) => {
    return api.put(`/master/modules/${id}`, data);
  },

  deleteModule: (id) => {
    return api.delete(`/master/modules/${id}`);
  },

  // Forms Management (CI3: Welcome/forms)
  getForms: () => {
    return api.get('/master/forms');
  },

  createForm: (data) => {
    return api.post('/master/forms', data);
  },

  updateForm: (id, data) => {
    return api.put(`/master/forms/${id}`, data);
  },

  deleteForm: (id) => {
    return api.delete(`/master/forms/${id}`);
  },

  // User Reports (CI3: Welcome/user_report)
  getUserReports: () => {
    return api.get('/master/user-reports');
  },

  getUserAccessList: (userId) => {
    return api.get(`/master/user-reports/${userId}`);
  },

  deleteUserAccess: (userId) => {
    return api.delete(`/master/user-reports/${userId}`);
  },

  // Shifts (CI3: stvshif)
  getShifts: () => api.get('/master/shifts'),
  createShift: (data) => api.post('/master/shifts', data),
  updateShift: (id, data) => api.put(`/master/shifts/${id}`, data),
  deleteShift: (id) => api.delete(`/master/shifts/${id}`),

  // Positions / Designations (CI3: stvposn)
  getPositions: () => api.get('/master/positions'),
  createPosition: (data) => api.post('/master/positions', data),
  updatePosition: (id, data) => api.put(`/master/positions/${id}`, data),
  deletePosition: (id) => api.delete(`/master/positions/${id}`),

  // Grades (CI3: stvgrds)
  getGrades: () => api.get('/master/grades'),
  createGrade: (data) => api.post('/master/grades', data),
  updateGrade: (id, data) => api.put(`/master/grades/${id}`, data),
  deleteGrade: (id) => api.delete(`/master/grades/${id}`),

  // States (CI3: stvstts)
  getStates: () => api.get('/master/states'),
  createState: (data) => api.post('/master/states', data),
  updateState: (id, data) => api.put(`/master/states/${id}`, data),
  deleteState: (id) => api.delete(`/master/states/${id}`),

  // Cities (CI3: stvcity)
  getCities: (stateCode) => api.get('/master/cities', { params: { stateCode } }),
  createCity: (data) => api.post('/master/cities', data),
  updateCity: (id, data) => api.put(`/master/cities/${id}`, data),
  deleteCity: (id) => api.delete(`/master/cities/${id}`),

  // Hold Reasons (CI3: stvhold)
  getHoldReasons: () => api.get('/master/hold-reasons'),
  createHoldReason: (data) => api.post('/master/hold-reasons', data),
  updateHoldReason: (id, data) => api.put(`/master/hold-reasons/${id}`, data),
  deleteHoldReason: (id) => api.delete(`/master/hold-reasons/${id}`),

  // Universities (CI3: stvuniv)
  getUniversities: () => api.get('/master/universities'),
  createUniversity: (data) => api.post('/master/universities', data),
  updateUniversity: (id, data) => api.put(`/master/universities/${id}`, data),
  deleteUniversity: (id) => api.delete(`/master/universities/${id}`),

  // Majors (CI3: stvmajr)
  getMajors: () => api.get('/master/majors'),
  createMajor: (data) => api.post('/master/majors', data),
  updateMajor: (id, data) => api.put(`/master/majors/${id}`, data),
  deleteMajor: (id) => api.delete(`/master/majors/${id}`),

  // Colleges (CI3: stvcolg / stvcoll)
  getColleges: () => api.get('/master/colleges'),
  createCollege: (data) => api.post('/master/colleges', data),
  updateCollege: (id, data) => api.put(`/master/colleges/${id}`, data),
  deleteCollege: (id) => api.delete(`/master/colleges/${id}`),

  // Student Active Holds (CI3: sprhold)
  getStudentHolds: () => api.get('/master/student-holds'),
  createStudentHold: (data) => api.post('/master/student-holds', data),
  deleteStudentHold: (id) => api.delete(`/master/student-holds/${id}`),

  // Student Class / Division Update (CI3: MasterForm/studentStdDiv)
  getStdDivDataByID: (id) => api.get(`/master/student-std-div?id=${encodeURIComponent(id)}`),
  updateClassDetails: (data) => api.post('/master/student-std-div/update', data),
};

export default masterService;

