/**
 * ClassAnalytics Routes — Express routing for Class & Student Exam Performance Analytics
 */
import express from 'express';
import {
  getFilterOptions,
  getClassAnalytics,
  getStudentDeepDive
} from '../controllers/classAnalytics.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/options', getFilterOptions);
router.get('/', getClassAnalytics);
router.get('/overview', getClassAnalytics);
router.get('/student/:student_id', getStudentDeepDive);

export default router;

