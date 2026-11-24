import express from 'express';
import multer from 'multer';
import {
  searchStudents,
  getStudentFeeLedger,
  collectFees,
  getFeesReport,
  getReceiptDetails,
  getFeeStructure,
  saveFeeStructure,
  getReconciliation,
  updateFeeDate,
  getFeesExecutiveDashboard,
  getFeesComments,
  getAllPreviousFeesReport,
  getLastYearFeesReport,
  getDailyFeesReport
} from '../controllers/fees.controller.js';
import {
  uploadBankFile,
  getBankTransactions,
  getBankGroups,
  getGroupDetails
} from '../controllers/bankUpload.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();
const upload = multer({ storage: multer.memoryStorage(), limits: { fileSize: 15 * 1024 * 1024 } });

router.use(authenticate);

// Student fee search and ledger
router.get('/search-students', searchStudents);
router.get('/ledger/:studentId', getStudentFeeLedger);
router.post('/collect', collectFees);

// Fee Reports
router.get('/report', getFeesReport);
router.get('/comments', getFeesComments);
router.get('/all-previous', getAllPreviousFeesReport);
router.get('/last-year', getLastYearFeesReport);
router.get('/daily', getDailyFeesReport);
router.get('/receipt/:studentId', getReceiptDetails);

// Fee Structure Master
router.get('/structure', getFeeStructure);
router.get('/structures', getFeeStructure);
router.get('/categories', getFeeStructure);
router.get('/heads', getFeeStructure);
router.post('/structure', saveFeeStructure);
router.post('/structures', saveFeeStructure);

// Reconciliation
router.get('/reconciliation', getReconciliation);
router.post('/reconciliation/update-date', updateFeeDate);

// Bank Statement Excel Upload & Audit (CI3: Fees_bank_upload.php)
router.post('/bank-upload', upload.single('bank_file'), uploadBankFile);
router.get('/bank-transactions', getBankTransactions);
router.get('/bank-groups', getBankGroups);
router.get('/bank-groups/:groupId', getGroupDetails);

// Executive Dashboard & Visual Analytics (CI3: FeesDashboard.php)
router.get('/executive-dashboard', getFeesExecutiveDashboard);

export default router;
