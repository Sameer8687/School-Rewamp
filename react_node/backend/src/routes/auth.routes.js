/**
 * Auth routes — maps to CI3 URLs exactly.
 *
 * POST  /api/auth/admin-login          → Login::validate()
 * POST  /api/auth/teacher-login        → Teacher_login::validate()
 * POST  /api/auth/student-login        → Student_login::validate()
 * GET   /api/auth/student-token-auth   → Student_login::index(?auth=true)
 * GET   /api/auth/me                   → session userdata reads
 * POST  /api/auth/logout               → session->sess_destroy()
 * GET   /api/auth/academic-years       → Student_login::index() dropdowns
 * POST  /api/auth/teacher-classes      → Teacher_login::getAssignedClasses()
 */
import { Router } from 'express';
import { authenticate } from '../middleware/auth.middleware.js';
import {
  adminLogin,
  teacherLogin,
  studentLogin,
  studentTokenLogin,
  getMe,
  logout,
  getAcademicYears,
  getTeacherClasses,
  changePassword,
} from '../controllers/auth.controller.js';

const router = Router();

// Public routes
router.post('/login', adminLogin);
router.post('/admin-login', adminLogin);
router.post('/teacher-login', teacherLogin);
router.post('/student-login', studentLogin);
router.get('/student-token-auth', studentTokenLogin);
router.get('/academic-years', getAcademicYears);
router.post('/teacher-classes', getTeacherClasses);

// Protected routes
router.get('/me', authenticate, getMe);
router.post('/logout', authenticate, logout);
router.post('/change-password', authenticate, changePassword);

export default router;
