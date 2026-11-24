import express from 'express';
import {
  getOptions,
  getClassStudents,
  getStudentProfileData,
} from '../controllers/classReport.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/', getClassStudents);
router.get('/options', getOptions);
router.get('/students', getClassStudents);
router.get('/student/:studentId', getStudentProfileData);

export default router;

