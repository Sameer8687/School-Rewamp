import express from 'express';
import {
  getDashboardKPIs,
  getStudentReport,
  getFeesReport,
  getAttendanceReport,
  getCertificatesReport,
  getTeacherActivityReport,
  getAuditReport,
  updateLcRequestStatus,
} from '../controllers/report.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

// ── Top KPIs & Overview ──
router.get('/kpis', getDashboardKPIs);

// ── Student & Admission Reports ──
router.get('/students', getStudentReport);

// ── Financial & Fees Reports ──
router.get('/fees', getFeesReport);

// ── Attendance & Absent Reports ──
router.get('/attendance', getAttendanceReport);

// ── Certificates (LC, Bonafide) Reports ──
router.get('/certificates', getCertificatesReport);
router.post('/lc-request/update', updateLcRequestStatus);

// ── Teacher & Academic Activity Reports ──
router.get('/teacher-activity', getTeacherActivityReport);

// ── Audit & Login Tracking Logs ──
router.get('/audit', getAuditReport);

export default router;
