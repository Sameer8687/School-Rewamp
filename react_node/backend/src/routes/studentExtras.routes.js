/**
 * StudentExtras Routes — ID Cards, Tailor Tracking, and Remarks
 */
import express from 'express';
import multer from 'multer';
import fs from 'fs';
import path from 'path';
import {
  getOptions,
  getICardStudents,
  uploadICardPhoto,
  getTailorStudents,
  saveTailorDetails,
  getRemarks,
  createRemark,
  replyRemark,
  deleteRemark,
  createAdminNote,
  deleteAdminNote
} from '../controllers/studentExtras.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

const uploadDir = path.join(process.cwd(), 'uploads', 'icards');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (_req, _file, cb) => cb(null, uploadDir),
  filename: (req, file, cb) => {
    const ext = path.extname(file.originalname) || '.jpg';
    cb(null, `icard_${req.params.studentId}_${Date.now()}${ext}`);
  }
});

const upload = multer({
  storage,
  limits: { fileSize: 5 * 1024 * 1024 }
});

router.use(authenticate);

router.get('/options', getOptions);

// ID Cards
router.get('/icards', getICardStudents);
router.post('/icards/:studentId/photo', upload.single('photo'), uploadICardPhoto);

// Tailor / Uniform
router.get('/tailor', getTailorStudents);
router.post('/tailor', saveTailorDetails);

// Remarks & Comments
router.get('/remarks', getRemarks);
router.post('/remarks', createRemark);
router.put('/remarks/:id/reply', replyRemark);
router.delete('/remarks/:id', deleteRemark);

router.post('/admin-notes', createAdminNote);
router.delete('/admin-notes/:id', deleteAdminNote);

export default router;

