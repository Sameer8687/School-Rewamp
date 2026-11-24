import NotesModel from '../models/Notes.model.js';
import { success, failure } from '../utils/helpers.js';

export async function getNotes(req, res) {
  try {
    const notes = await NotesModel.getAllNotes(req.query);
    return success(res, { notes, count: notes.length });
  } catch (error) {
    console.error('Error in getNotes:', error);
    return failure(res, error.message || 'Failed to fetch notes', 500);
  }
}

export async function createNote(req, res) {
  try {
    const doc_title = req.body.doc_title || req.body.title || req.body.name;
    const std_filter = req.body.std_filter || req.body.standard || req.body.class;
    const subject = req.body.subject || req.body.subject_name;
    const cur_acdm = req.body.cur_acdm || req.body.academic_year || req.body.academicYear || 9;

    if (!doc_title || !std_filter || !subject) {
      return failure(res, 'Document Title, Standard, and Subject are required.', 400);
    }
    const noteId = await NotesModel.createNote({
      ...req.body,
      doc_title,
      std_filter,
      subject,
      cur_acdm,
      created_by: req.user?.id || 1,
    });
    return success(res, { noteId, message: 'Note created successfully.' }, 201);
  } catch (error) {
    console.error('Error in createNote:', error);
    return failure(res, error.message || 'Failed to create note', 500);
  }
}

export async function deleteNote(req, res) {
  try {
    const { id } = req.params;
    await NotesModel.deleteNote(id);
    return success(res, { message: 'Note deleted successfully.' });
  } catch (error) {
    console.error('Error in deleteNote:', error);
    return failure(res, error.message || 'Failed to delete note', 500);
  }
}
