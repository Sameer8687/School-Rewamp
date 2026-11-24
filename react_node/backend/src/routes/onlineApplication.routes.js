import express from 'express';
import {
  listApplications,
  getApplicationDetails,
  getJuniorCollegeDetails,
  submitJuniorCollege,
  updateStatus,
  deleteApplication,
} from '../controllers/onlineApplication.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

// ─── Public Unauthenticated Routes (CI3: Forms/add_college_form) ───
router.post('/public/submit-college', submitJuniorCollege);
router.post('/public/submit-jr-college', submitJuniorCollege);

// ─── Protected Routes (Requires Auth) ───
router.use(authenticate);

router.get('/', listApplications);
router.get('/junior-college/:id', getJuniorCollegeDetails);
router.get('/:id', getApplicationDetails);
router.put('/:id/status', updateStatus);
router.delete('/:id', deleteApplication);

export default router;

