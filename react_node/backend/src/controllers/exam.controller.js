/**
 * Exam Controller — API handlers for Olive English School Examination Module
 */
import ExamModel from '../models/Exam.model.js';

export const getExams = async (req, res) => {
  try {
    const { year } = req.query;
    const exams = await ExamModel.getExams(year ? Number(year) : null);
    res.json({ success: true, data: exams });
  } catch (error) {
    console.error('getExams error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const createExam = async (req, res) => {
  try {
    const name = req.body.name || req.body.exam_name;
    const year = req.body.year || req.body.academic_year;
    if (!name) {
      return res.status(400).json({ success: false, message: 'Exam name is required' });
    }
    const result = await ExamModel.createExam({
      name,
      year: year ? Number(year) : null,
      addedBy: req.user?.username || 'admin',
    });
    res.status(201).json({ success: true, message: 'Exam created successfully', data: result });
  } catch (error) {
    console.error('createExam error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getSubjects = async (req, res) => {
  try {
    const { standard } = req.query;
    if (!standard) {
      return res.status(400).json({ success: false, message: 'Standard/Class is required' });
    }
    const subjects = await ExamModel.getSubjectsByStandard(standard);
    res.json({ success: true, data: subjects });
  } catch (error) {
    console.error('getSubjects error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getExamSetup = async (req, res) => {
  try {
    const { year, standard, examId } = req.query;
    if (!standard) {
      return res.status(400).json({ success: false, message: 'Standard/Class is required' });
    }
    const setup = await ExamModel.getExamSetup(
      year ? Number(year) : null,
      standard,
      examId ? Number(examId) : null
    );
    res.json({ success: true, data: setup });
  } catch (error) {
    console.error('getExamSetup error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const saveExamSetup = async (req, res) => {
  try {
    const examId = req.body.examId || req.body.exam_id;
    const subjectId = req.body.subjectId || req.body.subject_id;
    const standard = req.body.standard || req.body.class;
    const totalMarks = req.body.totalMarks || req.body.total_marks || req.body.max_marks;
    const passingMarks = req.body.passingMarks || req.body.passing_marks || req.body.min_marks;
    const year = req.body.year || req.body.academic_year || 9;

    if (!examId || !subjectId || !standard || !totalMarks || !passingMarks) {
      return res.status(400).json({ success: false, message: 'Missing required exam setup fields' });
    }
    const result = await ExamModel.saveExamSetup({
      examId: Number(examId),
      subjectId: Number(subjectId),
      standard,
      totalMarks: Number(totalMarks),
      passingMarks: Number(passingMarks),
      year: Number(year),
    });
    res.json({ success: true, message: 'Exam setup saved', data: result });
  } catch (error) {
    console.error('saveExamSetup error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getStudentMarks = async (req, res) => {
  try {
    const { year, standard, division, examId, subjectId } = req.query;
    const finalExamId = examId || req.query.exam || req.query.exam_id;
    const finalSubjectId = subjectId || req.query.subject || req.query.subject_id;
    if (!standard || !division || !finalExamId || !finalSubjectId) {
      return res.status(400).json({
        success: false,
        message: 'Standard, division, exam, and subject are required',
      });
    }
    const students = await ExamModel.getStudentMarks(
      year ? Number(year) : null,
      standard,
      division,
      Number(finalExamId),
      Number(finalSubjectId)
    );
    res.json({ success: true, data: students });
  } catch (error) {
    console.error('getStudentMarks error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const saveStudentMarks = async (req, res) => {
  try {
    const examId = req.body.examId || req.body.exam_id;
    const subjectId = req.body.subjectId || req.body.subject_id;
    const marksRecords = req.body.marksRecords || req.body.marks;
    const standard = req.body.standard || req.body.std || req.body.class;
    const division = req.body.division || req.body.div;
    const year = req.body.year || req.body.academic_year || 9;

    if (!standard || !division || !examId || !subjectId || !Array.isArray(marksRecords)) {
      return res.status(400).json({
        success: false,
        message: 'Standard, division, examId, subjectId, and marks array are required',
      });
    }

    const result = await ExamModel.saveMarks({
      year: Number(year),
      standard,
      division,
      examId: Number(examId),
      subjectId: Number(subjectId),
      marksRecords,
      userId: req.user?.username || 'admin',
    });

    res.json({
      success: true,
      message: `Successfully saved marks for ${result.savedCount} students`,
      data: result,
    });
  } catch (error) {
    console.error('saveStudentMarks error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getHallTickets = async (req, res) => {
  try {
    const { academicYear, standard, division, examId } = req.query;
    const tickets = await ExamModel.getHallTickets({
      academicYear: Number(academicYear) || 9,
      standard,
      division,
      examId: examId ? Number(examId) : null,
    });
    res.json({ success: true, data: tickets });
  } catch (error) {
    console.error('getHallTickets error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getHallTicketById = async (req, res) => {
  try {
    const { id } = req.params;
    const ticket = await ExamModel.getHallTicketById(Number(id));
    if (!ticket) {
      return res.status(404).json({ success: false, message: 'Hall Ticket not found' });
    }
    res.json({ success: true, data: ticket });
  } catch (error) {
    console.error('getHallTicketById error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const saveHallTicket = async (req, res) => {
  try {
    const { headerId, standard, division, examId, academicYear, shift, lines } = req.body;
    if (!standard || !division || !examId || !lines || lines.length === 0) {
      return res.status(400).json({
        success: false,
        message: 'Standard, division, exam, and at least one schedule line are required',
      });
    }

    const result = await ExamModel.saveHallTicket({
      headerId: headerId ? Number(headerId) : null,
      standard,
      division,
      examId: Number(examId),
      academicYear: Number(academicYear) || 9,
      shift: shift || 'Morning',
      lines,
      userId: req.user?.username || 'admin',
    });

    res.json({
      success: true,
      message: 'Hall ticket timetable saved successfully',
      data: result,
    });
  } catch (error) {
    console.error('saveHallTicket error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const deleteHallTicket = async (req, res) => {
  try {
    const { id } = req.params;
    const success = await ExamModel.deleteHallTicket(Number(id));
    if (!success) {
      return res.status(404).json({ success: false, message: 'Hall ticket not found' });
    }
    res.json({ success: true, message: 'Hall ticket deleted successfully' });
  } catch (error) {
    console.error('deleteHallTicket error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getHallTicketStudents = async (req, res) => {
  try {
    const { standard, division, academicYear, feesClearanceOnly } = req.query;
    if (!standard || !division) {
      return res.status(400).json({
        success: false,
        message: 'Standard and division are required',
      });
    }
    const students = await ExamModel.getStudentsForHallTicket(
      standard,
      division,
      academicYear ? Number(academicYear) : null,
      feesClearanceOnly === 'true' || feesClearanceOnly === true
    );
    res.json({ success: true, data: students });
  } catch (error) {
    console.error('getHallTicketStudents error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getStudentReportCard = async (req, res) => {
  try {
    const { studentId } = req.params;
    const { academicYear, examId } = req.query;
    const report = await ExamModel.getStudentReportCard(
      Number(studentId),
      academicYear ? Number(academicYear) : null,
      examId ? Number(examId) : null
    );
    if (!report) {
      return res.status(404).json({ success: false, message: 'Student record not found' });
    }
    res.json({ success: true, data: report });
  } catch (error) {
    console.error('getStudentReportCard error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};

export const getClassResults = async (req, res) => {
  try {
    const { academicYear, standard, division, examId } = req.query;
    if (!standard || !division) {
      return res.status(400).json({ success: false, message: 'Standard and division are required' });
    }
    const results = await ExamModel.getClassResults(
      academicYear ? Number(academicYear) : null,
      standard,
      division,
      examId ? Number(examId) : null
    );
    res.json({ success: true, data: results });
  } catch (error) {
    console.error('getClassResults error:', error);
    res.status(500).json({ success: false, message: error.message });
  }
};
