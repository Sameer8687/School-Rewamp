import express from 'express';
import {
  getStudentsForAttendance,
  saveAttendance,
  getClassAttendance,
  getAllClassesOverview,
  getAbsenteeReport,
  getDailyAbsentDates
} from '../controllers/attendance.controller.js';
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

// Mark / Take Attendance
router.get('/students', getStudentsForAttendance);
router.post('/save', saveAttendance);

// View Class Attendance
router.get('/class-summary', getClassAttendance);

// School-wide daily overview
router.get('/school-overview', getAllClassesOverview);

// Absentee & Streaks Report (both GET and POST for flexibility and parity)
router.get('/absentee-report', getAbsenteeReport);
router.post('/absentee-report', getAbsenteeReport);
router.get('/absent-dates/:studentId', getDailyAbsentDates);
router.post('/absent-dates', getDailyAbsentDates);

// CI3 exact parity method routes
router.post('/get_absent_details', getAbsenteeReport);
router.post('/absent_student', getDailyAbsentDates);
router.get('/absent_student', getDailyAbsentDates);

export default router;
