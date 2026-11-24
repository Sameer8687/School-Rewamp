/**
 * Exam Routes — Express routing for Olive English School Examination Module
 */
import express from 'express';
import {
  getExams,
  createExam,
  getSubjects,
  getExamSetup,
  saveExamSetup,
  getStudentMarks,
  saveStudentMarks,
  getHallTickets,
  getHallTicketById,
  saveHallTicket,
  deleteHallTicket,
  getHallTicketStudents,
  getStudentReportCard,
  getClassResults,
} from '../controllers/exam.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

// Apply auth middleware to all examination endpoints
router.use(authenticate);

// ─── 1. Exams & Subjects ───
router.get('/exams-list', getExams);
router.get('/masters', getExams);
router.post('/create-exam', createExam);
router.post('/masters', createExam);
router.get('/subjects', getSubjects);
router.get('/setup', getExamSetup);
router.post('/setup', saveExamSetup);

// ─── 2. Marks Entry ───
router.get('/marks', getStudentMarks);
router.post('/marks', saveStudentMarks);

// ─── 3. Hall Tickets ───
router.get('/hall-tickets', getHallTickets);
router.get('/hall-tickets/students', getHallTicketStudents);
router.get('/hall-tickets/:id', getHallTicketById);
router.post('/hall-tickets', saveHallTicket);
router.delete('/hall-tickets/:id', deleteHallTicket);

// ─── 4. Report Cards & Results ───
router.get('/report-card/:studentId', getStudentReportCard);
router.get('/class-results', getClassResults);

export default router;
