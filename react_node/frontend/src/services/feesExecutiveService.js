import api from './api';

export const feesExecutiveService = {
  getExecutiveDashboard: (params) => {
    return api.get('/fees/executive-dashboard', { params });
  },
};

export default feesExecutiveService;
