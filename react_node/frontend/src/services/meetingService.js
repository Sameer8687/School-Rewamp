import api from './api';

export const meetingService = {
  getMeetings: (academicYear) => {
    return api.get('/meetings', { params: { academicYear } });
  },

  createMeeting: (data) => {
    return api.post('/meetings', data);
  },

  deleteMeeting: (id) => {
    return api.delete(`/meetings/${id}`);
  },
};

export default meetingService;
