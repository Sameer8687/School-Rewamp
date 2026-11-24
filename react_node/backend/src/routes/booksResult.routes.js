import express from 'express';
import {
  getBooksResultList,
  createBooksResultRecord,
  deleteBooksResultRecord,
} from '../controllers/booksResult.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/', getBooksResultList);
router.get('/records', getBooksResultList);
router.post('/', createBooksResultRecord);
router.delete('/:id', deleteBooksResultRecord);

export default router;
