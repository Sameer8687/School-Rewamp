import OnlineApplicationModel from '../models/OnlineApplication.model.js';
import { success, failure } from '../utils/helpers.js';

export async function listApplications(req, res) {
  try {
    const type = req.query.type || 'all'; // 'school', 'junior_college', 'all'
    let schoolApps = [];
    let jrCollegeApps = [];

    if (type === 'all' || type === 'school') {
      try {
        schoolApps = await OnlineApplicationModel.getSchoolApplications();
      } catch (e) {
        console.warn('ext_application_header not queryable:', e.message);
      }
    }

    if (type === 'all' || type === 'junior_college') {
      try {
        jrCollegeApps = await OnlineApplicationModel.getJuniorCollegeApplications();
      } catch (e) {
        console.warn('clg_stu_det not queryable:', e.message);
      }
    }

    return success(res, {
      schoolApplications: schoolApps,
      juniorCollegeApplications: jrCollegeApps,
      totalCount: schoolApps.length + jrCollegeApps.length,
    });
  } catch (error) {
    console.error('Error in listApplications:', error);
    return failure(res, error.message || 'Failed to fetch online applications', 500);
  }
}

export async function getApplicationDetails(req, res) {
  try {
    const { id } = req.params;
    const type = req.query.type || 'school';

    if (type === 'junior_college') {
      const details = await OnlineApplicationModel.getJuniorCollegeApplicationById(id);
      if (!details) {
        return failure(res, 'Junior college application not found', 404);
      }
      return success(res, details);
    }

    const details = await OnlineApplicationModel.getSchoolApplicationById(id);
    if (!details) {
      return failure(res, 'Application not found', 404);
    }
    return success(res, details);
  } catch (error) {
    console.error('Error in getApplicationDetails:', error);
    return failure(res, error.message || 'Failed to fetch application details', 500);
  }
}

export async function submitJuniorCollege(req, res) {
  try {
    const result = await OnlineApplicationModel.submitJuniorCollegeApplication(req.body);
    return success(res, {
      message: 'Admission application submitted successfully!',
      applicationId: result.applicationId,
      id: result.id,
    });
  } catch (error) {
    console.error('Error in submitJuniorCollege:', error);
    return failure(res, error.message || 'Failed to submit admission application', 500);
  }
}

export async function getJuniorCollegeDetails(req, res) {
  try {
    const { id } = req.params;
    const details = await OnlineApplicationModel.getJuniorCollegeApplicationById(id);
    if (!details) {
      return failure(res, 'Junior college application not found', 404);
    }
    return success(res, details);
  } catch (error) {
    console.error('Error in getJuniorCollegeDetails:', error);
    return failure(res, error.message || 'Failed to fetch junior college application', 500);
  }
}

export async function updateStatus(req, res) {
  try {
    const { id } = req.params;
    const { status, comments } = req.body;
    if (!status) {
      return failure(res, 'Status is required', 400);
    }
    const ok = await OnlineApplicationModel.updateSchoolApplicationStatus(id, status, comments || '');
    return success(res, { updated: ok, message: 'Application status updated.' });
  } catch (error) {
    console.error('Error in updateStatus:', error);
    return failure(res, error.message || 'Failed to update status', 500);
  }
}

export async function deleteApplication(req, res) {
  try {
    const { id } = req.params;
    const type = req.query.type || 'school';

    if (type === 'junior_college') {
      await OnlineApplicationModel.deleteJuniorCollegeApplication(id);
    } else {
      await OnlineApplicationModel.deleteSchoolApplication(id);
    }

    return success(res, { message: 'Application deleted successfully.' });
  } catch (error) {
    console.error('Error in deleteApplication:', error);
    return failure(res, error.message || 'Failed to delete application', 500);
  }
}

