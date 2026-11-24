/**
 * Student Routes — API route definitions for Student Module.
 */
import { Router } from 'express';
import multer from 'multer';
import path from 'path';
import fs from 'fs';
import { authenticate } from '../middleware/auth.middleware.js';
import {
  getStudents,
  getOptions,
  getNextId,
  getStudent,
  postStudent,
  putStudent,
  patchStatus,
  getPromotionCandidates,
  postPromotion,
  getReadmission,
  postReadmission,
  getLc,
  postIssueLc,
  getStudentPortalProfile,
} from '../controllers/student.controller.js';
import {
  getICardStudents,
  getTailorStudents,
  saveTailorDetails,
  getRemarks,
  createRemark,
  replyRemark,
  deleteRemark,
} from '../controllers/studentExtras.controller.js';

// Setup upload directory for student photos
const uploadDir = path.join(process.cwd(), 'uploads', 'students');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (_req, _file, cb) => {
    cb(null, uploadDir);
  },
  filename: (_req, file, cb) => {
    const ext = path.extname(file.originalname);
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
    cb(null, 'student-' + uniqueSuffix + ext);
  },
});

const upload = multer({
  storage,
  limits: { fileSize: 5 * 1024 * 1024 }, // 5MB limit
  fileFilter: (_req, file, cb) => {
    if (file.mimetype.startsWith('image/')) {
      cb(null, true);
    } else {
      cb(new Error('Only image files are allowed!'), false);
    }
  },
});

const router = Router();

// Apply authentication to all student routes
router.use(authenticate);

// ── Dropdowns & Helpers ──
router.get('/options', getOptions);
router.get('/next-id', getNextId);

// ── Student Extras Aliases (Remarks, Tailor, ICards) ──
router.get('/remarks', getRemarks);
router.post('/remarks', createRemark);
router.put('/remarks/:id/reply', replyRemark);
router.delete('/remarks/:id', deleteRemark);
router.get('/tailor', getTailorStudents);
router.post('/tailor', saveTailorDetails);
router.get('/icards', getICardStudents);

// ── Promotion & Re-admission & LC ──
router.get('/promotions/candidates', getPromotionCandidates);
router.post('/promotions',           postPromotion);

router.get('/readmission/details',   getReadmission);
router.post('/readmission',          postReadmission);

router.get('/lc/records',            getLc);
router.post('/:id/issue-lc',         postIssueLc);

// ── Student Self-Service Portal (CI3: Profile.php / new-sp.php) ──
router.get('/portal-profile',            getStudentPortalProfile);
router.get('/portal-profile/:studentId', getStudentPortalProfile);

// ── CRUD Students ──
router.get('/',                      getStudents);
router.get('/:id',                   getStudent);
router.post('/',                     upload.single('photo'), postStudent);
router.put('/:id',                   upload.single('photo'), putStudent);
router.patch('/:id/status',          patchStatus);

export default router;
