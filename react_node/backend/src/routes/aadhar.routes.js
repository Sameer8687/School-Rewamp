import express from 'express';
import {
  getAadharReport,
  getStudentAadhar,
  saveStudentAadhar,
  getPublicStudentAadharInfo,
  savePublicStudentAadhar,
  validatePublicStudent,
} from '../controllers/aadhar.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

// ─── Public Unauthenticated Routes (CI3: public/Student.php for SMS/WhatsApp parent links) ───
router.get('/public/student-info', getPublicStudentAadharInfo);
router.post('/public/save', savePublicStudentAadhar);
router.post('/public/validate', validatePublicStudent);

// ─── Protected Routes (Requires Admin / Teacher Auth) ───
router.use(authenticate);

// Aadhar Report with Class/Div filtering
router.get('/report', getAadharReport);

// Individual Student Aadhar Details
router.get('/student/:studentId', getStudentAadhar);
router.post('/student/:studentId', saveStudentAadhar);
router.post('/save', saveStudentAadhar);

export default router;

