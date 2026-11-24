import AadharModel from '../models/Aadhar.model.js';

export const getAadharReport = async (req, res, next) => {
  try {
    const { standard, std, division, div, search } = req.query;
    const records = await AadharModel.getAadharReport({
      standard: standard || std || 'all',
      division: division || div || 'all',
      search: search || '',
    });

    res.json({
      success: true,
      data: {
        records,
        count: records.length,
      },
      records,
      count: records.length,
    });
  } catch (error) {
    next(error);
  }
};

export const getStudentAadhar = async (req, res, next) => {
  try {
    const { studentId } = req.params;
    const data = await AadharModel.getStudentAadhar(studentId);
    if (!data) {
      return res.status(404).json({ success: false, message: 'Student record not found.' });
    }
    res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

export const saveStudentAadhar = async (req, res, next) => {
  try {
    const { student_id, studentId } = req.body;
    const targetId = student_id || studentId || req.params.studentId;
    if (!targetId) {
      return res.status(400).json({ success: false, message: 'Student ID is required.' });
    }

    await AadharModel.saveStudentAadhar(targetId, req.body);
    res.json({
      success: true,
      message: 'Student Aadhar and parent details saved successfully.',
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Public parent KYC view (CI3: public/Student.php)
 */
export const getPublicStudentAadharInfo = async (req, res, next) => {
  try {
    const { sid, studentId, student_id, token } = req.query;
    const targetSid = sid || studentId || student_id;
    if (!targetSid) {
      return res.status(400).json({ success: false, message: 'Student ID (sid) is required.' });
    }

    const data = await AadharModel.getPublicStudentInfo(targetSid, token);
    if (!data) {
      return res.status(404).json({ success: false, message: 'Student record not found or invalid token.' });
    }

    res.json({ success: true, data });
  } catch (error) {
    next(error);
  }
};

export const savePublicStudentAadhar = async (req, res, next) => {
  try {
    const { student_id, studentId, sid } = req.body;
    const targetId = student_id || studentId || sid;
    if (!targetId) {
      return res.status(400).json({ success: false, message: 'Student ID is required.' });
    }

    await AadharModel.saveStudentAadhar(targetId, req.body);
    res.json({
      success: true,
      message: 'Aadhaar & parent KYC details submitted successfully.',
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Public parent login validation (CI3: public/Student.php validate)
 */
export const validatePublicStudent = async (req, res, next) => {
  try {
    const { student_id, studentId } = req.body;
    const targetId = student_id || studentId;
    if (!targetId) {
      return res.status(400).json({ success: false, message: 'Invalid Student ID.' });
    }

    const student = await AadharModel.validateStudentId(targetId);
    if (!student) {
      return res.status(404).json({
        success: false,
        message: 'Invalid Student Credentials, Student Details not found.',
      });
    }

    res.json({
      success: true,
      data: {
        sid: student.id,
        token: student.spriden_auth_token || '',
        student_id: student.SPRIDEN_student_id,
      },
    });
  } catch (error) {
    next(error);
  }
};

