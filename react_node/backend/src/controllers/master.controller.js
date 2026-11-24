/**
 * Master Controller — Request handlers for ERP Master Forms & Configurations
 */
import MasterModel from '../models/Master.model.js';

// ─── 1. Academic Years ───
export const getAcademicYears = async (_req, res) => {
  try {
    const list = await MasterModel.getAcademicYears();
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getAcademicYears error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createAcademicYear = async (req, res) => {
  try {
    const year = req.body.year || req.body.academic_year || req.body.name || req.body.stvacdm_year;
    let start = req.body.start || req.body.start_date || req.body.startDate;
    let end = req.body.end || req.body.end_date || req.body.endDate;
    const notes = req.body.notes || '';

    if (!start && year) {
      const match = year.match(/^(\d{4})/);
      const startYr = match ? match[1] : '2026';
      start = `${startYr}-06-01`;
    }
    if (!end && year) {
      const match = year.match(/-(\d{2,4})$/);
      let endYr = '2027';
      if (match) {
        endYr = match[1].length === 2 ? `20${match[1]}` : match[1];
      }
      end = `${endYr}-04-30`;
    }

    if (!start || !end) {
      return res.status(400).json({ success: false, message: 'Start and end dates are required' });
    }
    const result = await MasterModel.createAcademicYear({
      start,
      end,
      year: year || `${new Date().getFullYear()}-${new Date().getFullYear() + 1}`,
      notes,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, message: 'Academic Year created successfully', data: result });
  } catch (error) {
    console.error('createAcademicYear error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateAcademicYear = async (req, res) => {
  try {
    const { id } = req.params;
    const { start, end, year, notes } = req.body;
    const result = await MasterModel.updateAcademicYear(Number(id), { start, end, year, notes });
    res.json({ success: true, message: 'Academic Year updated successfully', data: result });
  } catch (error) {
    console.error('updateAcademicYear error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const setActiveAcademicYear = async (req, res) => {
  try {
    const { id } = req.params;
    const result = await MasterModel.setActiveAcademicYear(Number(id));
    res.json({ success: true, message: 'Active academic session updated', data: result });
  } catch (error) {
    console.error('setActiveAcademicYear error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── 2. Subjects Master ───
export const getSubjects = async (req, res) => {
  try {
    const { standard, academicYear } = req.query;
    const list = await MasterModel.getSubjects({
      standard,
      academicYear: academicYear ? Number(academicYear) : null,
    });
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getSubjects error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createSubject = async (req, res) => {
  try {
    const name = req.body.name || req.body.subject_name;
    const standard = req.body.standard || req.body.class || 'All';
    const academicYear = req.body.academicYear || req.body.academic_year || 9;
    if (!name) {
      return res.status(400).json({ success: false, message: 'Subject name is required' });
    }
    const result = await MasterModel.createSubject({
      name,
      standard,
      academicYear: academicYear ? Number(academicYear) : null,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, message: 'Subject created successfully', data: result });
  } catch (error) {
    console.error('createSubject error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateSubject = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, standard, academicYear } = req.body;
    const result = await MasterModel.updateSubject(Number(id), {
      name,
      standard,
      academicYear: academicYear ? Number(academicYear) : null,
    });
    res.json({ success: true, message: 'Subject updated successfully', data: result });
  } catch (error) {
    console.error('updateSubject error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteSubject = async (req, res) => {
  try {
    const { id } = req.params;
    const success = await MasterModel.deleteSubject(Number(id));
    if (!success) {
      return res.status(404).json({ success: false, message: 'Subject not found' });
    }
    res.json({ success: true, message: 'Subject deleted successfully' });
  } catch (error) {
    console.error('deleteSubject error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── 3. School Profile Master ───
export const getSchoolProfile = async (_req, res) => {
  try {
    const profile = await MasterModel.getSchoolProfile();
    res.json({ success: true, data: profile });
  } catch (error) {
    console.error('getSchoolProfile error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateSchoolProfile = async (req, res) => {
  try {
    const name = req.body.name || req.body.school_name;
    const { code, city, state, address, email, phone } = req.body;
    if (!name) {
      return res.status(400).json({ success: false, message: 'School name is required' });
    }
    const result = await MasterModel.updateSchoolProfile({
      code,
      name,
      city,
      state,
      address,
      email,
      phone,
      addedBy: req.user?.username || 'admin',
    });
    res.json({ success: true, message: 'School profile updated successfully', data: result });
  } catch (error) {
    console.error('updateSchoolProfile error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── 4. Web App Visibility Controls ───
export const getVisibilitySettings = async (_req, res) => {
  try {
    const settings = await MasterModel.getVisibilitySettings();
    res.json({ success: true, data: settings });
  } catch (error) {
    console.error('getVisibilitySettings error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateVisibilitySettings = async (req, res) => {
  try {
    const { student_profile, marks_visibility, attendance_visibility } = req.body;
    const result = await MasterModel.updateVisibilitySettings({
      student_profile: student_profile || 'Y',
      marks_visibility: marks_visibility || 'Y',
      attendance_visibility: attendance_visibility || 'Y',
      user: req.user?.username || 'admin',
    });
    res.json({ success: true, message: 'Visibility settings saved successfully', data: result });
  } catch (error) {
    console.error('updateVisibilitySettings error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── 5. User Access & Form Allocations ───
export const getStaffUsers = async (_req, res) => {
  try {
    const users = await MasterModel.getAllStaffUsers();
    res.json({ success: true, data: users });
  } catch (error) {
    console.error('getStaffUsers error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getModulesAndForms = async (_req, res) => {
  try {
    const modules = await MasterModel.getModulesAndForms();
    res.json({ success: true, data: modules });
  } catch (error) {
    console.error('getModulesAndForms error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getUserFormAccess = async (req, res) => {
  try {
    const { userId } = req.params;
    const access = await MasterModel.getUserFormAccess(userId);
    res.json({ success: true, data: access });
  } catch (error) {
    console.error('getUserFormAccess error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const saveUserFormAccess = async (req, res) => {
  try {
    const { userId, formUrls } = req.body;
    if (!userId || !Array.isArray(formUrls)) {
      return res.status(400).json({ success: false, message: 'User ID and formUrls array are required' });
    }
    const result = await MasterModel.saveUserFormAccess(userId, formUrls);
    res.json({ success: true, message: 'User access permissions saved successfully', data: result });
  } catch (error) {
    console.error('saveUserFormAccess error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Modules Management (CI3: Welcome/modules / Modules_Model) ───
export const getModules = async (_req, res) => {
  try {
    const modules = await MasterModel.getAllModules();
    res.json({ success: true, data: modules });
  } catch (error) {
    console.error('getModules error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getModuleById = async (req, res) => {
  try {
    const module = await MasterModel.getModuleById(req.params.id);
    if (!module) return res.status(404).json({ success: false, message: 'Module not found' });
    res.json({ success: true, data: module });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createModule = async (req, res) => {
  try {
    const { module_name, module_description, icon } = req.body;
    if (!module_name) return res.status(400).json({ success: false, message: 'Module Name is required' });
    const result = await MasterModel.createModule({ module_name, module_description, icon });
    res.json({ success: true, message: 'Module created successfully', data: result });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateModule = async (req, res) => {
  try {
    const { module_name, module_description, icon } = req.body;
    if (!module_name) return res.status(400).json({ success: false, message: 'Module Name is required' });
    const result = await MasterModel.updateModule(req.params.id, { module_name, module_description, icon });
    res.json({ success: true, message: 'Module updated successfully', data: result });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteModule = async (req, res) => {
  try {
    const ok = await MasterModel.deleteModule(req.params.id);
    res.json({ success: ok, message: ok ? 'Module and associated forms deleted' : 'Module not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Forms Management (CI3: Welcome/forms / Forms_Model) ───
export const getForms = async (_req, res) => {
  try {
    const forms = await MasterModel.getAllForms();
    res.json({ success: true, data: forms });
  } catch (error) {
    console.error('getForms error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getFormById = async (req, res) => {
  try {
    const form = await MasterModel.getFormById(req.params.id);
    if (!form) return res.status(404).json({ success: false, message: 'Form not found' });
    res.json({ success: true, data: form });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createForm = async (req, res) => {
  try {
    const { form_name, form_link, form_url, module_id, form_code, form_type } = req.body;
    if (!form_name || !module_id) {
      return res.status(400).json({ success: false, message: 'Form Name and Module are required' });
    }
    const result = await MasterModel.createForm({
      form_name,
      form_link: form_link || form_url,
      module_id,
      form_code,
      form_type,
    });
    res.json({ success: true, message: 'Form created successfully', data: result });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateForm = async (req, res) => {
  try {
    const { form_name, form_link, form_url, module_id, form_code, form_type } = req.body;
    if (!form_name || !module_id) {
      return res.status(400).json({ success: false, message: 'Form Name and Module are required' });
    }
    const result = await MasterModel.updateForm(req.params.id, {
      form_name,
      form_link: form_link || form_url,
      module_id,
      form_code,
      form_type,
    });
    res.json({ success: true, message: 'Form updated successfully', data: result });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteForm = async (req, res) => {
  try {
    const ok = await MasterModel.deleteForm(req.params.id);
    res.json({ success: ok, message: ok ? 'Form deleted successfully' : 'Form not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── User Access Reports (CI3: Welcome/user_report) ───
export const getSprUsers = async (_req, res) => {
  try {
    const users = await MasterModel.getSprUsers();
    res.json({ success: true, data: users });
  } catch (error) {
    console.error('getSprUsers error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getSprUserById = async (req, res) => {
  try {
    const user = await MasterModel.getSprUserById(req.params.id);
    if (!user) return res.status(404).json({ success: false, message: 'User not found' });
    res.json({ success: true, data: user });
  } catch (error) {
    console.error('getSprUserById error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getUsersWithAccess = async (_req, res) => {
  try {
    const users = await MasterModel.getUsersWithAccess();
    res.json({ success: true, data: users });
  } catch (error) {
    console.error('getUsersWithAccess error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getFormsByModuleId = async (req, res) => {
  try {
    const forms = await MasterModel.getFormsByModuleId(req.params.moduleId);
    res.json({ success: true, data: forms });
  } catch (error) {
    console.error('getFormsByModuleId error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getUserAccessMatrix = async (req, res) => {
  try {
    const access = await MasterModel.getUserAccessMatrix(req.params.userId);
    res.json({ success: true, data: access });
  } catch (error) {
    console.error('getUserAccessMatrix error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const saveUserAccessMatrix = async (req, res) => {
  try {
    const userId = req.body.userId || req.body.user_id;
    const rows = req.body.rows || (req.body.form_id ? [req.body] : []);
    const deletedRows = req.body.deletedRows || [];
    if (!userId) {
      return res.status(400).json({ success: false, message: 'User ID is required' });
    }
    const result = await MasterModel.saveUserAccessMatrix({ userId, rows, deletedRows });
    res.json({ success: true, message: 'User access permissions saved successfully', data: result });
  } catch (error) {
    console.error('saveUserAccessMatrix error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteUserAccessRows = async (req, res) => {
  try {
    const deletedRows = req.body;
    if (!Array.isArray(deletedRows) || deletedRows.length === 0) {
      return res.status(400).json({ success: false, message: 'No rows specified for deletion' });
    }
    await MasterModel.deleteUserAccessRows(deletedRows);
    res.json({ success: true, message: 'Rows deleted successfully' });
  } catch (error) {
    console.error('deleteUserAccessRows error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getUserReports = async (_req, res) => {
  try {
    const reports = await MasterModel.getUserReports();
    res.json({ success: true, data: reports });
  } catch (error) {
    console.error('getUserReports error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getUserAccessList = async (req, res) => {
  try {
    const list = await MasterModel.getUserAccessList(req.params.userId);
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getUserAccessList error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteUserAccess = async (req, res) => {
  try {
    await MasterModel.deleteUserAccess(req.params.userId);
    res.json({ success: true, message: 'User access revoked successfully' });
  } catch (error) {
    console.error('deleteUserAccess error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Shift Master ───
export const getShifts = async (_req, res) => {
  try {
    const list = await MasterModel.getShifts();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createShift = async (req, res) => {
  try {
    const result = await MasterModel.createShift({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'Shift created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateShift = async (req, res) => {
  try {
    const result = await MasterModel.updateShift(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'Shift updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteShift = async (req, res) => {
  try {
    const ok = await MasterModel.deleteShift(req.params.id);
    res.json({ success: ok, message: ok ? 'Shift deleted successfully' : 'Shift not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Position / Designation Master ───
export const getPositions = async (_req, res) => {
  try {
    const list = await MasterModel.getPositions();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createPosition = async (req, res) => {
  try {
    const result = await MasterModel.createPosition({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'Position created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updatePosition = async (req, res) => {
  try {
    const result = await MasterModel.updatePosition(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'Position updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deletePosition = async (req, res) => {
  try {
    const ok = await MasterModel.deletePosition(req.params.id);
    res.json({ success: ok, message: ok ? 'Position deleted successfully' : 'Position not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Grades Master ───
export const getGrades = async (_req, res) => {
  try {
    const list = await MasterModel.getGrades();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createGrade = async (req, res) => {
  try {
    const result = await MasterModel.createGrade({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'Grade created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateGrade = async (req, res) => {
  try {
    const result = await MasterModel.updateGrade(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'Grade updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteGrade = async (req, res) => {
  try {
    const ok = await MasterModel.deleteGrade(req.params.id);
    res.json({ success: ok, message: ok ? 'Grade deleted successfully' : 'Grade not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── State Master ───
export const getStates = async (_req, res) => {
  try {
    const list = await MasterModel.getStates();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createState = async (req, res) => {
  try {
    const result = await MasterModel.createState({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'State created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateState = async (req, res) => {
  try {
    const result = await MasterModel.updateState(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'State updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteState = async (req, res) => {
  try {
    const ok = await MasterModel.deleteState(req.params.id);
    res.json({ success: ok, message: ok ? 'State deleted successfully' : 'State not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── City Master ───
export const getCities = async (req, res) => {
  try {
    const list = await MasterModel.getCities(req.query.stateCode);
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createCity = async (req, res) => {
  try {
    const result = await MasterModel.createCity({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'City created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateCity = async (req, res) => {
  try {
    const result = await MasterModel.updateCity(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'City updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteCity = async (req, res) => {
  try {
    const ok = await MasterModel.deleteCity(req.params.id);
    res.json({ success: ok, message: ok ? 'City deleted successfully' : 'City not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Hold Reasons Master ───
export const getHoldReasons = async (_req, res) => {
  try {
    const list = await MasterModel.getHoldReasons();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createHoldReason = async (req, res) => {
  try {
    const result = await MasterModel.createHoldReason({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'Hold reason created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateHoldReason = async (req, res) => {
  try {
    const result = await MasterModel.updateHoldReason(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'Hold reason updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteHoldReason = async (req, res) => {
  try {
    const ok = await MasterModel.deleteHoldReason(req.params.id);
    res.json({ success: ok, message: ok ? 'Hold reason deleted successfully' : 'Hold reason not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── University Master ───
export const getUniversities = async (_req, res) => {
  try {
    const list = await MasterModel.getUniversities();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createUniversity = async (req, res) => {
  try {
    const result = await MasterModel.createUniversity({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'University created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateUniversity = async (req, res) => {
  try {
    const result = await MasterModel.updateUniversity(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'University updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteUniversity = async (req, res) => {
  try {
    const ok = await MasterModel.deleteUniversity(req.params.id);
    res.json({ success: ok, message: ok ? 'University deleted successfully' : 'University not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Major Master ───
export const getMajors = async (_req, res) => {
  try {
    const list = await MasterModel.getMajors();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createMajor = async (req, res) => {
  try {
    const result = await MasterModel.createMajor({
      ...req.body,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, data: result, message: 'Major created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateMajor = async (req, res) => {
  try {
    const result = await MasterModel.updateMajor(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'Major updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteMajor = async (req, res) => {
  try {
    const ok = await MasterModel.deleteMajor(req.params.id);
    res.json({ success: ok, message: ok ? 'Major deleted successfully' : 'Major not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── College Master (stvcoll) ───
export const getColleges = async (_req, res) => {
  try {
    const list = await MasterModel.getColleges();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createCollege = async (req, res) => {
  try {
    const result = await MasterModel.createCollege(req.body, req.user?.username || 'admin');
    res.status(201).json({ success: true, data: result, message: 'College created successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateCollege = async (req, res) => {
  try {
    const result = await MasterModel.updateCollege(req.params.id, req.body);
    res.json({ success: true, data: result, message: 'College updated successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteCollege = async (req, res) => {
  try {
    const ok = await MasterModel.deleteCollege(req.params.id);
    res.json({ success: ok, message: ok ? 'College deleted successfully' : 'College not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Student Active Holds (sprhold) ───
export const getStudentHolds = async (_req, res) => {
  try {
    const list = await MasterModel.getStudentHolds();
    res.json({ success: true, data: list });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createStudentHold = async (req, res) => {
  try {
    const result = await MasterModel.createStudentHold(req.body, req.user?.username || 'admin');
    res.status(201).json({ success: true, data: result, message: 'Hold applied to student successfully' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteStudentHold = async (req, res) => {
  try {
    const ok = await MasterModel.deleteStudentHold(req.params.id);
    res.json({ success: ok, message: ok ? 'Student hold released successfully' : 'Record not found' });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── Student Class / Division Update (CI3: MasterForm/studentStdDiv) ───
export const getStdDivDataByID = async (req, res) => {
  try {
    const studentId = req.query.id || req.query.student_id || req.query.studentId || req.params.id;
    if (!studentId) {
      return res.status(400).json({ success: false, message: 'Student ID is required' });
    }
    const data = await MasterModel.getStdDivDataByID(studentId);
    res.json({ success: true, data });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateClassDetails = async (req, res) => {
  try {
    const {
      STVCLAS_student_id,
      STVCLAS_code,
      STVCLAS_standard,
      STVCLAS_class,
      STVCLAS_acdm,
      STVCLAS_acdm_old,
      old_standard,
      old_class,
    } = req.body;

    const ok = await MasterModel.updateClassDetails({
      student_id: STVCLAS_student_id,
      code: STVCLAS_code,
      standard: STVCLAS_standard,
      class: STVCLAS_class,
      acdm: STVCLAS_acdm,
      old_acdm: STVCLAS_acdm_old,
      old_standard,
      old_class,
    });

    res.json({
      status: ok,
      success: ok,
      message: ok ? 'Student updated successfully.' : 'Update failed or no changes detected.',
    });
  } catch (error) {
    res.status(500).json({ success: false, message: error.message });
  }
};


