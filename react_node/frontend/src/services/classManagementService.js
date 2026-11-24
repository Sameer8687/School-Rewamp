import api from './api';

const classManagementService = {
  getOptions: async () => {
    const response = await api.get('/class-management/options');
    return response.data;
  },

  getUnassignedStudents: async (academicYear) => {
    const response = await api.get('/class-management/unassigned', {
      params: academicYear ? { academic_year: academicYear } : {},
    });
    return response.data;
  },

  assignClass: async (payload) => {
    const response = await api.post('/class-management/assign-class', payload);
    return response.data;
  },

  getClassRoster: async (params) => {
    const response = await api.get('/class-management/roster', { params });
    return response.data;
  },

  updateClassAssignment: async (id, payload) => {
    const response = await api.put(`/class-management/roster/${id}`, payload);
    return response.data;
  },

  getSubjectTeachers: async (academicYear) => {
    const response = await api.get('/class-management/subject-teachers', {
      params: academicYear ? { academic_year: academicYear } : {},
    });
    return response.data;
  },

  createSubjectTeacher: async (payload) => {
    const response = await api.post('/class-management/subject-teachers', payload);
    return response.data;
  },

  updateSubjectTeacher: async (id, payload) => {
    const response = await api.put(`/class-management/subject-teachers/${id}`, payload);
    return response.data;
  },

  deleteSubjectTeacher: async (id) => {
    const response = await api.delete(`/class-management/subject-teachers/${id}`);
    return response.data;
  },
};

export default classManagementService;

