import express from 'express';
import { getOptions, getActivityReport, getLoginTracking } from '../controllers/teacherActivity.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/', getActivityReport);
router.get('/options', getOptions);
router.get('/report', getActivityReport);
router.get('/login-tracking', getLoginTracking);

export default router;

