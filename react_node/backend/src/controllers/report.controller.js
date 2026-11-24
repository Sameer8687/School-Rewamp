import ReportModel from '../models/Report.model.js';
import { success, failure } from '../utils/helpers.js';

export async function getDashboardKPIs(req, res) {
  try {
    const academicYear = req.query.academicYear ? Number(req.query.academicYear) : null;
    const data = await ReportModel.getReportsKPIs(academicYear);
    return success(res, { kpis: data });
  } catch (error) {
    console.error('Error in getDashboardKPIs:', error);
    return failure(res, error.message || 'Failed to fetch report KPIs', 500);
  }
}

export async function getStudentReport(req, res) {
  try {
    const data = await ReportModel.getStudentReports(req.query);
    return success(res, data);
  } catch (error) {
    console.error('Error in getStudentReport:', error);
    return failure(res, error.message || 'Failed to fetch student report', 500);
  }
}

export async function getFeesReport(req, res) {
  try {
    const data = await ReportModel.getFeesReports(req.query);
    return success(res, data);
  } catch (error) {
    console.error('Error in getFeesReport:', error);
    return failure(res, error.message || 'Failed to fetch fees report', 500);
  }
}

export async function getAttendanceReport(req, res) {
  try {
    const data = await ReportModel.getAttendanceReports(req.query);
    return success(res, data);
  } catch (error) {
    console.error('Error in getAttendanceReport:', error);
    return failure(res, error.message || 'Failed to fetch attendance report', 500);
  }
}

export async function getCertificatesReport(req, res) {
  try {
    const data = await ReportModel.getCertificatesReports(req.query);
    return success(res, data);
  } catch (error) {
    console.error('Error in getCertificatesReport:', error);
    return failure(res, error.message || 'Failed to fetch certificates report', 500);
  }
}

export async function getTeacherActivityReport(req, res) {
  try {
    const data = await ReportModel.getTeacherActivityReports(req.query);
    return success(res, data);
  } catch (error) {
    console.error('Error in getTeacherActivityReport:', error);
    return failure(res, error.message || 'Failed to fetch teacher activity report', 500);
  }
}

export async function getAuditReport(req, res) {
  try {
    const data = await ReportModel.getAuditReports(req.query);
    return success(res, data);
  } catch (error) {
    console.error('Error in getAuditReport:', error);
    return failure(res, error.message || 'Failed to fetch audit report', 500);
  }
}

export async function updateLcRequestStatus(req, res) {
  try {
    const { requestId, status } = req.body;
    if (!requestId || !status) {
      return failure(res, 'Request ID and status are required', 400);
    }
    const userId = req.user?.id || 1;
    const ok = await ReportModel.updateLcRequestStatus(requestId, status, userId);
    return success(res, { updated: ok, message: 'LC request status updated successfully.' });
  } catch (error) {
    console.error('Error in updateLcRequestStatus:', error);
    return failure(res, error.message || 'Failed to update LC request', 500);
  }
}
