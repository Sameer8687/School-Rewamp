/**
 * ClassManagement Routes — Express routing for Class Creation, Roster, and Subject Teachers
 */
import express from 'express';
import {
  getOptions,
  getUnassignedStudents,
  assignStudentsToClass,
  getClassRoster,
  updateClassRosterRow,
  getSubjectTeachers,
  createSubjectTeacher,
  updateSubjectTeacher,
  deleteSubjectTeacher
} from '../controllers/classManagement.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/options', getOptions);
router.get('/classes', getOptions);
router.get('/unassigned', getUnassignedStudents);
router.post('/assign-class', assignStudentsToClass);
router.post('/assign', assignStudentsToClass);
router.get('/roster', getClassRoster);
router.put('/roster/:id', updateClassRosterRow);

router.get('/subject-teachers', getSubjectTeachers);
router.post('/subject-teachers', createSubjectTeacher);
router.put('/subject-teachers/:id', updateSubjectTeacher);
router.delete('/subject-teachers/:id', deleteSubjectTeacher);

export default router;

