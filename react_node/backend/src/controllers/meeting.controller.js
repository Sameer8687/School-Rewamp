import MeetingModel from '../models/Meeting.model.js';
import { success, failure } from '../utils/helpers.js';

export async function getMeetings(req, res) {
  try {
    const academicYear = req.query.academicYear ? Number(req.query.academicYear) : null;
    const meetings = await MeetingModel.getAllMeetings(academicYear);
    return success(res, { meetings, count: meetings.length });
  } catch (error) {
    console.error('Error in getMeetings:', error);
    return failure(res, error.message || 'Failed to fetch meetings', 500);
  }
}

export async function createMeeting(req, res) {
  try {
    const prameet_date = req.body.prameet_date || req.body.meeting_date || req.body.date;
    const prameet_time = req.body.prameet_time || req.body.meeting_time || req.body.time || '10:00:00';
    const prameet_vanue = req.body.prameet_vanue || req.body.venue || 'School Auditorium';
    const prameet_agenda = req.body.prameet_agenda || req.body.agenda || req.body.title || 'General Meeting';
    const prameet_class = req.body.prameet_class || req.body.standard || req.body.class || 'ALL';
    const prameet_academic_year = req.body.prameet_academic_year || req.body.academic_year || req.body.academicYear || 9;

    if (!prameet_date || !prameet_agenda) {
      return failure(res, 'Date and Agenda are required.', 400);
    }

    const payload = {
      ...req.body,
      prameet_date,
      prameet_time,
      prameet_vanue,
      prameet_agenda,
      prameet_class,
      prameet_academic_year,
      prameet_added_by: req.user?.id || 1,
    };

    const meetingId = await MeetingModel.createMeeting(payload);
    return success(res, { meetingId, message: 'Meeting scheduled successfully.' }, 201);
  } catch (error) {
    console.error('Error in createMeeting:', error);
    return failure(res, error.message || 'Failed to schedule meeting', 500);
  }
}

export async function deleteMeeting(req, res) {
  try {
    const { id } = req.params;
    await MeetingModel.deleteMeeting(id);
    return success(res, { message: 'Meeting deleted successfully.' });
  } catch (error) {
    console.error('Error in deleteMeeting:', error);
    return failure(res, error.message || 'Failed to delete meeting', 500);
  }
}
