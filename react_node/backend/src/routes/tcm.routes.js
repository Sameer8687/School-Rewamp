/**
 * TCM Routes — Express routing for Teacher Calling Management
 */
import express from 'express';
import {
  getTcmRecords,
  getTeachers,
  searchStudents,
  getStudentDetails,
  createTcmRecord,
  deleteTcmRecord
} from '../controllers/tcm.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/', getTcmRecords);
router.get('/call-logs', getTcmRecords);
router.get('/teachers', getTeachers);
router.get('/search-students', searchStudents);
router.get('/student/:id', getStudentDetails);
router.post('/', createTcmRecord);
router.post('/call-logs', createTcmRecord);
router.delete('/:id', deleteTcmRecord);

export default router;

