import express from 'express';
import {
  getVisitingRecords,
  createVisitingRecord,
  deleteVisitingRecord,
} from '../controllers/visiting.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/', getVisitingRecords);
router.get('/visitors', getVisitingRecords);
router.post('/', createVisitingRecord);
router.post('/visitors', createVisitingRecord);
router.delete('/:id', deleteVisitingRecord);

export default router;
