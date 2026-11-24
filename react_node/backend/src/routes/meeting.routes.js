import express from 'express';
import { getMeetings, createMeeting, deleteMeeting } from '../controllers/meeting.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/', getMeetings);
router.post('/', createMeeting);
router.delete('/:id', deleteMeeting);

export default router;
