/**
 * Express application factory.
 * Registers middleware and mounts all routes.
 */
import express from 'express';
import cors from 'cors';
import cookieParser from 'cookie-parser';
import path from 'path';
import { errorHandler, notFound } from './src/middleware/error.middleware.js';
import authRoutes from './src/routes/auth.routes.js';
import dashboardRoutes from './src/routes/dashboard.routes.js';
import studentRoutes from './src/routes/student.routes.js';
import feesRoutes from './src/routes/fees.routes.js';
import attendanceRoutes from './src/routes/attendance.routes.js';
import teacherRoutes from './src/routes/teacher.routes.js';
import examRoutes from './src/routes/exam.routes.js';
import masterRoutes from './src/routes/master.routes.js';
import transportationRoutes from './src/routes/transportation.routes.js';
import communicationRoutes from './src/routes/communication.routes.js';
import reportRoutes from './src/routes/report.routes.js';
import onlineApplicationRoutes from './src/routes/onlineApplication.routes.js';
import notesRoutes from './src/routes/notes.routes.js';
import meetingRoutes from './src/routes/meeting.routes.js';
import taskRoutes from './src/routes/task.routes.js';
import visitingRoutes from './src/routes/visiting.routes.js';
import booksResultRoutes from './src/routes/booksResult.routes.js';
import feesAlertRoutes from './src/routes/feesAlert.routes.js';
import lessonApprovalRoutes from './src/routes/lessonApproval.routes.js';
import aadharRoutes from './src/routes/aadhar.routes.js';
import tcmRoutes from './src/routes/tcm.routes.js';
import randomClassRoutes from './src/routes/randomClass.routes.js';
import classAnalyticsRoutes from './src/routes/classAnalytics.routes.js';
import quizRoutes from './src/routes/quiz.routes.js';
import classManagementRoutes from './src/routes/classManagement.routes.js';
import studentExtrasRoutes from './src/routes/studentExtras.routes.js';
import teacherActivityRoutes from './src/routes/teacherActivity.routes.js';
import classReportRoutes from './src/routes/classReport.routes.js';

const app = express();

// ── Core middleware ──
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());
app.use(cors({
  origin: process.env.CLIENT_ORIGIN || 'http://localhost:5173',
  credentials: true,

}));

// ── Static Files (Uploads) ──
app.use('/uploads', express.static(path.join(process.cwd(), 'uploads')));

// ── API Routes ──
app.use('/api/auth', authRoutes);
app.use('/api/dashboard', dashboardRoutes);
app.use('/api/students', studentRoutes);
app.use('/api/fees', feesRoutes);
app.use('/api/attendance', attendanceRoutes);
app.use('/Absent_student', attendanceRoutes);
app.use('/absent_student', attendanceRoutes);
app.use('/api/teachers', teacherRoutes);
app.use('/api/exams', examRoutes);
app.use('/api/master', masterRoutes);
app.use('/api/transportation', transportationRoutes);
app.use('/api/communication', communicationRoutes);
app.use('/api/reports', reportRoutes);
app.use('/api/online-applications', onlineApplicationRoutes);
app.use('/api/notes', notesRoutes);
app.use('/api/meetings', meetingRoutes);
app.use('/api/tasks', taskRoutes);
app.use('/api/visiting', visitingRoutes);
app.use('/api/books-result', booksResultRoutes);
app.use('/api/fees-alert', feesAlertRoutes);
app.use('/api/lesson-approval', lessonApprovalRoutes);
app.use('/api/aadhar', aadharRoutes);
app.use('/api/tcm', tcmRoutes);
app.use('/api/random-class', randomClassRoutes);
app.use('/api/class-analytics', classAnalyticsRoutes);
app.use('/api/quizzes', quizRoutes);
app.use('/api/class-management', classManagementRoutes);
app.use('/api/student-extras', studentExtrasRoutes);
app.use('/api/teacher-activity', teacherActivityRoutes);
app.use('/api/class-report', classReportRoutes);

// CodeIgniter 3 legacy path aliases
app.use('/TCM', tcmRoutes);
app.use('/tcm', tcmRoutes);
app.use('/RandomClass', randomClassRoutes);
app.use('/randomclass', randomClassRoutes);
app.use('/Class_DB', classAnalyticsRoutes);
app.use('/class_db', classAnalyticsRoutes);
app.use('/Result_db', classAnalyticsRoutes);
app.use('/result_db', classAnalyticsRoutes);

// ── Health check ──
app.get('/api/health', (_req, res) =>
  res.json({ success: true, message: 'Olive School API is running.' })
);

// ── 404 + error handlers (must be last) ──
app.use(notFound);
app.use(errorHandler);

export default app;
