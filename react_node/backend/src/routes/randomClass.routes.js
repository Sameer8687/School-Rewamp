/**
 * RandomClass Routes — Express routing for random class allocation
 */
import express from 'express';
import {
  getOptions,
  getStudentsByClass,
  saveAssignments
} from '../controllers/randomClass.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/options', getOptions);
router.get('/students', getStudentsByClass);
router.post('/assign', saveAssignments);

export default router;

