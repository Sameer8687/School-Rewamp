/**
 * Communication Controller — Handlers for Message Templates & School Events
 */
import CommunicationModel from '../models/Communication.model.js';

// ─── Message Templates ───
export const getTemplates = async (_req, res) => {
  try {
    const list = await CommunicationModel.getTemplates();
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getTemplates error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createTemplate = async (req, res) => {
  try {
    const rawText = req.body.text || req.body.content || req.body.message || req.body.title;
    const text = typeof rawText === 'string' ? rawText.trim() : '';
    if (!text) {
      return res.status(400).json({ success: false, message: 'Template text is required' });
    }
    const result = await CommunicationModel.createTemplate({
      text,
      user: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, message: 'Message template created successfully', data: result });
  } catch (error) {
    console.error('createTemplate error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateTemplate = async (req, res) => {
  try {
    const { id } = req.params;
    const { text } = req.body;
    if (!text || !text.trim()) {
      return res.status(400).json({ success: false, message: 'Template text is required' });
    }
    const result = await CommunicationModel.updateTemplate(Number(id), {
      text: text.trim(),
      user: req.user?.username || 'admin',
    });
    res.json({ success: true, message: 'Message template updated successfully', data: result });
  } catch (error) {
    console.error('updateTemplate error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteTemplate = async (req, res) => {
  try {
    const { id } = req.params;
    const success = await CommunicationModel.deleteTemplate(Number(id));
    if (!success) {
      return res.status(404).json({ success: false, message: 'Template not found' });
    }
    res.json({ success: true, message: 'Message template deleted successfully' });
  } catch (error) {
    console.error('deleteTemplate error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

// ─── School Events ───
export const getEvents = async (req, res) => {
  try {
    const { academicYear, standard } = req.query;
    const list = await CommunicationModel.getEvents({
      academicYear: academicYear ? Number(academicYear) : null,
      standard,
    });
    res.json({ success: true, data: list });
  } catch (error) {
    console.error('getEvents error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createEvent = async (req, res) => {
  try {
    const title = req.body.title || req.body.name;
    const details = req.body.details || req.body.description || '';
    const startDate = req.body.startDate || req.body.start_date || new Date().toISOString().slice(0, 10);
    const endDate = req.body.endDate || req.body.end_date || null;
    const standard = req.body.standard || req.body.class || 'all';
    const division = req.body.division || req.body.div || 'all';
    const academicYear = req.body.academicYear || req.body.academic_year || 9;

    if (!title || !startDate) {
      return res.status(400).json({ success: false, message: 'Event title and start date are required' });
    }
    const result = await CommunicationModel.createEvent({
      title,
      details,
      startDate,
      endDate,
      standard,
      division,
      academicYear: Number(academicYear) || null,
    });
    res.status(201).json({ success: true, message: 'School event published successfully', data: result });
  } catch (error) {
    console.error('createEvent error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const updateEvent = async (req, res) => {
  try {
    const { id } = req.params;
    const { title, details, startDate, endDate, standard, division, academicYear } = req.body;
    const result = await CommunicationModel.updateEvent(Number(id), {
      title,
      details,
      startDate,
      endDate,
      standard,
      division,
      academicYear: academicYear ? Number(academicYear) : null,
    });
    res.json({ success: true, message: 'Event updated successfully', data: result });
  } catch (error) {
    console.error('updateEvent error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteEvent = async (req, res) => {
  try {
    const { id } = req.params;
    const success = await CommunicationModel.deleteEvent(Number(id));
    if (!success) {
      return res.status(404).json({ success: false, message: 'Event not found' });
    }
    res.json({ success: true, message: 'Event deleted successfully' });
  } catch (error) {
    console.error('deleteEvent error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};
