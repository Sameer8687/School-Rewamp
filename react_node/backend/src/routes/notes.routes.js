import express from 'express';
import { getNotes, createNote, deleteNote } from '../controllers/notes.controller.js';
import { authenticate } from '../middleware/auth.middleware.js';

const router = express.Router();

router.use(authenticate);

router.get('/', getNotes);
router.post('/', createNote);
router.delete('/:id', deleteNote);

export default router;
