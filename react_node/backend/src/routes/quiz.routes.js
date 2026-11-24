/**
 * Quiz Routes — Express routing for Quizzes and Assessment Reviews
 */
import express from 'express';
import {
  getQuizzes,
  getSubmissions,
  getQuizReview
} from '../controllers/quiz.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/list', getQuizzes);
router.get('/submissions', getSubmissions);
router.get('/review/:quizId/:studentId', getQuizReview);

export default router;

