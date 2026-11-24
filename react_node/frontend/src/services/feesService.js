import api from './api';

export const feesService = {
  // Search students for fees entry
  searchStudents: (params) => {
    return api.get('/fees/search-students', { params });
  },

  // Get student fee ledger
  getStudentLedger: (studentId, academicYear) => {
    return api.get(`/fees/ledger/${studentId}`, {
      params: { academicYear }
    });
  },

  // Collect fees
  collectFees: (data) => {
    return api.post('/fees/collect', data);
  },

  // Fees report
  getFeesReport: (params) => {
    return api.get('/fees/report', { params });
  },

  // Fees Comments & WhatsApp alert report (CI3: Report::Fees_comment)
  getFeesComments: (params) => {
    return api.get('/fees/comments', { params });
  },

  // All Previous Fees Report (CI3: Report::all_previous_fees_report)
  getAllPreviousFeesReport: () => {
    return api.get('/fees/all-previous');
  },

  // Last Year Fees Report (CI3: Report::last_year_fees_report)
  getLastYearFeesReport: (params) => {
    return api.get('/fees/last-year', { params });
  },

  // Daily & Datewise Fees Report (CI3: Report::daily_fees_report)
  getDailyFeesReport: (params) => {
    return api.get('/fees/daily', { params });
  },

  // Receipt details
  getReceipt: (studentId, academicYear) => {
    return api.get(`/fees/receipt/${studentId}`, {
      params: { academicYear }
    });
  },

  // Fee structure master
  getFeeStructure: (academicYear) => {
    return api.get('/fees/structure', {
      params: { academic_year: academicYear }
    });
  },

  saveFeeStructure: (data) => {
    return api.post('/fees/structure', data);
  },

  // Reconciliation
  getReconciliation: (fromDate, toDate) => {
    return api.get('/fees/reconciliation', {
      params: { from_date: fromDate, to_date: toDate }
    });
  },

  updateFeeDate: (data) => {
    return api.post('/fees/reconciliation/update-date', data);
  },

  // Bank Statement Upload & Audit (CI3: Fees_bank_upload.php)
  uploadBankStatement: (formData) => {
    return api.post('/fees/bank-upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },

  fetchBankTransactions: (params) => {
    return api.get('/fees/bank-transactions', { params });
  },

  fetchBankGroups: () => {
    return api.get('/fees/bank-groups');
  },

  fetchGroupDetails: (groupId) => {
    return api.get(`/fees/bank-groups/${groupId}`);
  },
};

export default feesService;
