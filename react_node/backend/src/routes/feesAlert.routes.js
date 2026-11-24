import express from 'express';
import {
  getFeesMessageStudents,
  getStudentFeesHistory,
  getAdmissionConfirmationAlerts,
} from '../controllers/feesAlert.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

// Fees message & broadcast query
router.get('/students', getFeesMessageStudents);

// Student ledger / history for alert modal
router.get('/student-history/:id', getStudentFeesHistory);

// Admission confirmation alert query
router.get('/admission-confirmation', getAdmissionConfirmationAlerts);

export default router;
