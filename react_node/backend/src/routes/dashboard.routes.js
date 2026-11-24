/**
 * Dashboard routes — all protected by JWT authentication.
 *
 * CI3 controllers: Welcome.php, Dashboard.php, Profile.php, Task.php
 */
import { Router } from 'express';
import { authenticate } from '../middleware/auth.middleware.js';
import {
  getDashboardStats,
  getTasks,
  createTask,
  updateTaskDetails,
  updateTaskStatus,
  deleteTask,
  searchStudentFees,
  setAcademicYear,
} from '../controllers/dashboard.controller.js';

const router = Router();

// Protect all dashboard routes
router.use(authenticate);

// Main dashboard stats aggregator (role-aware)
router.get('/stats', getDashboardStats);

// Set academic year (CI3: Welcome/setAcademicYear)
router.post('/set-academic-year', setAcademicYear);

// Instant Student Fee Lookup (mirrors Student/fetchReadmissionDetails)
router.get('/student-fees-search', searchStudentFees);

// Task Manager routes (mirrors Task.php)
router.get('/tasks', getTasks);
router.post('/tasks', createTask);
router.put('/tasks/:id', updateTaskDetails);
router.patch('/tasks/:id/status', updateTaskStatus);
router.delete('/tasks/:id', deleteTask);

export default router;
