import express from 'express';
import {
  getTeachers,
  getNextTeacherId,
  getTeacherById,
  createTeacher,
  updateTeacher,
  updateTeacherStatus,
  getAssignedClassTeachers,
  assignClassTeacher,
  getTimetable,
  saveTimetable,
  getLessons,
  saveLesson,
  deleteLesson
} from '../controllers/teacher.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';
import { getMasterOptions } from '../models/Student.model.js';

const router = express.Router();

router.use(authenticate);

// Options & Standards
router.get('/standards', async (_req, res) => {
  try {
    const opts = await getMasterOptions();
    res.json({ success: true, standards: opts.classes, divisions: opts.divisions });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});
router.get('/options', async (_req, res) => {
  try {
    const opts = await getMasterOptions();
    res.json({ success: true, standards: opts.classes, divisions: opts.divisions });
  } catch (err) {
    res.status(500).json({ success: false, message: err.message });
  }
});

// Teacher directory & CRUD
router.get('/', getTeachers);
router.get('/next-id', getNextTeacherId);
router.get('/profile/:id', getTeacherById);
router.post('/register', createTeacher);
router.put('/update/:id', updateTeacher);
router.patch('/status/:id', updateTeacherStatus);

// Class Teacher Assignment
router.get('/assignments', getAssignedClassTeachers);
router.post('/assign-class', assignClassTeacher);
router.post('/assign-class-teacher', assignClassTeacher);

// Timetable
router.get('/timetable', getTimetable);
router.post('/timetable', saveTimetable);

// Lesson Management
router.get('/lessons', getLessons);
router.post('/lessons', saveLesson);
router.delete('/lessons/:id', deleteLesson);

export default router;
