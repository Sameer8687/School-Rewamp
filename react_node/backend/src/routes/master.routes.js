/**
 * Master Routes — Express routing for ERP Master Forms & Configurations
 */
import express from 'express';
import {
  getAcademicYears,
  createAcademicYear,
  updateAcademicYear,
  setActiveAcademicYear,
  getSubjects,
  createSubject,
  updateSubject,
  deleteSubject,
  getSchoolProfile,
  updateSchoolProfile,
  getVisibilitySettings,
  updateVisibilitySettings,
  getStaffUsers,
  getModulesAndForms,
  getUserFormAccess,
  saveUserFormAccess,
  getModules,
  getModuleById,
  createModule,
  updateModule,
  deleteModule,
  getForms,
  getFormById,
  createForm,
  updateForm,
  deleteForm,
  getUserReports,
  getUserAccessList,
  deleteUserAccess,
  getShifts,
  createShift,
  updateShift,
  deleteShift,
  getPositions,
  createPosition,
  updatePosition,
  deletePosition,
  getGrades,
  createGrade,
  updateGrade,
  deleteGrade,
  getStates,
  createState,
  updateState,
  deleteState,
  getCities,
  createCity,
  updateCity,
  deleteCity,
  getHoldReasons,
  createHoldReason,
  updateHoldReason,
  deleteHoldReason,
  getUniversities,
  createUniversity,
  updateUniversity,
  deleteUniversity,
  getMajors,
  createMajor,
  updateMajor,
  deleteMajor,
  getColleges,
  createCollege,
  updateCollege,
  deleteCollege,
  getStudentHolds,
  createStudentHold,
  deleteStudentHold,
  getStdDivDataByID,
  updateClassDetails,
  getSprUsers,
  getSprUserById,
  getUsersWithAccess,
  getFormsByModuleId,
  getUserAccessMatrix,
  saveUserAccessMatrix,
  deleteUserAccessRows,
} from '../controllers/master.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';
import { getMasterOptions } from '../models/Student.model.js';

const router = express.Router();

router.use(authenticate);

// ── Dropdowns / Lists ──
router.get('/standards', async (req, res) => {
  try {
    const opts = await getMasterOptions();
    res.json({ success: true, data: opts.classes, standards: opts.classes });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});
router.get('/divisions', async (req, res) => {
  try {
    const opts = await getMasterOptions();
    res.json({ success: true, data: opts.divisions, divisions: opts.divisions });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});
router.get('/classes', async (req, res) => {
  try {
    const opts = await getMasterOptions();
    res.json({ success: true, data: opts.classes, classes: opts.classes, divisions: opts.divisions });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});
router.get('/options', async (req, res) => {
  try {
    const opts = await getMasterOptions();
    res.json({ success: true, data: opts, options: opts, ...opts });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// ── Academic Years ──
router.get('/academic-years', getAcademicYears);
router.post('/academic-years', createAcademicYear);
router.put('/academic-years/:id', updateAcademicYear);
router.patch('/academic-years/:id/active', setActiveAcademicYear);

// ── Subjects ──
router.get('/subjects', getSubjects);
router.post('/subjects', createSubject);
router.put('/subjects/:id', updateSubject);
router.delete('/subjects/:id', deleteSubject);

// ── School Info ──
router.get('/school-profile', getSchoolProfile);
router.get('/school-info', getSchoolProfile);
router.post('/school-profile', updateSchoolProfile);
router.post('/school-info', updateSchoolProfile);

// ── Web App Visibility ──
router.get('/visibility', getVisibilitySettings);
router.post('/visibility', updateVisibilitySettings);

// ── User Access & Form Allocation ──
router.get('/users', getStaffUsers);
router.get('/sprusers', getSprUsers);
router.get('/sprusers/:id', getSprUserById);
router.get('/users-with-access', getUsersWithAccess);
router.get('/forms-by-module/:moduleId', getFormsByModuleId);
router.get('/user-access-matrix/:userId', getUserAccessMatrix);
router.post('/user-access-matrix', saveUserAccessMatrix);
router.post('/user-access-matrix/delete-rows', deleteUserAccessRows);
router.get('/modules-forms', getModulesAndForms);
router.get('/user-access/:userId', getUserFormAccess);
router.post('/user-access', saveUserFormAccess);

// ── Modules Management (CI3: Welcome/modules & Modules_Model) ──
router.get('/modules', getModules);
router.get('/modules/:id', getModuleById);
router.post('/modules', createModule);
router.put('/modules/:id', updateModule);
router.delete('/modules/:id', deleteModule);

// ── Forms Management (CI3: Welcome/forms & Forms_Model) ──
router.get('/forms', getForms);
router.get('/forms/:id', getFormById);
router.post('/forms', createForm);
router.put('/forms/:id', updateForm);
router.delete('/forms/:id', deleteForm);

// ── User Access Reports (CI3: Welcome/user_report) ──
router.get('/user-reports', getUserReports);
router.get('/user-reports/:userId', getUserAccessList);
router.delete('/user-reports/:userId', deleteUserAccess);

// ── Auxiliary Masters (CI3: MasterForm / Module 13) ──
// Shifts
router.get('/shifts', getShifts);
router.post('/shifts', createShift);
router.put('/shifts/:id', updateShift);
router.delete('/shifts/:id', deleteShift);

// Positions / Designations
router.get('/positions', getPositions);
router.post('/positions', createPosition);
router.put('/positions/:id', updatePosition);
router.delete('/positions/:id', deletePosition);

// Grades
router.get('/grades', getGrades);
router.post('/grades', createGrade);
router.put('/grades/:id', updateGrade);
router.delete('/grades/:id', deleteGrade);

// States
router.get('/states', getStates);
router.post('/states', createState);
router.put('/states/:id', updateState);
router.delete('/states/:id', deleteState);

// Cities
router.get('/cities', getCities);
router.post('/cities', createCity);
router.put('/cities/:id', updateCity);
router.delete('/cities/:id', deleteCity);

// Hold Reasons
router.get('/hold-reasons', getHoldReasons);
router.post('/hold-reasons', createHoldReason);
router.put('/hold-reasons/:id', updateHoldReason);
router.delete('/hold-reasons/:id', deleteHoldReason);

// Universities
router.get('/universities', getUniversities);
router.post('/universities', createUniversity);
router.put('/universities/:id', updateUniversity);
router.delete('/universities/:id', deleteUniversity);

// Majors
router.get('/majors', getMajors);
router.post('/majors', createMajor);
router.put('/majors/:id', updateMajor);
router.delete('/majors/:id', deleteMajor);

// Colleges (CI3: stvcolg / stvcoll)
router.get('/colleges', getColleges);
router.post('/colleges', createCollege);
router.put('/colleges/:id', updateCollege);
router.delete('/colleges/:id', deleteCollege);

// Student Active Holds (CI3: sprhold)
router.get('/student-holds', getStudentHolds);
router.post('/student-holds', createStudentHold);
router.delete('/student-holds/:id', deleteStudentHold);

// Student Class / Division Update (CI3: MasterForm/studentStdDiv)
router.get('/student-std-div', getStdDivDataByID);
router.post('/student-std-div/update', updateClassDetails);
router.get('/MasterForm/getStdDivDataByID', getStdDivDataByID);
router.post('/MasterForm/updateClassDetails', updateClassDetails);

export default router;

