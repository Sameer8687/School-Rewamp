import express from 'express';
import {
  getSubjects,
  getLessonNames,
  getLessons,
  updateStatus,
} from '../controllers/lessonApproval.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

// Root & filtered lesson submissions
router.get('/', getLessons);
router.get('/lessons', getLessons);

// Get available subjects for class & year
router.get('/subjects', getSubjects);

// Get distinct lesson titles/chapters
router.get('/lesson-names', getLessonNames);

// Get filtered lesson submissions
router.get('/lessons', getLessons);

// Update approval status of a specific component
router.post('/update-status', updateStatus);

export default router;
